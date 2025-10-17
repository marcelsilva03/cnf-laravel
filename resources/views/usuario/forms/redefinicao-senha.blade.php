<!-- Search Form Section -->
<section class="pt-5 d-flex align-items-center justify-content-center" style="min-height: 90vh">
    <div class="m-auto pt-5" style="max-width: 400px">
        <div class="mb-5 text-center">
            <h3 class="pt-5">REDEFINIÇÃO DE SENHA</h3>
            <p class="pb-3">Insira sua nova senha para redefinir o acesso à conta.</p>
        </div>
        <div class="tab-content espaco_tab">
            <form class="row g-3" action="{{ route('usuario.recebe-definicao-senha') }}" method="POST"
                  id="redefinicaoForm">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-floating mb-1">
                    <input type="password" class="form-control" id="password" placeholder="Nova senha" name="password"
                           required>
                    <label for="password">Digite a nova senha</label>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-cnf btn-large">REDEFINIR SENHA</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- End Search Form Section -->
