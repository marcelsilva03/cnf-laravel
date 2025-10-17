<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiRequisicao extends Model
{
    use HasFactory;

    protected $table = 'api_requisicoes';

    protected $fillable = [
        'user_id',
        'api_key',
        'cpf',
        'cpf_consultado',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com o usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o cliente API
     */
    public function apiClient(): BelongsTo
    {
        return $this->belongsTo(APIClient::class, 'api_key', 'api_key');
    }
}
