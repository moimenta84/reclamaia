<?php

namespace Database\Factories;

use App\Models\DeceasedInsuranceSearch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeceasedInsuranceSearchFactory extends Factory
{
    protected $model = DeceasedInsuranceSearch::class;

    public function definition(): array
    {
        return [
            'user_id'               => null,
            'deceased_name'         => fake()->name(),
            'deceased_dni'          => strtoupper(fake()->bothify('########?')),
            'deceased_birth_date'   => fake()->dateTimeBetween('-80 years', '-30 years')->format('Y-m-d'),
            'deceased_death_date'   => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'deceased_province'     => fake()->randomElement(['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Málaga']),
            'applicant_name'        => fake()->name(),
            'applicant_dni'         => strtoupper(fake()->bothify('########?')),
            'applicant_relationship'=> fake()->randomElement(['Heredero/a', 'Hijo/a', 'Cónyuge / pareja de hecho']),
            'applicant_email'       => fake()->safeEmail(),
            'applicant_phone'       => fake()->numerify('6########'),
            'status'                => 'pendiente_documentacion',
            'notes'                 => null,
        ];
    }

    public function withInsurer(): static
    {
        return $this->state([
            'status'         => 'seguro_encontrado',
            'insurer_found'  => 'MAPFRE Vida',
            'policy_type'    => 'Seguro de vida',
            'insured_amount' => fake()->randomFloat(2, 10000, 200000),
        ]);
    }

    public function cobrado(): static
    {
        return $this->state([
            'status'      => 'cobrado',
            'resolved_at' => now(),
        ]);
    }
}
