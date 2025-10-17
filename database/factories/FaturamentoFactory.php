<?php
namespace Database\Factories;

use App\Models\Faturamento;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaturamentoFactory extends Factory
{
    protected $model = Faturamento::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'valor' => $this->faker->randomFloat(2, 1, 1000),
            'data_pagamento' => $this->faker->date(),
            'descricao' => $this->faker->sentence(),
        ];
    }
}
