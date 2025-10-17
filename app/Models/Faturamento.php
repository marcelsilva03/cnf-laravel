<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faturamento extends Model
{
    use HasFactory;

    protected $table = 'faturamentos';

    protected $fillable = [
        'user_id',
        'valor',
        'metodo',
        'status',
        'data_pagamento',
        'descricao',
    ];

    protected $casts = [
        'data_pagamento' => 'date',
        'valor' => 'decimal:2',
    ];

    const STATUS = [
        'PENDENTE' => 'pendente',
        'CONCLUIDO' => 'concluido',
        'CANCELADO' => 'cancelado',
    ];

    const METODOS = [
        'PIX' => 'pix',
        'CARTAO' => 'cartao',
        'BOLETO' => 'boleto',
        'TRANSFERENCIA' => 'transferencia',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function statusList(): array
    {
        return array_flip(self::STATUS);
    }

    public static function metodosList(): array
    {
        return array_flip(self::METODOS);
    }
}
