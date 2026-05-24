<?php

namespace Tests\Unit;

use App\Models\Claim;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClaimModelTest extends TestCase
{
    use RefreshDatabase;

    // ─── isCompleted ──────────────────────────────────────────────

    public function test_is_completed_returns_true_when_status_completed(): void
    {
        $claim = Claim::factory()->create(['status' => Claim::STATUS_COMPLETED]);

        $this->assertTrue($claim->isCompleted());
    }

    /**
     * @dataProvider nonCompletedStatuses
     */
    public function test_is_completed_returns_false_for_non_completed_status(string $status): void
    {
        $claim = Claim::factory()->create(['status' => $status]);

        $this->assertFalse($claim->isCompleted());
    }

    public static function nonCompletedStatuses(): array
    {
        return [
            'pending'    => [Claim::STATUS_PENDING],
            'processing' => [Claim::STATUS_PROCESSING],
            'failed'     => [Claim::STATUS_FAILED],
        ];
    }

    // ─── isPaid ───────────────────────────────────────────────────

    public function test_is_paid_returns_false_without_payment(): void
    {
        $claim = Claim::factory()->create();

        $this->assertFalse($claim->isPaid());
    }

    public function test_is_paid_returns_true_with_completed_payment(): void
    {
        $user  = User::factory()->create();
        $claim = Claim::factory()->create(['user_id' => $user->id]);
        Payment::create([
            'claim_id'                  => $claim->id,
            'user_id'                   => $user->id,
            'stripe_payment_intent_id'  => 'pi_test_completed',
            'amount_cents'              => 999,
            'currency'                  => 'eur',
            'status'                    => Payment::STATUS_COMPLETED,
        ]);

        $claim->refresh();

        $this->assertTrue($claim->isPaid());
    }

    public function test_is_paid_returns_false_with_pending_payment(): void
    {
        $user  = User::factory()->create();
        $claim = Claim::factory()->create(['user_id' => $user->id]);
        Payment::create([
            'claim_id'                  => $claim->id,
            'user_id'                   => $user->id,
            'stripe_payment_intent_id'  => 'pi_test_pending',
            'amount_cents'              => 999,
            'currency'                  => 'eur',
            'status'                    => Payment::STATUS_PENDING,
        ]);

        $claim->refresh();

        $this->assertFalse($claim->isPaid());
    }

    // ─── Relations ───────────────────────────────────────────────

    public function test_claim_belongs_to_user(): void
    {
        $user  = User::factory()->create();
        $claim = Claim::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $claim->user);
        $this->assertEquals($user->id, $claim->user->id);
    }

    public function test_claim_has_payment_relation(): void
    {
        $claim = Claim::factory()->create();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasOne::class,
            $claim->payment()
        );
    }

    public function test_claim_has_document_relation(): void
    {
        $claim = Claim::factory()->create();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasOne::class,
            $claim->document()
        );
    }

    // ─── Constants ───────────────────────────────────────────────

    public function test_status_constants_are_defined(): void
    {
        $this->assertSame('pending',    Claim::STATUS_PENDING);
        $this->assertSame('processing', Claim::STATUS_PROCESSING);
        $this->assertSame('completed',  Claim::STATUS_COMPLETED);
        $this->assertSame('failed',     Claim::STATUS_FAILED);
    }

    // ─── Casts ───────────────────────────────────────────────────

    public function test_viability_analysis_is_cast_to_array(): void
    {
        $claim = Claim::factory()->create([
            'viability_analysis' => ['score' => 85, 'verdict' => 'viable'],
        ]);

        $this->assertIsArray($claim->viability_analysis);
        $this->assertSame(85, $claim->viability_analysis['score']);
    }

    public function test_policy_clauses_is_cast_to_array(): void
    {
        $claim = Claim::factory()->create([
            'policy_clauses' => ['cobertura_agua' => true, 'franquicia' => 300],
        ]);

        $this->assertIsArray($claim->policy_clauses);
        $this->assertTrue($claim->policy_clauses['cobertura_agua']);
    }
}
