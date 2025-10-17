<script>
    let detectedBrand = null;
    const containerMetodoEscolhido = document.querySelector('#forma-de-pagamento-escolhido');
    const formCartaoCredito = document.querySelector('#form-cartao-credito');
    const campoNumeroCartao = formCartaoCredito.querySelector('[name=numerocartao]');
    const campoParcelas = formCartaoCredito.querySelector('[name=numeroparcelas]');
    let parcelasLoaded  = false;
    let parcelasLoading = false;
    let parcelasBrand   = null; 
    const formBoleto = document.querySelector('#form-boleto');
    const coberturaTela = document.querySelector('#cobertura-carregamento-tela');

    const textoSingularOuPlural = (campos) => {
        let inicio = 'O campo';
        let fim = 'é obrigatório';
        if (campos.length > 1) {
            inicio = 'Os campos';
            fim = 'são obrigatórios';
        }
        const lista = campos.join(', ');
        return `${inicio} ${lista} ${fim}.`;
    };

    const obtemDadosProduto = () => {
        return {
            codigo: "{{ $solicitacao['pag_code'] }}",
            nomeSolicitante: "{{ $solicitacao['sol_nome_sol'] }}",
            telefone: "{{ $solicitacao['sol_tel_sol'] }}",
            email: "{{ $solicitacao['sol_email_sol'] }}",
            produto: "{{ $solicitacao['produto'] }}",
            valor: "{{ $solicitacao['sol_valor'] }}",
        }
    };

    const MENSAGEM_ERRO_PADRAO = 'Resposta inesperada do servidor. Tente novamente mais tarde ou entre em contato com a administração';
    const enviaDadosParaServidor = (dados, url, onSuccess = console.log, onError = console.error) => {
        inicarCarregamentoDaPagina();
    const body = {...obtemDadosProduto(), ...dados};
    //console.log('Dados completos do BODY enviados para o servidor:', body);

    fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(body)
    })
    .then(async res => {
      const text = await res.text();
      try {
        // tenta converter para JSON
        return JSON.parse(text);
      } catch (err) {
        // se falhar, joga no console o que veio do servidor
        console.error('Resposta não-JSON do servidor:', text);
        throw err;
      }
    })
    //recebe o JSON (se o parse funcionou)
    .then(json => {
      if (json.meta) {
        if (json.meta.code === 200) {
          onSuccess(json);
        } else {
          onError(json);
        }
      } else {
        console.error('JSON inesperado:', json);
        notificar(MENSAGEM_ERRO_PADRAO, 'erro');
      }
    })
    .catch(e => {
      // qualquer erro de rede, parse, ou lançado acima cai aqui
      console.error('Erro em enviaDadosParaServidor:', e);
      notificar(MENSAGEM_ERRO_PADRAO, 'erro');
    })
    .finally(finalizarCarregamentoDaPagina);
};

    const inicarCarregamentoDaPagina = () => {
        coberturaTela.classList.remove('d-none');
        document.body.classList.add('modal-open');
    };

    const finalizarCarregamentoDaPagina = () => {
        coberturaTela.classList.add('d-none');
        document.body.classList.remove('modal-open');
    };

    const obtemTokenPagamento = async (brand, number, cvv, expiration) => {
        if (typeof brand !== 'string' || !brand) {
            console.error('brand inválido em obtemTokenPagamento:', brand);
            notificar('Falha: bandeira do cartão não detectada.', 'erro');
            return '';
        }
        brand = brand.toLowerCase(); // SDK espera "visa", "mastercard", etc.
        const [mes, anoCurto] = expiration.split('/');
        const numeroDoCartao = number.replace(/\D/g, '');
        const dadosCartao = { 
            brand, 
            number: numeroDoCartao, 
            cvv,
            expirationMonth: mes,
            expirationYear: `20${anoCurto}`,
            reuse: false 
        };
        //console.log('dadosCartao:', dadosCartao);
    try {
        // 1) ambiente via SDK
        const efiEnv = '{{ config('efipay.options')['sandbox'] ? 'sandbox' : 'production' }}';
        //console.log('EFI Environment:', efiEnv);

        // 2) setup do SDK
        const efipaySetup = EfiPay.CreditCard
            .setAccount(efiAccount)
            .setEnvironment(efiEnv) 
            .setCreditCardData(dadosCartao);

        // 3) payment_token 
        const tokenData = await efipaySetup.getPaymentToken();
        //console.log('—> Dados do Token recebidos:', tokenData); 

        if (tokenData && tokenData.payment_token) {
            return tokenData.payment_token;
        } else {
            console.error('Falha ao obter payment_token: Estrutura de resposta inesperada', tokenData);
        }
    } catch (e) {
        console.error('Erro ao obter token de pagamento:', e);
    }
        return '';
    };

    const bandeirasSuportadas = ['visa', 'mastercard', 'elo', 'amex', 'hipercard'];
    const mensagemErroBandeira = 'Utilize um cartão das seguintes bandeiras: ' + bandeirasSuportadas.map(b => `"${b}"`).join(', ') + '.';
    const obtemBandeiraCartao = async (numero) => {
        let bandeiraCartao = '';
        await EfiPay.CreditCard
            .setCardNumber(numero)
            .verifyCardBrand()
            .then(brand => {
                //console.log('Bandeira detectada:', brand);
                if (bandeirasSuportadas.includes(brand)) {
                    bandeiraCartao = brand;
                }
            })
            .catch(e => {
                console.error(e)
            });
        return bandeiraCartao;
    };

    const efiAccount = '{{ env("EFI_ACCOUNT_IDENTIFIER") }}';
    const obtemParcelasCartao = async (brand) => {
        if (!(window.EfiJs && EfiJs.CreditCard)) {
            console.error('SDK EfiJs.CreditCard não encontrada');
            notificar('Não foi possível carregar as parcelas. Atualize a página.', 'erro');
            return [];
        }

    try {
        const result = await EfiJs.CreditCard
        .setAccount(efiAccount)
        .setEnvironment('{{ config('efipay.options')['sandbox'] ? 'sandbox' : 'production' }}')
        .setBrand(brand)
        <?php $valorInteiro = intval(preg_replace('/\D/', '', $solicitacao['sol_valor'])); ?>
        .setTotal({{ $valorInteiro }})
        .getInstallments();
        //console.log('Resposta getInstallments:', result);

        // Normaliza para SEMPRE devolver um array
        if (Array.isArray(result)) return result;
        if (result && Array.isArray(result.installments)) return result.installments;
        if (result && typeof result.installments === 'object') {
            // caso raro: installments ser um objeto de arrays
            return Object.values(result.installments).flat();
        }
        return [];
        } catch (e) {
            console.error('Erro ao obter parcelas:', e);
            return [];
        }
    };

    const criaTagHTMLOption = (valor, texto, name = null) => {
        const option = document.createElement('OPTION');
        option.value = valor;
        option.innerText = texto;
        if (name) {
            option.setAttribute('name', name);
        }
        return option;
    };

    const esvaziaOpcoesCampoParcelas = () => {
        campoParcelas.innerHTML = '';
    }
    const habilitaCampoParcelas = (parcelas) => {
        esvaziaOpcoesCampoParcelas();

        if (!Array.isArray(parcelas) || parcelas.length === 0) {
            campoParcelas.appendChild(criaTagHTMLOption('', 'Parcelas indisponíveis'));
            campoParcelas.setAttribute('disabled', 'disabled');
            return;
        }

        const options = parcelas.map(parcela => {
            let texto = `${parcela.installment}x de R$ ${parcela.currency}`;
            if (!parcela.has_interest) texto += ' (sem juros)';
            return criaTagHTMLOption(parcela.installment, texto);
        });

        campoParcelas.appendChild(criaTagHTMLOption('', 'Selecione o número de parcelas', 'untouched'));
        campoParcelas.append(...options);
        campoParcelas.removeAttribute('disabled');
    };

    // ===== Helpers globais =====
    const onlyDigits = (s = "") => s.replace(/\D/g, "");
    const isAmex = (digits) => /^3[47]/.test(digits); // 34 ou 37

    // Luhn (checa número de cartão real)
    const luhnIsValid = (digits) => {
        let sum = 0, dbl = false;
        for (let i = digits.length - 1; i >= 0; i--) {
            let n = digits.charCodeAt(i) - 48; // Number(digits[i]) sem alocação
            if (dbl) { n <<= 1; if (n > 9) n -= 9; }
            sum += n;
            dbl = !dbl;
        }
        return sum % 10 === 0;
    };

    // funções de carregamento de parcelaas
    function setParcelasDisabled(msg = 'Informe o número do cartão para ver opções de parcelas') {
        esvaziaOpcoesCampoParcelas();
        campoParcelas.appendChild(criaTagHTMLOption('', msg));
        campoParcelas.setAttribute('disabled', 'disabled');
        parcelasLoaded  = false;
        parcelasLoading = false;
        parcelasBrand   = null;
    }

    function setParcelasAwait(msg = 'Clique para consultar opções de parcelamento') {
        esvaziaOpcoesCampoParcelas();
        // opção "placeholder" inválida, força validação até o usuário escolher
        campoParcelas.appendChild(criaTagHTMLOption('', msg, 'untouched'));
        campoParcelas.removeAttribute('disabled');
        parcelasLoaded  = false;
        parcelasLoading = false;
        // mantém parcelsBrand para decidirmos se precisa recarregar quando a brand mudar
    }

    // carrega parcelas apenas quando necessário (primeira interação)
    async function loadParcelasIfNeeded() {
        if (parcelasLoaded || parcelasLoading) return;

        // número do cartão atual
        const numero = (campoNumeroCartao.value || '').replace(/\D/g, '');
        if (!numero) return; // proteção

        // detecta brand se ainda não temos ou se mudou
        if (!detectedBrand) {
            detectedBrand = await obtemBandeiraCartao(numero);
        }
        if (!detectedBrand) {
            mostraErro(campoNumeroCartao, mensagemErroBandeira);
            notificar(mensagemErroBandeira, 'erro');
            return;
        }

        // evita recarregar se já temos parcelas para esta mesma brand
        if (parcelasBrand && parcelasBrand === detectedBrand && parcelasLoaded) return;

        parcelasLoading = true;

        // feedback no select: "Carregando…"
        esvaziaOpcoesCampoParcelas();
        campoParcelas.appendChild(criaTagHTMLOption('', 'Carregando parcelas…'));
        campoParcelas.setAttribute('disabled', 'disabled');

        try {
            const parcelas = await obtemParcelasCartao(detectedBrand);
            habilitaCampoParcelas(parcelas); // sua função: popula e habilita
            parcelasLoaded = Array.isArray(parcelas) && parcelas.length > 0;
            parcelasBrand  = detectedBrand;
        } catch (e) {
            console.error(e);
            esvaziaOpcoesCampoParcelas();
            campoParcelas.appendChild(criaTagHTMLOption('', 'Parcelas indisponíveis. Tente novamente.'));
            campoParcelas.setAttribute('disabled', 'disabled');
            parcelasLoaded = false;
        } finally {
            parcelasLoading = false;
        }
    }

