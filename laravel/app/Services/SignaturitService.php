<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SignaturitService
{
    private string $apiKey;
    private string $baseUrl;
    private bool $sandbox;

    public function __construct()
    {
        $this->apiKey  = config('services.signaturit.api_key', '');
        $this->sandbox = config('services.signaturit.sandbox', true);
        $this->baseUrl = $this->sandbox
            ? 'https://api.sandbox.signaturit.com/v3'
            : 'https://api.signaturit.com/v3';
    }

    private function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * Create a signature request for a generated document.
     *
     * @param  string  $filePath   Absolute path to the PDF to sign
     * @param  array   $signers    [['name' => '...', 'email' => '...', 'phone' => '...'], ...]
     * @param  string  $subject    Email subject sent to signers
     * @param  string  $body       Email body sent to signers
     * @return array   ['id', 'status', 'sign_url', 'created_at']
     */
    public function createSignatureRequest(
        string $filePath,
        array  $signers,
        string $subject = 'Firma de documento ReclamaIA',
        string $body    = 'Por favor, firme el documento adjunto.'
    ): array {
        if (! $this->isConfigured()) {
            return $this->mockSignatureRequest($filePath, $signers);
        }

        $multipart = [
            ['name' => 'subject', 'contents' => $subject],
            ['name' => 'body',    'contents' => $body],
            ['name' => 'type',    'contents' => 'advanced'],    // advanced = eIDAS AdES
        ];

        foreach ($signers as $i => $signer) {
            $multipart[] = ['name' => "recipients[{$i}][name]",  'contents' => $signer['name']];
            $multipart[] = ['name' => "recipients[{$i}][email]", 'contents' => $signer['email']];
            if (! empty($signer['phone'])) {
                $multipart[] = ['name' => "recipients[{$i}][phone]", 'contents' => $signer['phone']];
            }
        }

        $multipart[] = [
            'name'     => 'files[0]',
            'contents' => fopen($filePath, 'r'),
            'filename' => basename($filePath),
        ];

        $response = Http::withToken($this->apiKey)
            ->asMultipart()
            ->post("{$this->baseUrl}/signatures.json", $multipart);

        if ($response->failed()) {
            Log::error('Signaturit API error', ['body' => $response->body()]);
            throw new \RuntimeException('Error al crear solicitud de firma: ' . $response->body());
        }

        $data = $response->json();

        return [
            'id'         => $data['id'],
            'status'     => $data['status'],
            'sign_url'   => $data['documents'][0]['events'][0]['url'] ?? null,
            'created_at' => $data['created_at'],
        ];
    }

    /**
     * Get the status of a signature request.
     */
    public function getSignatureStatus(string $signatureId): array
    {
        if (! $this->isConfigured()) {
            return ['id' => $signatureId, 'status' => 'pending', 'completed' => false];
        }

        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/signatures/{$signatureId}.json");

        $data = $response->json();

        return [
            'id'        => $data['id'],
            'status'    => $data['status'],
            'completed' => $data['status'] === 'completed',
            'documents' => $data['documents'] ?? [],
        ];
    }

    /**
     * Download a signed document PDF.
     */
    public function downloadSignedDocument(string $signatureId, string $documentId): string
    {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/signatures/{$signatureId}/documents/{$documentId}/download/signed/pdf");

        return $response->body();
    }

    /**
     * Create a biometric signature link (Signaturit Certified Email).
     * Useful for poderes and autorizaciones without in-person signing.
     */
    public function createCertifiedEmail(
        string $filePath,
        string $recipientName,
        string $recipientEmail,
        string $subject
    ): array {
        if (! $this->isConfigured()) {
            return $this->mockSignatureRequest($filePath, [['name' => $recipientName, 'email' => $recipientEmail]]);
        }

        $response = Http::withToken($this->apiKey)
            ->asMultipart()
            ->post("{$this->baseUrl}/emails.json", [
                ['name' => 'recipients[0][name]',  'contents' => $recipientName],
                ['name' => 'recipients[0][email]', 'contents' => $recipientEmail],
                ['name' => 'subject',              'contents' => $subject],
                ['name' => 'files[0]',             'contents' => fopen($filePath, 'r'), 'filename' => basename($filePath)],
            ]);

        return $response->json();
    }

    private function mockSignatureRequest(string $filePath, array $signers): array
    {
        return [
            'id'         => 'mock-' . uniqid(),
            'status'     => 'pending',
            'sign_url'   => 'https://sandbox.signaturit.com/sign/mock-link',
            'created_at' => now()->toIso8601String(),
            'warning'    => 'SIGNATURIT_API_KEY no configurada. Resultado simulado.',
            'signers'    => $signers,
            'file'       => basename($filePath),
        ];
    }
}
