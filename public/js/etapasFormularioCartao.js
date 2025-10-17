const forms = document.querySelectorAll('[data-etapa-form]');

const eIndicadorDaEtapaAtual = (indicador, atual) => {
    return indicador.getAttribute('data-etapa-indicador') === atual;
}
const ativaIndicador = (indicador) => {
    indicador.classList.add('bg-success');
    indicador.classList.add('text-white');
    indicador.classList.remove('bg-light');
}
const desativaIndicador = (indicador) => {
    indicador.classList.remove('bg-success');
    indicador.classList.remove('text-white');
    indicador.classList.add('bg-light');
}

const ajustaIndicador = (indicadores, atual) => {
    indicadores.forEach(indicador => {
        if (eIndicadorDaEtapaAtual(indicador, atual)) {
            ativaIndicador(indicador);
        } else {
            desativaIndicador(indicador);
        }
    })
}

const mostraEtapa = (etapa) => {
    etapa.classList.remove('d-none');
}
const escondeEtapa = (etapa) => {
    etapa.classList.add('d-none');
}
const eEtapaAtual = (etapa, atual) => {
    return etapa.getAttribute('data-etapa-conteudo') === atual;
}

const obtemEtapaAtual = (indicador) => {
    return parseInt(indicador.getAttribute('data-etapa-indicador'));
}
forms.forEach(form => {
    const indicadoresDeNavegacao = form.querySelectorAll('[data-etapa-indicador]');
    const etapaDeFormulario = form.querySelectorAll('[data-etapa-conteudo]');
    const indicadorEtapaInicial = indicadoresDeNavegacao[0].getAttribute('data-etapa-indicador');
    const etapaInicial = etapaDeFormulario[0];
    let etapaAtual = parseInt(indicadorEtapaInicial);
    mostraEtapa(etapaInicial);
    indicadoresDeNavegacao.forEach(indicador => {
        ajustaIndicador(indicadoresDeNavegacao, `${etapaAtual}`);
        indicador.addEventListener('click', (ev) => {
            etapaAtual = obtemEtapaAtual(ev.target);
            ajustaIndicador(indicadoresDeNavegacao, `${etapaAtual}`);
            etapaDeFormulario.forEach(etapa => {
                if (eEtapaAtual(etapa, `${etapaAtual}`)) {
                    mostraEtapa(etapa);
                } else {
                    escondeEtapa(etapa);
                }
            })
        })
    })
})
