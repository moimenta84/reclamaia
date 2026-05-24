<?php

namespace App\Http\Controllers;

use App\Models\DeceasedInsuranceSearch;
use Illuminate\Http\Request;

class DeceasedInsuranceController extends Controller
{
    public function index()
    {
        $searches = auth()->user()
            ->hasMany(DeceasedInsuranceSearch::class, 'user_id')
            ->latest()
            ->paginate(15);

        // Fix: use direct query
        $searches = DeceasedInsuranceSearch::where('user_id', auth()->id())
            ->latest()->paginate(15);

        return view('tools.seguros-fallecido.index', compact('searches'));
    }

    public function create()
    {
        return view('tools.seguros-fallecido.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'deceased_name'          => 'required|string|max:120',
            'deceased_dni'           => 'required|string|max:20',
            'deceased_birth_date'    => 'nullable|date|before:deceased_death_date',
            'deceased_death_date'    => 'required|date|before_or_equal:today',
            'deceased_province'      => 'nullable|string|max:80',
            'applicant_name'         => 'required|string|max:120',
            'applicant_dni'          => 'required|string|max:20',
            'applicant_relationship' => 'required|string|max:60',
            'applicant_email'        => 'nullable|email|max:120',
            'applicant_phone'        => 'nullable|string|max:30',
            'notes'                  => 'nullable|string|max:1000',
        ]);

        $search = DeceasedInsuranceSearch::create([
            ...$data,
            'user_id' => auth()->id(),
            'status'  => 'pendiente_documentacion',
        ]);

        return redirect()->route('tools.fallecido.show', $search)
            ->with('success', 'Expediente creado. Consulta los pasos a seguir.');
    }

    public function show(DeceasedInsuranceSearch $search)
    {
        abort_unless($search->user_id === auth()->id(), 403);
        return view('tools.seguros-fallecido.show', compact('search'));
    }

    public function update(Request $request, DeceasedInsuranceSearch $search)
    {
        abort_unless($search->user_id === auth()->id(), 403);

        $data = $request->validate([
            'status'                  => 'required|in:pendiente_documentacion,tramite_iniciado,certificado_recibido,seguro_encontrado,seguro_no_encontrado,reclamacion_enviada,cobrado',
            'insurer_found'           => 'nullable|string|max:120',
            'policy_type'             => 'nullable|string|max:80',
            'insured_amount'          => 'nullable|numeric|min:0',
            'tramite_sent_at'         => 'nullable|date',
            'certificate_received_at' => 'nullable|date',
            'claim_sent_at'           => 'nullable|date',
            'resolved_at'             => 'nullable|date',
            'notes'                   => 'nullable|string|max:2000',
        ]);

        $search->update($data);

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy(DeceasedInsuranceSearch $search)
    {
        abort_unless($search->user_id === auth()->id(), 403);
        $search->delete();
        return redirect()->route('tools.fallecido.index')
            ->with('success', 'Expediente eliminado.');
    }
}
