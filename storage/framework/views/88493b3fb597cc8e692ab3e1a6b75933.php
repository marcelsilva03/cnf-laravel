<form id="form-busca-avancada" novalidate class="row g-3 justify-content-center" action="<?php echo e(route('pagamento-pesquisa-post')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="row mt-3"><h4>Dados do Solicitante</h4></div>
    <div class="row">
        
        <div class="col-12 col-md-6 col-xl-6 form-group">
            <label for="empresa">Empresa <small class="text-muted">(opcional)</small>:</label>
            <input
                type="text"
                name="empresa"
                id="empresa"
                class="form-control"
                maxLength="255"
                placeholder="Nome da empresa (opcional)"
                autocomplete="organization"
                value="<?php echo e(old('empresa', $usuario['empresa'] ?? '')); ?>"
            />
            <div class="text-danger"></div>
        </div>

        
        <div class="col-12 col-md-6 col-xl-6 form-group">
            <label for="nomeso">Seu nome:</label>
            <?php if(isset($usuario)): ?>
                <input type="text" name="nomesol" class="form-control" data-required id="nomeso" maxLength="255"
                       placeholder="Nome e Sobrenome" readonly value="<?php echo e($usuario['nome']); ?>" />
            <?php else: ?>
                <input type="text" name="nomesol" class="form-control" data-required id="nomeso" maxLength="255"
                       placeholder="Nome e Sobrenome" value="<?php echo e(old('nomesol')); ?>" />
            <?php endif; ?>
            <div class="text-danger"></div>
        </div>

        
        <div class="col-12 col-md-6 col-xl-3 form-group">
            <label for="telsol">Seu telefone:</label>
            <?php if(isset($usuario)): ?>
                <input type="tel" name="telsol" class="form-control" data-required id="telsol" data-type="tel"
                       pattern="\([0-9]{2}\)\s?[0-9]{4,5}-?[0-9]{4}"
                       placeholder="(00) 00000-0000" maxLength="15" autocomplete="tel"
                       <?php echo e(isset($usuario['tel']) ? 'readonly' : ''); ?>

                       value="<?php echo e(isset($usuario['tel']) ? $usuario['tel'] : ''); ?>" />
            <?php else: ?>
                <input type="tel" name="telsol" class="form-control" data-required id="telsol" data-type="tel"
                       pattern="\([0-9]{2}\)\s?[0-9]{4,5}-?[0-9]{4}"
                       placeholder="(00) 00000-0000" maxLength="15" autocomplete="tel"
                       value="<?php echo e(old('telsol')); ?>" />
            <?php endif; ?>
            <div class="text-danger"></div>
        </div>

        
        <div class="col-12 col-md-6 col-xl-3 form-group">
            <label for="emailsol">Seu e-mail:</label>
            <?php if(isset($usuario)): ?>
                <input type="email" name="emailsol" data-type="email" maxLength="255" class="form-control" data-required
                       id="emailsol" placeholder="E-mail" readonly value="<?php echo e($usuario['email']); ?>" autocomplete="email"/>
            <?php else: ?>
                <input type="email" name="emailsol" data-type="email" maxLength="255" class="form-control" data-required
                       id="emailsol" placeholder="E-mail" value="<?php echo e(old('emailsol')); ?>" autocomplete="email"/>
            <?php endif; ?>
            <div class="text-danger"></div>
            <div id="emailHelp" class="form-text"></div>
        </div>
    </div>
    <hr>
    <div class="row mt-3"><h4>Dados do Falecido</h4></div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="nomefal">Nome do Falecido:</label>
            <input type="text" name="nome_fal" class="form-control" data-required id="nomefal" placeholder="Nome do Falecido"
                   value="<?php echo e($falecido['nome'] ?? ''); ?>" maxLength="60" <?php if(!empty($falecido['nome'])): ?> readonly <?php endif; ?>>
                   <div class="text-danger"></div>
        </div>
        <div class="col-md-2 form-group">
            <label for="cpf">CPF:</label>
            <input data-type="cpf" type="tel" inputmode="numeric" pattern="[0-9]*" name="cpf" class="form-control" id="cpf" placeholder="000.000.000-00" maxlength="14" value="">
            <div class="text-danger"></div>
        </div>
        <div class="col-md-4 form-group">
            <label for="nascf">Data de Nascimento:</label>
            <input type="date" name="nascf" class="form-control" id="nascf" placeholder="Data de Nascimento"
                   value="">
            <div class="text-danger">
            </div>
        </div>
        <!-- <div class="col-md-2 form-group">
            <label for="rg">RG:</label>
            <input type="text" name="rg" class="form-control" id="reg" placeholder="RG" maxlength="15"
                   value="">
            <div id="emailHelp" class="form-text fieldLegend">Apenas números</div>
        </div>
        <div class="col-md-2 form-group">
            <label for="eleitor">Título de Eleitor:</label>
            <input data-type="eleitor" type="text" name="eleitor" class="form-control" id="eleitor"
                   placeholder="Título de Eleitor" maxlength="14" value="">
            <div id="emailHelp" class="form-text fieldLegend">Apenas números</div>
        </div> -->
    </div>
    <div class="row mt-3">
        <div class="col-md-6 form-group">
            <label for="nomepai">Nome do Pai:</label>
            <input type="text" name="nomepai" class="form-control" id="nomepai" placeholder="Nome do Pai"
                   value="" maxLength="60">
        </div>
        <div class="col-md-6 form-group">
            <label for="nomemae">Nome do Mãe:</label>
            <input type="text" name="nomemae" class="form-control" id="nomemae" placeholder="Nome da Mãe" value="<?php echo e($falecido['mae'] ?? ''); ?>" maxLength="60" <?php if(!empty($falecido['mae'])): ?> readonly <?php endif; ?>>
            </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4 form-group">
            <label for="dfalec">Data do Falecimento:</label>
            <input type="date" name="dfalec" class="form-control" data-required id="dfalec" placeholder="Data do Falecimento"
                   value="<?php echo e($falecido['falecimento'] ?? ''); ?>" <?php if(!empty($falecido['falecimento'])): ?> readonly <?php endif; ?>>
            <div class="text-danger"></div>
        </div>
        <!-- <div class="col-md-4 form-group">
            <label for="abrangencia">Abrangência:</label>
            <select id="abrangencia" name="abrangencia" class="form-select">
                <option value="" name="untouched">Selecione a Abrangência</option>
                <?php $__currentLoopData = $abrangencia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indice => $abr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($indice); ?>"><?php echo e($abr); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select> -->
        <div class="col-md-3 form-group">
            <label for="estados">Estado (Óbito):</label>
            <?php if(!empty($falecido['uf'])): ?>  
                <input type="hidden" name="estado_obito" value="<?php echo e($falecido['uf']); ?>">
                <select id="estados" class="form-select" disabled>
                    <option value="<?php echo e($falecido['uf']); ?>" ><?php echo e($falecido['uf']); ?></option>
                </select>
            <?php else: ?>
                <select id="estados" name="estado_obito" class="form-select">
                    <option value="" selected>Selecione a UF</option>
                    <?php $__currentLoopData = $ufs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($uf); ?>"><?php echo e($uf); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php endif; ?>            
        </div>
        <?php if(!empty($falecido['cidade'])): ?>
            <input type="hidden" name="cidade_obito" value="<?php echo e($falecido['cidade']); ?>">
            <div class="col-md-5 form-group">
                <label for="cidades">Cidade (Óbito):</label>
                <select id="cidades" name="cidade_obito" class="form-select" disabled>
                    <option selected><?php echo e($falecido['cidade']); ?></option>
                </select>
            </div>
        <?php else: ?>
            <div class="col-md-5 form-group">
                <label for="cidades">Cidade (Óbito):</label>
                <select id="cidades" name="cidade_obito" class="form-select" disabled>
                    <option value="" disabled selected>Selecione o estado primeiro</option>
                </select>
            </div>
        <?php endif; ?>
    </div>

    <div class="row mt-3">
        <!-- <div class="col-md-3 form-group estadual">
            <label for="estados">Estado (Óbito):</label>
            <select id="estados" name="estado_obito" class="form-select">
                <option value="" name="untouched">Selecione a UF</option>
                <?php $__currentLoopData = $ufs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($uf); ?>"><?php echo e($uf); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3 form-group estadual">
            <label for="cidades">Cidade (Óbito):</label>
            <select id="cidades" name="cidade_obito" class="form-select" disabled>
            </select>
        </div> -->
        <div class="col-md-4 form-group">
            <label for="localfal">Local do Falecimento:</label>
            <select name="localfal" id="localfal" class="form-select">
                <option value="" name="untouched">Selecione o Local</option>
                <?php $__currentLoopData = $localFalecimento; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $local): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($local); ?>"><?php echo e($local); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label for="ecivil">Estado Civil:</label>
            <select name="ecivil" id="ecivil" class="form-select">
                <option value="" name="untouched">Selecione Estado Civil</option>
                <?php $__currentLoopData = $estadoCivil; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($estado); ?>"><?php echo e($text); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="form-group mt-3">
            <label for="comentarios">Informações Adicionais:</label>
            <textarea id="comentarios" class="form-control" name="comentarios" rows="3" maxlength="500" placeholder="Mensagem"></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12 ps-3 mb-3">
            <div class="form-check d-flex align-items-center gap-2">
                <input 
                    class="form-check-input flex-shrink-0" 
                    data-required
                    type="checkbox" 
                    id="termo_de_uso" 
                    name="termo_de_uso" 
                    data-type="termo_de_uso"
                >
                <label class="form-check-label mb-0" for="termo_de_uso">
                    Declaro que li e aceito os termos de uso do serviço, conforme descrito em 
                    <a href="/politica-de-privacidade" target="_blank">
                        Política de Privacidade e Segurança
                    </a>
                </label>
                    <div class="text-danger"></div>
             </div>
        </div>
    </div>
    <div class="row mt-3 col-md-2 justify-content-center">
        <button type="submit" class="btn btn-cnf">Avançar</button>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="<?php echo e(asset('js/abrangencia.js')); ?>"></script> -->
<script src="<?php echo e(asset('js/buscaEmCartorio.js')); ?>"></script>
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/forms/buscaEmCartorio.blade.php ENDPATH**/ ?>