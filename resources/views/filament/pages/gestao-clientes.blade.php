<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header com informações -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        📊 Gestão de Clientes
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Gerencie todos os clientes do sistema e seus faturamentos
                    </p>
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span>Cliente API</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span>Solicitante</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 bg-cyan-500 rounded-full"></div>
                        <span>Pesquisador</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instruções de uso -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Como usar esta página
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Filtros:</strong> Use os filtros para encontrar clientes específicos por tipo, status ou situação de faturamento</li>
                            <li><strong>Ações rápidas:</strong> Clique em "Novo Faturamento" para criar um faturamento diretamente para o cliente</li>
                            <li><strong>Busca:</strong> Use a busca para encontrar clientes por nome ou email</li>
                            <li><strong>Bulk actions:</strong> Selecione múltiplos clientes para ações em massa</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de clientes -->
        <div class="bg-white rounded-lg border border-gray-200">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page> 