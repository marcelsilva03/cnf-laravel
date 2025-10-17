<!-- Search Form Section -->
<section class="pt-5 d-flex align-items-center justify-content-center" style="min-height: 90vh">
    <div class="m-auto pt-5" style="max-width: 400px">
        <div class="mb-5 text-center">
            <h3 class="pt-5">RECUPERAÇÃO DE SENHA</h3>
            <p class="pb-3">Forneça o email vinculado a conta para envio de link de redefinição de senha.</p>
        </div>
        <div class="tab-content espaco_tab">
            <form class="row g-3" action="{{ route('usuario.recebe-esqueci-senha') }}" method="POST" id="pesquisaForm">
                @csrf
                <div class="form-floating mb-1">
                    <input type="email" class="form-control" id="emailP" placeholder="Insira o email" name="email"
                           required>
                    <label for="emailP">Digite o email</label>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-cnf btn-large">ENVIAR LINK</button>
                </div>
            </form>
            <div class="d-flex flex-column justify-content-center align-items-center mt-5">
                <a href="{{ route('usuario.registro') }}" class="text-danger mt-4">Criar uma conta para solicitações</a>
            </div>
        </div>
    </div>
</section>
<!-- End Search Form Section -->
