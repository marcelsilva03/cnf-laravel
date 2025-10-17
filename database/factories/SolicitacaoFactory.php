<?php

namespace Database\Factories;

use App\Models\Solicitacao;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Solicitacao>
 */
class SolicitacaoFactory extends Factory
{
    protected $model = Solicitacao::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $estadosCivis = array_keys(config('constants.estadosCivis'));
        $status = array_keys(config('constants.statusComunicadoDeObito'));
        $pagCode = Uuid::uuid4();
        $locais = config('constants.tipoLocalDeObito');
        return [
            'sol_status' => $this->faker->randomElement($status),
            'sol_nome_sol' => $this->faker->name,
            'sol_tel_sol' => $this->faker->numerify('###########'),
            'sol_email_sol' => $this->faker->email,
            'sol_nome_fal' => $this->faker->name,
            'sol_rg_fal' => $this->faker->numerify('#########'),
            'sol_cpf_fal' => $this->faker->numerify('###########'),
            'sol_nome_pai_fal' => $this->faker->name('male'),
            'sol_nome_mae_fal' => $this->faker->name('female'),
            'sol_data_nascimento' => $this->faker->date('Ymd'),
            'sol_data_obito' => $this->faker->date('Ymd'),
            'sol_local_obito_tipo' => $this->faker->randomElement($locais),
            'sol_valor' => $this->faker->randomFloat(2, 50, 150),
            'sol_obs' => $this->faker->sentence,
            'sol_estado_civil' => $this->faker->randomElement($estadosCivis),
            'sol_titulo_eleitor' => $this->faker->numerify('##########'),
            'pag_code' => $pagCode,
            'pag_metodo_escolhido' => $this->faker->randomElement([1,2,3,4]),
            'pag_token_transacao' => $this->faker->numerify('#########'),
            'pag_date' => $this->faker->dateTimeThisYear()->format('YmdHis'),
        ];
    }

    /**
     * Indicate that the solicitação is awaiting payment.
     */
    public function awaitingPayment()
    {
        return $this->state(function (array $attributes) {
            return [
                'sol_status' => Solicitacao::STATUS['AGUARDANDO_PAGAMENTO'],
            ];
        });
    }

    /**
     * Indicate that the solicitação has been paid.
     */
    public function paid()
    {
        return $this->state(function (array $attributes) {
            return [
                'sol_status' => Solicitacao::STATUS['PAGO'],
                'pag_date' => now()->format('YmdHis'),
                'pag_metodo_escolhido' => 1, // PIX
                'pag_token_transacao' => 'PAY-' . $this->faker->uuid(),
            ];
        });
    }

    /**
     * Indicate that the solicitação is in progress.
     */
    public function inProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'sol_status' => Solicitacao::STATUS['EM_ANDAMENTO'],
            ];
        });
    }

    /**
     * Indicate that the solicitação has been completed.
     */
    public function finalizado()
    {
        return $this->state(function (array $attributes) {
            return [
                'sol_status' => Solicitacao::STATUS['FINALIZADO'],
            ];
        });
    }

    /**
     * Indicate that the solicitação has been cancelled.
     */
    public function cancelado()
    {
        return $this->state(function (array $attributes) {
            return [
                'sol_status' => Solicitacao::STATUS['CANCELADO'],
            ];
        });
    }
}
