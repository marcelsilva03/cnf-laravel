<section class="container mt-2 pag-txt">
    <div class="row">
        <div class="col-md-6">
            <h2>Formas de pagamento</h2>
            <ul class="nav nav-tabs mb-3" id="formas-de-pagamento" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-confirmacao="Gerar QR Code" id="pix-tab" data-bs-toggle="tab"
                            data-bs-target="#pix" type="button"
                            role="tab" aria-controls="pix" aria-selected="false">Pix
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="boleto-tab" data-confirmacao="Confirmar pagamento" data-bs-toggle="tab"
                            data-bs-target="#boleto"
                            type="button" role="tab" aria-controls="boleto" aria-selected="false">Boleto bancário
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="cartao-credito-tab" data-bs-toggle="tab"
                            data-confirmacao="Confirmar pagamento"
                            data-bs-target="#cartao-credito" type="button" role="tab" aria-controls="cartao-credito"
                            aria-selected="true">Cartão de crédito
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-confirmacao="Gerar QR Code" id="banco-do-brasil-tab"
                            data-bs-toggle="tab" data-bs-target="#banco-do-brasil" type="button"
                            role="tab" aria-controls="banco-do-brasil" aria-selected="false">Banco do Brasil
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="forma-de-pagamento-escolhido">
                <div class="tab-pane fade show active" id="pix" role="tabpanel" aria-labelledby="pix-tab">
                    <div class="mb-3">
                        <div id="pre-qr-code">
                            <p>Confirme sua decisão por pagamento via PIX no botão abaixo para gerar o QR Code.</p>
                            <button id="botao-confirmacao-pix" role="button" class="btn btn-success btn-large w-100">Gerar QR Code</button>
                        </div>
                        <div id="dados-pix" class="d-none">
                            <label for="pix-copia-e-cola" style="padding-bottom: 6px;">Clique para copiar o código PIX:</label>
                            <input readonly class="form-control" name="pix-copia-e-cola" data-clipboard="pix-copia-e-cola" data-clipboard-message="PIX copia e cola copiado para área de transferência com sucesso." id="pix-copia-e-cola"/>
                            <div class="d-flex justify-content-center">
                                <img id="pix-qr-code" class="img-fluid" src="" alt="QR Code PIX" style="max-width: 250px;">
                            </div>
                        </div>
                        <div id="pix-confirmado" class="d-none">
                            <div class="alert alert-success mb-3">
                                <strong>Pagamento recebido!</strong> 
                                <span id="pix-status-msg">Pagamento confirmado.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="boleto" role="tabpanel" aria-labelledby="boleto-tab">
                    <div class="mb-3">
                        <form id="form-boleto" class="row">
                            <h4 class="col-12">Dados do Solicitante</h4>
                            <div class="mb-3">
                                <label for="nome-boleto" class="form-label">Nome ou Empresa</label>
                                <input name="nomeboleto" value="{{ old('nomeboleto') }}" type="text"
                                class="form-control"
                                pattern="^[A-Za-zÀ-ÖØ-öø-ÿ]+(?:\s[A-Za-zÀ-ÖØ-öø-ÿ]+)+$" id="nome-boleto"
                                placeholder="Digite Nome ou Empresa">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="cpfcnpj" class="form-label">CPF ou CNPJ</label>                
                                <input type="text" data-type="cpfcnpj" id="cpfcnpj" name="cpfcnpj" inputmode="numeric" maxlength="18" class="form-control" placeholder="Digite CPF ou CNPJ" value="{{ old('cpfcnpj') }}">
                            </div>
                            <button id="boleto-botao-confirmacao" role="button" class="btn btn-success w-100 btn-large">Gerar Boleto</button>
                        </form>
                        <div id="boleto-bem-sucedido" class="d-none">
                            <h4>Dados para pagamento do boleto</h4>
                            <label for="boleto-codigo-de-barras" class="mt-4">Clique para copiar o código de barras:</label>
                            <input readonly class="form-control" id="boleto-codigo-de-barras" data-clipboard="boleto-codigo-de-barras" data-clipboard-message="Código de barras copiado para área de transferência com sucesso." name="boleto-codigo-de-barras" />
                            <a id="boleto-link-pdf" href="" target="_blank" class="btn btn-success btn-large w-100 mt-4">Baixar PDF</a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="cartao-credito" role="tabpanel"
                     aria-labelledby="cartao-credito-tab">
                    @include('forms.dadosCartao')
                </div>
                <div class="tab-pane fade" id="banco-do-brasil" role="tabpanel" aria-labelledby="banco-do-brasil-tab">
                    <div class="mb-3">
                        <p class="text-danger">É necessário o envio de comprovante de pagamento para
                            <strong>{{ config('constants.emails')['financeiro'] }}</strong></p>
                        <div id="pre-bb">
                            <p>Confirme sua decisão por pagamento via Banco do Brasil no botão abaixo para liberar os dados .</p>
                            <button id="botao-confirmacao-bb" role="button" class="btn btn-success btn-large w-100">Mostrar dados Banco do Brasil</button>
                        </div>
                        <div id="confirmado-bb" class="d-none row gx-0" style="overflow:hidden;">
                            <div class="col-lg-8 col-12 mb-3 text-break">
                                @foreach($dadosBancariosBB as $chave => $valor)
                                    <p class="mb-1">{{ $chave }}: <strong>{{ $valor }}</strong></p>
                                @endforeach
                            </div>
                            <div class="col-lg-4 col-12 d-flex justify-content-center">
                                <div style="max-width:250px; width:100%; aspect-ratio:1/1; display:flex; justify-content:center; align-items:center;">                                
                                    <!-- <img class="mw-100" src="{{ asset('images/qrcode-pix-bb.jpeg') }}" alt="" title="QR Code PIX Banco do Brasil"> -->
                                    <img class="d-block mx-auto" src="{{ asset('images/QRCodeBB_CNF_TEC.jpg') }}" alt="" title="QR Code PIX BB CNF TEC" style="display:block; width:100%; height:100%; object-fit:contain;">
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 py-3 py-md-0">
            <h2>Resumo do pedido</h2>
            <div>
                <p>ID da solicitação:<br/><strong>{{ $solicitacao['sol_id'] }}</strong></p>
                <p>Cliente:<br/><strong>{{ !empty($solicitacao['sol_empresa']) ? $solicitacao['sol_empresa'] : $solicitacao['sol_nome_sol'] }} <br> ({{ $solicitacao['sol_email_sol'] }})</strong></p>
                <p>Produto:<br/><strong>{{ $solicitacao['produto'] }}</strong></p>
                <p>Valor:<br/><strong>{{ $solicitacao['sol_valor'] }}</strong></p>
                <div class="text-center d-flex flex-column justify-content-center align-items-center">
                    <img width="200" src="images/site-seguro-google.png" class="mt-4"/>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/payment-token-efi/dist/payment-token-efi-umd.min.js"></script>
    <script>
        // expõe EfiPay também como EfiJs
        window.EfiJs = window.EfiPay;
    </script>
</section>
