<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cartorio;
use App\Traits\HasCustomPagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartoriosAPIController extends Controller
{
    use HasCustomPagination;

    private function removePrefixos(array $colecao, string $prefixo): array
    {
        $novaColecao = [];
        foreach ($colecao as $item) {
            $novoItem = [];
            foreach ($item as $chave => $valor) {
                $novaChave = str_replace($prefixo, '', $chave);
                $novoItem[$novaChave] = $valor;
            }
            $novaColecao[] = $novoItem;
        }
        return $novaColecao;
    }
    public function obterCartoriosPorCidade(Request $request, string $uf, string $cidade): JsonResponse
    {
        $uf = strtoupper($uf);
        $cartorios = Cartorio::where('ccc_uf', $uf)
            ->where('ccc_cidade', $cidade)
            ->where('ccc_tipo', 254)
            ->get();
        $cartorios = $this->removePrefixos($cartorios->toArray(), 'ccc_');
        return response()->json($cartorios);

//        $perPage = $request->query('paginacao', 25);
//        $endpoint = $request->url();
//        $cartorios = Cartorio::where('ccc_uf', $uf)->where('ccc_cidade', $cidade);
//        $data = $this->splitDataAndPagination($cartorios, $perPage, $endpoint);
//        $res = [
//            'pagination' => $data['pagination'],
//            'data' => $data['data']
//        ];
//        return response()->json($res);
    }
}
