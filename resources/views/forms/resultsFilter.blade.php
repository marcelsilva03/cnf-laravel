<div class="card p-4 mb-4 border bg-light" style="border-radius: 1rem;">
    <form method="POST" action="{{ route('resultados') }}" id="filtro-form">
        <input type="hidden" name="recaptcha_version" value="RESULTADO">
        @csrf
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY_RESULTADO') }}"></script>
        <div class="row align-items-center pb-3 mb-3 g-3">
            <div class="col-lg-6 col-md-6 col-sm-12 col-12 mb-2 mb-lg-0">
                <input type="text" name="nome" class="form-control" id="nomefal"
                    placeholder="Nome:" required minlength="3"
                    value="{{ !empty($nome) ? $nome : old('nome') }}">
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-2 mb-lg-0">
                <div class="form-check">
                    <input type="hidden" name="nome-exato" value="0">
                    <input type="checkbox" name="nome-exato" value="1" {{ old('nome-exato', $exata ?? false) ? 'checked' : '' }} class="form-check-input" id="exatamente" style="margin-left: -1.2rem;"
                     {{ request('nome-exato') ? 'checked' : '' }}>
                    <label for="exatamente" class="form-check-label exatamenteTxt" style="margin-left: 0.2rem;">
                        Buscar exatamente como escrito
                    </label>
                </div>
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12 col-12 d-grid">
                <button type="submit" class="btn btn-success fw-bold" id="botao-filtro">Pesquisar</button>
            </div>
        </div>
        @if($totalResultados > 0)
        <div class="row align-items-center border-top pb-3 mb-3 g-3">
            <div class="col-lg-4 col-md-4 col-12 mb-2 mb-lg-0">
                Filtre os resultados por UF e/ou Cidade:
            </div>
            <div class="col-lg-2 col-md-2 col-4">
                <select name="estado" id="estados" class="form-select">
                @if(!isset($estado) && !request('estado'))
                    <option value="" name="untouched">UF</option>
                @endif
                @foreach($ufs as $uf)
                    @php
                        $estadoSelecionado = isset($estado) ? $estado : request('estado');
                    @endphp
                    <option value="{{ $uf }}" {{ $estadoSelecionado == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6 col-md-6 col-8">
            @php
                $cidadeSelecionada = isset($cidadeSelecionada) ? $cidadeSelecionada : $cidade;
            @endphp
                <select name="cidade" id="cidades" class="form-select" {{ empty($estadoSelecionado) ? 'disabled' : '' }}>
                    <option value="">Cidade</option>
                    @foreach($cidades as $cidade)
                    <option value="{{ $cidade }}" {{ $cidadeSelecionada == $cidade ? 'selected' : '' }}>{{ $cidade }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if(!empty($estadoSelecionado) || !empty($cidadeSelecionada))
            <div class="mb-3 text-center" style="color: #245846;">
            @if($paginacao['total'] == 1)
                Encontrado <strong>1</strong> resultado para o filtro
            @else
                Encontrados <strong>{{ number_format($paginacao['total'], 0, ',', '.') }}</strong> resultados para o filtro
            @endif
            @if(!empty($cidadeSelecionada) && !empty($estadoSelecionado))
                ({{ $cidadeSelecionada }}, {{ $estadoSelecionado }})
            @elseif(!empty($estadoSelecionado))
                ({{ $estadoSelecionado }})
            @elseif(!empty($cidadeSelecionada))
                ({{ $cidadeSelecionada }})
            @endif
            </div>
        @endif
    @endif
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
    </form>
    <div id="spinner-busca" class="text-center my-4" style="display:none;">
        <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Buscando...</span>
        </div>
        <p class="mt-2">Buscando resultados, aguarde...</p>
    </div>
</div>
@if($paginacao['total'] > 1)
    <div class="row">
        <div class="col-12 text-center">
            <div class="mt-1 mb-4" style="font-size: 1em;">
                Mostrando registros de {{ $paginacao['ordinalPrimeiro'] }} a {{ $paginacao['ordinalUltimo'] }}.
            </div>
        </div>
    </div>
@endif
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    /* Quando o form estiver em processamento, bloqueia todos os cliques */
    #filtro-form.processing {
        pointer-events: none;
    }

    /* Mostra cursor de espera em toda a página */
    body.processing {
        cursor: wait;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const filtroForm   = document.querySelector('#filtro-form');
  const button       = filtroForm.querySelector('#botao-filtro');
  const campoEstados = filtroForm.querySelector('#estados');
  const campoCidades = filtroForm.querySelector('#cidades');
  let isSubmitting   = false;

  function mostrarSpinner() {
    document.getElementById('spinner-busca').style.display = 'block';
    button.disabled = true;
  }

  function submitComReCaptcha() {
    if (isSubmitting) return;      // já está enviando, ignora
    isSubmitting = true;

    mostrarSpinner();
    // congela toda a área do form sem "desabilitar" os inputs
    filtroForm.classList.add('processing');
    // muda o cursor para “aguarde”
    document.body.classList.add('processing');

    grecaptcha.ready(function() {
      grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY_RESULTADO') }}', { action: 'submit' })
        .then(function(token) {
          document.getElementById('g-recaptcha-response').value = token;
          filtroForm.submit();
        });
    });
  }

  // clique no botão “filtrar”
  if (button) {
    button.addEventListener('click', (ev) => {
      ev.preventDefault();
      let valido = true;
      Array.from(filtroForm.elements).forEach((el) => {
        if (el.hasAttribute('required')) {
          const msgs = {
            'nome':   'O campo Nome deve conter pelo menos 3 caracteres.',
            'estado': 'Selecione uma UF para filtrar a busca.'
          };
          if (
            (el.name === 'nome' && el.value.trim().length < 3) ||
            (el.name === 'estado' && !el.value)
          ) {
            notificar(msgs[el.name], 'erro');
            valido = false;
          }
        }
      });
      if (valido) submitComReCaptcha();
    });
  }

  // mudança de estado
  if (campoEstados) {
    campoEstados.addEventListener('change', (ev) => {
      ev.preventDefault();
      // --- AQUI: limpa o campo cidade ---
      if (campoCidades) {
        // (opcional) para limpar toda a lista e deixar só a opção padrão:
        campoCidades.innerHTML = '<option value="">Cidade</option>';
        // 2) remove o name, para que NÃO seja enviado no form
        campoCidades.removeAttribute('name');
      }
      submitComReCaptcha();
    });
  }

  // mudança de cidade
  if (campoCidades) {
    campoCidades.addEventListener('change', (ev) => {
      ev.preventDefault();
      submitComReCaptcha();
    });
  }
});
</script>
