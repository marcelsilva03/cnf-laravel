function mostratNotificacao () {
    const caixaDeNotificacao = document.querySelector('#caixa-de-notificacao');
    caixaDeNotificacao.classList.add('notificao-aparente');
}
function escondeNotificacao() {
    const caixaDeNotificacao = document.querySelector('#caixa-de-notificacao');
    const iconeDaNotificacao = document.querySelector('#icone-notificacao i');
    caixaDeNotificacao.classList.remove('notificao-aparente');
    setTimeout(() => {
        caixaDeNotificacao.classList.remove('alert-success', 'alert-danger', 'alert-warning', 'alert-info');
        iconeDaNotificacao.classList.remove('fa-circle-check', 'fa-circle-xmark', 'fa-circle-exclamation', 'fa-circle-info');
    }, 10000);

}

function defineMensagem(mensagem) {
    const mensagemDaNotificacao = document.querySelector('#mensagem-notificacao');
    mensagemDaNotificacao.innerHTML = mensagem;
}

function defineTipo(tipo) {
    const tipos = {
        'sucesso': {
            icone: 'fa-circle-check',
            cor: 'alert-success',
        },
        'erro': {
            icone: 'fa-circle-xmark',
            cor: 'alert-danger',
        },
        'aviso': {
            icone: 'fa-circle-exclamation',
            cor: 'alert-warning',
        },
        'informe': {
            icone: 'fa-circle-info',
            cor: 'alert-info',
        }
    }
    const contexto = tipos[tipo];
    const caixaDeNotificacao = document.querySelector('#caixa-de-notificacao');
    const iconeDaNotificacao = document.querySelector('#icone-notificacao i');
    caixaDeNotificacao.classList.add(contexto.cor);
    iconeDaNotificacao.classList.add(contexto.icone);
}

function notificar(mensagem, tipo = 'informe') {
    defineMensagem(mensagem);
    defineTipo(tipo);
    mostratNotificacao();
    setTimeout(escondeNotificacao, 2000);
}
