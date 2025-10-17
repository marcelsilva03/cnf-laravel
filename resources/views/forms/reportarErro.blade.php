<form class="modal-content modal1" action="{{ route('receptor-comunicado-de-erro') }}" method="POST">
  @csrf
  <div class="modal-header">
    <h5 class="modal-title w-100" id="exampleModalLabel" style="line-height:1.2;">
        <div class="fw-bold fs-4 mb-1 d-flex justify-content-between align-items-center">
            <span><i class="bi bi-exclamation-circle text-danger me-2"></i>Comunicar Erro</span>
        </div>
        <div class="fs-6 fw-semibold mb-1">
            Cadastro <span class="text-primary">#<span id="tituloId"></span></span>
            &nbsp;|&nbsp; <span class="text-uppercase" id="tituloNome"></span>
        </div>
        <div class="fs-6 text-secondary" id="tituloLinha3"></div>
    </h5>
      <button type="button" class="bi bi-x-square-fill modal_close text-danger" data-bs-dismiss="modal"
              aria-label="Close"></button>
  </div>
  <div class="modal-body">
    <input type="hidden" name="id_falecido" id="idFal">
    <input type="hidden" name="uuid_falecido" id="uuidFal">
    <input type="hidden" name="nome_falecido" id="nomeFal">
    <input type="hidden" name="cidade_falecido" id="cidadeFal">
    <input type="hidden" name="uf_falecido" id="ufFal">
    <input type="hidden" name="data_falecimento" id="dataFal">

      <div class="mb-3">
          <label for="email_comunicante" class="col-form-label">Email para contato:</label>
          <input type="email" name="email_comunicante" class="form-control" id="email_comunicante" required value="{{ old('email_comunicante') }}">
      </div>

      <div class="mb-3">
          <label for="nome_comunicante" class="col-form-label">Seu nome:</label>
          <input type="text" name="nome_comunicante" class="form-control" id="nome_comunicante" required value="{{ old('nome_comunicante') }}">
      </div>

      <div class="mb-3">
          <label for="tipo_erro" class="col-form-label">Tipo de erro:</label>
          <select name="tipo_erro" id="tipo_erro" class="form-select" required>
              <option value="" disabled selected>Selecione uma opção</option>
              <option value="erro_nome">Erro de nome ou filiação</option>
              <option value="erro_data">Erro de data</option>
              <option value="erro_cidade">Erro de cidade</option>
              <option value="nao_e_falecido">Não é falecido</option>
              <option value="complemento">Informação incompleta</option>
              <option value="outros">Outros tipos de erro ou complemento</option>
          </select>
      </div>

      <div class="mb-3">
          <label for="mensagem" class="col-form-label">Descreva/detalhe o erro:</label>
          <textarea name="mensagem" class="form-control" id="mensagem" rows="3" required>{{ old('mensagem') }}</textarea>
      </div>
  </div>
  <div class="modal-footer">
      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
      <button type="submit" class="btn btn-danger" id="botao_comunicar">Comunicar</button>
  </div>
</form>