<?php

namespace App\Jobs;

use App\Models\Claim;
use App\Services\EmailService;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EscalateClaimJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 60;

    public function __construct(public int $claimId) {}

    public function handle(EmailService $email): void
    {
        $claim = Claim::with(['document', 'payment'])->findOrFail($this->claimId);

        if ($claim->escalation_status !== 'none') {
            return; // Already escalated
        }

        $http = new Client([
            'base_uri' => config('reclamaia.python_service_url', 'http://localhost:8001'),
            'timeout' => 60,
            'headers' => [
                'X-Internal-Key' => config('reclamaia.internal_api_secret'),
                'Content-Type' => 'application/json',
            ],
        ]);

        $sentAt = $claim->sent_to_insurer_at
            ? $claim->sent_to_insurer_at->format('d/m/Y')
            : now()->subDays(30)->format('d/m/Y');

        $daysElapsed = $claim->sent_to_insurer_at
            ? (int) $claim->sent_to_insurer_at->diffInDays(now())
            : 30;

        try {
            $response = $http->post('/api/escalate', [
                'json' => [
                    'claim_id' => $claim->id,
                    'insurer_name' => $claim->insurer_name,
                    'description' => $claim->description,
                    'policy_number' => $claim->policy_number,
                    'sent_at' => $sentAt,
                    'days_elapsed' => $daysElapsed,
                    'claimant' => [
                        'name' => $claim->claimant_name,
                        'dni' => $claim->claimant_dni,
                        'address' => $claim->claimant_address,
                    ],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $claim->update([
                'escalation_status' => 'dgsfp_escalated',
                'escalation_sent_at' => now(),
                'escalation_document_path' => $data['pdf_path'] ?? null,
            ]);

            \App\Models\EscalationLog::create([
                'claim_id' => $claim->id,
                'type' => 'dgsfp_escalation',
                'document_path' => $data['pdf_path'] ?? null,
                'email_sent' => false,
            ]);

            // Notify user
            $this->sendEscalationEmail($claim, $data, $email);
        } catch (\Throwable $e) {
            Log::error('EscalateClaimJob failed', ['claim_id' => $this->claimId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    private function sendEscalationEmail(Claim $claim, array $data, EmailService $email): void
    {
        try {
            \Illuminate\Support\Facades\Mail::send(
                'emails.escalation_ready',
                ['claim' => $claim, 'pdfPath' => $data['pdf_path'] ?? null],
                function ($m) use ($claim) {
                    $m->to($claim->claimant_email, $claim->claimant_name)
                      ->subject('Tu reclamación ha sido escalada a la DGSFP — ReclamaIA');
                }
            );
        } catch (\Throwable $e) {
            Log::warning('Escalation email failed', ['claim_id' => $claim->id]);
        }
    }
}
