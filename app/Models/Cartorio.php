<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartorio extends Model
{
    use HasFactory;

    protected $table = 'cartorio_real';
    
    protected $primaryKey = 'ccc_id';
    
    protected $fillable = [
        'ccc_id_ecd',
        'ccc_nome',
        'ccc_nome_fantasia',
        'ccc_cnpj',
        'ccc_cns',
        'ccc_tipo',
        'ccc_endereco',
        'ccc_bairro',
        'ccc_cidade',
        'ccc_uf',
        'ccc_cep',
        'ccc_comarca',
        'ccc_telefone',
        'ccc_fax',
        'ccc_email',
        'ccc_site',
        'ccc_area_abrangencia',
        'ccc_atribuicoes',
        'ccc_horario_funcionamento',
        'ccc_entrancia',
        'ccc_nome_titular',
        'ccc_nome_substituto',
        'ccc_nome_juiz',
        'ccc_obs',
        'ccc_ultima_atualizacao',
    ];
}
