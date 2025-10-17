<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComunicadoDeObito extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDENTE' => 0,
        'APROVADO' => 1,
        'REJEITADO' => 2,
    ];
    protected $table = 'comunicados_de_obito';
    protected $fillable = [
        'nome_sol',
        'fone_sol',
        'email_sol',
        'nome_fal',
        'cpf_fal',
        'rg_fal',
        'titulo_eleitor',
        'nome_pai_fal',
        'nome_mae_fal',
        'cidade_estado_obito',
        'cartorio_id',
        'data_nascimento',
        'data_obito',
        'local_obito_tipo',
        'estado_civil',
        'sexo',
        'obs',
        'status',
        'livro',
        'folha',
        'termo'
    ];

    public static function statusList(): array
    {
        return array_flip(self::STATUS);
    }
    public function preencher(array $dados) : void
    {
        $this->nome_sol = $dados['nome_sol'];
        $this->fone_sol = $dados['fone_sol'];
        $this->email_sol = $dados['email_sol'];
        $this->nome_fal = $dados['nome_fal'];
        $this->cpf_fal = $dados['cpf_fal'];
        $this->rg_fal = $dados['rg_fal'];
        $this->titulo_eleitor = $dados['titulo_eleitor'];
        $this->nome_pai_fal = $dados['nome_pai_fal'];
        $this->nome_mae_fal = $dados['nome_mae_fal'];
        $this->cidade_estado_obito = $dados['cidade_estado_obito'];
        $this->cartorio_id = $dados['cartorio_id'];
        $this->data_nascimento = $dados['data_nascimento'];
        $this->data_obito = $dados['data_obito'];
        $this->local_obito_tipo = $dados['local_obito_tipo'];
        $this->estado_civil = $dados['estado_civil'];
        $this->sexo = $dados['sexo'];
        $this->obs = $dados['obs'];
        $this->livro = $dados['livro'];
        $this->folha = $dados['folha'];
        $this->termo = $dados['termo'];
        $this->status = $dados['status'];
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email_sol');
    }
    public function cartorio(): BelongsTo
    {
        return $this->belongsTo(Cartorio::class, 'cartorio_id', 'ccc_id');
    }
}
