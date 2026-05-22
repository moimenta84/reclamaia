<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PolicyController extends Controller
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'base_uri' => config('reclamaia.python_service_url', 'http://localhost:8001'),
            'timeout' => 30,
        ]);
    }

    public function upload(Request $request, Claim $claim)
    {
        $request->validate([
            'policy_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        $file = $request->file('policy_pdf');
        $path = $file->store("policies/{$claim->id}", 'local');

        $claim->update(['policy_pdf_path' => $path]);

        // Extract clauses from Python service
        try {
            $response = $this->http->post('/api/extract-policy', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen(Storage::path($path), 'r'),
                        'filename' => 'poliza.pdf',
                    ],
                    [
                        'name' => 'claim_description',
                        'contents' => $claim->description,
                    ],
                ],
                'headers' => [
                    'X-Internal-Key' => config('reclamaia.internal_api_secret'),
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['clauses'])) {
                $claim->update(['policy_clauses' => $data['clauses']]);
            }

            return response()->json([
                'status' => 'success',
                'clauses' => $data['clauses'] ?? [],
                'message' => 'Póliza analizada. Las cláusulas relevantes se incluirán en tu reclamación.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Policy extraction failed', ['claim_id' => $claim->id, 'error' => $e->getMessage()]);
            return response()->json([
                'status' => 'partial',
                'message' => 'La póliza se ha guardado pero no se pudo extraer automáticamente. Se incluirá como referencia.',
            ]);
        }
    }

    public function analysis(Claim $claim)
    {
        if (!$claim->policy_clauses) {
            return response()->json(['status' => 'not_analyzed']);
        }

        return response()->json([
            'status' => 'success',
            'clauses' => $claim->policy_clauses,
        ]);
    }
}
