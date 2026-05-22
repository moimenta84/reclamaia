<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClaimController extends Controller
{
    public function create(): View
    {
        return view('claim.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'claim_type' => 'required|in:insurance',
            'insurer_name' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'claimant_name' => 'required|string|max:255',
            'claimant_dni' => 'required|string|max:20',
            'claimant_email' => 'required|email|max:255',
            'claimant_phone' => 'nullable|string|max:20',
            'claimant_address' => 'required|string',
            'policy_number' => 'nullable|string|max:100',
        ]);

        $claim = Claim::create(array_merge($validated, [
            'user_id' => auth()->id(),
        ]));

        // Store claim_id in session so anonymous users can track it
        $request->session()->push('claim_ids', $claim->id);

        return redirect()->route('payment.show', $claim);
    }

    public function downloadWord(Claim $claim)
    {
        $this->authorizeDownload($claim);

        $path = storage_path('app/' . $claim->document->word_path);

        abort_unless(file_exists($path), 404, 'Documento no encontrado');

        $claim->document->increment('download_count');

        return response()->download($path, "reclamacion_{$claim->id}.docx");
    }

    public function downloadPdf(Claim $claim)
    {
        $this->authorizeDownload($claim);

        $path = storage_path('app/' . $claim->document->pdf_path);

        abort_unless(file_exists($path), 404, 'Documento no encontrado');

        $claim->document->increment('download_count');

        return response()->download($path, "reclamacion_{$claim->id}.pdf");
    }

    public function markAsSent(Claim $claim)
    {
        abort_unless(
            auth()->check() && ($claim->user_id === auth()->id()),
            403
        );

        if (!$claim->sent_to_insurer_at) {
            $claim->update(['sent_to_insurer_at' => now()]);
        }

        return back()->with('success', 'Perfecto. Te avisaremos automáticamente si la aseguradora no responde en 30 días.');
    }

    public function downloadEscalation(Claim $claim)
    {
        $this->authorizeDownload($claim);

        abort_unless($claim->escalation_document_path, 404, 'Carta de escalada no disponible');

        $path = storage_path('app/' . $claim->escalation_document_path);
        abort_unless(file_exists($path), 404);

        return response()->download($path, "escalada_dgsfp_{$claim->id}.pdf");
    }

    private function authorizeDownload(Claim $claim): void
    {
        // Allow if authenticated owner, or if claim id is in session (anonymous flow)
        $ownedByUser = auth()->check() && $claim->user_id === auth()->id();
        $inSession = in_array($claim->id, request()->session()->get('claim_ids', []));

        abort_unless($ownedByUser || $inSession, 403, 'No autorizado');
        abort_unless($claim->isCompleted() && $claim->document, 404, 'Documento no disponible aún');
    }
}
