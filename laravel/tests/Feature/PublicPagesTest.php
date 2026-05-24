<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Tests para páginas públicas accesibles sin autenticación.
 * Verifica que el marketing site, las páginas legales y las herramientas
 * públicas responden correctamente.
 */
class PublicPagesTest extends TestCase
{
    // ─── Home ─────────────────────────────────────────────────────────

    public function test_home_returns_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_home_contains_b2b_copy(): void
    {
        $response = $this->get('/');
        $response->assertSee('asesor');  // "asesorías" o "asesor"
    }

    // ─── Healthcheck ───────────────────────────────────────────────────

    public function test_up_endpoint_returns_200(): void
    {
        $response = $this->get('/up');
        $response->assertStatus(200);
    }

    // ─── Legal pages ───────────────────────────────────────────────────

    public function test_legal_terminos_returns_200(): void
    {
        $this->get(route('legal.terminos'))->assertStatus(200);
    }

    public function test_legal_privacidad_returns_200(): void
    {
        $this->get(route('legal.privacidad'))->assertStatus(200);
    }

    public function test_legal_aviso_returns_200(): void
    {
        $this->get(route('legal.aviso'))->assertStatus(200);
    }

    public function test_legal_cookies_returns_200(): void
    {
        $this->get(route('legal.cookies'))->assertStatus(200);
    }

    public function test_legal_reembolso_returns_200(): void
    {
        $this->get(route('legal.reembolso'))->assertStatus(200);
    }

    // ─── Baremo (público, sin auth) ────────────────────────────────────

    public function test_baremo_page_returns_200(): void
    {
        $this->get(route('tools.baremo.show'))->assertStatus(200);
    }

    // ─── Claim form (público) ──────────────────────────────────────────

    public function test_claim_create_returns_200(): void
    {
        $this->get(route('claim.create'))->assertStatus(200);
    }

    // ─── Auth pages ────────────────────────────────────────────────────

    public function test_login_page_returns_200(): void
    {
        $this->get(route('login'))->assertStatus(200);
    }

    public function test_register_page_returns_200(): void
    {
        $this->get(route('register'))->assertStatus(200);
    }
}
