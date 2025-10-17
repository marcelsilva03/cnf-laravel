<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\APIClient;
use App\Models\ApiRequisicao;
use App\Models\Falecido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteApiController extends Controller
{
    public function getClienteData(Request $request): JsonResponse
    {
        $request->validate([
            'cpf' => 'required|string|size:11', 
            'api_key' => 'required|string', 
        ]);

        // Check API key in the database
        $apiKey = $request->input('api_key');
        $client = APIClient::where('api_key', $apiKey)->first();

        if (!$client) {
            return response()->json(['message' => 'Unauthorized: Invalid API key'], 401);
        }

        // Log the API request
        ApiRequisicao::create([
            'api_key' => $request->input('api_key'),
            'cpf' => $request->input('cpf'),
            'user_id' => $client->id, // Use the user_id from the api_clients table
            'cpf_consultado' => $request->input('cpf'), // Add cpf_consultado
            'created_at' => now(),
        ]);

        $cpf = $request->input('cpf');
        $falecido = Falecido::where('fal_cpf', $cpf)->first();

        if (!$falecido) {
            return response()->json([
                'cpf' => $cpf,
                'message' => 'Registro não encontrado no banco de dados.'
            ]);
        }

        return response()->json([
            'cpf' => $falecido->fal_cpf,
            'full_name' => $falecido->fal_nome,
            'mothers_name' => $falecido->fal_nome_mae,
            'birthday' => $falecido->fal_data_nascimento,
            'data de falecimento' => $falecido->fal_data_falecimento
        ]);
    }
}
