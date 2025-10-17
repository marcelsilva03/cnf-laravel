<?php if(!empty($estado)): ?>
<?php
    $paginaAtual = $paginacao['paginaAtual'];
    $ultimaPagina = $paginacao['paginas'];

    // Calcula os limites
    $inicio = max(1, $paginaAtual - 3);
    $fim = min($ultimaPagina, $paginaAtual + 3);

    // Pega o token da view (se não tiver, pode dar erro)
    $tokenQuery = $token ? '&token=' . $token : '';
?>
<nav aria-label="Page navigation">
  <ul class="pagination d-flex justify-content-center align-items-center">
    <li class="page-item <?php echo e(empty($paginacao['anterior']) ? 'disabled' : ''); ?>">
      <a class="page-link" href="<?php echo e(empty($paginacao['anterior']) ? '' : $paginacao['anterior'] . $tokenQuery); ?>" aria-label="Anterior">
        <i id="previous-page" class="bi bi-arrow-left-square-fill" aria-hidden="true"></i>
      </a>
    </li>
    <?php for($pagina = $inicio; $pagina <= $fim; $pagina++): ?>
        <li class="page-item <?php echo e($pagina == $paginaAtual ? 'active' : ''); ?>">
            <a class="page-link" href="<?php echo e($resultados->url($pagina) . $tokenQuery); ?>">
                <?php echo e($pagina); ?>

            </a>
        </li>
    <?php endfor; ?>
    <li class="page-item <?php echo e(empty($paginacao['proxima']) ? 'disabled' : ''); ?>">
      <a class="page-link" href="<?php echo e(empty($paginacao['proxima']) ? '' : $paginacao['proxima'] . $tokenQuery); ?>" aria-label="Próxima">
        <i id="next-page" class="bi bi-arrow-right-square-fill" aria-hidden="true"></i>
      </a>
    </li>
  </ul>
</nav>
<?php endif; ?><?php /**PATH /home/cnfbr/laravel_teste/resources/views/partials/pagination.blade.php ENDPATH**/ ?>