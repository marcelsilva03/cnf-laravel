const botaoDeEnvioEscolha = document.querySelector('#envio-opcao-fundo');
botaoDeEnvioEscolha.addEventListener('click', function() {
    const opcaoEscolhida = document.querySelector('[name=opcaoImagem]:checked');
    if (opcaoEscolhida) {
        const valor = opcaoEscolhida.value;
        document.querySelector('#opcaoImagemFundo').value = valor;
        document.querySelector('#modal-dismiss').click();
    } else {
        notificar('A opção de imagem de fundo deve ser escolhida.', 'erro');
    }
})
