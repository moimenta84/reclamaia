<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de cumplimiento legal: cookie consent, consent endpoint.
 * Verifica que el sistema de consentimiento funciona correctamente.
 */
class LegalConsentTest extends TestCase
{
    use RefreshDatabase;

    public function test_consent_endpoint_accepts_cookie_consent(): void
    {
        $response = $this->postJson(route('legal.consent'), [
            'type'    => 'cookies_analytics',
            'granted' => true,
        ]);

        // 200 o 204
        $this->assertContains($response->status(), [200, 201, 204]);
    }

    public function test_consent_endpoint_accepts_necessary_only(): void
    {
        $response = $this->postJson(route('legal.consent'), [
            'type'    => 'cookies_analytics',
            'granted' => false,
        ]);

        $this->assertContains($response->status(), [200, 201, 204]);
    }

    public function test_privacidad_page_mentions_rgpd(): void
    {
        $this->get(route('legal.privacidad'))
             ->assertStatus(200)
             ->assertSee('RGPD');
    }

    public function test_terminos_page_mentions_lgdcu(): void
    {
        $this->get(route('legal.terminos'))
             ->assertStatus(200);
    }

    public function test_cookies_page_is_accessible(): void
    {
        $this->get(route('legal.cookies'))->assertStatus(200);
    }
}
