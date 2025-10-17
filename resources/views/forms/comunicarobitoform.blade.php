<form class="row g-3 justify-content-center" action="{{ route('submit.form') }}" method="POST">
@csrf {{-- CSRF Token for form protection --}}
    <div class="row mt-3">
        <h4>Dados do Solicitante</h4>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="nomeso">Nome do solicitante:</label>
            <input type="text" name="nome_sol" class="form-control" id="nomeso" value="{{ old('nome_sol') }}" placeholder="Nome do solicitante" required>
            <div class="form-text fieldLegend">Apenas o texto sem acentos</div>
        </div>
        <div class="col-md-3 form-group">
            <label for="telsol">Telefone:</label>
            <input type="tel" name="fone_sol" class="form-control" data-type="tel" id="telsol"
                   value="{{ old('fone_sol') }}"
                   placeholder="Telefone:" required>
            <div class="form-text fieldLegend">Apenas números com DDD.</div>
        </div>
        <div class="col-md-3 form-group">
            <label for="emailsol">E-mail:</label>
            <input type="text" name="email_sol" class="form-control" data-type="email" id="emailsol"
                   value="{{ old('email_sol') }}"
                   placeholder="E-mail:" required>
            <div class="form-text"></div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="form-check checkcomunicar">
            <input type="checkbox" class="form-check-input" id="comunicarobito"
                   name="comunicarobito" {{ old('comunicarobito') ? 'checked' : '' }} required/>
            <label class="form-check-label comunicarTxt" for="comunicarobito">Declaro, para fins de responsabilidades
                civil e criminal, que as informações do óbito abaixo descriminado são verídicas e de minha inteira
                responsabilidade.</label>
        </div>
    </div>
    <hr>
    <div class="row mt-3">
        <h4>Dados do Falecido</h4>
    </div>
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="obito_nomeinst">Nome do Falecido:</label>
            <input type="text" name="nome_fal" class="form-control" id="obito_nomeinst" value="{{ old('nome_fal') }}"
                   placeholder="Nome do Falecido:" required>
            <div class="form-text fieldLegend">Apenas o texto sem acentos.</div>
        </div>
        <div class="col-md-2 form-group">
            <label for="obito_cpfinst">CPF:</label>
            <input type="text" name="cpf_fal" class="form-control" id="obito_cpfinst" value="{{ old('cpf_fal') }}"
                placeholder="000.000.000-00" maxlength="14" data-type="cpf">
            <div class="form-text fieldLegend">Apenas números</div>
        </div>
        <div class="col-md-2 form-group">
            <label for="obito_reginst">RG:</label>
            <input type="text" name="rg_fal" class="form-control" id="obito_reginst" value="{{ old('rg_fal') }}"
                   placeholder="RG:" maxlength="12" pattern="\d{12}">
            <div class="form-text fieldLegend">Apenas números</div>
        </div>
        <div class="col-md-2 form-group">
            <label for="obito_eleitor">Título de Eleitor:</label>
            <input type="text" name="titulo_eleitor" class="form-control" id="obito_eleitor"
                   value="{{ old('titulo_eleitor') }}" placeholder="Título de Eleitor:" data-type="eleitor"
                   maxlength="14">
            <div class="form-text fieldLegend">Apenas números</div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6 form-group">
            <label for="obito_nomepai">Nome do pai:</label>
            <input type="text" name="nome_pai_fal" class="form-control" id="obito_nomepai"
                   value="{{ old('nome_pai_fal') }}" placeholder="Nome do pai:">
            <div class="form-text fieldLegend">Apenas o texto sem acentos.</div>
        </div>
        <div class="col-md-6 form-group">
            <label for="obito_nomemae">Nome da mãe:</label>
            <input type="text" name="nome_mae_fal" class="form-control" id="obito_nomemae"
                   value="{{ old('nome_mae_fal') }}" placeholder="Nome da mãe:" required>
            <div class="form-text fieldLegend">Apenas o texto sem acentos.</div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-2 form-group">
            <label for="estados">Estado (Óbito):</label>
            <select name="ufobito" class="form-select" id="estados" required>
                <option value="" name="untouched">Selecione a UF</option>
                @foreach($ufs as $uf)
                    <option value="{{ $uf }}">{{ $uf }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label for="obito_cidadeinstnome">Cidade (Óbito):</label>
            <select name="cidadeobito" class="form-select" id="cidades" disabled required></select>
        </div>
        <div class="col-md-6 form-group">
            <label for="obito_cartorio">Cartório (Óbito):</label>
            <select name="cartorio_id" class="form-select" id="obito_cartorio" disabled required></select>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4 form-group">
            <label for="obito_nascf">Data de nascimento:</label>
            <input type="date" name="data_nascimento" class="form-control" id="obito_nascf"
                   value="{{ old('data_nascimento') }}" placeholder="Data de nascimento:" required>
        </div>
        <div class="col-md-4 form-group">
            <label for="obito_dfalec">Data do falecimento:</label>
            <input type="date" name="data_obito" class="form-control" id="obito_dfalec" value="{{ old('data_obito') }}"
                   placeholder="Data do falecimento:" required>
        </div>
        <div class="col-md-4 form-group">
            <label for="obito_localfal">Local do falecimento:</label>
            <select name="local_obito_tipo" id="tipolocalfal" class="form-select">
                <option value="">Selecione um local</option>
                @foreach($tipoLocaisDeObito as $key => $label)
                    <option 
                        value="{{ $key }}" 
                        {{ old('local_obito_tipo', '') === (string) $key ? 'selected' : '' }}
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label for="obito_ecivil">Estado Civil:</label>
            <select name="estado_civil" id="obito_ecivil" class="form-select">
                <option value="" name="untouched">Selecione um estado civil</option>
                @foreach($estadosCivil as $estadoCivil => $texto)
                    <option value="{{ $estadoCivil }}" {{ old('estado_civil') ? 'selected' : '' }}>{{ $texto }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label for="obito_sexo">Sexo:</label>
            <select name="sexo" id="obito_sexo" class="form-select" required>
                <option value="" name="untouched">Selecione o sexo</option>
                @foreach([1 => 'Masculino',2 => 'Feminino'] as $idx => $texto)
                    <option value="{{ $idx }}" {{ old('sexo') ? 'selected' : '' }}>{{ $texto }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <label for="livro">Livro:</label>
            <input type="text" name="livro" class="form-control" id="livro" value="{{ old('livro') }}" placeholder="Livro" />
        </div>
        <div class="col-md-4">
            <label for="folha">Folha:</label>
            <input type="text" name="folha" class="form-control" id="folha" value="{{ old('folha') }}" placeholder="Folha" />
        </div>
        <div class="col-md-4">
            <label for="termo">Termo:</label>
            <input type="text" name="termo" class="form-control" id="termo" value="{{ old('termo') }}" placeholder="Termo" />
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="form-group mt-3">
            <label for="comentarios">Informações adicionais:</label>
            <textarea class="form-control" name="obs" rows="3" id="comentarios"
                      placeholder="Mensagem:" maxlength="500">{{ old('obs') }}</textarea>
            @include('partials.contadorTextArea', [ 'dataCounterFor' => 'obs' ])
        </div>
    </div>
    <div class="row mt-3 col-md-2 justify-content-center">
        <button type="submit" class="btn btn-large btn-cnf">Enviar</button>
    </div>
</form>
