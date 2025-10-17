<?php

namespace App\Http\Controllers;

use App\Services\EfiPayService;

class EfiPayController extends Controller
{
    /**
     * Endpoint para registrar o webhook Pix via navegador ou API.
     */
    public function registerWebhook(string $pixKey)
    {
        $svc    = new EfiPayService();
        $result = $svc->registerWebhook($pixKey);

        return response()->json($result);
    }
}
