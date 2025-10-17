<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class FalecidoHashService
{
    /**
     * Retorna os dados do falecido a partir do hash.
     */
    public function findByHash(string $hash): array
    {
        $registro = DB::table('tabela_hashes as th')
            ->join('pessoa_unica as pu',  'th.pes_id',           '=', 'pu.pes_id')
            ->join('endereco_cidade as ec','pu.pes_obito_id_ecd', '=', 'ec.ecd_id')
            ->join('endereco_estado as ee','ec.ecd_id_ees',      '=', 'ee.ees_id')
            ->where('th.hash', $hash)
            ->selectRaw("
                pu.pes_nome               AS nome,
                pu.pes_nome_mae           AS mae,
                str_to_date(pu.pes_data_falecimento,'%Y%m%d')
                                          AS falecimento,
                date_format(
                  str_to_date(pu.pes_data_falecimento,'%Y%m%d'),
                  '%d/%m/%Y'
                )                        AS falecimento_form,
                ec.ecd_id,
                ec.ecd_nome               AS cidade,
                ee.ees_id,
                ee.ees_nome               AS uf_nome,
                ee.ees_sigla              AS uf
            ")
            ->first();

        return $registro ? (array) $registro : [];
    }
}
