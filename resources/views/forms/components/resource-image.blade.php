<div class="fi-fo-field-wrp">
    <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">{{ $label }}</label>
    <div class="flex justify-content-center align-items-center">
        @if ($field)
            <img
                src="{{ asset($getRecord()->{$field}) }}"
                alt="Foto"
                class="max-w-full h-auto rounded-lg shadow-md m-auto"
                width="200"
            />
        @else
            <p class="text-gray-500">Nenhuma imagem disponível.</p>
        @endif
    </div>
</div>

