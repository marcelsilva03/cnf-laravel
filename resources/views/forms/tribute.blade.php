<form
    class="row mt-3 justify-content-center"
    action="{{ route('receptor-de-nova-homenagem') }}"
    method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="uuid" value="{{ $falecido['fal_uuid'] }}"/>
    <div class="col-12 col-sm-6 form-group">
        <label for="email">Email</label>
        <input
            type="email"
            name="email"
            class="form-control"
            id="email"
            data-type="email"
            placeholder="example@example.com"
            required
            value="{{$solicitante['email'] ?? old('email')}}"
            @if(!empty($solicitante['email']))
                readonly
            @endif
        />
        <div class="invalid-feedback" id="email-error"></div>
    </div>
    <div class="col-12 col-sm-6 form-group">
        <label for="nomehomenagem">Nome do autor</label>
        <input
            type="text"
            name="nome_autor"
            class="form-control"
            id="nomehomenagem"
            placeholder="Digite o seu nome:"
            required
            value="{{$solicitante['name'] ?? old('nome_autor')}}"
            @if(!empty($solicitante['name']))
                readonly
            @endif
        />
    </div>
    <div class="col-12 col-sm-6 form-group">
        <label for="cpfHomenagem">CPF</label>
        <input
            type="text"
            name="cpf_autor"
            class="form-control"
            id="cpfHomenagem"
            placeholder="000.000.000-00"
            maxLength="14"
            data-type="cpf"
            required
            value="{{ $solicitante['perfil']['cpf'] ?? old('cpf_autor') }}"
            @if(!empty($solicitante['perfil']['cpf']))
                readonly
            @endif
        />
        <div class="invalid-feedback" id="cpf-error-message" style="display: none;">CPF inválido.</div>
    </div>
    <div class="col-12 col-sm-6 form-group">
        <label for="whatsapp">WhatsApp</label>
        <input
            type="text"
            name="whatsapp"
            class="form-control"
            id="whatsapp"
            data-type="tel"
            placeholder="(00) 000000000"
            maxLength="15"
            required
            value="{{ $solicitante['perfil']['fone_numero'] ?? old('whatsapp') }}"
            @if(!empty($solicitante['perfil']['fone_numero']))
                readonly
            @endif
        />
        <div class="invalid-feedback" id="whatsapp-error"></div>
    </div>
    <div class="row g-3">
        <div class="col-12 col-md-4 form-group">
            <label for="parentesco">Parentesco</label>
            <select name="parentesco" class="form-select" id="parentesco" required>
                @if(empty(old('parentesco')))
                    <option value="" name="untouched">-- Selecione o parentesco --</option>
                @endif
                @foreach($parentescos as $parentescoValue => $parentescoLabel)
                    <option value="{{ $parentescoValue }}">{{ $parentescoLabel }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-4 form-group d-flex flex-column justify-content-end">
            <label for="fotohomenagem" class="btn btn-foto">Foto do falecido</label>
            <input name="fotofalecido" type="file" class="form-control" id="fotohomenagem"
                   placeholder="Escolha uma foto do falecido:">
        </div>
        <div class="col-12 col-md-4 form-group d-flex flex-column justify-content-end">
            <label id="modal-caller" for="fotofundo" class="btn btn-foto" data-bs-toggle="modal"
                   data-bs-target="#modalExemplo">Foto de Fundo</label>
            <input type="hidden" name="opcaoImagemFundo" id="opcaoImagemFundo" required/>
        </div>
    </div>
    <div class="row g-3 justify-content-center">
        <div class="form-group mt-3">
            <label for="homenagem">Texto da homenagem:</label>
            <textarea class="form-control" name="homenagem" rows="3" required maxlength="1275"
                      placeholder="Escreva aqui sua homenagem:">{{ old('homenagem') }}</textarea>
            @include('partials.contadorTextArea', [ 'dataCounterFor' => 'homenagem' ])
        </div>
    </div>
    <div class="container d-flex justify-content-center mt-3">
        <a
            role="button"
            href="{{ empty($falecido['url']) ? '#' : $falecido['url'] }}"
            class="btn btn-large btn-cnf {{ empty($falecido['url']) ? 'disabled' : '' }}"
        >Ver homenagens</a>
        <button type="submit" role="button" class="mx-2 btn btn-large btn-cnf btn-separation">Enviar</button>
    </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/validaCPF.js') }}"></script>
<script src="{{ asset('js/validaMail.js') }}"></script>
