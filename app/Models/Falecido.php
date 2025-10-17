<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

class Falecido extends Model
{
    use HasFactory;

    protected $table = 'falecidos';

    const STATUS = [
        'ATIVO' => 1,
        'INATIVO' => 0,
    ];
    protected $primaryKey = 'fal_id';

    protected $fillable = [
        'fal_nome',
        'fal_cpf',
        'fal_rg',
        'fal_titulo_eleitor',
        'fal_nome_pai',
        'fal_nome_mae',
        'fal_data_nascimento',
        'fal_data_falecimento',
        'fal_tipo_local_falecimento',
        'fal_estado_civil',
        'fal_obs',
        'fal_status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($falecido) {
            if (empty($falecido->fal_uuid)) {
                $falecido->fal_uuid = $falecido->gerarUUIDUnico();
            }
        });
    }

    public function gerarUUIDUnico(): string
    {
        do {
            $uuid = Uuid::uuid4()->toString();
        } while (Falecido::where('fal_uuid', $uuid)->exists());
        return $uuid;
    }

    public function homenagens(): HasMany
    {
        return $this->hasMany(Homenagem::class, 'hom_id_falecido', 'fal_id');
    }

    public function comunicadosDeErro(): HasMany
    {
        return $this->hasMany(ComunicadoDeErro::class, 'id', 'fal_id');
    }

    public static function fromComunicadoDeObito(ComunicadoDeObito $comunicadoDeObito)
    {
        return self::create([
            'fal_nome' => $comunicadoDeObito->nome_fal,
            'fal_cpf' => $comunicadoDeObito->cpf_fal,
            'fal_rg' => $comunicadoDeObito->rg_fal,
            'fal_titulo_eleitor' => $comunicadoDeObito->titulo_eleitor,
            'fal_nome_pai' => $comunicadoDeObito->nome_pai_fal,
            'fal_nome_mae' => $comunicadoDeObito->nome_mae_fal,
            'fal_data_nascimento' => $comunicadoDeObito->data_nascimento,
            'fal_data_falecimento' => $comunicadoDeObito->data_obito,
            'fal_tipo_local_falecimento' => $comunicadoDeObito->local_obito_tipo,
            'fal_estado_civil' => $comunicadoDeObito->estado_civil,
            'fal_obs' => $comunicadoDeObito->obs,
            'fal_status' => 1,
        ]);
    }
}
