const copiarParaAreaDeTrasnferencia = (valor, mensagem = 'Copiado para área de transferência com sucesso.') => {
    navigator.clipboard.writeText(valor)
        .then(() => {
            notificar(mensagem, 'sucesso');
        })
        .catch(erro => {
            console.error(erro);
        });
};
const gatilhosAreaDeTrasnferencia = document.querySelectorAll('[data-clipboard]');
if (gatilhosAreaDeTrasnferencia) {
    gatilhosAreaDeTrasnferencia.forEach(element => {
        element.addEventListener('click', () => {
            const targetName = element.getAttribute('data-clipboard');
            const targetElement = document.querySelector(`[name=${targetName}]`);
            const targetMessage = element.getAttribute('data-clipboard-message') || null;
            if (targetElement) {
                const clipboardValue = targetElement.value;
                copiarParaAreaDeTrasnferencia(clipboardValue, targetMessage);
            }
        })
    });
}
