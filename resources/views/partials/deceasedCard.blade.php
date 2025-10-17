<div class="row resultado_contorno border-top border-dark py-3 align-items-start">
  {{-- Falecido --}}
  <div class="col-md-3">
    <div class="titulo_resultado_mae">Falecido:</div>
    <div class="nome_falecido_resultado texto_lista text-uppercase">
      {{ $resultado->pes_nome }}
    </div>
  </div>

  {{-- Local (Cidade / UF) --}}
  <div class="col-md-3">
    <div class="titulo_resultado_local">Local:</div>
    <div class="local_resultado texto_lista text-uppercase">
      {{ $resultado->pes_cidade ?? '–' }} / {{ $resultado->pes_uf ?? '–' }}
    </div>
  </div>

  {{-- Mãe --}}
  <div class="col-md-2">
    <div class="titulo_resultado_mae">Mãe:</div>
    @php
      $nomeDaMae = empty($resultado->pes_nome_mae)
        ? '–'
        : explode(' ', $resultado->pes_nome_mae)[0];
    @endphp
    <div class="nome_mae_resultado texto_lista text-uppercase">
      {{ $nomeDaMae }}
    </div>
  </div>

  {{-- Data de Falecimento --}}
  <div class="col-md-2 text-md-end">
    <div class="titulo_resultado_data">Falecimento:</div>
    <div class="data_falecido_resultado texto_lista">
      {{ \Carbon\Carbon::parse($resultado->pes_data_falecimento)
           ->format('d/m/Y') }}
    </div>
  </div>

  {{-- Botões --}}
  <div class="col-md-2 d-flex flex-column justify-content-end gap-2">
    <a href="{{ route('formulario-pesquisa') }}?hash={{ $resultado->hash }}"
       class="btn btn-cnf btn-sm w-100">Ver Cartório</a>
    <a href="{{ route('registrador-de-nova-homenagem') }}?uuid={{ $resultado->hash }}"
       class="btn btn-warning btn-sm w-100">Homenagear</a>
    <button class="btn btn-danger btn-sm w-100"
            data-bs-toggle="modal"
            data-bs-target="#exampleModal"
            data-cnf-nome="{{ $resultado->pes_nome }}"
            data-cnf-uuid="{{ $resultado->hash }}"
            data-cnf-id="{{ $resultado->pes_id }}"
            data-cnf-cidade="{{ $resultado->pes_cidade ?? '' }}"
            data-cnf-uf="{{ $resultado->pes_uf ?? '' }}"
            data-cnf-data="{{ \Carbon\Carbon::parse($resultado->pes_data_falecimento)->format('d/m/Y') }}">
      Comunicar Erro
    </button>
  </div>
</div>
