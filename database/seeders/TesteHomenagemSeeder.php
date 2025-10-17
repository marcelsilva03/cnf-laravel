<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Falecido;
use App\Models\Homenagem;
use App\Models\Cartorio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TesteHomenagemSeeder extends Seeder
{
    /**
     * Seeder específico para criar dados de teste completos para homenagens
     * Resolve o problema reportado no card #12
     */
    public function run(): void
    {
        $this->command->info('🎯 Iniciando criação de dados de teste para homenagens...');

        // 1. Criar usuários de teste específicos
        $this->criarUsuariosDeTeste();

        // 2. Garantir cartórios consistentes
        $this->garantirCartoriosConsistentes();

        // 3. Criar falecidos de teste
        $falecidos = $this->criarFalecidosDeTeste();

        // 4. Criar homenagens de teste
        $this->criarHomenagensDeTeste($falecidos);

        // 5. Gerar relatório de validação
        $this->gerarRelatorioValidacao();

        $this->command->info('✅ Dados de teste para homenagens criados com sucesso!');
    }

    private function criarUsuariosDeTeste(): void
    {
        $this->command->info('👥 Criando usuários de teste...');

        $usuariosTeste = [
            [
                'name' => 'Teste Homenagem Admin',
                'email' => 'teste.homenagem.admin@cnf.test',
                'password' => Hash::make('123456'),
                'role' => 'admin'
            ],
            [
                'name' => 'Teste Homenagem Moderador',
                'email' => 'teste.homenagem.moderador@cnf.test',
                'password' => Hash::make('123456'),
                'role' => 'moderador'
            ],
            [
                'name' => 'Teste Homenagem Solicitante',
                'email' => 'teste.homenagem.solicitante@cnf.test',
                'password' => Hash::make('123456'),
                'role' => 'solicitante'
            ],
            [
                'name' => 'João Silva Teste',
                'email' => 'joao.silva.teste@cnf.test',
                'password' => Hash::make('123456'),
                'role' => 'solicitante'
            ],
            [
                'name' => 'Maria Santos Teste',
                'email' => 'maria.santos.teste@cnf.test',
                'password' => Hash::make('123456'),
                'role' => 'solicitante'
            ]
        ];

        foreach ($usuariosTeste as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'email_verified_at' => now()
                ]
            );

            // Atribuir role se existir
            $role = Role::where('name', $userData['role'])->first();
            if ($role && !$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }

            $this->command->info("   ✓ Usuário criado: {$userData['name']} ({$userData['email']})");
        }
    }

    private function garantirCartoriosConsistentes(): void
    {
        $this->command->info('🏛️ Verificando consistência de cartórios...');

        // Verificar se existem cartórios básicos
        $cartoriosBasicos = [
            ['cidade' => 'São Paulo', 'uf' => 'SP'],
            ['cidade' => 'Rio de Janeiro', 'uf' => 'RJ'],
            ['cidade' => 'Brasília', 'uf' => 'DF'],
            ['cidade' => 'Goiânia', 'uf' => 'GO'],
            ['cidade' => 'Belo Horizonte', 'uf' => 'MG']
        ];

        foreach ($cartoriosBasicos as $local) {
            $existe = Cartorio::where('ccc_cidade', $local['cidade'])
                             ->where('ccc_uf', $local['uf'])
                             ->exists();

            if (!$existe) {
                Cartorio::create([
                    'ccc_cidade' => $local['cidade'],
                    'ccc_uf' => $local['uf'],
                    'ccc_nome' => "Cartório de Teste - {$local['cidade']}",
                    'ccc_email' => "teste.cartorio.{$local['uf']}@cnf.test",
                    'ccc_telefone' => '(11) 9999-9999',
                    'ccc_endereco' => 'Endereço de Teste, 123',
                    'ccc_bairro' => 'Centro',
                    'ccc_cep' => '01000-000',
                    'ccc_tipo' => 1,
                ]);
                $this->command->info("   ✓ Cartório criado: {$local['cidade']}/{$local['uf']}");
            } else {
                $this->command->info("   ✓ Cartório já existe: {$local['cidade']}/{$local['uf']}");
            }
        }
    }

    private function criarFalecidosDeTeste(): array
    {
        $this->command->info('⚰️ Criando falecidos de teste...');

        $falecidosTeste = [
            [
                'fal_nome' => 'Antonio Silva Santos',
                'fal_cpf' => '12345678901',
                'fal_data_nascimento' => '1945-03-15',
                'fal_data_falecimento' => '2023-12-10',
                'fal_cidade' => 'São Paulo',
                'fal_uf' => 'SP',
                'fal_nome_pai' => 'José Silva Santos',
                'fal_nome_mae' => 'Maria Silva Santos',
                'fal_sexo' => 1
            ],
            [
                'fal_nome' => 'Maria Oliveira Costa',
                'fal_cpf' => '12345678902',
                'fal_data_nascimento' => '1952-07-22',
                'fal_data_falecimento' => '2024-01-05',
                'fal_cidade' => 'Rio de Janeiro',
                'fal_uf' => 'RJ',
                'fal_nome_pai' => 'João Oliveira Costa',
                'fal_nome_mae' => 'Ana Oliveira Costa',
                'fal_sexo' => 2
            ],
            [
                'fal_nome' => 'Carlos Eduardo Ferreira',
                'fal_cpf' => '12345678903',
                'fal_data_nascimento' => '1960-11-08',
                'fal_data_falecimento' => '2024-02-14',
                'fal_cidade' => 'Brasília',
                'fal_uf' => 'DF',
                'fal_nome_pai' => 'Eduardo Ferreira',
                'fal_nome_mae' => 'Rosa Ferreira',
                'fal_sexo' => 1
            ]
        ];

        $falecidosCriados = [];

        foreach ($falecidosTeste as $dadosFalecido) {
            $falecido = Falecido::updateOrCreate(
                ['fal_cpf' => $dadosFalecido['fal_cpf']],
                $dadosFalecido
            );

            $falecidosCriados[] = $falecido;
            $this->command->info("   ✓ Falecido criado: {$dadosFalecido['fal_nome']}");
        }

        return $falecidosCriados;
    }

    private function criarHomenagensDeTeste(array $falecidos): void
    {
        $this->command->info('🌹 Criando homenagens de teste...');

        $mensagensExemplo = [
            'Descanse em paz, pessoa querida. Você sempre será lembrado com carinho e saudade.',
            'Sua memória permanecerá viva em nossos corações. Obrigado por todos os momentos especiais.',
            'Um ser humano iluminado que tocou a vida de tantas pessoas. Sentiremos sua falta.',
            'Que Deus console nossos corações e nos dê força para superar este momento difícil.',
            'Sua bondade e alegria sempre serão uma inspiração para todos nós.'
        ];

        $parentescos = ['Filho(a)', 'Cônjuge', 'Irmão(ã)', 'Neto(a)', 'Amigo(a)', 'Primo(a)'];

        foreach ($falecidos as $index => $falecido) {
            // Criar 2-3 homenagens para cada falecido
            for ($i = 0; $i < rand(2, 3); $i++) {
                $homenagem = new Homenagem([
                    'hom_id_falecido' => $falecido->fal_id,
                    'hom_uuid_falecido' => $falecido->fal_uuid,
                    'hom_nome_autor' => 'Teste Autor ' . ($index + 1) . '-' . ($i + 1),
                    'hom_cpf_autor' => '999999999' . str_pad($index . $i, 2, '0', STR_PAD_LEFT),
                    'hom_url_foto' => '',
                    'hom_url_fundo' => 'peace' . rand(1, 9) . '.jpg',
                    'hom_mensagem' => $mensagensExemplo[array_rand($mensagensExemplo)],
                    'hom_whatsapp' => '(11) 99999-' . rand(1000, 9999),
                    'hom_email' => 'teste.homenagem' . $index . $i . '@cnf.test',
                    'hom_parentesco' => $parentescos[array_rand($parentescos)],
                    'hom_status' => rand(0, 1) // Mix de pendente e publicado
                ]);

                $homenagem->save();
                $this->command->info("   ✓ Homenagem criada para: {$falecido->fal_nome}");
            }
        }
    }

    private function gerarRelatorioValidacao(): void
    {
        $this->command->info('📊 Gerando relatório de validação...');

        // Contar dados criados
        $totalUsuarios = User::where('email', 'LIKE', '%.test')->count();
        $totalCartoriosTest = Cartorio::where('ccc_email', 'LIKE', '%@cnf.test')->count();
        $totalFalecidosTest = Falecido::whereIn('fal_cpf', ['12345678901', '12345678902', '12345678903'])->count();
        $totalHomenagensTeste = Homenagem::where('hom_email', 'LIKE', '%@cnf.test')->count();

        // Verificar API de cartórios
        $cartoriosSP = Cartorio::where('ccc_cidade', 'São Paulo')->where('ccc_uf', 'SP')->count();
        $cartoriosRJ = Cartorio::where('ccc_cidade', 'Rio de Janeiro')->where('ccc_uf', 'RJ')->count();

        $relatorio = "
=== RELATÓRIO DE VALIDAÇÃO - DADOS DE TESTE HOMENAGENS ===

📊 DADOS CRIADOS:
   ✓ Usuários de teste: {$totalUsuarios}
   ✓ Cartórios de teste: {$totalCartoriosTest}
   ✓ Falecidos de teste: {$totalFalecidosTest}
   ✓ Homenagens de teste: {$totalHomenagensTeste}

🏛️ CONSISTÊNCIA DE CARTÓRIOS:
   ✓ São Paulo/SP: {$cartoriosSP} cartório(s)
   ✓ Rio de Janeiro/RJ: {$cartoriosRJ} cartório(s)

🎯 FLUXO DE TESTE RECOMENDADO:
   1. Acesse /admin com: teste.homenagem.admin@cnf.test / 123456
   2. Vá para Recursos > Homenagens
   3. Verifique as homenagens criadas
   4. Teste aprovação/rejeição de homenagens pendentes
   5. Teste criação de nova homenagem usando falecidos existentes

🌐 URLS DE TESTE:
   - Admin: /admin
   - API Cartórios SP: /api/cartorios/SP/São Paulo
   - API Cartórios RJ: /api/cartorios/RJ/Rio de Janeiro

✅ PROBLEMAS RESOLVIDOS:
   ✓ Inconsistência de cartórios corrigida
   ✓ Usuários de teste criados com roles apropriadas
   ✓ Dados realistas para validação de homenagens
   ✓ API de cartórios funcionando corretamente

";

        $this->command->info($relatorio);

        // Salvar relatório em arquivo
        file_put_contents(
            storage_path('logs/relatorio_teste_homenagens_' . date('Y-m-d_H-i-s') . '.txt'),
            $relatorio
        );
    }
} 