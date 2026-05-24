<?php

namespace Tests\Feature;

use App\Models\DeceasedInsuranceSearch;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests del módulo RCSCF — Seguros del Fallecido.
 * Verifica acceso, CRUD y protección de ownership.
 */
class DeceasedInsuranceTest extends TestCase
{
    use RefreshDatabase;

    private function subscribedUser(): User
    {
        $user = User::factory()->create();
        Subscription::factory()->active()->create(['user_id' => $user->id]);
        return $user;
    }

    // ─── Acceso y gating ───────────────────────────────────────────────

    public function test_guest_is_redirected_from_index(): void
    {
        $this->get(route('tools.fallecido.index'))->assertRedirect(route('login'));
    }

    public function test_non_subscriber_is_redirected_from_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->get(route('tools.fallecido.index'))
             ->assertRedirect(route('subscription.plans'));
    }

    public function test_subscriber_can_access_index(): void
    {
        $user = $this->subscribedUser();

        $this->actingAs($user)
             ->get(route('tools.fallecido.index'))
             ->assertStatus(200);
    }

    public function test_subscriber_can_access_create_form(): void
    {
        $user = $this->subscribedUser();

        $this->actingAs($user)
             ->get(route('tools.fallecido.create'))
             ->assertStatus(200);
    }

    // ─── Crear expediente ──────────────────────────────────────────────

    public function test_subscriber_can_create_search(): void
    {
        $user = $this->subscribedUser();

        $response = $this->actingAs($user)->post(route('tools.fallecido.store'), [
            'deceased_name'          => 'Manuel Fernández Ruiz',
            'deceased_dni'           => '12345678A',
            'deceased_death_date'    => '2024-01-15',
            'applicant_name'         => 'Laura Fernández',
            'applicant_dni'          => '87654321B',
            'applicant_relationship' => 'Heredero/a',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('deceased_insurance_searches', [
            'deceased_name'  => 'Manuel Fernández Ruiz',
            'user_id'        => $user->id,
        ]);
    }

    public function test_store_requires_mandatory_fields(): void
    {
        $user = $this->subscribedUser();

        $response = $this->actingAs($user)->post(route('tools.fallecido.store'), []);

        $response->assertSessionHasErrors([
            'deceased_name',
            'deceased_dni',
            'deceased_death_date',
            'applicant_name',
            'applicant_dni',
            'applicant_relationship',
        ]);
    }

    // ─── Ver expediente ────────────────────────────────────────────────

    public function test_subscriber_can_view_own_search(): void
    {
        $user   = $this->subscribedUser();
        $search = DeceasedInsuranceSearch::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
             ->get(route('tools.fallecido.show', $search))
             ->assertStatus(200);
    }

    public function test_subscriber_cannot_view_another_users_search(): void
    {
        $owner  = $this->subscribedUser();
        $other  = $this->subscribedUser();
        $search = DeceasedInsuranceSearch::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
             ->get(route('tools.fallecido.show', $search))
             ->assertStatus(403);
    }

    // ─── Actualizar estado ─────────────────────────────────────────────

    public function test_subscriber_can_update_search_status(): void
    {
        $user   = $this->subscribedUser();
        $search = DeceasedInsuranceSearch::factory()->create([
            'user_id' => $user->id,
            'status'  => 'pendiente_documentacion',
        ]);

        $response = $this->actingAs($user)->patch(route('tools.fallecido.update', $search), [
            'status' => 'tramite_iniciado',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('deceased_insurance_searches', [
            'id'     => $search->id,
            'status' => 'tramite_iniciado',
        ]);
    }

    // ─── Eliminar expediente ───────────────────────────────────────────

    public function test_subscriber_can_delete_own_search(): void
    {
        $user   = $this->subscribedUser();
        $search = DeceasedInsuranceSearch::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
             ->delete(route('tools.fallecido.destroy', $search))
             ->assertRedirect(route('tools.fallecido.index'));

        $this->assertDatabaseMissing('deceased_insurance_searches', ['id' => $search->id]);
    }

    public function test_subscriber_cannot_delete_another_users_search(): void
    {
        $owner  = $this->subscribedUser();
        $other  = $this->subscribedUser();
        $search = DeceasedInsuranceSearch::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
             ->delete(route('tools.fallecido.destroy', $search))
             ->assertStatus(403);

        $this->assertDatabaseHas('deceased_insurance_searches', ['id' => $search->id]);
    }

    // ─── Index solo muestra expedientes propios ─────────────────────────

    public function test_index_only_shows_own_searches(): void
    {
        $user  = $this->subscribedUser();
        $other = $this->subscribedUser();

        DeceasedInsuranceSearch::factory()->create([
            'user_id'       => $user->id,
            'deceased_name' => 'Propio Fallecido',
        ]);
        DeceasedInsuranceSearch::factory()->create([
            'user_id'       => $other->id,
            'deceased_name' => 'Ajeno Fallecido',
        ]);

        $response = $this->actingAs($user)->get(route('tools.fallecido.index'));

        $response->assertSee('Propio Fallecido');
        $response->assertDontSee('Ajeno Fallecido');
    }
}
