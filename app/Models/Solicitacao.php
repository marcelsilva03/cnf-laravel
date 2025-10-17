<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Solicitacao extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDENTE' => 0,
        'APROVADA' => 1,
        'REJEITADA' => 2,
        'PAGA' => 3,
        'LIBERADA' => 4
    ];
    public static function statusList(): array
    {
        return array_flip(self::STATUS);
    }

    protected $table = 'solicitacoes';
    protected $primaryKey = 'sol_id';

    protected $fillable = [
        'sol_empresa',
        'sol_nome_sol',
        'sol_tel_sol',
        'sol_email_sol',
        'sol_nome_fal',
        'sol_cpf_fal',
        'sol_rg_fal',
        'sol_titulo_eleitor',
        'sol_nome_pai_fal',
        'sol_nome_mae_fal',
        'sol_data_nascimento',
        'sol_data_obito',
        'sol_estado_cidade',
        'sol_local_obito_tipo',
        'sol_estado_civil',
        'sol_obs',
        'sol_valor',
        'pag_code',
        'pag_metodo_escolhido',
        'sol_id_abr',
        'user_id',
        'status',
        'sol_status',
        'pag_date'
    ];

    public function preencher(array $dados): void
    {
        $this->sol_empresa = $dados['sol_empresa'];
        $this->sol_nome_sol = $dados['sol_nome_sol'];
        $this->sol_tel_sol = $dados['sol_tel_sol'];
        $this->sol_email_sol = $dados['sol_email_sol'];
        $this->sol_nome_fal = $dados['sol_nome_fal'];
        $this->sol_cpf_fal = $dados['sol_cpf_fal'];
        $this->sol_rg_fal = $dados['sol_rg_fal'];
        $this->sol_titulo_eleitor = $dados['sol_titulo_eleitor'];
        $this->sol_nome_pai_fal = $dados['sol_nome_pai_fal'];
        $this->sol_nome_mae_fal = $dados['sol_nome_mae_fal'];
        $this->sol_data_nascimento = $dados['sol_data_nascimento'];
        $this->sol_data_obito = $dados['sol_data_obito'];
        $this->sol_estado_cidade = $dados['sol_estado_cidade'];
        $this->sol_local_obito_tipo = $dados['sol_local_obito_tipo'];
        $this->sol_estado_civil = $dados['sol_estado_civil'];
        $this->sol_obs = $dados['sol_obs'];
        $this->sol_valor = $dados['sol_valor'];
        $this->sol_id_abr = $dados['sol_id_abr'];
        
        // Se houver um usuário autenticado, associar a solicitação a ele
        if (isset($dados['user_id'])) {
            $this->user_id = $dados['user_id'];
        }
        
        // Status padrão é PENDENTE
        $this->status = self::STATUS['PENDENTE'];
    }

    public function abrangencia()
    {
        return $this->belongsTo(Abrangencia::class, 'sol_id_abr', 'abr_id');
    }
    
    /**
     * Relacionamento com o usuário que fez a solicitação
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    /**
     * Retorna o status formatado para exibição
     */
    public function getStatusFormatadoAttribute()
    {
        $statusList = self::statusList();
        return isset($statusList[$this->status]) ? $statusList[$this->status] : 'Desconhecido';
    }
    
    /**
     * Conta solicitações por status para um usuário específico
     */
    public static function contarPorStatus($userId)
    {
        $total = self::where('user_id', $userId)->count();
        $pagas = self::where('user_id', $userId)->where('status', self::STATUS['PAGA'])->count();
        $liberadas = self::where('user_id', $userId)->where('status', self::STATUS['LIBERADA'])->count();
        
        return [
            'total' => $total,
            'pagas' => $pagas,
            'liberadas' => $liberadas,
            'pendentes' => $total - $pagas - $liberadas
        ];
    }
    
    /**
     * Obtém todas as solicitações de um usuário específico
     */
    public static function obterPorUsuario($userId)
    {
        return self::where('user_id', $userId)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Mutator para remover a máscara do CPF antes de salvar.
    */
    public function setSolCpfFalAttribute($value)
    {
        // Remove a máscara do CPF, deixando apenas os números
        $this->attributes['sol_cpf_fal'] = preg_replace('/\D/', '', $value);
    }
}
