@if ($field)
    <img
        src="{{ asset($getRecord()->{$field}) }}"
        alt="Foto"
        class="max-w-full h-auto rounded-lg shadow-md"
    />
@else
    <p class="text-gray-500">Nenhuma imagem disponível.</p>
@endif
