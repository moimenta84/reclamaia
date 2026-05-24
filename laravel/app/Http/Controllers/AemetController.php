<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AemetController extends Controller
{
    private string $pythonUrl;
    private string $internalKey;

    public function __construct()
    {
        $this->pythonUrl   = rtrim(config('services.python.url', 'http://localhost:8001'), '/');
        $this->internalKey = config('services.python.internal_secret', '');
    }

    public function alertas()
    {
        // Cache alerts for 30 minutes — AEMET updates every hour
        $data = Cache::remember('aemet_alertas', 1800, function () {
            $response = Http::withHeaders(['X-Internal-Key' => $this->internalKey])
                ->timeout(10)
                ->get("{$this->pythonUrl}/api/aemet/alertas");

            return $response->successful() ? $response->json() : ['alertas' => []];
        });

        return response()->json($data);
    }

    public function historial(Request $request)
    {
        $request->validate([
            'fecha'     => 'required|date_format:Y-m-d',
            'provincia' => 'required|string|max:60',
        ]);

        $response = Http::withHeaders(['X-Internal-Key' => $this->internalKey])
            ->timeout(12)
            ->post("{$this->pythonUrl}/api/aemet/historial", $request->only('fecha', 'provincia'));

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'No se pudo obtener datos de AEMET'], 502);
    }
}
