<?php

namespace App\Services;

use App\Models\Claim;
use App\Models\Document;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class DocumentGeneratorService
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'base_uri' => config('reclamaia.python_service_url', 'http://localhost:8001'),
            'timeout' => config('reclamaia.python_timeout_seconds', 35),
            'headers' => [
                'X-Internal-Key' => config('reclamaia.internal_api_secret'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function generate(Claim $claim): Document
    {
        try {
            $response = $this->http->post('/api/generate', [
                'json' => [
                    'claim_id' => $claim->id,
                    'claim_type' => $claim->claim_type,
                    'insurer_name' => $claim->insurer_name,
                    'description' => $claim->description,
                    'claimant' => [
                        'name' => $claim->claimant_name,
                        'dni' => $claim->claimant_dni,
                        'email' => $claim->claimant_email,
                        'phone' => $claim->claimant_phone,
                        'address' => $claim->claimant_address,
                    ],
                    'policy_number' => $claim->policy_number,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return Document::create([
                'claim_id' => $claim->id,
                'word_path' => $data['word_path'],
                'pdf_path' => $data['pdf_path'],
                'generated_at' => $data['generated_at'],
            ]);
        } catch (RequestException $e) {
            Log::error('DocumentGeneratorService error', [
                'claim_id' => $claim->id,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Error al generar el documento: ' . $e->getMessage());
        }
    }
}
