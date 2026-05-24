<?php

namespace Tests\Unit;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionModelTest extends TestCase
{
    use RefreshDatabase;

    // ─── isActive ────────────────────────────────────────────────

    public function test_is_active_returns_true_when_status_active(): void
    {
        $user = User::factory()->create();
        $sub  = Subscription::factory()->active()->create(['user_id' => $user->id]);

        $this->assertTrue($sub->isActive());
    }

    /**
     * @dataProvider inactiveStatuses
     */
    public function test_is_active_returns_false_for_inactive_status(string $status): void
    {
        $user = User::factory()->create();
        $sub  = Subscription::factory()->create([
            'user_id' => $user->id,
            'status'  => $status,
        ]);

        $this->assertFalse($sub->isActive());
    }

    public static function inactiveStatuses(): array
    {
        return [
            'canceled' => ['canceled'],
            'past_due' => ['past_due'],
            'unpaid'   => ['unpaid'],
        ];
    }

    // ─── Relations ───────────────────────────────────────────────

    public function test_subscription_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $sub  = Subscription::factory()->active()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $sub->user);
        $this->assertEquals($user->id, $sub->user->id);
    }

    // ─── Casts ───────────────────────────────────────────────────

    public function test_current_period_end_is_cast_to_datetime(): void
    {
        $user = User::factory()->create();
        $sub  = Subscription::factory()->create([
            'user_id'            => $user->id,
            'current_period_end' => '2026-12-31 23:59:59',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $sub->current_period_end);
        $this->assertSame(2026, $sub->current_period_end->year);
    }

    public function test_canceled_at_is_cast_to_datetime_when_set(): void
    {
        $user = User::factory()->create();
        $sub  = Subscription::factory()->canceled()->create([
            'user_id'    => $user->id,
            'canceled_at' => '2026-06-01 10:00:00',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $sub->canceled_at);
    }

    public function test_canceled_at_is_null_when_not_set(): void
    {
        $user = User::factory()->create();
        $sub  = Subscription::factory()->active()->create([
            'user_id'    => $user->id,
            'canceled_at' => null,
        ]);

        $this->assertNull($sub->canceled_at);
    }
}
