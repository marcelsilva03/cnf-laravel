<?php

namespace Database\Factories;

use App\Models\Cartorio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cartorio>
 */
class CartorioFactory extends Factory
{
    protected $model = Cartorio::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $localidades = config('constants.localidades');
        $uf = $this->faker->randomElement(array_keys($localidades));
        $cidade = $this->faker->randomElement($localidades[$uf]['cidades']);
        return [
            'ccc_id_ecd' => $this->faker->numberBetween(1, 10),
            'ccc_cidade' => $cidade,
            'ccc_uf' => $uf,
            'ccc_email' => $this->faker->unique()->safeEmail,
            'ccc_ultima_atualizacao' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'ccc_nome' => $this->faker->company,
            'ccc_nome_fantasia' => $this->faker->companySuffix,
            'ccc_area_abrangencia' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'ccc_atribuicoes' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'ccc_comarca' => $this->faker->city,
            'ccc_telefone' => $this->faker->phoneNumber,
            'ccc_fax' => $this->faker->phoneNumber,
            'ccc_obs' => $this->faker->text($maxNbChars = 200),
            'ccc_site' => $this->faker->url,
            'ccc_cnpj' => $this->faker->numerify('##.###.###/####-##'),
            'ccc_cns' => $this->faker->numerify('#####'),
            'ccc_endereco' => $this->faker->streetAddress,
            'ccc_bairro' => $this->faker->citySuffix,
            'ccc_cep' => $this->faker->postcode,
            'ccc_nome_titular' => $this->faker->name,
            'ccc_nome_substituto' => $this->faker->name,
            'ccc_nome_juiz' => $this->faker->name,
            'ccc_horario_funcionamento' => $this->faker->time($format = 'H:i:s', $max = 'now'),
            'ccc_entrancia' => $this->faker->word,
            'ccc_tipo' => $this->faker->randomElement([1, 2, 3, 4]), // Assuming types are 1, 2, 3, 4
        ];
    }
}
