<div class="row resultado_contorno border-top border-dark py-3 align-items-start">
  
  <div class="col-md-3">
    <div class="titulo_resultado_mae">Falecido:</div>
    <div class="nome_falecido_resultado texto_lista text-uppercase">
      <?php echo e($resultado->pes_nome); ?>

    </div>
  </div>

  
  <div class="col-md-3">
    <div class="titulo_resultado_local">Local:</div>
    <div class="local_resultado texto_lista text-uppercase">
      <?php echo e($resultado->pes_cidade ?? '–'); ?> / <?php echo e($resultado->pes_uf ?? '–'); ?>

    </div>
  </div>

  
  <div class="col-md-2">
    <div class="titulo_resultado_mae">Mãe:</div>
    <?php
      $nomeDaMae = empty($resultado->pes_nome_mae)
        ? '–'
        : explode(' ', $resultado->pes_nome_mae)[0];
    ?>
    <div class="nome_mae_resultado texto_lista text-uppercase">
      <?php echo e($nomeDaMae); ?>

    </div>
  </div>

  
  <div class="col-md-2 text-md-end">
    <div class="titulo_resultado_data">Falecimento:</div>
    <div class="data_falecido_resultado texto_lista">
      <?php echo e(\Carbon\Carbon::parse($resultado->pes_data_falecimento)
           ->format('d/m/Y')); ?>

    </div>
  </div>

  
  <div class="col-md-2 d-flex flex-column justify-content-end gap-2">
    <a href="<?php echo e(route('formulario-pesquisa')); ?>?hash=<?php echo e($resultado->hash); ?>"
       class="btn btn-cnf btn-sm w-100">Ver Cartório</a>
    <a href="<?php echo e(route('registrador-de-nova-homenagem')); ?>?uuid=<?php echo e($resultado->hash); ?>"
       class="btn btn-warning btn-sm w-100">Homenagear</a>
    <button class="btn btn-danger btn-sm w-100"
            data-bs-toggle="modal"
            data-bs-target="#exampleModal"
            data-cnf-nome="<?php echo e($resultado->pes_nome); ?>"
            data-cnf-uuid="<?php echo e($resultado->hash); ?>"
            data-cnf-id="<?php echo e($resultado->pes_id); ?>"
            data-cnf-cidade="<?php echo e($resultado->pes_cidade ?? ''); ?>"
            data-cnf-uf="<?php echo e($resultado->pes_uf ?? ''); ?>"
            data-cnf-data="<?php echo e(\Carbon\Carbon::parse($resultado->pes_data_falecimento)->format('d/m/Y')); ?>">
      Comunicar Erro
    </button>
  </div>
</div>
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/partials/deceasedCard.blade.php ENDPATH**/ ?>