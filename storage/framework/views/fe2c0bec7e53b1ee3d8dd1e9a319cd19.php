<?php $__env->startSection('title', 'CNF - Cadastro Nacional de Falecidos'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.comunicarobitocard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.brands', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cnfbr/laravel_teste/resources/views/comunicarobito.blade.php ENDPATH**/ ?>