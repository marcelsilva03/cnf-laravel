<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsuariosAPIController extends Controller
{
    public function checkEmail(Request $request): JsonResponse
    {
        $email = $request->get('email');
        if (!$email) {
            return response()->json([
                'email' => $email,
                'status' => false,
            ],Response::HTTP_BAD_REQUEST);
        }

        $usuario = User::with('perfil')
            ->where('email', $email)
            ->first();
        if (!$usuario) {
            return response()->json([
                'email' => $email,
                'status' => false,
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'email' => $email,
            'status' => true,
            'usuario' => $usuario
        ], Response::HTTP_OK);
    }
}
