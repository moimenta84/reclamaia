<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'user_id'                => null,
            'stripe_subscription_id' => 'sub_' . fake()->regexify('[A-Za-z0-9]{24}'),
            'stripe_price_id'        => 'price_' . fake()->regexify('[A-Za-z0-9]{24}'),
            'status'                 => 'active',
            'current_period_end'     => now()->addMonth(),
            'canceled_at'            => null,
        ];
    }

    public function active(): static
    {
        return $this->state([
            'status'             => 'active',
            'current_period_end' => now()->addMonth(),
            'canceled_at'        => null,
        ]);
    }

    public function canceled(): static
    {
        return $this->state([
            'status'      => 'canceled',
            'canceled_at' => now(),
        ]);
    }
}
