<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Services\ViabilityService;
use Illuminate\Http\Request;

class ViabilityController extends Controller
{
    public function __construct(private ViabilityService $viability) {}

    /**
     * Show viability analysis form (step before the main claim form).
     */
    public function show(): \Illuminate\View\View
    {
        return view('viability.check');
    }

    /**
     * Analyze viability from AJAX (called from the claim creation form on blur).
     */
    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'insurer_name' => 'required|string|max:255',
            'description' => 'required|string|min:30',
            'claim_type' => 'nullable|string',
            'policy_number' => 'nullable|string',
        ]);

        $analysis = $this->viability->analyze($validated);

        if (empty($analysis)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo analizar el caso en este momento.',
            ], 503);
        }

        return response()->json([
            'status' => 'success',
            'analysis' => $analysis,
        ]);
    }

    /**
     * Fetch analysis for an existing claim (after creation, before payment).
     */
    public function forClaim(Claim $claim)
    {
        // If already analyzed, return cached result
        if ($claim->viability_analysis) {
            return response()->json([
                'status' => 'success',
                'analysis' => $claim->viability_analysis,
                'cached' => true,
            ]);
        }

        $analysis = $this->viability->analyze([
            'claim_type' => $claim->claim_type,
            'insurer_name' => $claim->insurer_name,
            'description' => $claim->description,
            'policy_number' => $claim->policy_number,
        ]);

        if (!empty($analysis)) {
            $claim->update([
                'viability_score' => $analysis['score'] ?? null,
                'viability_probability' => $analysis['probabilidad_estimada'] ?? null,
                'viability_analysis' => $analysis,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'analysis' => $analysis,
            'cached' => false,
        ]);
    }
}
