<?php

namespace App\Services;

use App\Models\Claim;
use App\Models\Document;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendDocumentReady(Claim $claim, Document $document): void
    {
        Mail::send('emails.document_ready', [
            'claim' => $claim,
            'document' => $document,
            'wordUrl' => route('claim.download.word', $claim),
            'pdfUrl' => route('claim.download.pdf', $claim),
        ], function ($message) use ($claim) {
            $message->to($claim->claimant_email, $claim->claimant_name)
                ->subject('Tu reclamación está lista — ReclamaIA');
        });
    }

    public function sendDocumentFailed(Claim $claim): void
    {
        Mail::send('emails.document_failed', [
            'claim' => $claim,
        ], function ($message) use ($claim) {
            $message->to($claim->claimant_email, $claim->claimant_name)
                ->subject('Problema con tu reclamación — ReclamaIA');
        });
    }
}
