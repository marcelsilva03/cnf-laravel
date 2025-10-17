<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Histórico de Pagamentos</h2>
            <p class="text-sm text-gray-600 mb-6">
                Visualize todos os seus pagamentos realizados, incluindo status, métodos de pagamento e datas.
            </p>
            
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page> 