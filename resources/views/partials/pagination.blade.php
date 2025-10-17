@if(!empty($estado))
@php
    $paginaAtual = $paginacao['paginaAtual'];
    $ultimaPagina = $paginacao['paginas'];

    // Calcula os limites
    $inicio = max(1, $paginaAtual - 3);
    $fim = min($ultimaPagina, $paginaAtual + 3);

    // Pega o token da view (se não tiver, pode dar erro)
    $tokenQuery = $token ? '&token=' . $token : '';
@endphp
<nav aria-label="Page navigation">
  <ul class="pagination d-flex justify-content-center align-items-center">
    <li class="page-item {{ empty($paginacao['anterior']) ? 'disabled' : '' }}">
      <a class="page-link" href="{{ empty($paginacao['anterior']) ? '' : $paginacao['anterior'] . $tokenQuery }}" aria-label="Anterior">
        <i id="previous-page" class="bi bi-arrow-left-square-fill" aria-hidden="true"></i>
      </a>
    </li>
    @for ($pagina = $inicio; $pagina <= $fim; $pagina++)
        <li class="page-item {{ $pagina == $paginaAtual ? 'active' : '' }}">
            <a class="page-link" href="{{ $resultados->url($pagina) . $tokenQuery }}">
                {{ $pagina }}
            </a>
        </li>
    @endfor
    <li class="page-item {{ empty($paginacao['proxima']) ? 'disabled' : '' }}">
      <a class="page-link" href="{{ empty($paginacao['proxima']) ? '' : $paginacao['proxima'] . $tokenQuery }}" aria-label="Próxima">
        <i id="next-page" class="bi bi-arrow-right-square-fill" aria-hidden="true"></i>
      </a>
    </li>
  </ul>
</nav>
@endif