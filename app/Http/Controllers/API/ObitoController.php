<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Obito;
use Illuminate\Support\Facades\Validator;

class ObitoController extends Controller
{
    /**
     * Store a newly created obituary in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function comunicarobito(Request $request): JsonResponse
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'nomesol' => 'required|string|max:255',
            'telsol' => 'required|string|max:15',
            'emailsol' => 'required|email',
            'comunicarobito' => 'accepted',
            'obito_nomeinst' => 'required|string|max:255',
            'obito_cpfinst' => 'required|string|max:11',
            'obito_reginst' => 'nullable|string|max:12',
            'obito_eleitor' => 'nullable|string|max:12',
            'obito_nomepai' => 'required|string|max:255',
            'obito_nomemae' => 'required|string|max:255',
            'obito_nascf' => 'nullable|date',
            'obito_dfalec' => 'nullable|date',
            'obito_estadoinstnome' => 'nullable|string|max:255',
            'obito_cidadeinstnome' => 'nullable|string|max:255',
            'obito_localfal' => 'required|string',
            'obito_ecivil' => 'required|string',
            'comentarios' => 'nullable|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new Obito record using the validated data
        $obito = new Obito([
            'nomesol' => $request->nomesol,
            'telsol' => $request->telsol,
            'emailsol' => $request->emailsol,
            'obito_nomeinst' => $request->obito_nomeinst,
            'obito_cpfinst' => $request->obito_cpfinst,
            'obito_reginst' => $request->obito_reginst,
            'obito_eleitor' => $request->obito_eleitor,
            'obito_nomepai' => $request->obito_nomepai,
            'obito_nomemae' => $request->obito_nomemae,
            'obito_nascf' => $request->obito_nascf,
            'obito_dfalec' => $request->obito_dfalec,
            'obito_estadoinstnome' => $request->obito_estadoinstnome,
            'obito_cidadeinstnome' => $request->obito_cidadeinstnome,
            'obito_localfal' => $request->obito_localfal,
            'obito_ecivil' => $request->obito_ecivil,
            'comentarios' => $request->comentarios,
        ]);

        // Save the Obito record
        $obito->save();

        // Return a response, typically including the new obituary record
        return response()->json(['message' => 'Obituary reported successfully', 'data' => $obito], 201);
    }
}
