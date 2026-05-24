<?php

namespace App\Http\Controllers;

use App\Services\ToolsApiService;
use Illuminate\Http\Request;

class ToolsController extends Controller
{
    public function __construct(private ToolsApiService $tools) {}

    public function index()
    {
        return view('tools.index');
    }

    // ── Valoración de daños vehiculares ───────────────────────────────────────
    public function showValoracion()
    {
        return view('tools.valoracion-vehiculo');
    }

    public function calculateValoracion(Request $request)
    {
        $data = $request->validate([
            'vehicle.vin'     => 'nullable|string|max:17',
            'vehicle.plate'   => 'nullable|string|max:12',
            'vehicle.make'    => 'required|string|max:50',
            'vehicle.model'   => 'required|string|max:50',
            'vehicle.year'    => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'vehicle.mileage' => 'required|integer|min:0|max:1000000',
            'damage.description' => 'required|string|min:10|max:1000',
            'damage.parts'    => 'nullable|array',
        ]);

        $result = $this->tools->getDamageValuation($data['vehicle'], $data['damage']);

        return response()->json($result);
    }

    // ── OCR de documentos ─────────────────────────────────────────────────────
    public function showOcr()
    {
        return view('tools.ocr-documento');
    }

    public function processOcr(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:20480',  // 20 MB
            'doc_type' => 'required|in:poliza,parte_medico,informe_pericial,sentencia',
        ]);

        $result = $this->tools->ocrDocument($request->file('document'), $request->input('doc_type'));

        return response()->json($result);
    }

    // ── Jurisprudencia CENDOJ ─────────────────────────────────────────────────
    public function showJurisprudencia()
    {
        return view('tools.jurisprudencia');
    }

    public function searchJurisprudencia(Request $request)
    {
        $data = $request->validate([
            'description'   => 'required|string|min:20|max:2000',
            'insurer_name'  => 'nullable|string|max:100',
            'claim_type'    => 'nullable|string|max:50',
        ]);

        $result = $this->tools->searchJurisprudence(
            $data['description'],
            $data['insurer_name'] ?? '',
            $data['claim_type']   ?? 'seguros',
        );

        return response()->json($result);
    }
}
