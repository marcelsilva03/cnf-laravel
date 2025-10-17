<!doctype html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link href="<?php echo e(asset('vendor/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet" />
    <title><?php echo $__env->yieldContent('title'); ?></title>
</head>
<body>
    <div class="d-flex flex-column justify-content-center align-items-center">
        <?php echo $__env->make('partials.logo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <h1><?php echo $__env->yieldContent('title'); ?></h1>
        <p><?php echo $__env->yieldContent('subject'); ?></p>
    </div>
    <div>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/layouts/email.blade.php ENDPATH**/ ?>