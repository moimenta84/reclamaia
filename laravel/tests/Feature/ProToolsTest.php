<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProToolsTest extends TestCase
{
    use RefreshDatabase;

    private function subscriber(): User
    {
        $user = User::factory()->create();
        Subscription::factory()->active()->create(['user_id' => $user->id]);
        return $user;
    }

    private function freeUser(): User
    {
        return User::factory()->create();
    }

    // ─── Tools index ─────────────────────────────────────────────

    public function test_tools_index_redirects_guest(): void
    {
        $this->get(route('tools.index'))->assertRedirect(route('login'));
    }

    public function test_tools_index_redirects_non_subscriber(): void
    {
        $this->actingAs($this->freeUser())
             ->get(route('tools.index'))
             ->assertRedirect();
    }

    public function test_subscriber_can_access_tools_index(): void
    {
        $this->actingAs($this->subscriber())
             ->get(route('tools.index'))
             ->assertStatus(200);
    }

    // ─── OCR ─────────────────────────────────────────────────────

    public function test_ocr_show_redirects_guest(): void
    {
        $this->get(route('tools.ocr.show'))->assertRedirect(route('login'));
    }

    public function test_ocr_show_redirects_non_subscriber(): void
    {
        $this->actingAs($this->freeUser())
             ->get(route('tools.ocr.show'))
             ->assertRedirect();
    }

    public function test_subscriber_can_access_ocr(): void
    {
        $this->actingAs($this->subscriber())
             ->get(route('tools.ocr.show'))
             ->assertStatus(200);
    }

    // ─── Jurisprudencia ──────────────────────────────────────────

    public function test_jurisprudencia_redirects_guest(): void
    {
        $this->get(route('tools.jurisprudencia.show'))->assertRedirect(route('login'));
    }

    public function test_jurisprudencia_redirects_non_subscriber(): void
    {
        $this->actingAs($this->freeUser())
             ->get(route('tools.jurisprudencia.show'))
             ->assertRedirect();
    }

    public function test_subscriber_can_access_jurisprudencia(): void
    {
        $this->actingAs($this->subscriber())
             ->get(route('tools.jurisprudencia.show'))
             ->assertStatus(200);
    }

    // ─── Valoración vehicular ────────────────────────────────────

    public function test_valoracion_redirects_guest(): void
    {
        $this->get(route('tools.valoracion.show'))->assertRedirect(route('login'));
    }

    public function test_valoracion_redirects_non_subscriber(): void
    {
        $this->actingAs($this->freeUser())
             ->get(route('tools.valoracion.show'))
             ->assertRedirect();
    }

    public function test_subscriber_can_access_valoracion(): void
    {
        $this->actingAs($this->subscriber())
             ->get(route('tools.valoracion.show'))
             ->assertStatus(200);
    }

    // ─── Seguros del fallecido ────────────────────────────────────

    public function test_fallecido_index_redirects_guest(): void
    {
        $this->get(route('tools.fallecido.index'))->assertRedirect(route('login'));
    }

    public function test_fallecido_index_redirects_non_subscriber(): void
    {
        $this->actingAs($this->freeUser())
             ->get(route('tools.fallecido.index'))
             ->assertRedirect();
    }

    public function test_subscriber_can_access_fallecido(): void
    {
        $this->actingAs($this->subscriber())
             ->get(route('tools.fallecido.index'))
             ->assertStatus(200);
    }

    // ─── Viability ───────────────────────────────────────────────

    public function test_viability_redirects_guest(): void
    {
        $this->get(route('viability.show'))->assertRedirect(route('login'));
    }

    public function test_viability_redirects_non_subscriber(): void
    {
        $this->actingAs($this->freeUser())
             ->get(route('viability.show'))
             ->assertRedirect();
    }

    public function test_subscriber_can_access_viability(): void
    {
        $this->actingAs($this->subscriber())
             ->get(route('viability.show'))
             ->assertStatus(200);
    }
}
