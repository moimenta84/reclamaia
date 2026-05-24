<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de flujo de autenticación: registro, login, logout.
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ─── Registro ─────────────────────────────────────────────────────

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post(route('register'), [
            'name'                  => 'Carlos López',
            'email'                 => 'carlos@asesorialopez.es',
            'password'              => 'segura1234',
            'password_confirmation' => 'segura1234',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'carlos@asesorialopez.es']);
    }

    public function test_registration_fails_with_weak_password(): void
    {
        $response = $this->post(route('register'), [
            'name'                  => 'Test',
            'email'                 => 'test@test.com',
            'password'              => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existente@test.com']);

        $response = $this->post(route('register'), [
            'name'                  => 'Otro',
            'email'                 => 'existente@test.com',
            'password'              => 'segura1234',
            'password_confirmation' => 'segura1234',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_fails_without_name(): void
    {
        $response = $this->post(route('register'), [
            'email'                 => 'test@test.com',
            'password'              => 'segura1234',
            'password_confirmation' => 'segura1234',
        ]);

        $response->assertSessionHasErrors('name');
    }

    // ─── Login ─────────────────────────────────────────────────────────

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create(['password' => bcrypt('mi-password')]);

        $response = $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'mi-password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correcta')]);

        $response = $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'incorrecta',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_fails_with_unknown_email(): void
    {
        $response = $this->post(route('login'), [
            'email'    => 'noexiste@test.com',
            'password' => 'cualquiera',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // ─── Logout ────────────────────────────────────────────────────────

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    // ─── Redirect guards ───────────────────────────────────────────────

    public function test_authenticated_user_is_redirected_from_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('login'))->assertRedirect();
    }

    public function test_authenticated_user_is_redirected_from_register(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('register'))->assertRedirect();
    }
}
