<?php if (isset($url)): ?>
    <a href="<?php echo e($url); ?>">
        <img src="<?php echo e(asset('images/cnf_lg.png')); ?>" alt="CNF" width="<?php echo e($width ?? ''); ?>" />
    </a>
<?php else: ?>
    <img src="<?php echo e(asset('images/cnf_lg.png')); ?>" alt="CNF" width="<?php echo e($width ?? ''); ?>" />
<?php endif; ?>
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/partials/logo.blade.php ENDPATH**/ ?>