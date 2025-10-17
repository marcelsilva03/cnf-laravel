<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'CNF')</title>

    <!-- Favicons -->
    <link href="{{ asset('images/favicon.png') }}" rel="icon">

    @if(Route::currentRouteName() == 'home' || Route::currentRouteName() == 'home-homenagens')
        <!-- Google ReCaptcha -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    @if(Route::currentRouteName() == 'pagamento-pesquisa')
        <!-- CDN Pagamentos EFI Pay -->
        {{--<script src="https://cdn.jsdelivr.net/gh/efipay/js-payment-token-efi/dist/payment-token-efi.min.js"></script>--}}
        <script src="{{ asset('js/efijs.sdk.ajustada.js') }}"></script>
    @endif

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Your Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/modal.css') }}" rel="stylesheet">
    <link href="{{ asset('css/notificacao.css') }}" rel="stylesheet">

    <!-- Custom JS Scripts definitions -->
    <script src="{{ asset('js/sistemaNotificacao.js') }}"></script>
    {{--<script src="{{ asset('js/historicoNavegadorCustomizado.js') }}"></script>--}}
</head>
<body>

@include('partials.header')

@include('partials.caixaDeNotificacao')
<main id="main">
    @yield('content')
</main>
<div id="modal-overlay" class="justify-content-center align-items-center d-none">
    <div id="modal-container" class="d-flex flex-column bg-white rounded p-5" role="dialog">
        <div class="d-flex flex-row justify-content-between align-items-center">
            <h5 class="text-center flex-grow-1">@yield('modal-title')</h5>
            <span id="modal-dismiss" class="mb-3 text-danger"><i class="fas fa-xmark fa-2x"></i></span>
        </div>
        <hr/>
        <div class="py-3">@yield('modal-content')</div>
        <hr />
        <div class="d-flex justify-content-end">@yield('modal-footer')</div>
    </div>
</div>

@include('partials.footer')
@if(session('notificacao'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mensagem = {!! json_encode(session('notificacao')['mensagem']) !!};
        const tipo = {!! json_encode(session('notificacao')['tipo']) !!};
        notificar(mensagem, tipo);
    });
</script>
@endif

<div id="cobertura-carregamento-tela"
     class="d-none d-flex justify-content-center align-items-center flex-column"
     style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.4); z-index:2000;">
    <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
        <span class="visually-hidden">Carregando...</span>
    </div>
    <p class="mt-2 text-white">Carregando, aguarde...</p>
</div>

<!-- Vendor JS Files -->
<script src="{{ asset('vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('vendor/aos/aos.js') }}"></script>
<script src="{{ asset('vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>

<!-- Custom JS Files -->
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/slide.js') }}"></script>
<script src="{{ asset('js/modal.js') }}"></script>
<script src="{{ asset('js/cidadesPorUF.js') }}"></script>
<script src="{{ asset('js/removeOpcaoNeutra.js') }}"></script>
<script src="{{ asset('js/mascarasFormulario.js') }}"></script>
<script src="{{ asset('js/mascarasCartao.js') }}"></script>
<script src="{{ asset('js/contagemCaracteresTextArea.js') }}"></script>
<!-- <script src="{{ asset('js/etapasFormularioCartao.js') }}"></script> -->
<script src="{{ asset('js/validadorCamposFormulario.js') }}"></script>
<script src="{{ asset('js/areaDeTransferencia.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Template specific Files -->
@yield('template-js')

</body>
</html>
