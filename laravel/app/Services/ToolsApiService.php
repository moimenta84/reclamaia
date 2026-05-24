<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class ToolsApiService
{
    private string $baseUrl;
    private string $secret;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('reclamaia.python_service_url', 'http://localhost:8001');
        $this->secret  = config('reclamaia.internal_api_secret', '');
        $this->timeout = (int) config('reclamaia.python_timeout_seconds', 35);
    }

    /**
     * Vehicle damage valuation via DAT / Audatex / GT / mock.
     */
    public function getDamageValuation(array $vehicle, array $damage): array
    {
        $response = Http::withHeaders($this->headers())
            ->timeout($this->timeout)
            ->post("{$this->baseUrl}/api/valuacion-danos", [
                'vehicle' => $vehicle,
                'damage'  => $damage,
            ]);

        if ($response->failed()) {
            Log::error('Damage valuation failed', ['body' => $response->body()]);
            return ['status' => 'error', 'message' => $response->json('detail', 'Servicio no disponible')];
        }

        return $response->json();
    }

    /**
     * OCR + structured extraction for any PDF document.
     */
    public function ocrDocument(UploadedFile $file, string $docType = 'poliza'): array
    {
        $response = Http::withHeaders($this->headers())
            ->timeout($this->timeout + 20)
            ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
            ->post("{$this->baseUrl}/api/ocr-documento?doc_type=" . urlencode($docType));

        if ($response->failed()) {
            Log::error('OCR failed', ['body' => $response->body()]);
            return ['status' => 'error', 'message' => $response->json('detail', 'Servicio no disponible')];
        }

        return $response->json();
    }

    /**
     * Search Spanish jurisprudence (CENDOJ + Claude analysis).
     */
    public function searchJurisprudence(string $description, string $insurerName = '', string $claimType = 'seguros'): array
    {
        $response = Http::withHeaders($this->headers())
            ->timeout($this->timeout)
            ->post("{$this->baseUrl}/api/jurisprudencia", [
                'claim_description' => $description,
                'insurer_name'      => $insurerName,
                'claim_type'        => $claimType,
            ]);

        if ($response->failed()) {
            Log::error('Jurisprudence failed', ['body' => $response->body()]);
            return ['status' => 'error', 'message' => $response->json('detail', 'Servicio no disponible')];
        }

        return $response->json();
    }

    private function headers(): array
    {
        return [
            'X-Internal-Key' => $this->secret,
            'Accept'         => 'application/json',
        ];
    }
}
