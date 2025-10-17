@php
    if($paginacao['total'] > 0) {
        $corBox = '#eaf8f2';      // Verde claro
        $corTexto = '#245846';    // Verde escuro (texto)
        $corIcone = '#25a244';    // Verde forte (ícone)
    } else {
        $corBox = '#ffe8e3';      // Laranja/avermelhado claro
        $corTexto = '#ed3c0d';    // Laranja forte (texto)
        $corIcone = '#ed3c0d';    // Laranja forte (ícone)
    }
@endphp

<div class="result-title card mb-4 px-4 py-3 d-flex flex-row align-items-center gap-3"
     style="border-radius: 1rem; background: {{ $corBox }}; color: {{ $corTexto }};">
    <i class="bi {{ $paginacao['total'] > 0 ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }} fs-2" style="color: {{ $corIcone }};"></i>
    <div class="flex-grow-1">
        <h4 class="fw-bold mb-1" style="color: {{ $corTexto }};">
            @if(isset($nome))
                {{ $totalResultados == 1 ? 'Resultado' : 'Resultados' }} da pesquisa para "{{ $nome }}"
            @else
                Nenhum registro encontrado.
            @endif
        </h4>
        @if(isset($nome))
            <div class="text-muted mb-2 text-break" style="font-size: 1em; word-break: break-all; max-width: 100%;">
                @if($exata)
                    (pesquisado nome exato: "{{ $nomeSanitizado }}")
                @else
                    (pesquisado nomes contendo: "{{ str_replace(' ', '+', $nomeSanitizado). '+' }}")
                @endif
            </div>
        @endif
        <div>
            <strong>
                @if($totalResultados > 0)
                    {{ $totalResultados == 1 ? '1 registro encontrado.' : number_format($totalResultados, 0, ',', '.') . ' registros encontrados.' }}
                @else
                    Nenhum registro encontrado.
                @endif
            </strong>
        </div>
    </div>
</div>