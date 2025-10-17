<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoOrcamento extends Model
{
    use HasFactory;
    
    protected $table = 'solicitacoes_orcamento';
    
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'mensagem',
        'status',
    ];
    
    const STATUS = [
        'PENDENTE' => 0,
        'RESPONDIDO' => 1,
        'CANCELADO' => 2,
    ];
} 