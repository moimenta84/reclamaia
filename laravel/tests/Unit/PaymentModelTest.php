<?php

namespace Tests\Unit;

use App\Models\Claim;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentModelTest extends TestCase
{
    use RefreshDatabase;

    // ─── Constants ───────────────────────────────────────────────

    public function test_status_constants_are_defined(): void
    {
        $this->assertSame('pending',   Payment::STATUS_PENDING);
        $this->assertSame('completed', Payment::STATUS_COMPLETED);
        $this->assertSame('failed',    Payment::STATUS_FAILED);
        $this->assertSame('refunded',  Payment::STATUS_REFUNDED);
    }

    // ─── Relations ───────────────────────────────────────────────

    public function test_payment_belongs_to_claim(): void
    {
        $user    = User::factory()->create();
        $claim   = Claim::factory()->create(['user_id' => $user->id]);
        $payment = Payment::create([
            'claim_id'                 => $claim->id,
            'user_id'                  => $user->id,
            'stripe_payment_intent_id' => 'pi_test_belongs',
            'amount_cents'             => 999,
            'currency'                 => 'eur',
            'status'                   => Payment::STATUS_PENDING,
        ]);

        $this->assertInstanceOf(Claim::class, $payment->claim);
        $this->assertEquals($claim->id, $payment->claim->id);
    }

    public function test_payment_belongs_to_user(): void
    {
        $user    = User::factory()->create();
        $claim   = Claim::factory()->create(['user_id' => $user->id]);
        $payment = Payment::create([
            'claim_id'                 => $claim->id,
            'user_id'                  => $user->id,
            'stripe_payment_intent_id' => 'pi_test_user',
            'amount_cents'             => 999,
            'currency'                 => 'eur',
            'status'                   => Payment::STATUS_PENDING,
        ]);

        $this->assertInstanceOf(User::class, $payment->user);
        $this->assertEquals($user->id, $payment->user->id);
    }

    // ─── Casts ───────────────────────────────────────────────────

    public function test_amount_cents_is_cast_to_integer(): void
    {
        $user    = User::factory()->create();
        $claim   = Claim::factory()->create(['user_id' => $user->id]);
        $payment = Payment::create([
            'claim_id'                 => $claim->id,
            'user_id'                  => $user->id,
            'stripe_payment_intent_id' => 'pi_test_cast',
            'amount_cents'             => '999',
            'currency'                 => 'eur',
            'status'                   => Payment::STATUS_PENDING,
        ]);

        $this->assertIsInt($payment->amount_cents);
        $this->assertSame(999, $payment->amount_cents);
    }
}
