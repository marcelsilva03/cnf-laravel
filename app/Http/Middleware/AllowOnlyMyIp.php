<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowOnlyMyIp
{
    public function handle(Request $request, Closure $next)
    {
        // se RESTRICT_TEST_DOMAIN for true, aplica a checagem de IP
        if (env('RESTRICT_TEST_DOMAIN', false)) {
            $allowed = explode(',', env('ALLOWED_IPS', ''));
            // remove espaços em branco e IPs vazios
            $allowed = array_filter(array_map('trim', $allowed));

            if (! in_array($request->ip(), $allowed)) {
                abort(403, 'Acesso restrito.');
            }
        }

        return $next($request);
    }
}