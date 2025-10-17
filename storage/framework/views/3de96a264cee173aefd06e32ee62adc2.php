<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?php echo $__env->yieldContent('title', 'CNF'); ?></title>

    <!-- Favicons -->
    <link href="<?php echo e(asset('images/favicon.png')); ?>" rel="icon">

    <?php if(Route::currentRouteName() == 'home' || Route::currentRouteName() == 'home-homenagens'): ?>
        <!-- Google ReCaptcha -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    <?php if(Route::currentRouteName() == 'pagamento-pesquisa'): ?>
        <!-- CDN Pagamentos EFI Pay -->
        
        <script src="<?php echo e(asset('js/efijs.sdk.ajustada.js')); ?>"></script>
    <?php endif; ?>

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <!-- Vendor CSS Files -->
    <link href="<?php echo e(asset('vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('vendor/animate.css/animate.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('vendor/aos/aos.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('vendor/boxicons/css/boxicons.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('vendor/glightbox/css/glightbox.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('vendor/swiper/swiper-bundle.min.css')); ?>" rel="stylesheet">

    <!-- Your Custom CSS -->
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/modal.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/notificacao.css')); ?>" rel="stylesheet">

    <!-- Custom JS Scripts definitions -->
    <script src="<?php echo e(asset('js/sistemaNotificacao.js')); ?>"></script>
    
</head>
<body>

<?php echo $__env->make('partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('partials.caixaDeNotificacao', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<main id="main">
    <?php echo $__env->yieldContent('content'); ?>
</main>
<div id="modal-overlay" class="justify-content-center align-items-center d-none">
    <div id="modal-container" class="d-flex flex-column bg-white rounded p-5" role="dialog">
        <div class="d-flex flex-row justify-content-between align-items-center">
            <h5 class="text-center flex-grow-1"><?php echo $__env->yieldContent('modal-title'); ?></h5>
            <span id="modal-dismiss" class="mb-3 text-danger"><i class="fas fa-xmark fa-2x"></i></span>
        </div>
        <hr/>
        <div class="py-3"><?php echo $__env->yieldContent('modal-content'); ?></div>
        <hr />
        <div class="d-flex justify-content-end"><?php echo $__env->yieldContent('modal-footer'); ?></div>
    </div>
</div>

<?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php if(session('notificacao')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mensagem = <?php echo json_encode(session('notificacao')['mensagem']); ?>;
        const tipo = <?php echo json_encode(session('notificacao')['tipo']); ?>;
        notificar(mensagem, tipo);
    });
</script>
<?php endif; ?>

<div id="cobertura-carregamento-tela"
     class="d-none d-flex justify-content-center align-items-center flex-column"
     style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.4); z-index:2000;">
    <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
        <span class="visually-hidden">Carregando...</span>
    </div>
    <p class="mt-2 text-white">Carregando, aguarde...</p>
</div>

<!-- Vendor JS Files -->
<script src="<?php echo e(asset('vendor/purecounter/purecounter_vanilla.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/aos/aos.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/glightbox/js/glightbox.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/swiper/swiper-bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/php-email-form/validate.js')); ?>"></script>

<!-- Custom JS Files -->
<script src="<?php echo e(asset('js/main.js')); ?>"></script>
<script src="<?php echo e(asset('js/slide.js')); ?>"></script>
<script src="<?php echo e(asset('js/modal.js')); ?>"></script>
<script src="<?php echo e(asset('js/cidadesPorUF.js')); ?>"></script>
<script src="<?php echo e(asset('js/removeOpcaoNeutra.js')); ?>"></script>
<script src="<?php echo e(asset('js/mascarasFormulario.js')); ?>"></script>
<script src="<?php echo e(asset('js/mascarasCartao.js')); ?>"></script>
<script src="<?php echo e(asset('js/contagemCaracteresTextArea.js')); ?>"></script>
<!-- <script src="<?php echo e(asset('js/etapasFormularioCartao.js')); ?>"></script> -->
<script src="<?php echo e(asset('js/validadorCamposFormulario.js')); ?>"></script>
<script src="<?php echo e(asset('js/areaDeTransferencia.js')); ?>"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Template specific Files -->
<?php echo $__env->yieldContent('template-js'); ?>

</body>
</html>
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/layouts/app.blade.php ENDPATH**/ ?>