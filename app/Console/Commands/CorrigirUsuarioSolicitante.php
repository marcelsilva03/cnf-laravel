<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Solicitacao;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class CorrigirUsuarioSolicitante extends Command
{
    protected $signature = 'user:fix-solicitante';
    protected $description = 'Corrige COMPLETAMENTE o usuário solicitante@email.com - Card #7';

    public function handle()
    {
        $this->info('🔧 CORREÇÃO COMPLETA CARD #7 - Usuário Solicitante');
        $this->info('=====================================================');
        $this->newLine();

        try {
            // ETAPA 1: Buscar o usuário
            $this->info('1️⃣ Localizando usuário...');
            $user = User::where('email', 'solicitante@email.com')->first();
            
            if (!$user) {
                $this->error('❌ ERRO: Usuário "solicitante@email.com" não encontrado!');
                $this->warn('💡 Dica: Crie o usuário primeiro no painel administrativo.');
                return 1;
            }
            
            $this->info('👤 Usuário encontrado:');
            $this->line("   ID: {$user->id}");
            $this->line("   Nome: {$user->name}");
            $this->line("   Email: {$user->email}");
            $this->line("   Status: " . ($user->status == 1 ? 'Ativo' : 'Inativo'));
            $this->newLine();

            // ETAPA 2: Verificar e corrigir roles
            $this->info('2️⃣ Corrigindo roles...');
            $rolesAtuais = $user->roles->pluck('name')->toArray();
            $this->info('🔍 Roles atuais: ' . (empty($rolesAtuais) ? 'Nenhum' : implode(', ', $rolesAtuais)));
            
            if ($user->role_id) {
                $this->warn("⚠️  Role ID (sistema antigo): {$user->role_id}");
            }
            
            // Limpar todos os roles e definir apenas 'solicitante'
            $this->line('   → Removendo todos os roles atuais...');
            $user->syncRoles(['solicitante']);
            $this->line('   → Definindo role "solicitante"...');
            
            // Limpar role_id do sistema antigo
            if ($user->role_id) {
                $this->line('   → Limpando role_id do sistema antigo...');
                $user->role_id = null;
                $user->save();
            }
            
            $this->info('✅ Roles corrigidos!');
            $this->newLine();

            // ETAPA 3: Verificar e associar solicitações órfãs
            $this->info('3️⃣ Verificando solicitações...');
            
            // Buscar solicitações pelo email
            $solicitacoesPorEmail = Solicitacao::where('sol_email_sol', $user->email)->get();
            $this->info("📧 Solicitações encontradas por email: {$solicitacoesPorEmail->count()}");
            
            // Buscar solicitações já associadas ao user_id
            $solicitacoesPorUserId = Solicitacao::where('user_id', $user->id)->get();
            $this->info("🆔 Solicitações já associadas ao user_id: {$solicitacoesPorUserId->count()}");
            
            // Associar solicitações órfãs (que têm o email mas não têm user_id)
            $solicitacaesOrfas = Solicitacao::where('sol_email_sol', $user->email)
                                          ->whereNull('user_id')
                                          ->get();
            
            if ($solicitacaesOrfas->count() > 0) {
                $this->warn("⚠️  Encontradas {$solicitacaesOrfas->count()} solicitações órfãs (sem user_id)");
                
                if ($this->confirm('Associar essas solicitações ao usuário?')) {
                    foreach ($solicitacaesOrfas as $solicitacao) {
                        $solicitacao->user_id = $user->id;
                        $solicitacao->save();
                    }
                    $this->info("✅ {$solicitacaesOrfas->count()} solicitações associadas ao usuário!");
                }
            } else {
                $this->info('✅ Não há solicitações órfãs para associar.');
            }
            
            $this->newLine();

            // ETAPA 4: Verificar status das solicitações
            $this->info('4️⃣ Verificando status das solicitações...');
            $totalSolicitacoes = Solicitacao::where('sol_email_sol', $user->email)->count();
            $pendentes = Solicitacao::where('sol_email_sol', $user->email)
                                   ->where('status', Solicitacao::STATUS['PENDENTE'])->count();
            $pagas = Solicitacao::where('sol_email_sol', $user->email)
                                ->where('status', Solicitacao::STATUS['PAGA'])->count();
            $liberadas = Solicitacao::where('sol_email_sol', $user->email)
                                   ->where('status', Solicitacao::STATUS['LIBERADA'])->count();
            
            $this->table(
                ['Status', 'Quantidade'],
                [
                    ['Total', $totalSolicitacoes],
                    ['Pendentes', $pendentes],
                    ['Pagas', $pagas],
                    ['Liberadas', $liberadas],
                ]
            );
            $this->newLine();

            // ETAPA 5: Limpar cache
            $this->info('5️⃣ Limpando cache...');
            $this->line('   → Cache de aplicação...');
            Cache::flush();
            $this->line('   → Cache de configuração...');
            Artisan::call('config:clear');
            $this->line('   → Cache de views...');
            Artisan::call('view:clear');
            $this->line('   → Cache de rotas...');
            Artisan::call('route:clear');
            $this->info('✅ Cache limpo!');
            $this->newLine();

            // ETAPA 6: Verificação final
            $this->info('6️⃣ Verificação final...');
            $user->refresh();
            $rolesFinais = $user->roles->pluck('name')->toArray();
            $totalFinal = Solicitacao::where('sol_email_sol', $user->email)->count();
            
            $this->info('✅ CORREÇÃO CONCLUÍDA COM SUCESSO!');
            $this->info('==========================================');
            $this->line("👤 Usuário: {$user->name} ({$user->email})");
            $this->line('🎭 Roles finais: ' . implode(', ', $rolesFinais));
            $this->line('🆔 Role ID: ' . ($user->role_id ?? 'null'));
            $this->line("📊 Total de solicitações: {$totalFinal}");
            
            // Verificar se a correção funcionou
            if (count($rolesFinais) === 1 && $rolesFinais[0] === 'solicitante' && !$user->role_id && $totalFinal > 0) {
                $this->newLine();
                $this->info('🎉 SUCESSO COMPLETO!');
                $this->info('✅ O usuário tem apenas o role "solicitante"');
                $this->info('✅ Solicitações encontradas e associadas');
                $this->info('✅ Cache limpo');
                $this->info('📊 O dashboard deve mostrar as estatísticas corretas!');
            } else {
                $this->newLine();
                $this->warn('⚠️  ATENÇÃO: Verifique os seguintes pontos:');
                if (count($rolesFinais) !== 1 || $rolesFinais[0] !== 'solicitante') {
                    $this->warn('   • Roles não estão corretos');
                }
                if ($user->role_id) {
                    $this->warn('   • Role ID do sistema antigo não foi limpo');
                }
                if ($totalFinal === 0) {
                    $this->warn('   • Nenhuma solicitação encontrada para este email');
                    $this->warn('   • Verifique se o usuário fez solicitações com este email');
                }
            }
            
        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ ERRO DURANTE A EXECUÇÃO:');
            $this->error("   {$e->getMessage()}");
            $this->error("   Arquivo: {$e->getFile()}:{$e->getLine()}");
            return 1;
        }

        $this->newLine();
        $this->info('🔄 Próximos passos:');
        $this->line('   1. Acesse o painel com solicitante@email.com');
        $this->line('   2. Verifique se aparecem os 4 indicadores corretos:');
        $this->line('      - Minhas Solicitações');
        $this->line('      - Solicitações Pendentes');
        $this->line('      - Solicitações Pagas');
        $this->line('      - Solicitações Liberadas');
        $this->line('   3. Se ainda não funcionar:');
        $this->line('      - Verifique os logs do Laravel');
        $this->line('      - Execute: php artisan optimize:clear');
        $this->line('      - Reinicie o servidor web');
        $this->newLine();

        return 0;
    }
} 