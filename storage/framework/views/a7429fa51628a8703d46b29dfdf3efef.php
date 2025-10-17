<?php $__env->startSection('box-content'); ?>
    <div class="section-title">
      <h4>SOLICITAÇÃO DE PESQUISA</h4>
    </div>
    <?php echo $__env->make('partials.alertaPagamentoPesquisa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.formasDePagamento', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom-js'); ?>
    <?php echo $__env->make('scripts.formasDePagamento', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.bgSectionPage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cnfbr/laravel_teste/resources/views/pagamentoPesquisa.blade.php ENDPATH**/ ?>