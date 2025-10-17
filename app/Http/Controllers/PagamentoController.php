<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitacao;
use App\Http\Controllers\BuscaEmCartorioController;

class PagamentoController extends Controller
{
    public function sucesso(Request $request)
    {
        // opcional: validar que veio charge_id, codigo etc.
        $chargeId = $request->query('charge_id');
        $codigo   = $request->query('codigo');
        $statusIngles = $request->query('status');
        $statusPt     = statusEmPortugues($statusIngles);

        $solicitacao = Solicitacao::where('pag_code', $codigo)->firstOrFail();

        // Instancia o controller que contém a lógica de nome do produto
        $buscaCtrl = app(BuscaEmCartorioController::class);
        // Use a data que aquele método espera — supondo que seja o falecimento
        $dataParaProduto = $solicitacao->sol_data_obito; 
        $produto = $buscaCtrl->obterNomeDoProdutoPorData($dataParaProduto);

        return view('pagamento.sucesso', [
            'chargeId'     => $chargeId,
            'codigo'       => $codigo,
            'status'       => $statusPt,
            'produto'      => $produto,
            'sol_id'       => $solicitacao->sol_id,
        ]);
    }
}
