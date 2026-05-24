<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Tests de los endpoints API internos (AEMET, BOE, DGSFP).
 * Se mockean las llamadas HTTP al Python service para aislar los tests.
 */
class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Cache::flush();
    }

    // ─── Auth gating ───────────────────────────────────────────────────

    public function test_aemet_alertas_requires_auth(): void
    {
        $this->getJson('/api/aemet/alertas')->assertStatus(401);
    }

    public function test_boe_norma_requires_auth(): void
    {
        $this->getJson('/api/boe/norma/LCS')->assertStatus(401);
    }

    public function test_dgsfp_aseguradora_requires_auth(): void
    {
        $this->postJson('/api/dgsfp/aseguradora', ['nombre' => 'MAPFRE'])
             ->assertStatus(401);
    }

    // ─── AEMET alerts ──────────────────────────────────────────────────

    public function test_aemet_alertas_returns_json_for_auth_user(): void
    {
        Http::fake([
            'localhost:8001/api/alertas-meteorologicas*' => Http::response([
                'alertas' => [],
                'resumen' => 'Sin alertas activas',
            ], 200),
        ]);

        $this->actingAs($this->user)
             ->getJson('/api/aemet/alertas')
             ->assertStatus(200)
             ->assertJsonStructure(['alertas']);
    }

    // ─── BOE ───────────────────────────────────────────────────────────

    public function test_boe_search_returns_json_for_auth_user(): void
    {
        Http::fake([
            'localhost:8001/api/boe/search*' => Http::response([
                'results' => [],
                'total'   => 0,
            ], 200),
        ]);

        $this->actingAs($this->user)
             ->postJson('/api/boe/search', ['query' => 'LCS artículo 18'])
             ->assertStatus(200);
    }

    public function test_boe_normativa_reclamacion_requires_claim_type(): void
    {
        $this->actingAs($this->user)
             ->postJson('/api/boe/normativa-reclamacion', [])
             ->assertStatus(422);
    }

    // ─── DGSFP ─────────────────────────────────────────────────────────

    public function test_dgsfp_aseguradora_requires_nombre(): void
    {
        $this->actingAs($this->user)
             ->postJson('/api/dgsfp/aseguradora', [])
             ->assertStatus(422);
    }

    public function test_dgsfp_aseguradora_returns_json_for_known_insurer(): void
    {
        Http::fake([
            'localhost:8001/api/dgsfp/buscar*' => Http::response([
                'nombre'         => 'MAPFRE España',
                'nif'            => 'A28587208',
                'registro'       => 'R-0030',
                'estado'         => 'Autorizada',
                'defensor_email' => 'defensor@mapfre.com',
            ], 200),
        ]);

        $this->actingAs($this->user)
             ->postJson('/api/dgsfp/aseguradora', ['nombre' => 'MAPFRE'])
             ->assertStatus(200);
    }

    // ─── Pipeline log (internal key) ─────────────────────────────────────

    public function test_pipeline_log_accepts_post(): void
    {
        $secret = 'test-internal-secret';
        config(['reclamaia.internal_api_secret' => $secret]);

        $response = $this->withHeaders(['X-Internal-Key' => $secret])
                         ->postJson('/api/pipeline/log', [
                             'task_ref' => 'T001',
                             'result'   => 'success',
                         ]);

        // 200 — returns {status: logged}
        $this->assertContains($response->status(), [200, 201, 204]);
    }
}
