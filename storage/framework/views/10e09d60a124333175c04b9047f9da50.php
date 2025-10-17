<?php $__env->startSection('title', 'CNF - Cadastro Nacional de Falecidos'); ?>

<?php $__env->startSection('content'); ?>
    <section id="resultados" class="resultados section-bg topo">
        <div class="container fundo" data-aos="fade-up">
            <?php echo $__env->yieldContent('box-content'); ?>
        </div>
        <div class="row justify-content-center topo"></div>
    </section>
    <?php echo $__env->yieldContent('after-box'); ?>
    <?php echo $__env->make('partials.brands', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modal-title'); ?>
    <?php echo $__env->yieldContent('titulo-do-modal'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modal-content'); ?>
    <?php echo $__env->yieldContent('conteudo-do-modal'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modal-footer'); ?>
    <?php echo $__env->yieldContent('rodape-do-modal'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('template-js'); ?>
    <?php echo $__env->yieldContent('custom-js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cnfbr/laravel_teste/resources/views/layouts/bgSectionPage.blade.php ENDPATH**/ ?>