<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ViabilityService
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'base_uri' => config('reclamaia.python_service_url', 'http://localhost:8001'),
            'timeout' => 20,
            'headers' => [
                'X-Internal-Key' => config('reclamaia.internal_api_secret'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function analyze(array $claimData): array
    {
        try {
            $response = $this->http->post('/api/analyze', [
                'json' => [
                    'claim_type' => $claimData['claim_type'] ?? 'insurance',
                    'insurer_name' => $claimData['insurer_name'] ?? '',
                    'description' => $claimData['description'] ?? '',
                    'policy_number' => $claimData['policy_number'] ?? null,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['analysis'] ?? [];
        } catch (\Throwable $e) {
            Log::error('ViabilityService error', ['error' => $e->getMessage()]);
            return [];
        }
    }
}
