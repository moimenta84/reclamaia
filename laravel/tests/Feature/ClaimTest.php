<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests del flujo de creación y gestión de reclamaciones.
 */
class ClaimTest extends TestCase
{
    use RefreshDatabase;

    // ─── Formulario público ────────────────────────────────────────────

    public function test_claim_create_form_is_accessible(): void
    {
        $this->get(route('claim.create'))->assertStatus(200);
    }

    public function test_claim_store_requires_minimum_fields(): void
    {
        $response = $this->post(route('claim.store'), []);

        // Debe fallar validación con 422 o redirect con errors
        $response->assertStatus(302); // redirect back con errors
        $response->assertSessionHasErrors(['claim_type', 'insurer_name', 'description', 'claimant_name', 'claimant_dni']);
    }

    public function test_claim_store_with_valid_data_redirects(): void
    {
        $response = $this->post(route('claim.store'), [
            'claim_type'       => 'insurance',
            'insurer_name'     => 'MAPFRE',
            'description'      => 'El seguro de hogar no ha cubierto los daños por agua correctamente, superando los 50 caracteres mínimos.',
            'claimant_name'    => 'Ana García',
            'claimant_dni'     => '12345678A',
            'claimant_email'   => 'ana@test.com',
            'claimant_phone'   => '600000000',
            'claimant_address' => 'Calle Mayor 1, 28001 Madrid',
            'policy_number'    => 'POL-123456',
        ]);

        // Debe redirigir (a pago o show)
        $response->assertRedirect();
        $this->assertDatabaseHas('claims', [
            'claimant_name' => 'Ana García',
            'insurer_name'  => 'MAPFRE',
        ]);
    }

    // ─── Ownership protection ──────────────────────────────────────────

    public function test_user_cannot_access_another_users_claim(): void
    {
        $owner  = User::factory()->create();
        $hacker = User::factory()->create();
        $claim  = Claim::factory()->forUser($owner)->create();

        // Intentar descargar el PDF del claim de otro usuario
        $response = $this->actingAs($hacker)
                         ->get(route('claim.download.pdf', $claim));

        // Debe ser 403 — no autorizado
        $response->assertStatus(403);
    }

    // ─── Mark as sent ──────────────────────────────────────────────────

    public function test_user_can_mark_own_claim_as_sent(): void
    {
        $user  = User::factory()->create();
        $claim = Claim::factory()->forUser($user)->create([
            'status' => Claim::STATUS_COMPLETED,
        ]);

        $response = $this->actingAs($user)
                         ->post(route('claim.mark-sent', $claim));

        $response->assertRedirect();
        $this->assertNotNull($claim->fresh()->sent_to_insurer_at);
    }

    public function test_guest_cannot_mark_claim_as_sent(): void
    {
        $user  = User::factory()->create();
        $claim = Claim::factory()->forUser($user)->create();

        $this->post(route('claim.mark-sent', $claim))
             ->assertRedirect(route('login'));
    }
}
