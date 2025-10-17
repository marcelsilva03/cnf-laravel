<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ClearSessionOnProfileChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if ($user) {
            $currentRole = $user->roles->first()?->name ?? 'guest';
            $sessionRole = $request->session()->get('current_user_role');
            
            // Se há uma mudança de perfil detectada
            if ($sessionRole && $sessionRole !== $currentRole) {
                Log::info('Profile change detected', [
                    'user_id' => $user->id,
                    'previous_role' => $sessionRole,
                    'current_role' => $currentRole
                ]);
                
                // Limpar dados específicos de sessão que podem causar conflito
                $request->session()->forget([
                    'password_hash_web',
                    'filament_dashboard_widgets',
                    'filament_navigation',
                    'current_user_role'
                ]);
                
                // Regenerar a sessão para evitar conflitos
                $request->session()->regenerate();
            }
            
            // Atualizar o role atual na sessão
            $request->session()->put('current_user_role', $currentRole);
        }

        return $next($request);
    }
} 