<?php

namespace Database\Factories;

use App\Enums\Parentesco;
use App\Models\Falecido;
use App\Models\Homenagem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Homenagem>
 */
class HomenagemFactory extends Factory
{
    protected $model = Homenagem::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bgImages = [];
        for($i = 1; $i < 10; ++$i) {
            $bgImages[] = "peace$i.jpg";
        }
        $grausDeParentescos = array_keys(Parentesco::toArray());
        $randomHexNumber = $this->faker->numberBetween(0, 0xffffffff);
        $falecido = Falecido::inRandomOrder()->first();
        return [
            'hom_id_falecido' => $falecido->fal_id,
            'hom_uuid_falecido' => $falecido->fal_uuid,
            'hom_nome_autor' => $this->faker->name,
            'hom_cpf_autor' => $this->faker->numerify('###########'),
            'hom_url_foto' => '',
            'hom_url_fundo' => $this->faker->randomElement($bgImages),
            'hom_mensagem' => $this->faker->paragraph,
            'hom_whatsapp' => $this->faker->e164PhoneNumber(),
            'hom_email' => $this->faker->email(),
            'hom_parentesco' => $this->faker->randomElement($grausDeParentescos),
            'hom_codigo' => str_pad(dechex($randomHexNumber), 8, '0', STR_PAD_LEFT),
        ];
    }
}
