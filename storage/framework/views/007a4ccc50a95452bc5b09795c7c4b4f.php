<form action="<?php echo e(route('contact.submit')); ?>" method="post" role="form" data-form="contact-form">
    <input type="hidden" name="form_type" value="contato">
    <?php echo csrf_field(); ?>
    <div class="container align-items-center" id="form-container">
        <div class="row justify-content-center">
            <div class="col-md-6 form-group mb-3">
                <label for="nome">Seu Nome:</label>
                <input type="text" name="nome" class="form-control" id="nome" placeholder="Seu Nome:" value="<?php echo e(old('nome')); ?>" required>
            </div>
            <div class="col-md-6 form-group mb-3">
                <?php
                $assuntos = [
                    'API'                             => 'API - Orçamento',
                    'Acompanhamento de Pesquisa'     => 'Acompanhamento de Pesquisa já Pagas',
                    'Devolução'                       => 'Devolução de Pagamento de Pesquisa',
                    'Dificuldade Preenchimento'       => 'Dificuldade ao Preencher Solicitação',
                    'Nome Não Encontrado'             => 'Falecido Não Encontrado na Busca',
                    'Dúvidas Diversas'                => 'Dúvidas Diversas',
                ];
                ?>
                <label for="assunto">Assunto:</label>
                <select name="assunto" id="assunto" class="form-select" required>
                    <option value="">Selecione um assunto</option>
                    <?php $__currentLoopData = $assuntos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 form-group mb-3">
                <label for="email">Seu E-mail:</label>
                <input data-type="email" type="email" class="form-control" name="email" id="email" placeholder="Seu E-mail" value="<?php echo e(old('email')); ?>" required>
            </div>
            <div class="col-md-6 form-group mb-3">
                <label for="telefone">Seu Telefone:</label>
                <input data-type="tel" type="text" class="form-control" name="telefone" id="telefone" placeholder="Seu Telefone:" value="<?php echo e(old('telefone')); ?>" required>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 form-group mb-3">
                <label for="message">Mensagem:</label>
                <textarea class="form-control" name="mensagem" id="message" rows="5" maxlength="500" placeholder="Mensagem:" required><?php echo e(old('mensagem')); ?></textarea>
                <?php echo $__env->make('partials.contadorTextArea', [ 'dataCounterFor' => 'mensagem' ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <div id="recaptcha" class="g-recaptcha" data-sitekey="<?php echo e(env('RECAPTCHA_SITE_KEY_CONTATO')); ?>"></div>
        </div>
        <div class="text-center">
            <button type="submit" style="margin-top: 12px;">Enviar</button>
        </div>
    </div>
</form>
<?php if(session('scroll_to_form')): ?>
    <script>
        window.onload = function() {
            setTimeout(function(){
                const formElement = document.querySelector('[data-form="contact-form"]'); // Utilizando o atributo `data-form`
                if (formElement) {
                    formElement.scrollIntoView({ behavior: 'smooth' });
                }
            }, 500);
        };
    </script>
<?php endif; ?>

<?php /**PATH /home/cnfbr/laravel_teste/resources/views/forms/contato.blade.php ENDPATH**/ ?>