<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            🔧 Debug de Sessões e Perfis
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Informações do Usuário -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-3">👤 Usuário Atual</h3>
                <div class="space-y-2 text-sm">
                    <div><strong>ID:</strong> {{ $userInfo['id'] }}</div>
                    <div><strong>Nome:</strong> {{ $userInfo['name'] }}</div>
                    <div><strong>Email:</strong> {{ $userInfo['email'] }}</div>
                    <div><strong>Status:</strong> 
                        <span class="px-2 py-1 rounded text-xs {{ $userInfo['status'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $userInfo['status'] ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                    <div><strong>Perfis:</strong> 
                        @foreach($userInfo['roles'] as $role)
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs mr-1">{{ $role }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Informações da Sessão -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-3">🔐 Sessão Atual</h3>
                <div class="space-y-2 text-sm">
                    <div><strong>Driver:</strong> 
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded text-xs">
                            {{ strtoupper($sessionInfo['driver']) }}
                        </span>
                    </div>
                    <div><strong>ID da Sessão:</strong> <code class="text-xs">{{ substr($sessionInfo['session_id'], 0, 10) }}...</code></div>
                    <div><strong>Perfil na Sessão:</strong> 
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">
                            {{ $sessionInfo['current_role'] ?? 'Não definido' }}
                        </span>
                    </div>
                    <div><strong>Hash de Senha:</strong> 
                        <span class="px-2 py-1 rounded text-xs {{ $sessionInfo['password_hash_stored'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $sessionInfo['password_hash_stored'] ? 'Armazenado' : 'Não encontrado' }}
                        </span>
                    </div>
                    <div><strong>Chaves na Sessão:</strong> {{ count($sessionInfo['session_data_keys']) }}</div>
                </div>
            </div>

            <!-- Estatísticas de Sessões -->
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-3">📊 Estatísticas</h3>
                <div class="space-y-2 text-sm">
                    <div><strong>Driver de Sessão:</strong> 
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                            {{ strtoupper($sessionStats['driver']) }}
                        </span>
                    </div>

                    @if($sessionStats['driver'] === 'database')
                        @if($sessionStats['table_exists'])
                            <div><strong>Tabela Sessions:</strong> 
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">✅ Existe</span>
                            </div>
                            @if(isset($sessionStats['error']))
                                <div class="text-red-600"><strong>Erro:</strong> {{ $sessionStats['error'] }}</div>
                            @else
                                <div><strong>Total de Sessões:</strong> {{ $sessionStats['total_sessions'] }}</div>
                                <div><strong>Sessões Autenticadas:</strong> {{ $sessionStats['authenticated_sessions'] }}</div>
                                <div><strong>Suas Sessões:</strong> {{ $sessionStats['user_sessions'] }}</div>
                            @endif
                        @else
                            <div><strong>Tabela Sessions:</strong> 
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">❌ Não existe</span>
                            </div>
                            <div class="text-orange-600 text-xs">
                                <strong>Ação necessária:</strong><br>
                                Execute: <code>php artisan migrate</code>
                            </div>
                        @endif
                    @elseif($sessionStats['driver'] === 'file')
                        @if(isset($sessionStats['error']))
                            <div class="text-red-600"><strong>Erro:</strong> {{ $sessionStats['error'] }}</div>
                        @else
                            <div><strong>Arquivos de Sessão:</strong> {{ $sessionStats['total_sessions'] }}</div>
                            <div class="text-xs text-gray-600">
                                <strong>Caminho:</strong><br>
                                <code>{{ $sessionStats['session_path'] ?? 'N/A' }}</code>
                            </div>
                        @endif
                    @else
                        <div class="text-blue-600">
                            <strong>Driver:</strong> {{ $sessionStats['driver'] }}<br>
                            <span class="text-xs">Estatísticas não disponíveis para este driver</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ações de Debug -->
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('usuario.force-logout') }}" 
               class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                🚨 Logout Forçado
            </a>
            
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                🔄 Recarregar Página
            </button>
            
            <button onclick="clearLocalStorage()" 
                    class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm">
                🗑️ Limpar Cache Local
            </button>

            <button onclick="clearSessions()" 
                    class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 text-sm">
                🧹 Limpar Todas as Sessões
            </button>

            @if($sessionStats['driver'] === 'database' && !$sessionStats['table_exists'])
                <button onclick="createSessionsTable()" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                    🔧 Criar Tabela Sessions
                </button>
            @endif
        </div>

        <!-- Últimos Logins -->
        <div class="mt-6">
            <h3 class="font-semibold text-gray-900 mb-3">📝 Últimos Acessos</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">ID</th>
                            <th class="px-3 py-2 text-left">Nome</th>
                            <th class="px-3 py-2 text-left">Email</th>
                            <th class="px-3 py-2 text-left">Último Acesso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLogins as $login)
                        <tr class="border-b">
                            <td class="px-3 py-2">{{ $login->id }}</td>
                            <td class="px-3 py-2">{{ $login->name }}</td>
                            <td class="px-3 py-2">{{ $login->email }}</td>
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($login->updated_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-filament::section>

    <script>
        function clearLocalStorage() {
            localStorage.clear();
            sessionStorage.clear();
            alert('Cache local limpo!');
        }

        function clearSessions() {
            if (confirm('Deseja limpar todas as sessões? Todos os usuários precisarão fazer login novamente.')) {
                alert('Execute no terminal: php artisan cnf:clear-sessions --all');
            }
        }

        function createSessionsTable() {
            if (confirm('Deseja criar a tabela sessions? Isso executará as migrações.')) {
                alert('Execute no terminal: php artisan migrate');
            }
        }
    </script>
</x-filament-widgets::widget> 