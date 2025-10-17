<ul class="nav flex-column">
{{-- Item “ACESSAR” adaptado --}}
@if(auth()->user())
  <li class="nav-item dropdown mt-3">
    <a 
      class="nav-link dropdown-toggle text-danger border border-danger d-flex justify-content-center align-items-center py-2" 
      href="#" 
      id="offcanvasUserMenu" 
      role="button" 
      data-bs-toggle="dropdown" 
      aria-expanded="false"
    >
    ACESSAR
    </a>
    <ul class="dropdown-menu" aria-labelledby="offcanvasUserMenu">
      <li>
        <a class="dropdown-item" href="/admin">Entrar</a>
      </li>
      <li>
        <a class="dropdown-item" href="{{ route('usuario.logout') }}">Logout</a>
      </li>
      </ul>
    </li>
@else
  <li class="nav-item dropdown mt-3">
    <a 
      class="nav-link dropdown-toggle text-danger border border-danger d-flex justify-content-center align-items-center py-2" 
      href="#" 
      id="offcanvasUserMenu" 
      role="button" 
      data-bs-toggle="dropdown" 
      aria-expanded="false"
    >
    ACESSAR
    </a>
    <ul class="dropdown-menu" aria-labelledby="offcanvasUserMenu">
      <li>
        <a class="dropdown-item" href="/admin/login">Login</a>
      </li>
      <li>
        <a class="dropdown-item" href="{{ route('usuario.registro') }}">Registrar</a>
      </li>
      <li>
        <a class="dropdown-item" href="{{ route('usuario.esqueci-senha') }}">Recuperar Senha</a>
      </li>
    </ul>
  </li>
  @endif
</ul>