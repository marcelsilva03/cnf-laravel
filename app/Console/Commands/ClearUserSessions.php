<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class ClearUserSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnf:clear-sessions {--user-id= : ID específico do usuário} {--all : Limpar todas as sessões}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa sessões de usuários para resolver problemas de perfil';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $all = $this->option('all');

        if ($all) {
            $this->clearAllSessions();
        } elseif ($userId) {
            $this->clearUserSession($userId);
        } else {
            $this->error('Especifique --user-id=ID ou --all');
            return 1;
        }

        return 0;
    }

    private function clearAllSessions()
    {
        $this->info('Limpando todas as sessões...');
        
        try {
            $sessionDriver = config('session.driver');
            
            if ($sessionDriver === 'database' && Schema::hasTable('sessions')) {
                // Limpar tabela de sessões
                DB::table('sessions')->delete();
                $this->info('✅ Sessões do banco de dados limpas!');
            } elseif ($sessionDriver === 'file') {
                // Limpar arquivos de sessão
                $sessionPath = config('session.files');
                if (File::exists($sessionPath)) {
                    $files = File::files($sessionPath);
                    foreach ($files as $file) {
                        File::delete($file);
                    }
                    $this->info('✅ ' . count($files) . ' arquivo(s) de sessão removido(s)!');
                } else {
                    $this->warn('⚠️  Diretório de sessões não encontrado: ' . $sessionPath);
                }
            }
            
            // Limpar cache relacionado a sessões
            Cache::flush();
            
            $this->info('✅ Todas as sessões foram limpas com sucesso!');
            $this->warn('⚠️  Todos os usuários precisarão fazer login novamente.');
        } catch (\Exception $e) {
            $this->error('❌ Erro ao limpar sessões: ' . $e->getMessage());
        }
    }

    private function clearUserSession($userId)
    {
        $this->info("Limpando sessões do usuário ID: {$userId}");
        
        try {
            // Buscar o usuário
            $user = DB::table('users')->where('id', $userId)->first();
            
            if (!$user) {
                $this->error("Usuário com ID {$userId} não encontrado!");
                return;
            }
            
            $sessionDriver = config('session.driver');
            $deletedSessions = 0;
            
            if ($sessionDriver === 'database' && Schema::hasTable('sessions')) {
                // Limpar sessões específicas do usuário (se usando database sessions)
                $deletedSessions = DB::table('sessions')
                    ->where('user_id', $userId)
                    ->delete();
            } elseif ($sessionDriver === 'file') {
                // Para sessões baseadas em arquivos, não podemos filtrar por usuário facilmente
                // Então limpamos todas as sessões
                $sessionPath = config('session.files');
                if (File::exists($sessionPath)) {
                    $files = File::files($sessionPath);
                    foreach ($files as $file) {
                        File::delete($file);
                    }
                    $deletedSessions = count($files);
                    $this->warn('⚠️  Com sessões baseadas em arquivos, todas as sessões foram limpas.');
                }
            }
            
            // Limpar cache específico do usuário
            Cache::forget("user_session_{$userId}");
            Cache::forget("user_roles_{$userId}");
            
            $this->info("✅ Sessões do usuário {$user->name} ({$user->email}) foram limpas!");
            $this->info("📊 {$deletedSessions} sessão(ões) removida(s)");
            $this->warn("⚠️  O usuário precisará fazer login novamente.");
        } catch (\Exception $e) {
            $this->error('❌ Erro ao limpar sessões do usuário: ' . $e->getMessage());
        }
    }
} 