<?php

namespace Database\Factories;

use App\Models\Falecido;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Falecido>
 */
class FalecidoFactory extends Factory
{
    protected $model = Falecido::class;

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
        $tipoDeLocais = config('constants.tipoLocalDeObito');
        $estadosCivis = array_keys(config('constants.estadosCivis'));
        return [
            'fal_uuid' => Str::uuid(),
            'fal_id_cnf' => $this->faker->numberBetween(1, 10),
            'fal_foto' => '',
            'fal_data_cadastro' => $this->faker->unixTime,
            'fal_id_usr' => $this->faker->numberBetween(1, 10),
            'fal_id_fun' => $this->faker->numberBetween(1, 10),
            'fal_id_prf' => $this->faker->numberBetween(1, 10),
            'fal_domicilio_id_ecd' => $this->faker->numberBetween(1, 10),
            'fal_id_esc' => $this->faker->numberBetween(1, 10),
            'fal_id_crt' => $this->faker->numberBetween(1, 10),
            'fal_id_ccc' => $this->faker->numberBetween(1, 10),
            'fal_id_orf' => $this->faker->numberBetween(1, 10),
            'fal_id_cmn' => $this->faker->numberBetween(1, 10),
            'fal_nome_abr' => strtoupper($this->faker->lexify('???')),
            'fal_nome' => $this->faker->name,
            'fal_apelido' => $this->faker->firstName,
            'fal_cpf' => $this->faker->randomElement(['', $this->faker->numerify('###########')]),
            'fal_rg' => $this->faker->randomElement([
                '',
                $this->faker->numerify('#######'),
                $this->faker->numerify('########'),
                $this->faker->numerify('#########'),
                $this->faker->numerify('##########'),
            ]),
            'fal_sexo' => $this->faker->numberBetween(1, 2),
            'fal_titulo_eleitor' => $this->faker->numerify('##########'),
            'fal_data_falecimento' => $this->faker->date,
            'fal_nome_pai' => $this->faker->name('male'),
            'fal_nome_mae' => $this->faker->name('female'),
            'fal_homenagem' => $this->faker->paragraph,
            'fal_biografia' => $this->faker->text,
            'fal_tipo_logo' => $this->faker->numberBetween(1, 3),
            'fal_obito_id_ecd' => $this->faker->numberBetween(1, 10),
            'fal_cartorio_obito' => $this->faker->company,
            'fal_sepultamento_id_ecd' => $this->faker->numberBetween(1, 10),
            'fal_local_sepultamento' => $this->faker->address,
            'fal_data_sepultamento' => $this->faker->unixTime,
            'fal_hora_sepultamento' => $this->faker->numberBetween(0, 2359),
            'fal_local_velorio' => $this->faker->address,
            'fal_tipo_local_falecimento' => $this->faker->randomElement($tipoDeLocais),
            'fal_data_nascimento' => $this->faker->date,
            'fal_estado_civil' => $this->faker->randomElement($estadosCivis),
            'fal_album_id' => $this->faker->unique()->numberBetween(1, 10000000),
            'fal_importada' => $this->faker->boolean,
            'fal_uf' => $uf,
            'fal_cidade' => $cidade,
            'fal_orkut' => $this->faker->url,
            'fal_youtube' => $this->faker->url,
            'fal_co_livro' => $this->faker->numerify('###'),
            'fal_co_folha' => $this->faker->numerify('###'),
            'fal_co_termo' => $this->faker->numerify('###'),
            'fal_co_declaracao' => $this->faker->numerify('###'),
            'fal_controle1' => $this->faker->boolean,
            'fal_controle2' => $this->faker->boolean,
            'fal_idade' => $this->faker->numberBetween(1, 100),
            'fal_obs' => $this->faker->sentence,
//            'fal_tit_ele_aux' => $this->faker->unique()->numberBetween(1, 10000),
        ];
    }
}
