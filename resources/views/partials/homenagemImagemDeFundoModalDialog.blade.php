<div class="modal-body">
    <div class="row">
        @foreach($opcoesImagem as $indice => $opcao)
            <label class="col-md-4">
                <img src="{{ asset($opcao) }}" alt="Opção {{ $indice + 1 }}" class="img-fluid img-thumbnail" />
                <div class="form-check mt-2 d-flex justify-content-center">
                    <input class="form-check-input" type="radio" name="opcaoImagem" value="{{ $opcao }}">
                </div>
            </label>
        @endforeach
    </div>
</div>
