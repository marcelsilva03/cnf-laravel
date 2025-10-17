<form id="form-busca-avancada" novalidate class="row g-3 justify-content-center" action="{{ route('pagamento-pesquisa-post') }}" method="POST">
    @csrf
    <div class="row mt-3"><h4>Dados do Solicitante</h4></div>
    <div class="row">
        {{-- Empresa (opcional) --}}
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
                value="{{ old('empresa', $usuario['empresa'] ?? '') }}"
            />
            <div class="text-danger"></div>
        </div>

        {{-- Seu nome --}}
        <div class="col-12 col-md-6 col-xl-6 form-group">
            <label for="nomeso">Seu nome:</label>
            @if(isset($usuario))
                <input type="text" name="nomesol" class="form-control" data-required id="nomeso" maxLength="255"
                       placeholder="Nome e Sobrenome" readonly value="{{ $usuario['nome'] }}" />
            @else
                <input type="text" name="nomesol" class="form-control" data-required id="nomeso" maxLength="255"
                       placeholder="Nome e Sobrenome" value="{{ old('nomesol') }}" />
            @endif
            <div class="text-danger"></div>
        </div>

        {{-- Telefone --}}
        <div class="col-12 col-md-6 col-xl-3 form-group">
            <label for="telsol">Seu telefone:</label>
            @if(isset($usuario))
                <input type="tel" name="telsol" class="form-control" data-required id="telsol" data-type="tel"
                       pattern="\([0-9]{2}\)\s?[0-9]{4,5}-?[0-9]{4}"
                       placeholder="(00) 00000-0000" maxLength="15" autocomplete="tel"
                       {{ isset($usuario['tel']) ? 'readonly' : '' }}
                       value="{{ isset($usuario['tel']) ? $usuario['tel'] : '' }}" />
            @else
                <input type="tel" name="telsol" class="form-control" data-required id="telsol" data-type="tel"
                       pattern="\([0-9]{2}\)\s?[0-9]{4,5}-?[0-9]{4}"
                       placeholder="(00) 00000-0000" maxLength="15" autocomplete="tel"
                       value="{{ old('telsol') }}" />
            @endif
            <div class="text-danger"></div>
        </div>

        {{-- E-mail --}}
        <div class="col-12 col-md-6 col-xl-3 form-group">
            <label for="emailsol">Seu e-mail:</label>
            @if(isset($usuario))
                <input type="email" name="emailsol" data-type="email" maxLength="255" class="form-control" data-required
                       id="emailsol" placeholder="E-mail" readonly value="{{ $usuario['email'] }}" autocomplete="email"/>
            @else
                <input type="email" name="emailsol" data-type="email" maxLength="255" class="form-control" data-required
                       id="emailsol" placeholder="E-mail" value="{{ old('emailsol') }}" autocomplete="email"/>
            @endif
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
                   value="{{ $falecido['nome'] ?? '' }}" maxLength="60" @if(!empty($falecido['nome'])) readonly @endif>
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
            <input type="text" name="nomemae" class="form-control" id="nomemae" placeholder="Nome da Mãe" value="{{ $falecido['mae'] ?? '' }}" maxLength="60" @if(!empty($falecido['mae'])) readonly @endif>
            </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4 form-group">
            <label for="dfalec">Data do Falecimento:</label>
            <input type="date" name="dfalec" class="form-control" data-required id="dfalec" placeholder="Data do Falecimento"
                   value="{{ $falecido['falecimento'] ?? '' }}" @if(!empty($falecido['falecimento'])) readonly @endif>
            <div class="text-danger"></div>
        </div>
        <!-- <div class="col-md-4 form-group">
            <label for="abrangencia">Abrangência:</label>
            <select id="abrangencia" name="abrangencia" class="form-select">
                <option value="" name="untouched">Selecione a Abrangência</option>
                @foreach($abrangencia as $indice => $abr)
                    <option value="{{ $indice }}">{{ $abr }}</option>
                @endforeach
            </select> -->
        <div class="col-md-3 form-group">
            <label for="estados">Estado (Óbito):</label>
            @if(!empty($falecido['uf']))  
                <input type="hidden" name="estado_obito" value="{{ $falecido['uf'] }}">
                <select id="estados" class="form-select" disabled>
                    <option value="{{ $falecido['uf'] }}" >{{ $falecido['uf'] }}</option>
                </select>
            @else
                <select id="estados" name="estado_obito" class="form-select">
                    <option value="" selected>Selecione a UF</option>
                    @foreach($ufs as $uf)
                        <option value="{{ $uf }}">{{ $uf }}</option>
                    @endforeach
                </select>
            @endif            
        </div>
        @if(!empty($falecido['cidade']))
            <input type="hidden" name="cidade_obito" value="{{ $falecido['cidade'] }}">
            <div class="col-md-5 form-group">
                <label for="cidades">Cidade (Óbito):</label>
                <select id="cidades" name="cidade_obito" class="form-select" disabled>
                    <option selected>{{ $falecido['cidade'] }}</option>
                </select>
            </div>
        @else
            <div class="col-md-5 form-group">
                <label for="cidades">Cidade (Óbito):</label>
                <select id="cidades" name="cidade_obito" class="form-select" disabled>
                    <option value="" disabled selected>Selecione o estado primeiro</option>
                </select>
            </div>
        @endif
    </div>

    <div class="row mt-3">
        <!-- <div class="col-md-3 form-group estadual">
            <label for="estados">Estado (Óbito):</label>
            <select id="estados" name="estado_obito" class="form-select">
                <option value="" name="untouched">Selecione a UF</option>
                @foreach($ufs as $uf)
                    <option value="{{ $uf }}">{{ $uf }}</option>
                @endforeach
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
                @foreach ($localFalecimento as $local)
                    <option value="{{ $local }}">{{ $local }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label for="ecivil">Estado Civil:</label>
            <select name="ecivil" id="ecivil" class="form-select">
                <option value="" name="untouched">Selecione Estado Civil</option>
                @foreach ($estadoCivil as $estado => $text)
                    <option value="{{ $estado }}">{{ $text }}</option>
                @endforeach
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
<!-- <script src="{{ asset('js/abrangencia.js') }}"></script> -->
<script src="{{ asset('js/buscaEmCartorio.js') }}"></script>
