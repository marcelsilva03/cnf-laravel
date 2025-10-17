<?php

namespace Database\Factories;

use App\Models\PrecoCertidoes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrecoCertidoes>
 */
class PrecoCertidoesFactory extends Factory
{
    protected $model = PrecoCertidoes::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'UF' => $this->faker->stateAbbr,
            'BREVE_RELATO' => $this->faker->randomFloat(2, 20, 100),
            'INTEIRO_TEOR' => $this->faker->randomFloat(2, 50, 150),
            'CNF_BREVE_RELATO' => $this->faker->numberBetween(100, 500),
            'CNF_INTEIRO_TEOR' => $this->faker->numberBetween(500, 1000),
        ];
    }
}
