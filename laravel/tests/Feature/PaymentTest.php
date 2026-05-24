<?php

namespace Tests\Feature;

use App\Models\Claim;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    // ─── Payment page (GET /pago/{claim}) ────────────────────────

    public function test_payment_page_returns_200_for_existing_claim(): void
    {
        $claim = Claim::factory()->create();

        $this->get(route('payment.show', $claim))
             ->assertStatus(200);
    }

    public function test_payment_page_returns_404_for_nonexistent_claim(): void
    {
        $this->get(route('payment.show', 99999))
             ->assertStatus(404);
    }

    public function test_subscriber_is_redirected_from_payment_to_download(): void
    {
        $user  = User::factory()->create();
        Subscription::factory()->active()->create(['user_id' => $user->id]);
        $claim = Claim::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
             ->get(route('payment.show', $claim))
             ->assertRedirect(route('claim.download', $claim));
    }

    public function test_guest_can_view_payment_page(): void
    {
        $claim = Claim::factory()->create();

        $this->get(route('payment.show', $claim))
             ->assertStatus(200);
    }

    // ─── Success page (GET /pago/{claim}/completado) ──────────────

    public function test_payment_success_page_returns_200(): void
    {
        $claim = Claim::factory()->create(['status' => Claim::STATUS_COMPLETED]);

        $this->get(route('payment.success', $claim))
             ->assertStatus(200);
    }

    public function test_payment_success_returns_404_for_nonexistent_claim(): void
    {
        $this->get(route('payment.success', 99999))
             ->assertStatus(404);
    }

    // ─── Create intent (POST /pago/{claim}/intent) ────────────────

    public function test_create_intent_returns_409_if_claim_already_paid(): void
    {
        $user  = User::factory()->create();
        $claim = Claim::factory()->create(['user_id' => $user->id]);
        Payment::create([
            'claim_id'                 => $claim->id,
            'user_id'                  => $user->id,
            'stripe_payment_intent_id' => 'pi_already_paid',
            'amount_cents'             => 999,
            'currency'                 => 'eur',
            'status'                   => Payment::STATUS_COMPLETED,
        ]);

        $this->postJson(route('payment.intent', $claim))
             ->assertStatus(409);
    }

    public function test_create_intent_returns_500_without_stripe_configured(): void
    {
        config(['services.stripe.secret' => '']);
        $claim = Claim::factory()->create();

        $this->postJson(route('payment.intent', $claim))
             ->assertStatus(500);
    }
}
