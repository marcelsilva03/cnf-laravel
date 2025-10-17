<?php

namespace Database\Factories;

use App\Models\ComunicadoDeErro;
use App\Models\Falecido;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComunicadoDeErro>
 */
class ComunicadoDeErroFactory extends Factory
{
    protected $model = ComunicadoDeErro::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $falecido = Falecido::inRandomOrder()->first();
        return [
            'id_falecido' => $falecido->fal_id,
            'uuid_falecido' => $falecido->fal_uuid,
            'nome_comunicante' => $this->faker->name,
            'email_comunicante' => $this->faker->email,
            'mensagem' => $this->faker->sentence,
        ];
    }
}
