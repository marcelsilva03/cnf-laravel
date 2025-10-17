<?php $__env->startSection('box-content'); ?>
    <section id="duvidas" class="appointment section-bg">
        <div class="container" data-aos="fade-up">
            <?php echo $__env->make('partials.buscaapiIntroducao', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('forms.buscaapiSolicitarOrcamento', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.bgSectionPage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cnfbr/laravel_teste/resources/views/buscaapi.blade.php ENDPATH**/ ?>