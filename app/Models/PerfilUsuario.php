<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilUsuario extends Model
{
    use HasFactory;
    protected $table = 'perfil_usuarios';
    protected $fillable = [
        'user_id',
        'old_id',
        'data_cadastro',
        'ativo',
        'login',
        'cod_altera_senha',
        'email',
        'email_confirmado',
        'nome',
        'responsavel',
        'data_nascimento',
        'sexo',
        'cpf',
        'rg',
        'endereco',
        'endereco_numero',
        'endereco_complemento',
        'endereco_bairro',
        'endereco_cep',
        'endereco_id_ecd',
        'fone_numero',
        'id_fun'
    ];

}
