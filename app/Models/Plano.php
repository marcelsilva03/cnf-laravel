<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    protected $table = 'planos';

    protected $fillable = [
        'faixa_inicial',
        'faixa_final',
        'preco_por_consulta',
        'ativo',
    ];

    protected $casts = [
        'preco_por_consulta' => 'decimal:4',
    ];
}
