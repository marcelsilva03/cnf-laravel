

<?php $__env->startSection('box-content'); ?>
<div class="container mb-5" data-aos="fade-up">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

      
      <div class="d-flex justify-content-center align-items-center bg-success text-white rounded p-3 mb-4" style="border-radius: 0.65rem !important;">
        <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
        <h4 class="mb-0">Pagamento Aprovado!</h4>
      </div>

      
      <ul class="list-unstyled mb-4">
        <li class="mb-2"><strong>Produto:</strong> <?php echo e($produto); ?> nº <?php echo e($sol_id); ?></li>
        <li class="mb-2"><strong>Código da Solicitação:</strong> <?php echo e($codigo); ?></li>
        <?php if(!empty($chargeId)): ?>
        <li class="mb-2"><strong>ID da Cobrança:</strong> <?php echo e($chargeId); ?></li>
        <?php endif; ?>
        <li class="mb-2"><strong>Status:</strong> <span class="text-success"><?php echo e($status); ?></span></li>
      </ul>

    </div>
  </div>
</div>
    <?php echo $__env->make('partials.agradecimentoPagamentoPesquisa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bgSectionPage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cnfbr/laravel_teste/resources/views/pagamento/sucesso.blade.php ENDPATH**/ ?>