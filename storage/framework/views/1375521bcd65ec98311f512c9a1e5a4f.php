<header id ="header" class="fixed-top bg-light shadow-sm">
    <div class="container d-flex justify-content-between align-items-center py-2">
        
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
            <img src="<?php echo e(asset('images/cnf_lg.png')); ?>" alt="Logo" style="max-height:80px;">
        </a>
        
        <nav class="navbar navbar-light">
            <button
                class="navbar-toggler d-flex align-items-center px-3 py-2"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar"
                style="border: 1px solid #626262; border-radius: 6px;"
                >
                <span class="navbar-toggler-icon me-2"></span>
                Menu
            </button>
        </nav>
    </div>
        
        <div
            class="offcanvas offcanvas-end"
            data-bs-scroll="true"
            data-bs-backdrop="true"
            tabindex="-1"
            id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel"
        >
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                <button
                    type="button"
                    class="btn-close text-reset"
                    data-bs-dismiss="offcanvas"
                    aria-label="Fechar"
                >
                </button>
            </div>
        <div class="offcanvas-body" id="navbar">
            <style>
                /* estilos base para os links do offcanvas */
                #offcanvasNavbar .nav-link {
                    font-family: "Roboto", sans-serif;
                    font-size: 16px;
                    color: #626262;
                    text-transform: uppercase;
                    font-weight: 500;
                    transition: color 0.3s;
                }
                /* hover e active */
                #offcanvasNavbar .nav-link:hover,
                #offcanvasNavbar .nav-link.active,
                #offcanvasNavbar .nav-link.active:focus,
                #offcanvasNavbar .nav-item:hover > .nav-link {
                    color: #00762c;
                }
            </style>
            <ul class="nav flex-column mb-4">
                <li class="nav-item">
                    <a
                    href="<?php echo e(url('/')); ?>#hero"
                    class="nav-link scrollto <?php echo e(request()->getRequestUri() == '/' ? 'active' : ''); ?>"
                    data-bs-dismiss="offcanvas"
                    >
                    Home
                    </a>
                </li>
            </ul>
            <!-- Bloco Serviços -->
            <h6 class="text-uppercase small text-muted mb-2" style="color: #626262 !important;">Nossos Serviços</h6>
            <ul class="nav flex-column mb-4">
                <li class="nav-item">
                    <a
                    href="<?php echo e(url('/')); ?>#hero"
                    class="nav-link scrollto <?php echo e(request()->getRequestUri() == '/' ? 'active' : ''); ?>"
                    data-bs-dismiss="offcanvas"
                    >
                    Pesquisa de Óbito
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="<?php echo e(url('/homenagens')); ?>#top"
                    class="nav-link <?php echo e(Request::is('homenagens*') ? 'active' : ''); ?>"
                    data-bs-dismiss="offcanvas"
                    >
                    Homenagens
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="<?php echo e(url('comunicarobito')); ?>"
                    class="nav-link <?php echo e(Request::path() == 'comunicarobito' ? 'active' : ''); ?>"
                    data-bs-dismiss="offcanvas"
                    >
                    Comunicar Óbito
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="<?php echo e(url('busca-avancada')); ?>"
                    class="nav-link <?php echo e(Request::is('busca-avancada*') ? 'active' : ''); ?>"
                    data-bs-dismiss="offcanvas"
                    >
                    Busca Avançada
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="<?php echo e(url('comunicarobito')); ?>"
                    class="nav-link <?php echo e(Request::is('comunicarobito') ? 'active' : ''); ?>"
                    data-bs-dismiss="offcanvas"
                    >
                    Pedido de Certidão
                    </a>
                </li>
                
            </ul>
            <!-- Bloco Institucional -->
            <h6 class="text-uppercase small text-muted mb-2" style="color: #626262 !important;">Mais</h6>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a
                    href="<?php echo e(url('/')); ?>#about"
                    class="nav-link scrollto <?php echo e(request()->getRequestUri() == '/#about' ? 'active' : ''); ?>"
                    data-bs-dismiss="offcanvas"
                    >
                    Sobre Nós
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="<?php echo e(url('/')); ?>#contact"
                    class="nav-link scrollto <?php echo e(request()->getRequestUri() == '/#contact' ? 'active' : ''); ?>"
                    data-bs-dismiss="offcanvas"
                    >
                    Fale Conosco
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="<?php echo e(url('politica-de-privacidade')); ?>"
                    class="nav-link <?php echo e(Request::is('politica-de-privacidade') ? 'active' : ''); ?>"
                    data-bs-dismiss="offcanvas"
                    >
                    Política de Privacidade
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <?php echo $__env->make('usuario.components.item-navegacao', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </li>
            </ul>
        </div>
    </div>
</header><?php /**PATH /home/cnfbr/laravel_teste/resources/views/partials/header.blade.php ENDPATH**/ ?>