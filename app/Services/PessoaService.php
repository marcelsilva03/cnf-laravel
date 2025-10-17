<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class PessoaService
{
    public static function determinarTabelaPorNome(string $nome): string
    {
        $prefixo = substr(strtoupper(remover_acentos(trim($nome))), 0, 3);

        $mapa = [
            'pessoa_1' => ['MAR'],
            'pessoa_2' => ['JOS', 'JOA'],
            'pessoa_3' => ['ANT', 'FRA', 'MAN', 'LUI', 'SEB'],
            'pessoa_4' => ['CAR', 'PED', 'GER', 'ANA', 'RAI', 'VAL', 'BEN', 'ROS', 'PAU', 'ELI', 'TER', 'LUC', 'ADE'],
            'pessoa_5' => ['JUL', 'CLA', 'SEV', 'WAL', 'HEL', 'LEO', 'JOR', 'EDI', 'LAU', 'VIC', 'NEL', 'CEL', 'GIL', 'ADA', 'CLE', 'ALE', 'ANG', 'AMA', 'NIL', 'GEN', 'SIL', 'OLI', 'ALB', 'ALC', 'ROB', 'WIL', 'IZA', 'DOM'],
            'pessoa_6' => ['AND', 'ISA', 'ALI', 'LUZ', 'APA', 'IRA', 'MIG', 'SAN', 'JAI', 'HER', 'MAU', 'EVA', 'AUR', 'FER', 'LOU', 'DOR', 'SER', 'DAN', 'VER', 'CIC', 'REG', 'OSV', 'RIT', 'ARL', 'VIT', 'FLO', 'EMI', 'NAI', 'VAN', 'HIL', 'IVA', 'JAN', 'ARI', 'GUI', 'JAC', 'AME', 'AUG', 'EDU', 'THE', 'REN', 'ALZ', 'LIN', 'FEL', 'ORL', 'OTA', 'CON'],
            'pessoa_7' => ['ARM','EDS','NAT','IVO','JOV','ALF','ANI','BER','ERN','ANN','FAB','ALV','MIL','ELZ','IRE','NEU','DIO','ALD','CEC','CRI','EST','ALM','DAR','DEL','JUR','SAL','MAT','ROM','ADR','RIC','DAV','DAL','GAB','DIV','ARN','NOE','ALA','ART','ADI','JUV','ELV','RUB','ALT','OSM','IDA','RON','HEN','EDM','BRA','VIL','ZIL','ODE','LAZ','RAF','SON','ROD','FLA','JOE','OSW','CLO','JES','JON','OLG','ERI','EUR','LEA','ODI','EDE','MOA','DIR','EDN','VIR','WAN','NIC','ZUL','IRI'],
            'pessoa_8' => ['NOR','INA','OSC','CAT','DEO','CAN','SUE','NAD','ELE','BRU','ZEN','EUN','JUS','DUR','LID','REI','ROG','JUD','EDV','ZEL','EUC','ADO','TEO','EUG','BEL','ENE','DAM','JER','ILD','AGE','GEO','NEI','DJA','SID','ABI','RUT','CRE','MIR','SAM','ANE','DUL','DEN','MER','AVE','CAS','NIV','ESP','ELO','SIM','INE','JUA','DIL','DIN','ARA','AFO','SEL','AGO','ABE','EVE','ARG','WEL','MOI','ERM','RAU','HOR','COR','DER','LEN','ERO','HON','QUI','RAM','ETE','DON','FIL','ITA','BEA','PAT','EXP','ESM','EDG','ROQ','GRA','PER','ALO','AIL','CES','ONO','NER','DOL','COS','ISM','SOL','ENI','CIR','MAX','ARC','MAG','LAE','DEU','PAL','OTI','JEF','VEN','ROZ','EUL','LOR','IOL','GUS','PET','BAL','OCT','FAU','RAY','THI','IGN','SIN','MIC','ATA','ELS','ERC','BAR','ODA','OLA','AGN','GON','DIE','JAR','WAG','FIR','NAR','CAM','HUM','AGU','IRM','VIV','ERA','TIA','TAN','ARY','YOL','EME','NAZ','SYL','HUG','FRE','ORI','MAD','ISR','IDE','LEI','APP','HAR','NES','LEV','EMA','TAR','THA','EFI','TOM','LIB','JOC','AIR','IZI','FAT','ELA','LED','SHI','DOU','GIO','LIL','GLA','JUC','HEI','PAS','SIR','LAI'],
            'pessoa_9' => ['GET','ALU','MIN','JAY','AMI','MES','MAI','GRE','JEA','CEZ','SAB','STE','SAT','AMB','GEL','RAQ','KAT','DEJ','MEL','JAM','ASS','GES','CAC','GIS','SAR','DEM','VIN','ELP','ELC','LYD','UBI','CUS','MAL','EZE','GLO','MON','ONE','OTT','AGR','PRI','ORE','CHR','IZO','ZAC','EUF','ABR','OND','HAM','WAS','LET','CHA','ELM','ORA','IVE','MAS','DAI','WES','LUA','JAD','DEI','GAS','EUD','ABD','OLE','KAR','RUI','GLE','APO','SUZ','RIV','ENO','UMB','ISO','TEL','RUY','FID','GAL','DEC','SEN','ULI','AST','AID','CAE','ERE','ILZ','GUM','ELY','BAS','NEW','FRI','CAL','DEB','JEN','OVI','DIA','EUZ','NEY','ISI','HOM','ORM','TAT','MUR','EGI','ANS','GIV','RUD','PIE','LAD','PLA','DEV','LAR','PLI','LUD','SAU','OZI','CAI','SOF','NAN','ILS','RAP','ONI','BRE','MAY','ATI','ION','ACA','VAG','YOS','IAR','WEN','ZEF','THO','ILM','LIV','SAD','MAC','RAN','OSO','JUN','DIM','CID','KLE','GUA','KEL','ORO','CIN','DAG','JOH','KAU','JAQ','GIU','INO','BRI','ZAI','AFF','POR','YAS','FIO','TAD','ERV','CEN','LIC','AUT','AQU','FOR','EPI','IGO','OSN','ARO','ACI','OLY','RUF','JOZ','LIO','CIL','EDW','EMM','EUS','BIA','ZOR','LUR','OZO','LUS','MOD','JHO','EZI','ELL','ELD','ODO','TAK','GUE','ROL','MAF','JOI','TAI','OZE','AGA','JEO','ING','HIR','ILI','SOP','TAL','REY','LIG','LEC','PRO','URS','ADM','CYR','DES','IED','ACE','MOY','YVO','GRI','LIA','ALL','AVA','UBA','PHI','MIT','AYR','MIS','RIN','ELT','GIN','URB','BON','AUD','PAB','NAY','BOA','LIZ','RAC','ABA','','JAS','ERL','POL','TRA','TOS','RN','ARE','OTO','LIS','GEM','WER','EVI','MOZ','KAI','NAP','ORD','NAS','AFR','HAY','IRO','ATH','HED','VOL','HIG','DAY','GED','NED','TIB','REJ','EDY','APR','QUE','YAR','RIS','NOB','REM','TAM','ENY','CIP','ILA','OSE','LAC','LEL','GEC','ADH','HAN','SHE','OLM','LIR','LAV','OSI','CLI','SET','OMA','KEN','KAZ','ORC','OZA','ALG','MYR','ENZ','GEI','PRE','VLA','CER','WLA','JOB','EPA','SIV','MOR','ATT','HID','HOS','NEC','IDI','ARQ','REC','CHI','GIA','EUT','LYG','SUS','VAS','GOM','SIZ','OLD','NEM','HAI','DAU','ASC','VID','JAL','MEI','NAL','IBR','MAM','ILT','YUR','ENC','ANU','SEI','KEI','LOI','PUR','KIY','TSU','GAR','ZIT','UIL','POM','ELB','AZE','ILK','TIT','FAR','ORT','ARS','NAG','NAU','MEN','LAN','OTE','COL','GEA','ANO','SEM','VAR','HIP','BAT','OTH','BLA','NIR','CAU','TRI','NAO','AKI','RUA','LAY','NIN','URI','WAR','GOD','ORN','MIK','IND','ITE','VIO','ELF','GIR','DEA','BOL','MIZ','EGO','KAM','RIL','PRA','ACH','EPH','TOR','SUL','HEB','YED','LON','ROC','IZE','WOL','GUT','MIQ','BET','ADY','STA','TAC','EDL','NAH','REB','LIE','YON','GLI','VEL','NUB','DAC','ENA','GAU','FAN','AZI','ACY','OCI','EDA','SEC','FUM','ANC','SOE','HUD','ARD','AYL','HOL','PRU','KAY','ATE','DID','LOT','SEG','AGI','ECI','OSA','NIZ','ALY','NAB','BIB','LUP','ILO','SOR','LEU','SAI','SIG','ILC','ESC','KET','YUK','TUL','NEV','CHE','HIS','GIZ','DIJ','ADJ','OPH','ENG','SIB','ZEZ','PIO','REA','KIM','POS','IDO','PAM','EWA','ULY','SUM','TON','EBE','TAY','RYA','LAS','OFE','ENR','TIM','OBE','TEM','AZA','DEZ','LAF','EGY','HEM','PAC','KAL','FUL','ALS','BAZ','SAV','PAR','EMY','FAG','EDJ','NEZ','NAC','GID','ADN','UEL','RIZ','ZAQ','DUI','RED','JOY','AYD','JAE','UNI','JAK','IAN','ARV','ACR','WEV','EDO','SEZ','TEC','LAM','DEY','KEV','ABN','ELU','JOL','PEN','HOZ','ILV','JUP','ERU','HUL','BOR','FRU','BRO','AUZ','EWE','SHO','CYN','YAN','NEO','HAL','WEB','JEC','PAO','AUS','JUB','PAN','NIS','NIE','OST','SOC','ORV','ALP','TOB','ERW','WAD','IRL','ZAN','RAL','NID','TUR','HAT','PON','ORF','ENN','EPO','DOC','TOK','DOS','DOZ','ACC','WED','JIL','LER','TOL','ILG','CIB','SUD','EGL','NUN','ZOE','KUR','XIS','TAU','ILE','IAG','EUV','AMO','ESD','MIY','CED','ZOZ','ARZ','KAS','TIO','DUC','AGL','ESI','KUN','DUA','ISN','LOD','ONA','IMA','SIE','ZIN','JHE','URC','URA','AEL','SCH','RIA','FUR','AGD','JOD','YAG','JOF','ZIZ','GEZ','SYD','ICA','MAB','BRY','SED','AUL','VAI','AEC','REV','VAD','GOR','ZUI','JOM','NAE','ADV','AVI','NOL','ANF','ZAL','MOH','BEI','JAU','DOV','JAZ','MET','ECL','WIN'],
        ];

        foreach ($mapa as $tabela => $prefixos) {
            if (in_array($prefixo, $prefixos, true)) {
                return $tabela;
            }
        }

        return 'pessoa_10';
    }

    /**
     * Executa a busca pelo nome, estado e cidade, paginando os resultados.
     *
     * @param string      $nome
     * @param string|null $estado
     * @param string|null $cidade
     * @param bool        $exata
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    
    public static function buscarPorNomeSemPaginacao(string $nome, bool $exata = false)
    {
        $tabela = self::determinarTabelaPorNome($nome);

        $nomeSanitizado = strtoupper(remover_acentos(trim($nome)));

        $query = DB::table("{$tabela} as a")
        ->join('pessoa_unica as b', 'a.pes_id', '=', 'b.pes_id')
        ->selectRaw(
            "a.pes_id,
             a.pes_nome,
             a.pes_data_falecimento,
             SUBSTRING_INDEX(b.pes_nome_mae, ' ', 1) as pes_nome_mae,
             a.pes_cidade,
             a.pes_uf,
             SHA2(a.pes_id, 256) as hash"
        )
        ->whereIn('a.pes_origem', ['255', '254', '202'])
        ->whereBetween('a.pes_data_falecimento', ['2000-01-01', \Illuminate\Support\Carbon::now()->toDateString()]);

        if ($exata) {
            $query->where('a.pes_nome', '=', $nomeSanitizado);
        } else {
            $nomeClean = preg_replace('/[^[:alpha:] ]/', ' ', $nomeSanitizado);
            $novoWildcard = implode('%', array_filter(explode(' ', $nomeSanitizado))) . '%';
            $query->where('a.pes_nome', 'like', $novoWildcard);
        }

        return $query->get();
    }

    public static function buscarPorNome(string $nome, ?string $estado = null, ?string $cidade = null, bool $exata = false)
    {
        $tabela = self::determinarTabelaPorNome($nome);

        $nomeSanitizado = strtoupper(remover_acentos(trim($nome)));

        $query = DB::table("{$tabela} as a")
        ->join('pessoa_unica as b', 'a.pes_id', '=', 'b.pes_id')
        ->selectRaw(
            "a.pes_id,
             a.pes_nome,
             a.pes_data_falecimento,
             SUBSTRING_INDEX(b.pes_nome_mae, ' ', 1) as pes_nome_mae,
             DATE_FORMAT(a.pes_data_falecimento, '%d-%m-%Y') as data_falecimento,
             a.pes_cidade,
             a.pes_uf,
             SHA2(a.pes_id, 256) as hash"
        )
        ->whereIn('a.pes_origem', ['255', '254', '202'])
        ->whereBetween('a.pes_data_falecimento', ['2000-01-01', Carbon::now()->toDateString()])
        ->groupBy(
            'a.pes_id',
            'a.pes_nome',
            'a.pes_data_falecimento',
            'b.pes_nome_mae',
            'a.pes_cidade',
            'a.pes_uf'
        );

        if ($exata) {
            $query->where('a.pes_nome', '=', $nomeSanitizado);
        } else {
            $nomeClean = preg_replace('/[^[:alpha:] ]/', ' ', $nomeSanitizado);
            $novoWildcard = implode('%', array_filter(explode(' ', $nomeSanitizado))) . '%';
            $query->where('a.pes_nome', 'like', $novoWildcard);
        }

        if ($estado) {
            $query->where('a.pes_uf', '=', $estado);
        }
        if ($cidade) {
            $query->where('a.pes_cidade', '=', $cidade);
        }

        return $query
        ->orderByDesc('a.pes_data_falecimento')
        ->paginate(config('constants.paginacaoPadrao'));
    }
}