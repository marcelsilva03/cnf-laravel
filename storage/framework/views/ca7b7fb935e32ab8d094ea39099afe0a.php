<?php $__env->startSection('box-content'); ?>
    <?php echo $__env->make('partials.avisoPesquisaEmCartorio', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.buscaEmCartorio', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.bgSectionPage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cnfbr/laravel_teste/resources/views/busca-avancada.blade.php ENDPATH**/ ?>