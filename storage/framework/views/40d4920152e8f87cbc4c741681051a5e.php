<form action="<?php echo e(route('buscaapi.recebe-solicite-orcamento')); ?>" method="POST" role="form" class="p-email-form">
    <?php echo csrf_field(); ?>
    <div class="container align-items-center" id="form-container">
        <div class="row  justify-content-center">
            <div class="col-md-8 form-group mb-3">
                <label style="visibility: hidden; font-size: 0;">Nome</label>
                <input type="text" name="api_nome" class="form-control" id="api_nome" placeholder="Seu Nome:" required>
            </div>
        </div>
        <div class="row  justify-content-center">
            <div class="col-md-4 form-group mb-3 mt-md-0">
                <label style="visibility: hidden; font-size: 0;">Email</label>
                <input type="text" data-type="email" class="form-control" name="api_email" id="api_email" placeholder="Seu E-mail" required>
            </div>
            <div class="col-md-4 form-group mb-3">
                <label style="visibility: hidden; font-size: 0;">Telefone</label>
                <input type="tel" data-type="tel" name="api_telefone" class="form-control" id="api_telefone" placeholder="Seu Telefone:" required>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 form-group mb-3">
                <label style="visibility: hidden; font-size: 0;">Mensagem</label>
                <textarea class="form-control" name="api_message" id="api_mensagem" rows="5" placeholder="Mensagem:" required></textarea>
            </div>
        </div>
        <div class="text-center">
            <button type="submit">Enviar</button>
        </div>
    </div>
</form>
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/forms/buscaapiSolicitarOrcamento.blade.php ENDPATH**/ ?>