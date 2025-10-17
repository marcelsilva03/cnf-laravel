<?php

if (!function_exists('remover_acentos')) {
    function remover_acentos($string) {
        return iconv('UTF-8', 'ASCII//TRANSLIT', trim($string));
    }
}

if (! function_exists('statusEmPortugues')) {
    /**
     * Traduz código de status EfiPay do Inglês para Português.
     */
    function statusEmPortugues(string $status): string
    {
        $map = [
            'new'        => 'Novo',
            'waiting'    => 'Aguardando',
            'identified' => 'Identificado',
            'approved'   => 'Aprovado',
            'paid'       => 'Pago',
            'unpaid'     => 'Não pago',
            'refunded'   => 'Devolvido',
            'contested'  => 'Contestado',
            'canceled'   => 'Cancelado',
            'settled'    => 'Marcado como pago',
            'expired'    => 'Expirado',
        ];

        return $map[$status] ?? ucfirst($status);
    }
}