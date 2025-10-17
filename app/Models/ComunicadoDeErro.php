<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ComunicadoDeErroNotification;

class ComunicadoDeErro extends Model
{
    use HasFactory;

    protected $table = 'comunicados_de_erro';

    public const STATUS = [
        'PENDENTE' => 0,
        'APROVADO' => 1,
        'REJEITADO' => 2,
    ];

    public const TIPOS_ERRO_LABEL = [
        'erro_nome'      => 'Erro de nome ou filiação',
        'erro_data'      => 'Erro de data',
        'erro_cidade'    => 'Erro de cidade',
        'nao_e_falecido' => 'Não é falecido',
        'complemento'    => 'Informação incompleta',
        'outros'         => 'Outros tipos de erro ou complemento',
    ];

    protected $fillable = [
        'mensagem',
        'id_falecido',
        'uuid_falecido',
        'nome_falecido',
        'cidade_falecido',
        'uf_falecido',
        'email_comunicante',
        'nome_comunicante',
        'tipo_erro',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($comunicadoDeErro) {
            // Enviar notificação para administradores e moderadores
            $adminUsers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'moderador']);
            })->get();

            Notification::send($adminUsers, new ComunicadoDeErroNotification($comunicadoDeErro));
        });
    }

    public function preencher(array $dados): void
    {
        $this->mensagem = $dados['mensagem'];
        $this->email_comunicante = $dados['email_comunicante'];
        $this->nome_comunicante = $dados['nome_comunicante'];
        $this->id_falecido = $dados['id_falecido'];
        $this->uuid_falecido = $dados['uuid_falecido'];
        $this->nome_falecido = $dados['nome_falecido'];
        $this->cidade_falecido = $dados['cidade_falecido'];
        $this->uf_falecido = $dados['uf_falecido'];
        $this->tipo_erro = $dados['tipo_erro'];
        $this->status = $dados['status'] ?? self::STATUS['PENDENTE'];
    }

    public function falecido(): BelongsTo
    {
        return $this->belongsTo(Falecido::class, 'id_falecido', 'fal_id');
    }

    public static function statusList(): array
    {
        return array_flip(self::STATUS);
    }
}