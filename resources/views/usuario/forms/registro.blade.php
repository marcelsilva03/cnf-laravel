<!-- Search Form Section -->
<section class="pt-5 d-flex align-items-center justify-content-center" style="min-height: 90vh">
    <div class="m-auto pt-5" style="max-width: 400px">
        <div class="mb-5 text-center">
            <h3 class="pt-5">REGISTRO DE USUÁRIO</h3>
            <p class="pb-3">Preencha os campos abaixo para realizar o cadastro e fazer solicitações.</p>
        </div>
        <div class="tab-content espaco_tab">
            <form class="row g-3" action="{{ route('usuario.recebe-registro') }}" method="POST" id="pesquisaForm">
                @csrf
                <div class="form-floating mb-1">
                    <input type="text" class="form-control" id="nomeP" placeholder="Insira o nome completo"
                           name="nome_completo" required>
                    <label for="nomeP">Digite o nome completo</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="email" class="form-control" id="emailP" placeholder="Insira o email" name="email"
                           required>
                    <label for="emailP">Digite o email</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="text" class="form-control" id="telefoneP" placeholder="Insira o telefone"
                           name="telefone" required>
                    <label for="telefoneP">Digite o telefone (WhatsApp)</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="password" class="form-control" id="senhaP" placeholder="Insira a senha" name="senha"
                           required>
                    <label for="senhaP">Digite a senha</label>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-cnf btn-large">REGISTRAR</button>
                </div>
            </form>
            <div class="d-flex justify-content-center mt-5">
                <a href="{{ route('login') }}" class="text-danger">Já tenho conta</a>
            </div>
        </div>
    </div>
</section>
<!-- End Search Form Section -->
