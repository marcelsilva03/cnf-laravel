<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Homenagem extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDENTE' => 0,
        'PUBLICADO' => 1,
        'REMOVIDO' => 2,
    ];

    protected $table = 'homenagens';
    protected $primaryKey = 'hom_id';
    protected $fillable = ['hom_id_falecido', 'hom_uuid_falecido', 'hom_nome_autor', 'hom_cpf_autor', 'hom_url_foto', 'hom_url_fundo', 'hom_mensagem', 'hom_whatsapp', 'hom_email', 'hom_parentesco', 'hom_status'];

    protected function gerarCodigo(): string
    {
        $hexadecimal = dechex(mt_rand(1, 0xffffffff));
        return strtoupper(str_pad($hexadecimal, 8, '0', STR_PAD_LEFT));
    }

    protected function codigoIndisponivel($codigo): bool
    {
        return $this->where('hom_codigo', $codigo)->exists();
    }

    protected function gerarCodigoUnico(): string
    {
        do {
            $codigo = $this->gerarCodigo();
        } while ($this->codigoIndisponivel($codigo));

        return $codigo;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->hom_codigo = $this->gerarCodigoUnico();
    }

    public static function statusList(): array
    {
        return array_flip(self::STATUS);
    }

    public function preencher(array $dados): void
    {
        $this->hom_id_falecido = $dados['id_falecido'];
        $this->hom_uuid_falecido = $dados['uuid_falecido'];
        $this->hom_nome_autor = $dados['nome_autor'];
        $this->hom_cpf_autor = $dados['cpf_autor'];
        $this->hom_url_foto = $dados['url_foto'];
        $this->hom_url_fundo = $dados['url_fundo'];
        $this->hom_mensagem = $dados['mensagem'];
        $this->hom_whatsapp = $dados['whatsapp'];
        $this->hom_email = $dados['email'];
        $this->hom_parentesco = $dados['parentesco'];
    }

    public function falecido(): BelongsTo
    {
        return $this->belongsTo(Falecido::class, 'hom_id_falecido', 'fal_id');
    }
}
