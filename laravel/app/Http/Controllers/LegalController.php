<?php

namespace App\Http\Controllers;

use App\Models\UserConsent;
use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function avisoLegal()  { return view('legal.aviso-legal'); }
    public function privacidad()  { return view('legal.privacidad');  }
    public function cookies()     { return view('legal.cookies');     }
    public function terminos()    { return view('legal.terminos');    }
    public function reembolso()   { return view('legal.reembolso');   }

    /**
     * Record a user consent (RGPD art. 7.1 - burden of proof).
     */
    public function recordConsent(Request $request)
    {
        $data = $request->validate([
            'type'    => 'required|in:cookies_analytics,cookies_marketing,tos,privacy,withdrawal_waiver',
            'granted' => 'required|boolean',
            'version' => 'nullable|string|max:20',
        ]);

        UserConsent::create([
            'user_id'      => auth()->id(),
            'session_id'   => $request->session()->getId(),
            'consent_type' => $data['type'],
            'granted'      => $data['granted'],
            'version'      => $data['version'] ?? '1.0',
            'ip_address'   => $request->ip(),
            'user_agent'   => substr($request->userAgent() ?? '', 0, 500),
        ]);

        return response()->json(['ok' => true]);
    }
}
