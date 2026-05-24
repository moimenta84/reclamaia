<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PublicDataController extends Controller
{
    private string $pythonUrl;
    private string $internalKey;

    public function __construct()
    {
        $this->pythonUrl   = rtrim(config('services.python.url', 'http://localhost:8001'), '/');
        $this->internalKey = config('services.python.internal_secret', '');
    }

    private function python(string $method, string $endpoint, array $data = []): array
    {
        $req = Http::withHeaders(['X-Internal-Key' => $this->internalKey])->timeout(12);
        $res = $method === 'GET'
            ? $req->get("{$this->pythonUrl}{$endpoint}")
            : $req->post("{$this->pythonUrl}{$endpoint}", $data);

        return $res->successful() ? $res->json() : ['error' => 'Sin respuesta del servicio'];
    }

    // ─── BOE ─────────────────────────────────────────────────────────────────

    public function boeNorma(string $key)
    {
        $data = Cache::remember("boe_norma_{$key}", 86400, fn() =>
            $this->python('GET', "/api/boe/norma/{$key}")
        );
        return response()->json($data);
    }

    public function boeSearch(Request $request)
    {
        $request->validate(['query' => 'required|string|max:200']);
        $data = $this->python('POST', '/api/boe/search', $request->only('query', 'date_from'));
        return response()->json($data);
    }

    public function boeNormativaReclamacion(Request $request)
    {
        $request->validate(['tipo_seguro' => 'required|string|max:100']);
        $tipo = $request->input('tipo_seguro');
        $data = Cache::remember("boe_normativa_" . md5($tipo), 3600, fn() =>
            $this->python('POST', '/api/boe/normativa-reclamacion', ['tipo_seguro' => $tipo])
        );
        return response()->json($data);
    }

    // ─── DGSFP ───────────────────────────────────────────────────────────────

    public function dgsfpAseguradora(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:120']);
        $nombre = $request->input('nombre');
        $data = Cache::remember("dgsfp_aseg_" . md5($nombre), 86400, fn() =>
            $this->python('POST', '/api/dgsfp/aseguradora', ['nombre' => $nombre])
        );
        return response()->json($data);
    }

    public function dgsfpSanciones(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:120']);
        $nombre = $request->input('nombre');
        $data = Cache::remember("dgsfp_sanc_" . md5($nombre), 86400, fn() =>
            $this->python('POST', '/api/dgsfp/sanciones', ['nombre' => $nombre])
        );
        return response()->json($data);
    }

    public function dgsfpDefensor(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:120']);
        $nombre = $request->input('nombre');
        $data = Cache::remember("dgsfp_def_" . md5($nombre), 86400, fn() =>
            $this->python('POST', '/api/dgsfp/defensor', ['nombre' => $nombre])
        );
        return response()->json($data);
    }
}
