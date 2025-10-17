<x-filament-panels::page>
    <x-filament::section>
        <div class="space-y-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Documentação da API CNF</h2>
                <p class="mt-2 text-gray-600">Esta documentação fornece informações sobre como utilizar a API do Cadastro Nacional de Falecidos.</p>
            </div>

            <!-- Autenticação -->
            <div class="bg-blue-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">🔐 Autenticação</h3>
                <p class="text-blue-800 mb-3">Para acessar a API, você deve incluir sua chave de API no corpo da requisição.</p>
                <div class="bg-blue-100 p-3 rounded">
                    <code class="text-sm text-blue-900">
                        Sua chave de API: <strong>{{ auth()->user()->apiClient?->api_key ?? 'Não configurada' }}</strong>
                    </code>
                </div>
            </div>

            <!-- Endpoint Principal -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📡 Endpoint Principal</h3>
                
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-900">Consultar Falecido</h4>
                        <div class="mt-2 bg-gray-50 p-3 rounded">
                            <p><span class="font-medium">URL:</span> <code>POST /api/falecido</code></p>
                            <p><span class="font-medium">Descrição:</span> Recupera os dados de um falecido com base no CPF</p>
                        </div>
                    </div>

                    <div>
                        <h5 class="font-medium text-gray-700">Parâmetros Obrigatórios:</h5>
                        <ul class="mt-2 space-y-1 text-sm text-gray-600">
                            <li>• <code>cpf</code> - CPF do falecido (11 dígitos, apenas números)</li>
                            <li>• <code>api_key</code> - Sua chave de API</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Exemplo de Requisição -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">📝 Exemplo de Requisição</h3>
                <div class="bg-gray-900 text-green-400 p-4 rounded text-sm overflow-x-auto">
<pre><code>curl -X POST {{ url('/api/falecido') }} \
    -H "Content-Type: application/json" \
    -d '{
        "cpf": "12345678901",
        "api_key": "{{ auth()->user()->apiClient?->api_key ?? 'SUA_CHAVE_API' }}"
    }'</code></pre>
                </div>
            </div>

            <!-- Exemplo de Resposta -->
            <div class="bg-green-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-green-900 mb-3">✅ Exemplo de Resposta (Sucesso)</h3>
                <div class="bg-green-100 p-4 rounded text-sm overflow-x-auto">
<pre><code>{
    "cpf": "12345678901",
    "full_name": "João da Silva",
    "mothers_name": "Maria da Silva",
    "birthday": "1950-01-15",
    "data de falecimento": "2023-12-01"
}</code></pre>
                </div>
            </div>

            <!-- Resposta de Erro -->
            <div class="bg-red-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-red-900 mb-3">❌ Exemplo de Resposta (Não Encontrado)</h3>
                <div class="bg-red-100 p-4 rounded text-sm overflow-x-auto">
<pre><code>{
    "cpf": "12345678901",
    "message": "Registro não encontrado no banco de dados."
}</code></pre>
                </div>
            </div>

            <!-- Códigos de Status -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Códigos de Status HTTP</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="font-medium">200 OK</span>
                        <span class="text-gray-600">Requisição bem-sucedida</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">400 Bad Request</span>
                        <span class="text-gray-600">Parâmetros inválidos ou ausentes</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">401 Unauthorized</span>
                        <span class="text-gray-600">Chave de API inválida</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">404 Not Found</span>
                        <span class="text-gray-600">CPF não encontrado na base de dados</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">429 Too Many Requests</span>
                        <span class="text-gray-600">Limite de requisições excedido</span>
                    </div>
                </div>
            </div>

            <!-- Limites e Uso -->
            <div class="bg-yellow-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-900 mb-3">⚠️ Limites de Uso</h3>
                <div class="space-y-2 text-yellow-800">
                    @if(auth()->user()->apiClient)
                        <p>• <strong>Limite mensal:</strong> {{ number_format(auth()->user()->apiClient->request_limit, 0, ',', '.') }} requisições</p>
                        <p>• <strong>Uso atual:</strong> {{ number_format(auth()->user()->apiClient->requests_made ?? 0, 0, ',', '.') }} requisições</p>
                        <p>• <strong>Disponível:</strong> {{ number_format((auth()->user()->apiClient->request_limit - (auth()->user()->apiClient->requests_made ?? 0)), 0, ',', '.') }} requisições</p>
                    @else
                        <p>• Configure sua chave de API para ver os limites</p>
                    @endif
                    <p>• Todas as requisições são registradas e monitoradas</p>
                    <p>• Contate o suporte se precisar aumentar seu limite</p>
                </div>
            </div>

            <!-- Suporte -->
            <div class="bg-blue-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">🆘 Suporte</h3>
                <div class="space-y-2 text-blue-800">
                    <p>• <strong>Email:</strong> suporte@cnf.gov.br</p>
                    <p>• <strong>Telefone:</strong> (11) 1234-5678</p>
                    <p>• <strong>Horário:</strong> Segunda a Sexta, 8h às 18h</p>
                    <p>• <strong>Documentação:</strong> Esta página é atualizada automaticamente</p>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page> 