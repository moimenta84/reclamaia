<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Services\SignaturitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SignaturitController extends Controller
{
    public function __construct(private SignaturitService $signaturit) {}

    /**
     * Show the signing page for a claim document.
     */
    public function showSign(Claim $claim)
    {
        $this->authorizeClaimAccess($claim);
        return view('tools.firma', compact('claim'));
    }

    /**
     * Create a signature request and redirect to the signing URL.
     */
    public function requestSign(Request $request, Claim $claim)
    {
        $this->authorizeClaimAccess($claim);

        $validated = $request->validate([
            'signer_name'  => 'required|string|max:255',
            'signer_email' => 'required|email',
            'signer_phone' => 'nullable|string|max:20',
            'doc_type'     => 'required|in:reclamacion,poder,autorizacion,contrato,cesion',
        ]);

        // Locate the PDF for this claim
        $pdfPath = $this->getPdfPath($claim);
        if (! $pdfPath) {
            return back()->with('error', 'El documento PDF no está disponible todavía. Genera primero el documento.');
        }

        $subjects = [
            'reclamacion' => 'Firma de reclamación a aseguradora',
            'poder'       => 'Firma de poder de representación',
            'autorizacion'=> 'Firma de autorización',
            'contrato'    => 'Firma de contrato de servicios',
            'cesion'      => 'Firma de cesión de derechos',
        ];

        $result = $this->signaturit->createSignatureRequest(
            filePath: $pdfPath,
            signers: [[
                'name'  => $validated['signer_name'],
                'email' => $validated['signer_email'],
                'phone' => $validated['signer_phone'] ?? null,
            ]],
            subject: $subjects[$validated['doc_type']],
            body: "Por favor, revise y firme el documento adjunto de ReclamaIA. "
                . "Una vez firmado, le enviaremos copia del documento certificado.",
        );

        // Save signature request ID on the claim for tracking
        $claim->update(['signaturit_id' => $result['id']]);

        if (! empty($result['warning'])) {
            return back()->with('info', 'Modo simulación: ' . $result['warning'])
                         ->with('sign_url', $result['sign_url']);
        }

        return redirect($result['sign_url']);
    }

    /**
     * Signaturit webhook — called when document is signed.
     */
    public function webhook(Request $request)
    {
        $event = $request->input('type');
        $sigId = $request->input('document.signatureId') ?? $request->input('signature.id');

        if ($event === 'signature_request.completed' && $sigId) {
            $claim = Claim::where('signaturit_id', $sigId)->first();
            if ($claim) {
                $claim->update(['signed_at' => now()]);
            }
        }

        return response()->json(['ok' => true]);
    }

    private function authorizeClaimAccess(Claim $claim): void
    {
        if (auth()->check() && $claim->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function getPdfPath(Claim $claim): ?string
    {
        if ($claim->document) {
            $path = Storage::path('app/' . $claim->document->pdf_path);
            if (file_exists($path)) {
                return $path;
            }
        }
        return null;
    }
}
