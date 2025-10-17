<?php
namespace App\Enums;

enum Parentesco: int
{
    case OUTROS = 0;
    case CONJUGE = 1;
    case NAMORADO = 2;
    case MAE = 3;
    case PAI = 4;
    case FILHO = 5;
    case IRMAO = 6;
    case AVO = 7;
    case TIA = 8;
    case PRIMO = 9;
    case CUNHADO = 10;
    case AMIGO= 11;

    public function label(): string
    {
        return match ($this) {
            self::CONJUGE => 'Cônjuge',
            self::NAMORADO => 'Namorada(o)',
            self::MAE => 'Mãe',
            self::PAI => 'Pai',
            self::FILHO => 'Filha(o)',
            self::IRMAO => 'Irmã(o)',
            self::AVO => 'Avó(ô)',
            self::TIA => 'Tia(o)',
            self::PRIMO => 'Prima(o)',
            self::CUNHADO => 'Cunhada(o)',
            self::AMIGO => 'Amiga(o)',
            self::OUTROS => 'Outros',
        };
    }

    public static function toArray(): array
    {
        return array_reduce(
            self::cases(),
            function ($result, $case) {
                $result[$case->value] = $case->label();
                return $result;
            },
            []
        );
    }
}
