<?php

namespace Database\Factories;

use App\Models\ComunicadoDeObito;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComunicadoDeObito>
 */
class ComunicadoDeObitoFactory extends Factory
{
    protected $model = ComunicadoDeObito::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rgsOuNulo = [
            $this->faker->numerify('#########'),
            $this->faker->numerify('############'),
            ''
        ];
        $locaisDeObito = config('constants.tipoLocalDeObito');
        $estadosCivis = array_keys(config('constants.estadosCivis'));
        $listDeStatus = array_keys(config('constants.statusComunicadoDeObito'));
        $randomText = $this->faker->text();
        $localidades = config('constants.localidades');
        $uf = $this->faker->randomElement(array_keys($localidades));
        $cidade = $this->faker->randomElement($localidades[$uf]['cidades']);
        return [
            'nome_sol' => $this->faker->name,
            'fone_sol' => $this->faker->phoneNumber,
            'email_sol' => $this->faker->email,
            'nome_fal' => $this->faker->name,
            'cpf_fal' => $this->faker->numerify('###########'),
            'rg_fal' => $this->faker->randomElement($rgsOuNulo),
            'titulo_eleitor' => $this->faker->numerify('############'),
            'nome_pai_fal' => $this->faker->name,
            'nome_mae_fal' => $this->faker->name,
            'cidade_estado_obito' => "$cidade/$uf",
            'cartorio_id' => $this->faker->numberBetween(1,500),
            'data_nascimento' => $this->faker->dateTimeBetween('1900-01-01', '2010-12-31')->format('Y-m-d'),
            'data_obito' => $this->faker->dateTimeBetween('-50 years', 'now')->format('Y-m-d'),
            'local_obito_tipo' => $this->faker->numberBetween(1, 3),
            'estado_civil' => $this->faker->randomElement($estadosCivis),
            'obs' => $this->faker->randomElement(['', $randomText]),
            'status' => $this->faker->randomElement($listDeStatus),
            'livro' => $this->faker->randomNumber(3),
            'folha' => $this->faker->randomNumber(3),
            'termo' => $this->faker->randomNumber(3),
        ];
    }
}
