<?php

namespace App\Jobs;

use App\Models\Claim;
use App\Models\Payment;
use App\Services\DocumentGeneratorService;
use App\Services\EmailService;
use App\Services\StripeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 60;

    public function __construct(public int $claimId) {}

    public function handle(
        DocumentGeneratorService $generator,
        EmailService $emailService,
        StripeService $stripe,
    ): void {
        $claim = Claim::with('payment')->findOrFail($this->claimId);

        $claim->update(['status' => Claim::STATUS_PROCESSING]);

        try {
            $document = $generator->generate($claim);
            $claim->update(['status' => Claim::STATUS_COMPLETED]);

            try {
                $emailService->sendDocumentReady($claim, $document);
            } catch (\Throwable $e) {
                Log::warning('Email send failed after generation', ['claim_id' => $this->claimId, 'error' => $e->getMessage()]);
            }
        } catch (\Throwable $e) {
            $claim->update(['status' => Claim::STATUS_FAILED]);
            Log::error('GenerateDocumentJob failed', ['claim_id' => $this->claimId, 'error' => $e->getMessage()]);

            $payment = $claim->payment;
            if ($payment && $payment->status === Payment::STATUS_COMPLETED) {
                try {
                    $stripe->refundPaymentIntent($payment->stripe_payment_intent_id);
                    $payment->update(['status' => Payment::STATUS_REFUNDED]);
                } catch (\Throwable $refundEx) {
                    Log::error('Refund failed', ['payment_id' => $payment->id, 'error' => $refundEx->getMessage()]);
                }
            }

            try {
                $emailService->sendDocumentFailed($claim);
            } catch (\Throwable $e) {
                Log::warning('Failed email send failed', ['claim_id' => $this->claimId]);
            }

            throw $e;
        }
    }
}
