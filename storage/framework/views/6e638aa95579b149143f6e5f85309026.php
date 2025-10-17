<?php $__env->startSection('box-content'); ?>
    <?php echo $__env->make('partials.resultsTitle', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partials.resultsFilterCard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php $__currentLoopData = $resultados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resultado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('partials.deceasedCard', ['fluxo' => 'principal'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('after-box'); ?>
    <?php echo $__env->make('partials.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php if(!empty($paginacao['paginas'])): ?>
        <?php echo $__env->make('partials.pagination', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php echo $__env->make('partials.ctaForAdvancedSearch', [
        'message' => 'Solicite uma Pesquisa de Registro de Óbito e Indicação de Cartório.',
        'alertText' => 'Não achou o que procurava?'
      ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('template-js'); ?>
    <?php echo $__env->make('scripts.dadosParaModal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.bgSectionPage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cnfbr/laravel_teste/resources/views/resultados.blade.php ENDPATH**/ ?>