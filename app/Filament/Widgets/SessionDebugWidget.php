<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class SessionDebugWidget extends Widget
{
    protected static string $view = 'filament.widgets.session-debug-widget';
    
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole(['proprietario', 'socio-gestor']);
    }

    protected function getViewData(): array
    {
        $user = Auth::user();
        
        // Verificar se o usuário existe
        if (!$user) {
            return [
                'userInfo' => ['error' => 'Usuário não encontrado'],
                'sessionInfo' => [],
                'sessionStats' => [],
                'recentLogins' => [],
            ];
        }
        
        // Informações do usuário atual
        $userInfo = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'roles' => $user->roles->pluck('name')->toArray(),
        ];

        // Informações da sessão
        $sessionInfo = [
            'session_id' => session()->getId(),
            'current_role' => session('current_user_role'),
            'password_hash_stored' => session()->has('password_hash_web'),
            'session_data_keys' => array_keys(session()->all()),
            'driver' => config('session.driver'),
        ];

        // Estatísticas de sessões ativas
        $sessionStats = [
            'total_sessions' => 0,
            'authenticated_sessions' => 0,
            'user_sessions' => 0,
            'table_exists' => Schema::hasTable('sessions'),
            'driver' => config('session.driver'),
        ];

        if ($sessionStats['driver'] === 'database' && $sessionStats['table_exists']) {
            try {
                $sessionStats['total_sessions'] = DB::table('sessions')->count();
                $sessionStats['authenticated_sessions'] = DB::table('sessions')->whereNotNull('user_id')->count();
                $sessionStats['user_sessions'] = DB::table('sessions')->where('user_id', $user->id)->count();
            } catch (\Exception $e) {
                $sessionStats['error'] = $e->getMessage();
            }
        } elseif ($sessionStats['driver'] === 'file') {
            try {
                $sessionPath = config('session.files');
                if (File::exists($sessionPath)) {
                    $files = File::files($sessionPath);
                    $sessionStats['total_sessions'] = count($files);
                    $sessionStats['session_path'] = $sessionPath;
                } else {
                    $sessionStats['error'] = 'Diretório de sessões não encontrado: ' . $sessionPath;
                }
            } catch (\Exception $e) {
                $sessionStats['error'] = $e->getMessage();
            }
        }

        // Últimos logins
        $recentLogins = DB::table('users')
            ->select('id', 'name', 'email', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'userInfo' => $userInfo,
            'sessionInfo' => $sessionInfo,
            'sessionStats' => $sessionStats,
            'recentLogins' => $recentLogins,
        ];
    }
} 