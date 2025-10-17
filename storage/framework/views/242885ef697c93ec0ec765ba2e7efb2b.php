<form id="form-cartao-credito" data-etapa-form>
    <?php echo csrf_field(); ?>
    <div data-etapa-conteudo="1">
        <h4>Dados do cartão</h4>        
        <div class="mb-3">
            <label for="numero-cartao" class="form-label">Número do cartão</label>
            <input required type="text" data-type="card-number" class="form-control" id="numero-cartao"
                inputmode="numeric" autocomplete="cc-number"
                pattern="^(\d{4} \d{4} \d{4} \d{4}|\d{4} \d{6} \d{5})$" placeholder="0000 0000 0000 0000 ou 0000 000000 00000" maxlength="19"
                name="numerocartao"
                value="<?php echo e(old('numerocartao')); ?>">
        </div>
        <div class="row">
            <div class="col-7 mb-3">
                <label for="validade-cartao" class="form-label">Data de validade</label>
                <input required type="text" data-type="card-expiration-date" class="form-control" id="validade-cartao"
                       inputmode="numeric" placeholder="MM/YY" name="validadecartao" pattern="\d{2}/\d{2}" maxlength="5"
                       value="<?php echo e(old('validadecartao')); ?>">
            </div>
            <div class="col-5 mb-3">
                <label for="cvv-cartao" class="form-label">CVV</label>
                <input required type="text" data-type="card-cvv" class="form-control" id="cvv-cartao" inputmode="numeric" placeholder="000 ou 0000"
                       name="cvv" maxlength="4" pattern="\d{3,4}" value="<?php echo e(old('cvv')); ?>">
            </div>
        </div>
        <div class="mb-3">
            <label for="nome-cartao" class="form-label">Nome do titular</label>
            <input required name="nometitular" value="<?php echo e(old('nometitular')); ?>" type="text"
                   class="form-control"
                   pattern="^[A-Za-zÀ-ÖØ-öø-ÿ]+(?:\s[A-Za-zÀ-ÖØ-öø-ÿ]+)+$" id="nome-cartao"
                   placeholder="Nome titular do cartão">
        </div>
        <div class="row">
            <div class="col-12 col-lg-5 mb-3">                
                <label for="cpfcnpj" class="form-label">CPF ou CNPJ</label>                
                <input type="text" data-type="cpfcnpj" id="cpfcnpj" name="cpfcnpj" inputmode="numeric" maxlength="18" class="form-control" placeholder="Digite CPF ou CNPJ" value="<?php echo e(old('cpfcnpj')); ?>">
            </div>
            <div class="col-12 col-lg-7 mb-3">
                <label for="parcelamento" class="form-label">Parcelamento</label>
                <select required disabled class="form-select" id="parcelamento" name="numeroparcelas"></select>
            </div>
        </div>            
    </div>
</form>
<button role="button" id="cartao-credito-botao-confirmacao" class="btn btn-success btn-large w-100">Confirmar pagamento</button>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo e(asset('js/validaCPF.js')); ?>"></script>
<script src="<?php echo e(asset('js/validaMail.js')); ?>"></script>
<script src="<?php echo e(asset('js/validaDataNascimento.js')); ?>"></script>
<script src="<?php echo e(asset('js/validaWhatsapp.js')); ?>"></script><?php /**PATH /home/cnfbr/laravel_teste/resources/views/forms/dadosCartao.blade.php ENDPATH**/ ?>