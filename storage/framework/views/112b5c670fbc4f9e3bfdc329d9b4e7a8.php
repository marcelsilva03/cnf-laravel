<!-- Search Form Section -->
<section class="pt-5 d-flex align-items-center justify-content-center" style="min-height: 90vh">
    <div class="m-auto pt-5" style="max-width: 400px">
        <div class="mb-5 text-center">
            <h3 class="pt-5">LOGIN DE USUÁRIO</h3>
            <p class="pb-3">Preencha os campos abaixo para realizar o acesso e fazer solicitações.</p>
        </div>
        <div class="tab-content espaco_tab">
            <form class="row g-3" action="<?php echo e(route('usuario.recebe-login')); ?>" method="POST" id="pesquisaForm">
                <?php echo csrf_field(); ?>
                <div class="form-floating mb-1">
                    <input type="email" class="form-control" id="emailP" placeholder="Insira o email" name="email"
                           required>
                    <label for="emailP">Digite o email</label>
                </div>
                <div class="form-floating mb-1">
                    <input type="password" class="form-control" id="senhaP" placeholder="Insira a senha" name="senha"
                           required>
                    <label for="senhaP">Digite a senha</label>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-cnf btn-large">ACESSAR</button>
                </div>
            </form>
            <div class="d-flex flex-column justify-content-center align-items-center mt-5">
                <a href="<?php echo e(route('usuario.esqueci-senha')); ?>" class="text-danger">Esqueci minha senha</a>
                <a href="<?php echo e(route('usuario.registro')); ?>" class="text-danger mt-4">Criar uma conta para solicitações</a>
            </div>
        </div>
    </div>
</section>
<!-- End Search Form Section -->
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/usuario/forms/login.blade.php ENDPATH**/ ?>