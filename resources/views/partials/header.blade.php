<header id ="header" class="fixed-top bg-light shadow-sm">
    <div class="container d-flex justify-content-between align-items-center py-2">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/cnf_lg.png') }}" alt="Logo" style="max-height:80px;">
        </a>
        {{-- Botão hamburger que abre o drawer --}}
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
        {{-- Drawer Offcanvas --}}
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
                    href="{{ url('/') }}#hero"
                    class="nav-link scrollto {{ request()->getRequestUri() == '/' ? 'active' : '' }}"
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
                    href="{{ url('/') }}#hero"
                    class="nav-link scrollto {{ request()->getRequestUri() == '/' ? 'active' : '' }}"
                    data-bs-dismiss="offcanvas"
                    >
                    Pesquisa de Óbito
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="{{ url('/homenagens') }}#top"
                    class="nav-link {{ Request::is('homenagens*') ? 'active' : '' }}"
                    data-bs-dismiss="offcanvas"
                    >
                    Homenagens
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="{{ url('comunicarobito') }}"
                    class="nav-link {{ Request::path() == 'comunicarobito' ? 'active' : '' }}"
                    data-bs-dismiss="offcanvas"
                    >
                    Comunicar Óbito
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="{{ url('busca-avancada') }}"
                    class="nav-link {{ Request::is('busca-avancada*') ? 'active' : '' }}"
                    data-bs-dismiss="offcanvas"
                    >
                    Busca Avançada
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="{{ url('comunicarobito') }}"
                    class="nav-link {{ Request::is('comunicarobito') ? 'active' : '' }}"
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
                    href="{{ url('/') }}#about"
                    class="nav-link scrollto {{ request()->getRequestUri() == '/#about' ? 'active' : '' }}"
                    data-bs-dismiss="offcanvas"
                    >
                    Sobre Nós
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="{{ url('/') }}#contact"
                    class="nav-link scrollto {{ request()->getRequestUri() == '/#contact' ? 'active' : '' }}"
                    data-bs-dismiss="offcanvas"
                    >
                    Fale Conosco
                    </a>
                </li>
                <li class="nav-item">
                    <a
                    href="{{ url('politica-de-privacidade') }}"
                    class="nav-link {{ Request::is('politica-de-privacidade') ? 'active' : '' }}"
                    data-bs-dismiss="offcanvas"
                    >
                    Política de Privacidade
                    </a>
                </li>
                <li class="nav-item mt-3">
                    @include('usuario.components.item-navegacao')
                </li>
            </ul>
        </div>
    </div>
</header>