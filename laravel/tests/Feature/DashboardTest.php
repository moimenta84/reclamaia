<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests del panel de control: acceso, KPIs, rutas protegidas.
 */
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    // ─── Acceso ────────────────────────────────────────────────────────

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('dashboard'))->assertStatus(200);
    }

    // ─── Contenido ─────────────────────────────────────────────────────

    public function test_dashboard_shows_user_claims_count(): void
    {
        $user = User::factory()->create();
        Claim::factory()->count(3)->forUser($user)->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_dashboard_does_not_show_other_users_claims(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        Claim::factory()->forUser($other)->create(['claimant_name' => 'OtroUsuario']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertDontSee('OtroUsuario');
    }

    // ─── Herramientas Pro — subscriber gating ──────────────────────────

    public function test_pro_tools_redirect_guest_to_login(): void
    {
        $this->get(route('tools.index'))->assertRedirect(route('login'));
    }

    public function test_pro_tools_redirect_non_subscriber_to_plans(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('tools.index'))
             ->assertRedirect(route('subscription.plans'));
    }

    public function test_subscriber_can_access_pro_tools(): void
    {
        $user = User::factory()->create();
        Subscription::factory()->active()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get(route('tools.index'))->assertStatus(200);
    }

    // ─── Suscripción plans page ─────────────────────────────────────────

    public function test_subscription_plans_requires_auth(): void
    {
        $this->get(route('subscription.plans'))->assertRedirect(route('login'));
    }

    public function test_subscription_plans_returns_200_for_auth_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('subscription.plans'))->assertStatus(200);
    }
}
