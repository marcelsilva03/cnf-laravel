<?php
    if($paginacao['total'] > 0) {
        $corBox = '#eaf8f2';      // Verde claro
        $corTexto = '#245846';    // Verde escuro (texto)
        $corIcone = '#25a244';    // Verde forte (ícone)
    } else {
        $corBox = '#ffe8e3';      // Laranja/avermelhado claro
        $corTexto = '#ed3c0d';    // Laranja forte (texto)
        $corIcone = '#ed3c0d';    // Laranja forte (ícone)
    }
?>

<div class="result-title card mb-4 px-4 py-3 d-flex flex-row align-items-center gap-3"
     style="border-radius: 1rem; background: <?php echo e($corBox); ?>; color: <?php echo e($corTexto); ?>;">
    <i class="bi <?php echo e($paginacao['total'] > 0 ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'); ?> fs-2" style="color: <?php echo e($corIcone); ?>;"></i>
    <div class="flex-grow-1">
        <h4 class="fw-bold mb-1" style="color: <?php echo e($corTexto); ?>;">
            <?php if(isset($nome)): ?>
                <?php echo e($totalResultados == 1 ? 'Resultado' : 'Resultados'); ?> da pesquisa para "<?php echo e($nome); ?>"
            <?php else: ?>
                Nenhum registro encontrado.
            <?php endif; ?>
        </h4>
        <?php if(isset($nome)): ?>
            <div class="text-muted mb-2 text-break" style="font-size: 1em; word-break: break-all; max-width: 100%;">
                <?php if($exata): ?>
                    (pesquisado nome exato: "<?php echo e($nomeSanitizado); ?>")
                <?php else: ?>
                    (pesquisado nomes contendo: "<?php echo e(str_replace(' ', '+', $nomeSanitizado). '+'); ?>")
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div>
            <strong>
                <?php if($totalResultados > 0): ?>
                    <?php echo e($totalResultados == 1 ? '1 registro encontrado.' : number_format($totalResultados, 0, ',', '.') . ' registros encontrados.'); ?>

                <?php else: ?>
                    Nenhum registro encontrado.
                <?php endif; ?>
            </strong>
        </div>
    </div>
</div><?php /**PATH /home/cnfbr/laravel_teste/resources/views/partials/resultsTitle.blade.php ENDPATH**/ ?>