const atualizaContador = (textarea, contagem) => {
    const textareaName = textarea.getAttribute('name');
    const contador = document.querySelector(`[data-counter-for=${textareaName}]`);
    if (contador) {
        contador.innerText = contagem;
    }
}
const obtemContagem = (textarea, maximo) => {
    const contagem = textarea.value.length;
    return `${contagem}/${maximo}`;
}

const tarefaContador = (textarea, maximo) => {
    return () => {
        const textoContador = obtemContagem(textarea, maximo);
        atualizaContador(textarea, textoContador);
    }
}
const textareas = document.querySelectorAll("textarea");
if (textareas) {
    textareas.forEach((textarea) => {
        const maximo = textarea.getAttribute('maxlength');
        textarea.addEventListener("input", tarefaContador(textarea, maximo));
        textarea.addEventListener("paste", tarefaContador(textarea, maximo));
        textarea.addEventListener("change", tarefaContador(textarea, maximo));
    })
}
