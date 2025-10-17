<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Valor</label>
            <p class="mt-1 text-sm text-gray-900">R$ {{ number_format($record->valor, 2, ',', '.') }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Método de Pagamento</label>
            <p class="mt-1 text-sm text-gray-900">
                @switch($record->metodo)
                    @case('pix')
                        PIX
                        @break
                    @case('cartao')
                        Cartão de Crédito
                        @break
                    @case('boleto')
                        Boleto Bancário
                        @break
                    @case('transferencia')
                        Transferência Bancária
                        @break
                    @default
                        {{ ucfirst($record->metodo) }}
                @endswitch
            </p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <p class="mt-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @switch($record->status)
                        @case('pendente')
                            bg-yellow-100 text-yellow-800
                            @break
                        @case('concluido')
                            bg-green-100 text-green-800
                            @break
                        @case('cancelado')
                            bg-red-100 text-red-800
                            @break
                        @default
                            bg-gray-100 text-gray-800
                    @endswitch
                ">
                    @switch($record->status)
                        @case('pendente')
                            Pendente
                            @break
                        @case('concluido')
                            Concluído
                            @break
                        @case('cancelado')
                            Cancelado
                            @break
                        @default
                            {{ ucfirst($record->status) }}
                    @endswitch
                </span>
            </p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Data do Pagamento</label>
            <p class="mt-1 text-sm text-gray-900">{{ $record->data_pagamento->format('d/m/Y') }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Data de Criação</label>
            <p class="mt-1 text-sm text-gray-900">{{ $record->created_at->format('d/m/Y H:i') }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Última Atualização</label>
            <p class="mt-1 text-sm text-gray-900">{{ $record->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    
    @if($record->descricao)
        <div>
            <label class="block text-sm font-medium text-gray-700">Descrição</label>
            <p class="mt-1 text-sm text-gray-900">{{ $record->descricao }}</p>
        </div>
    @endif
</div> 