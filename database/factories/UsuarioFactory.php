<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usr_data_cadastro' => $this->faker->unixTime,
            'usr_ativo' => $this->faker->boolean,
            'usr_login' => $this->faker->userName,
            'usr_senha' => $this->faker->password,
            'usr_email' => $this->faker->safeEmail,
            'usr_nome' => $this->faker->name,
            'usr_data_nascimento' => $this->faker->randomNumber(),
            'usr_sexo' => $this->faker->numberBetween(0,1),
            'usr_cpf' => $this->faker->numerify('###.###.###-##'),
            'usr_rg' => $this->faker->numerify('##.###.###-#'),
            'usr_endereco' => $this->faker->streetAddress,
            'usr_endereco_numero' => $this->faker->buildingNumber,
            'usr_endereco_complemento' => $this->faker->secondaryAddress,
            'usr_endereco_bairro' => $this->faker->word,
            'usr_endereco_cep' => $this->faker->postcode,
            'usr_fone_numero' => $this->faker->phoneNumber,
            'usr_id_fun' => $this->faker->numberBetween(1, 10), // assuming there are 10 rows in the usuario_funcao table
        ];
    }
}
