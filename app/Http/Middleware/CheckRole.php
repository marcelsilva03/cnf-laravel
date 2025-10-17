<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        $allowedRoles = explode('|', $roles);
        
        // CORREÇÃO: Usar sistema Spatie em vez do sistema antigo (role_id)
        if (!$user->hasAnyRole($allowedRoles)) {
            Log::warning('Access denied for user', [
                'user_id' => $user->id,
                'user_roles' => $user->roles->pluck('name')->toArray(),
                'required_roles' => $allowedRoles
            ]);
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
} 