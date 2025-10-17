#form-wrapper {
    /* sem alturas fixas, sem padding extra */
    position: static;
    padding: 0;
    margin: 0;
}

<!-- Search Form Section -->
<div id="form-wrapper">
    <input type="hidden" name="form_type" value="pesquisa">
    <div class="container col-10 col-md-6 col-lg-4" id="form-container">
        <div class="row text-center">
            <h3>PESQUISA DE ÓBITO</h3>
        </div>
        <div class="tab-content espaco_tab">
            <form class="row g-3" action="<?php echo e(route('resultados-recaptcha')); ?>" method="POST" id="pesquisaForm">
                <input type="hidden" name="recaptcha_version" value="BUSCA">
                <?php echo csrf_field(); ?>
                <div class="mb-1">
                    <label for="nome" class="d-none">Nome</label>
                    <input type="text" class="form-control" id="nome" placeholder="Insira o nome" name="nome">
                </div>
                <div class="form-check checkPesquisa">
                    <input type="hidden" name="nome-exato" value="0">
                    <input type="checkbox" class="form-check-input" id="exatamente" name="nome-exato" value="1" <?php echo e(old('nome-exato', $exata ?? false) ? 'checked' : ''); ?>>
                    <label for="privacidade" class="form-check-label exatamenteTxt">Buscar exatamente como escrito</label>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <div id="recaptcha" class="g-recaptcha" data-sitekey="<?php echo e(env('RECAPTCHA_SITE_KEY_BUSCA')); ?>"></div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-cnf">Pesquisar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- End Search Form Section --><?php /**PATH /home/cnfbr/laravel_teste/resources/views/partials/deathSearch.blade.php ENDPATH**/ ?>