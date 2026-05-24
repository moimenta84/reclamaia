<?php

namespace Tests\Unit;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests del modelo User.
 * Verifica hasActiveSubscription() y relaciones.
 */
class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_active_subscription_returns_false_without_subscription(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->hasActiveSubscription());
    }

    public function test_has_active_subscription_returns_true_with_active_subscription(): void
    {
        $user = User::factory()->create();
        Subscription::factory()->active()->create(['user_id' => $user->id]);

        $user->refresh();
        $this->assertTrue($user->hasActiveSubscription());
    }

    public function test_has_active_subscription_returns_false_with_canceled_subscription(): void
    {
        $user = User::factory()->create();
        Subscription::factory()->canceled()->create(['user_id' => $user->id]);

        $user->refresh();
        $this->assertFalse($user->hasActiveSubscription());
    }

    public function test_user_has_claims_relation(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->claims());
    }

    public function test_user_has_subscription_relation(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class, $user->subscription());
    }

    public function test_password_is_hidden_in_serialization(): void
    {
        $user = User::factory()->create();
        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }
}