// helpers de UI para erro
    function getMsgDiv(input) {
        let div = input.nextElementSibling;
        if (!div || !div.classList.contains('invalid-feedback')) {
            div = document.createElement('div');
            div.className = 'invalid-feedback';
            input.insertAdjacentElement('afterend', div);
        }
        return div;
    }
    function mostraErro(input, msg) {
        const div = getMsgDiv(input);
        div.textContent = msg;
        div.style.display = 'block';
        input.classList.add('is-invalid');
    }
    function limpaErro(input) {
        const div = getMsgDiv(input);
        div.textContent = '';
        div.style.display = 'none';
        input.classList.remove('is-invalid');
    }

    // ===== Validações de campos =====
    function validaNumeroCartao(input) {
        const digits = onlyDigits(input.value);
        const amex = isAmex(digits);
        const expected = amex ? 15 : 16;

        if (!digits) {
            mostraErro(input, 'Campo obrigatório.');
            return false;
        }
        if (digits.length !== expected) {
            mostraErro(input, amex ? 'Número Amex deve ter 15 dígitos.' : 'Número do cartão deve ter 16 dígitos.');
            return false;
        }
        if (!luhnIsValid(digits)) {
            mostraErro(input, 'Número do cartão inválido.');
            return false;
        }
        limpaErro(input);
        return true;
    }

    function validaValidadeCartao(input) {
        const raw = (input.value || '').trim();
        const m = raw.match(/^(\d{2})\/(\d{2})$/);
        if (!m) {
            mostraErro(input, 'Use o formato MM/AA.');
            return false;
        }
        const mm = Number(m[1]), yy = Number(m[2]);
        if (mm < 1 || mm > 12) {
            mostraErro(input, 'Mês inválido.');
            return false;
        }

        const now = new Date();
        const curYY = Number(String(now.getFullYear()).slice(-2));
        const curMM = now.getMonth() + 1;

        const expirado = yy < curYY || (yy === curYY && mm < curMM);
        if (expirado) {
            mostraErro(input, 'Cartão vencido.');
            return false;
        }
        limpaErro(input);
        return true;
    }

    function validaCVVCartao(input, inputNumeroCartao) {
        const numDigits = onlyDigits(inputNumeroCartao.value || '');
        const amex = isAmex(numDigits);
        const cvv = onlyDigits(input.value || '');
        const expected = amex ? 4 : 3;

        if (cvv.length !== expected) {
            mostraErro(input, amex ? 'CVV do Amex deve ter 4 dígitos.' : 'CVV deve ter 3 dígitos.');
            return false;
        }
        limpaErro(input);
        return true;
    }

    function validaNomeTitular(input) {
        const raw = (input.value || '');
        const valor = raw.trim().replace(/\s+/g, ' '); // normaliza múltiplos espaços

        if (!valor) {
            mostraErro(input, 'Campo obrigatório.');
            return false;
        }

        // Regras:
        // - começa com letra ou dígito
        // - permite letras (com acento), dígitos, espaço, ponto, vírgula, barra, hífen,
        //   apóstrofo, aspas, parênteses e símbolos º ª °
        // - termina com letra, dígito ou ')'
        const re = /^[\p{L}\p{M}\d][\p{L}\p{M}\d .,\/\-'"()ºª°’]*$/u; 
        if (!re.test(valor)) {
            mostraErro(input, "Use apenas letras/números e . , / - ' \" ( ) º ª ° e espaços.");
            return false;
        }

        // ao menos duas palavras
        const partes = valor.split(' ').filter(Boolean);
        if (partes.length < 2) {
            mostraErro(input, 'Informe nome impresso no cartão.');
            return false;
        }

        // passou em tudo
        limpaErro(input);
        // opcional: manter o valor já normalizado no campo
        input.value = valor;
        return true;
    }

    function validaParcelas(select) {
        // trata null/undefined
        if (!select) return false;

        // valor selecionado (strings '' ou '0' contam como inválido)
        const val = (select.value || '').trim();

        // se o select estiver desabilitado, considere inválido (não dá pra escolher)
        if (select.disabled || !val || val === '0') {
        mostraErro(select, 'Selecione o número de parcelas.');
        return false;
        }

        // opcional: garantir que é número >= 1
        const n = parseInt(val, 10);
        if (Number.isNaN(n) || n < 1) {
            mostraErro(select, 'Selecione o número de parcelas.');
            return false;
        }

        // ok
        limpaErro(select);
        return true;
    }

    function validaNomeBoleto(input) {
        const raw = (input.value || '');
        const valor = raw.trim().replace(/\s+/g, ' '); // normaliza múltiplos espaços

        if (!valor) {
            mostraErro(input, 'Campo obrigatório.');
            return false;
        }

        // Regras:
        // - começa com letra ou dígito
        // - permite letras (com acento), dígitos, espaço, ponto, vírgula, barra, hífen,
        //   apóstrofo, aspas, parênteses e símbolos º ª °
        // - termina com letra, dígito ou ')'
        const re = /^[\p{L}\p{M}\d][\p{L}\p{M}\d .,\/\-'"()ºª°’]*$/u; 
        if (!re.test(valor)) {
            mostraErro(input, "Use apenas letras/números e . , / - ' \" ( ) º ª ° e espaços.");
            return false;
        }

        // ao menos duas palavras
        const partes = valor.split(' ').filter(Boolean);
        if (partes.length < 2) {
            mostraErro(input, 'Informe nome ou empresa.');
            return false;
        }

        // passou em tudo
        limpaErro(input);
        // opcional: manter o valor já normalizado no campo
        input.value = valor;
        return true;
    }

    campoNumeroCartao.addEventListener('blur', async () => {
        // 1) valida número (tamanho + luhn)
        if (!validaNumeroCartao(campoNumeroCartao)) {
            // número inválido → mantém select desabilitado
            setParcelasDisabled('Informe o número do cartão para ver opções de parcelas');
            return;
        }

        // 2) Detecta brand e ajusta CVV, mas NÃO busca parcelas aqui
        const numero = campoNumeroCartao.value.replace(/\D/g, '');
        if (numero.length < 6) return; // BIN mínimo para detectar

        try {
            detectedBrand = await obtemBandeiraCartao(numero);
            if (!detectedBrand) {
                mostraErro(campoNumeroCartao, mensagemErroBandeira);
                notificar(mensagemErroBandeira, 'erro');
                setParcelasDisabled('Bandeira não suportada.');
                return;
            }

            // Ajusta CVV conforme a bandeira
            const campoCVV = formCartaoCredito.querySelector('[name=cvv]');
            const isAmexFlag = (detectedBrand.toLowerCase() === 'amex');
            campoCVV.maxLength = isAmexFlag ? 4 : 3;
            campoCVV.setAttribute('pattern', isAmexFlag ? '\\d{4}' : '\\d{3}');
            campoCVV.setAttribute('placeholder', isAmexFlag ? '0000' : '000');
            if (campoCVV.value) validaCVVCartao(campoCVV, campoNumeroCartao);

            // 3) Número válido + brand conhecida → deixa o select pronto para carregar sob demanda
            setParcelasAwait('Clique para consultar opções de parcelamento');
        } catch (e) {
            console.error(e);
            notificar('Erro ao processar cartão', 'erro');
            setParcelasDisabled('Falha ao detectar a bandeira.');
        }
    });

    campoNumeroCartao.addEventListener('input', () => {
        const digits = onlyDigits(campoNumeroCartao.value);
        // se o número ficou curto ou obviamente inválido, volta para o estado desabilitado
        if (digits.length < 15) { // antes de completar (15 Amex / 16 demais)
            setParcelasDisabled('Informe o número do cartão para ver opções de parcelas');
        } else {
            // ainda não sabemos se passou no Luhn – o blur fará isso.
            // aqui, só evitamos manter parcelas carregadas se o usuário alterou o BIN
            parcelasLoaded  = false;
            parcelasBrand   = null;
        }
    });

    const campoValidade = formCartaoCredito.querySelector('[name=validadecartao]');
    const campoCVV = formCartaoCredito.querySelector('[name=cvv]');
    const campoInputTit = formCartaoCredito.querySelector('[name=nometitular]');

    campoValidade.addEventListener('blur', () => {
        validaValidadeCartao(campoValidade);
    });

    campoCVV.addEventListener('blur', () => {
        validaCVVCartao(campoCVV, campoNumeroCartao);
    });

    campoInputTit.addEventListener('blur', () => {
        validaNomeTitular(campoInputTit);
    });

    const selectParc = formCartaoCredito.querySelector('[name=numeroparcelas]');
    if (selectParc) {
        // Ao abrir o dropdown: mousedown (desktop) e focus (acessibilidade / mobile)
        selectParc.addEventListener('mousedown', loadParcelasIfNeeded, { once: false });
        selectParc.addEventListener('focus',     loadParcelasIfNeeded, { once: false });

        // Se o usuário escolher uma opção, valida e limpa erro
        selectParc.addEventListener('change', () => {
            validaParcelas(selectParc);
        });
    }

    const sucessoUrl = "{{ route('pagamento.sucesso') }}";
    const callbackCartao = (response) => {
        //console.log('>> callbackCartao recebeu:', response);
        if (response.meta.code === 200) {
            notificar(response.meta.message, 'sucesso');
            const params = new URLSearchParams({
                charge_id: response.data.charge_id,
                codigo:    response.data.codigo,
                status:    response.data.status
            });
            const origin = window.location.origin;
            window.location.replace(`${sucessoUrl}?${params.toString()}`);
        } else {
            notificar(response.meta.message, 'erro');
        }
    }

    const inputNomeBoleto = formBoleto.querySelector('[name=nomeboleto]');
    if (inputNomeBoleto) {
        // valida ao sair do campo
        inputNomeBoleto.addEventListener('blur', () => {
            validaNomeBoleto(inputNomeBoleto);
        });

        // opcional: enquanto digita, apenas remove o erro quando ficar ok
        inputNomeBoleto.addEventListener('input', () => {
            const v = (inputNomeBoleto.value || '').trim();
            if (v.length >= 1) {
              limpaErro(inputNomeBoleto);
            }
        });
    }

    const callbackErro = (response) => {
        notificar(response.meta.message, 'erro');
    }

    /////////////////////////////
    // submit do form do cartão//
    /////////////////////////////
    (function () {
        const form = document.getElementById('form-cartao-credito');
        const botao = document.getElementById('cartao-credito-botao-confirmacao');
        if (!form || !botao) return;

        botao.addEventListener('click', async () => {
            const campos = [
                { name: 'numerocartao',   label: 'Número do cartão' },
                { name: 'validadecartao', label: 'Validade' },
                { name: 'cvv',            label: 'CVV' },
                { name: 'nometitular',    label: 'Nome do titular' },
                { name: 'cpfcnpj',        label: 'CPF ou CNPJ' },
                { name: 'numeroparcelas', label: 'Número de parcelas' },
            ];

            // -------- PASSAGEM ÚNICA: required + validação específica --------
            const dados = {};
            let primeiroInvalido = null;

            // mapeia cada campo para sua validação específica (usando seus helpers)
            const validators = {
                numerocartao:   (el, f) => validaNumeroCartao(el),
                validadecartao: (el, f) => validaValidadeCartao(el),
                cvv:            (el, f) => validaCVVCartao(el, f.querySelector('[name=numerocartao]')),
                nometitular:    (el, f) => validaNomeTitular(el),
                cpfcnpj: (el, f) => {
                    const valor = (el.value || '').trim();
                    if (!valor) { // required já marca, mas garantimos aqui tbm
                        mostraErro(el, 'Campo obrigatório.');
                        return false;
                    }

                    const ok = !!ValidaBR.validarCpfCnpj(valor); // ← booleana do seu arquivo externo
                    if (ok) {
                        limpaErro(el);
                        return true;
                    }

                    // mensagem conforme tamanho
                    const d = (ValidaBR.soDigitos ? ValidaBR.soDigitos(valor) : valor.replace(/\D/g, ''));
                    let msg = 'CPF/CNPJ inválido.';
                    if (d.length <= 11) {
                        msg = d.length === 11 ? 'CPF inválido ou CNPJ incompleto.' : 'CPF ou CNPJ incompleto.';
                    } else {
                        msg = d.length === 14 ? 'CNPJ inválido.' : 'CNPJ deve ter 14 dígitos.';
                    }
                    mostraErro(el, msg);
                    return false;
                },
                numeroparcelas: (el, f) => validaParcelas(el),
            };

            for (const { name } of campos) {
                const input = form.querySelector(`[name=${name}]`);
                if (!input || input.disabled) continue;

                const valor = (input.value || '').trim();

                // 1) required
                if (!valor) {
                    mostraErro(input, 'Campo obrigatório.');
                    if (!primeiroInvalido) primeiroInvalido = input;
                    continue; // não valida específico, segue para o próximo
                }

                // 2) validação específica (se houver)
                const validate = validators[name];
                if (typeof validate === 'function') {
                    const ok = validate(input, form);
                    if (!ok) {
                        if (!primeiroInvalido) primeiroInvalido = input;
                    continue; // não guarda em dados se inválido
                    }
                } else {
                    // sem validador específico -> limpa erro, se existir
                    limpaErro(input);
                }

                // 3) guarda o valor válido
                dados[name] = valor;
            }

            // 4) foco no primeiro inválido (vazio ou inválido específico) e interrompe
            if (primeiroInvalido) {
                primeiroInvalido.focus();
                setTimeout(() => primeiroInvalido.focus(), 0); // reforço de foco
                return;
            }


            // obtendo bandeira e token
            const numeroLimpo = dados.numerocartao.replace(/\D/g, '');
            // carrega parcelas após número validado
            const brand = (typeof detectedBrand === 'string' && detectedBrand)
                ? detectedBrand
                : await obtemBandeiraCartao(numeroLimpo);

            if (!brand) {
                notificar('Bandeira do cartão não suportada.', 'erro');
                return;
            }

            //console.log('brand no submit:', brand);

            inicarCarregamentoDaPagina();
            const token = await obtemTokenPagamento(
                brand, numeroLimpo, dados.cvv, dados.validadecartao
            );
            finalizarCarregamentoDaPagina();

            if (!token) {
                notificar('Falha ao obter token de pagamento.', 'erro');
                return;
            }

            // payload
            const payload = {
                ...obtemDadosProduto(),
                nometitular:    dados.nometitular,
                cpfcnpj:        ValidaBR.soDigitos(dados.cpfcnpj),
                numeroparcelas: dados.numeroparcelas,
                tokenpagamento: token,
            };

            enviaDadosParaServidor(
                payload,
                '{{ route("api-pagamentos.efi.recebe-cartao") }}',
                callbackCartao,
                callbackErro
            );
        });
    })();

    /////////////////////////////
    // submit do form do boleto //
    /////////////////////////////
    (function () {
        const formBoleto   = document.getElementById('form-boleto');
        const botaoBoleto  = document.getElementById('boleto-botao-confirmacao');
        if (!formBoleto || !botaoBoleto) return;

        // elementos da UI de sucesso (já existiam no seu código)
        const containerBoleto       = document.querySelector('#boleto-bem-sucedido');
        const codigoDeBarrasBoleto  = document.querySelector('#boleto-codigo-de-barras');
        const botaoLinkPDFBoleto    = document.querySelector('#boleto-link-pdf');

        // campos mínimos para boleto (ajuste os names se forem diferentes no seu form)
        const campos = [
            { name: 'nomeboleto',  label: 'Nome ou Empresa' },
            { name: 'cpfcnpj',  label: 'CPF ou CNPJ' },
        ];

        // validadores específicos
        const validators = {
            nomeboleto:    (el, f) => validaNomeBoleto(el),
            cpfcnpj: (el) => {
                const valor = (el.value || '').trim();
                if (!valor) { mostraErro(el, 'Campo obrigatório.'); return false; }

                const ok = !!ValidaBR.validarCpfCnpj(valor);
                if (ok) { limpaErro(el); return true; }

                const d = (ValidaBR.soDigitos ? ValidaBR.soDigitos(valor) : valor.replace(/\D/g, ''));
                let msg = 'CPF/CNPJ inválido.';
                if (d.length <= 11) {
                    msg = d.length === 11 ? 'CPF inválido ou CNPJ incompleto.' : 'CPF ou CNPJ incompleto.';
                } else {
                    msg = d.length === 14 ? 'CNPJ inválido.' : 'CNPJ deve ter 14 dígitos.';
                }
                mostraErro(el, msg);
                return false;
            },
        };

        botaoBoleto.addEventListener('click', () => {
            const dados = {};
            let primeiroInvalido = null;

            for (const { name } of campos) {
                const input = formBoleto.querySelector(`[name=${name}]`);
                if (!input || input.disabled) continue;

                // ignora inputs escondidos por etapas (caso use data-etapa-conteudo)
                const isVisible = !input.closest('[data-etapa-conteudo]')?.classList.contains('d-none');
                if (!isVisible) continue;

                const valor = (input.value || '').trim();

                // required
                if (!valor) {
                    mostraErro(input, 'Campo obrigatório.');
                    if (!primeiroInvalido) primeiroInvalido = input;
                    continue;
                }

                // validação específica
                const validate = validators[name];
                if (typeof validate === 'function') {
                    const ok = validate(input, formBoleto);
                    if (!ok && !primeiroInvalido) primeiroInvalido = input;
                    if (!ok) continue;
                } else {
                    limpaErro(input);
                }

                dados[name] = valor;
            }

            if (primeiroInvalido) {
                primeiroInvalido.focus();
                setTimeout(() => primeiroInvalido.focus(), 0);
                return;
            }

            // monta payload para o backend (reaproveita dados do produto)
            const payload = {
                ...obtemDadosProduto(),
                nomeboleto:  dados.nomeboleto,
                cpfcnpj:  ValidaBR.soDigitos ? ValidaBR.soDigitos(dados.cpfcnpj) : (dados.cpfcnpj || '').replace(/\D/g, ''),
            };

            // opcional: travar botão para evitar duplo clique
            botaoBoleto.disabled = true;

            enviaDadosParaServidor(
                payload,
                '{{ route("api-pagamentos.efi.recebe-boleto") }}',
                (response) => {
                    botaoBoleto.disabled = false;
                    // callbackBoleto (mesma lógica que você já tinha)
                    if (response.meta.code === 200) {
                        notificar(response.meta.message, 'sucesso');
                        formBoleto.classList.add('d-none');
                        containerBoleto.classList.remove('d-none');
                        if (codigoDeBarrasBoleto) codigoDeBarrasBoleto.value = response.data.codigo_de_barras;
                        if (botaoLinkPDFBoleto)   botaoLinkPDFBoleto.href  = response.data.pdf;
                    } else {
                        notificar(response.meta.message || 'Falha na emissão do boleto.', 'erro');
                    }
                },
                (response) => {
                    botaoBoleto.disabled = false;
                    // callbackErro
                    notificar(response.meta?.message || 'Erro ao enviar dados do boleto.', 'erro');
                }
            );
        });
    })();
    
    //////////////////////////////
    // submit do banco do brasil//
    //////////////////////////////
    const botaoBB = document.querySelector('#botao-confirmacao-bb');
    const containerBB = document.querySelector('#pre-bb');
    const dadosBB = document.querySelector('#confirmado-bb');
    const callbackBB = () => {
        notificar('Confirmada escolha de pagamento via Banco do Brasil TED ou PIX', 'sucesso')
        containerBB.classList.add('d-none');
        dadosBB.classList.remove('d-none');
    }
    botaoBB.addEventListener('click', () => {
        const dados = obtemDadosProduto();
        enviaDadosParaServidor(dados, "{{ route('api-pagamentos.bb.pix-ou-ted') }}", callbackBB, callbackErro);
    });

    //////////////////////////
    // submit do form do PIX//
    //////////////////////////
    const containerPIX = document.querySelector('#dados-pix');
    const campoPIXCopiaECola = document.querySelector('#pix-copia-e-cola');
    const imagemQRCode = document.querySelector('#pix-qr-code');
    const botaoGerarPIX = document.querySelector('#botao-confirmacao-pix');
    const secaoGerarQRCode = document.querySelector('#pre-qr-code');

    const code   = @json($solicitacao['pag_code']);
    const okBox  = document.getElementById('pix-confirmado');
    const msgEl  = document.getElementById('pix-status-msg');

    let es = null;
    let sseAtivo = false;
    let jaMostreiPago = false;

    function formatarDataHoraBr(iso) {
        if (!iso) return '';
        const dt = new Date(iso.replace(' ', 'T'));
        if (isNaN(dt.getTime())) return '';
        return dt.toLocaleString('pt-BR', { hour12: false });
    }

    function mostrarPago(d) {
        if (jaMostreiPago) return;
        jaMostreiPago = true;
        containerPIX?.classList.add('d-none');
        okBox?.classList.remove('d-none');

        const quando = formatarDataHoraBr(d?.paid_at);
        if (msgEl) {
            msgEl.textContent = quando ? `Pagamento confirmado em ${quando}.` : 'Pagamento confirmado.';
        }

        try {
            okBox?.scrollIntoView({ behavior: 'smooth', block: 'start' }); 
        } catch {}
        
        try {
            es?.close(); 
        } catch {}
        
        sseAtivo = false;

        const sucessoUrl = "{{ route('pagamento.sucesso') }}";
        const params = new URLSearchParams({
            codigo: d?.code || @json($solicitacao['pag_code']), // fallback
            status: 'approved',
            ...(d?.txid ? { txid: d.txid } : {}), // opcional: inclui txid se veio
        });

        // esperar 1.2s para o usuário ver o "confirmado"
        setTimeout(() => {
        window.location.replace(`${sucessoUrl}?${params.toString()}`);
        }, 1200);
    }

    function conectaSSE() {
        if (sseAtivo) return;
        es = new EventSource(`/api/pagamentos/efi/stream/${encodeURIComponent(code)}`);
        sseAtivo = true;

        es.addEventListener('pago', (e) => {
            try {
                mostrarPago(JSON.parse(e.data || '{}')); 
            } catch {
                mostrarPago({}); 
            }
        });

        es.onerror = () => {
            // EventSource reconecta sozinho
        };
    }

    const callbackPIX = (response) => {
        if (response.meta.code === 200) {
            notificar(response.meta.message, 'sucesso');
            const valorCopiaECola = response.data.qrcode;
            const base64StringQRCode = response.data.imagemQrcode;
            campoPIXCopiaECola.value = valorCopiaECola;
            imagemQRCode.src = base64StringQRCode;
            secaoGerarQRCode.classList.add('d-none');
            containerPIX.classList.remove('d-none');
            botaoGerarPIX.disabled = true;
            conectaSSE();
        } else {
            notificar('Falha ao gerar QR Code. Tente novamente.', 'erro');
            botaoGerarPIX.disabled = false;
        }
    }

    const gerarQRCode = () => {
        const dados = obtemDadosProduto();
        const url = '{{ route("api-pagamentos.efi.gera-qrcode") }}';
        enviaDadosParaServidor(dados, url, callbackPIX, callbackErro);
    }

    botaoGerarPIX.addEventListener('click', () => {
        botaoGerarPIX.disabled = true;
        gerarQRCode();
    });

    setParcelasDisabled(); // ao carregar a página
    
</script>
