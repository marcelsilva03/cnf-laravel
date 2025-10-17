<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\LocalidadesService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LocalidadesAPIController extends Controller
{
    protected LocalidadesService $localidades;

    public function __construct(LocalidadesService $localidadesService)
    {
        $this->localidades = $localidadesService;
    }

    public function obterEstados(): Application|ResponseFactory|Response|JsonResponse
    {
        $estados = $this->localidades->obterEstados();
        return response()->json($estados);
    }

    public function obterCidades($uf): Application|ResponseFactory|Response|JsonResponse
    {
        if (!$this->localidades->UFExiste($uf)) {
            return response()->json(['error' => "A UF $uf não é válida."], 404);
        }
        $cidades = $this->localidades->obterCidades($uf);
        return response()->json($cidades);
    }
}
