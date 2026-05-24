<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClaimFactory extends Factory
{
    protected $model = Claim::class;

    public function definition(): array
    {
        return [
            'user_id'          => null,
            'claim_type'       => 'insurance',
            'insurer_name'     => fake()->randomElement(['MAPFRE', 'AXA', 'Generali', 'Allianz', 'Zurich']),
            'description'      => fake()->paragraph(2),
            'claimant_name'    => fake()->name(),
            'claimant_dni'     => strtoupper(fake()->bothify('########?')),
            'claimant_email'   => fake()->safeEmail(),
            'claimant_phone'   => fake()->numerify('6########'),
            'claimant_address' => fake()->address(),
            'policy_number'    => fake()->bothify('POL-########'),
            'status'           => Claim::STATUS_COMPLETED,
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => Claim::STATUS_PENDING]);
    }

    public function forUser(User $user): static
    {
        return $this->state(['user_id' => $user->id]);
    }
}
