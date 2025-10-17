<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class APIClient extends Model
{
    use HasFactory;

    protected $table = 'api_clients';

    const STATUS = [
        'ATIVO' => 1,
        'INATIVO' => 0,
    ];
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'api_key',
        'api_secret',
        'request_limit',
        'requests_made',
        'user_email',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        // Gerar automaticamente api_key e api_secret ao criar um registro
        static::creating(function ($model) {
            $model->api_key = $model->generateApiKey();
            $model->api_secret = $model->generateApiSecret();
        });
    }

    /**
     * Método para gerar um API Key
     *
     * @return string
     */
    public function generateApiKey(): string
    {
        return Str::random(32);
    }

    /**
     * Método para gerar um API Secret
     *
     * @return string
     */
    public function generateApiSecret(): string
    {
        return Str::random(32);
    }

    /**
     * Relacionamento com o modelo User via user_email
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_email', 'email');
    }

    /**
     * Relacionamento com as requisições da API
     *
     * @return HasMany
     */
    public function requisicoes(): HasMany
    {
        return $this->hasMany(ApiRequisicao::class, 'api_key', 'api_key');
    }

    /**
     * Verifica se o cliente ainda tem requisições disponíveis
     *
     * @return bool
     */
    public function hasRequestsAvailable(): bool
    {
        return $this->requests_made < $this->request_limit;
    }

    /**
     * Incrementa o contador de requisições
     *
     * @return void
     */
    public function incrementRequests(): void
    {
        $this->increment('requests_made');
    }

    /**
     * Calcula o percentual de uso
     *
     * @return float
     */
    public function getUsagePercentage(): float
    {
        if ($this->request_limit == 0) {
            return 0;
        }
        
        return round(($this->requests_made / $this->request_limit) * 100, 1);
    }

    /**
     * Retorna as requisições restantes
     *
     * @return int
     */
    public function getRemainingRequests(): int
    {
        return max(0, $this->request_limit - $this->requests_made);
    }
}
