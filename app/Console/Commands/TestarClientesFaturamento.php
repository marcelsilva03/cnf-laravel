<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestarClientesFaturamento extends Command
{
    protected $signature = 'teste:clientes-faturamento';
    protected $description = 'Testa se há clientes disponíveis para faturamento';

    public function handle()
    {
        $this->info('🔍 TESTE DE CLIENTES PARA FATURAMENTO');
        $this->info(str_repeat("=", 50));
        $this->newLine();

        try {
            // Verificar clientes disponíveis para faturamento
            $clientesDisponiveis = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['clienteapi', 'solicitante', 'pesquisador']);
            })
            ->where('status', User::STATUS['ATIVO'])
            ->with('roles')
            ->orderBy('name')
            ->get();

            $this->info("📊 ESTATÍSTICAS:");
            $this->info("   Total de clientes disponíveis: " . $clientesDisponiveis->count());
            $this->newLine();

            if ($clientesDisponiveis->count() > 0) {
                $this->info("✅ HÁ CLIENTES DISPONÍVEIS!");
                $this->newLine();

                $headers = ['ID', 'Nome', 'Email', 'Tipo', 'Status'];
                $rows = [];

                foreach ($clientesDisponiveis as $cliente) {
                    $roleDisplayName = match($cliente->roles->first()?->name) {
                        'clienteapi' => 'Cliente API',
                        'solicitante' => 'Solicitante', 
                        'pesquisador' => 'Pesquisador',
                        default => 'N/A'
                    };

                    $statusText = $cliente->status === User::STATUS['ATIVO'] ? 'ATIVO' : 'INATIVO';

                    $rows[] = [
                        $cliente->id,
                        substr($cliente->name, 0, 20),
                        substr($cliente->email, 0, 25),
                        $roleDisplayName,
                        $statusText
                    ];
                }

                $this->table($headers, $rows);

                $primeiro = $clientesDisponiveis->first();
                $this->info("🎯 DADOS PARA TESTE NO FORMULÁRIO:");
                $this->info("Cliente ID: {$primeiro->id}");
                $this->info("Nome: {$primeiro->name}");
                $this->info("Email: {$primeiro->email}");
                $this->info("Tipo: " . match($primeiro->roles->first()?->name) {
                    'clienteapi' => 'Cliente API',
                    'solicitante' => 'Solicitante',
                    'pesquisador' => 'Pesquisador',
                    default => 'N/A'
                });

                // Agrupar por tipo
                $this->newLine();
                $this->info("📊 CLIENTES POR TIPO:");
                $porTipo = $clientesDisponiveis->groupBy(function($cliente) {
                    return $cliente->roles->first()?->name ?? 'sem_role';
                });

                foreach ($porTipo as $tipo => $clientes) {
                    $tipoDisplay = match($tipo) {
                        'clienteapi' => 'Cliente API',
                        'solicitante' => 'Solicitante',
                        'pesquisador' => 'Pesquisador',
                        default => 'Sem Role'
                    };
                    $this->info("   {$tipoDisplay}: {$clientes->count()} cliente(s)");
                }

                $this->newLine();
                $this->info("💡 PRÓXIMOS PASSOS:");
                $this->info("1. Acesse: /admin/faturamentos/create");
                $this->info("2. No campo 'Cliente', busque por: '{$primeiro->name}'");
                $this->info("3. Verifique se o cliente aparece na lista");
                $this->info("4. Teste a criação do faturamento");

            } else {
                $this->error("❌ NÃO HÁ CLIENTES DISPONÍVEIS!");
                $this->newLine();
                $this->info("💡 SOLUÇÕES:");
                $this->info("1. Criar cliente manualmente no admin");
                $this->info("2. Usar o botão '+' no formulário de faturamento");
                $this->info("3. Verificar se há usuários com roles corretas");

                // Verificar se há usuários sem roles apropriadas
                $usuariosSemRole = User::where('status', User::STATUS['ATIVO'])
                    ->whereDoesntHave('roles', function ($query) {
                        $query->whereIn('name', ['clienteapi', 'solicitante', 'pesquisador']);
                    })
                    ->count();

                $this->info("ℹ️  Usuários ativos sem role apropriada: {$usuariosSemRole}");
            }

            $this->newLine();
            $this->info(str_repeat("=", 50));
            $this->info("✅ TESTE CONCLUÍDO!");

        } catch (\Exception $e) {
            $this->error("❌ ERRO: " . $e->getMessage());
            $this->error("Stack trace:");
            $this->error($e->getTraceAsString());
        }
    }
} 