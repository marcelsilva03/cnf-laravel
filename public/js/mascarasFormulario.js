const aplicaMascaraTelefone = (el) => {
    el.value = el.value
        .replace(/[^\d]/g, '')
        .replace(/(\d{2})(\d{1,5})?(\d+)?/g, (_, p1, p2, p3) => {
            if (p3) {
                return `(${p1}) ${p2}-${p3}`;
            }
            if (p2) {
                return `(${p1}) ${p2}`;
            }
            return `(${p1}`;
        });
}

const aplicaMascaraCep = (el) => {
    el.value = el.value
        .replace(/[^\d]/g, '')
        .replace(/(\d{5})(\d{1,3})?/g, (_, p1, p2) => {
            if (p2) {
                return `${p1}-${p2}`;
            }
            return p1;
        });
}

const aplicaMascaraCpf = (el) => {
    el.value = el.value
        .replace(/[^\d]/g, '')
        .replace(/(\d{3})(\d{1,3})?(\d{1,3})?(\d{1,2})?/, (_, p1, p2, p3, p4) => {
            if (p4) {
                return `${p1}.${p2}.${p3}-${p4}`;
            }
            if (p3) {
                return `${p1}.${p2}.${p3}`;
            }
            if (p2) {
                return `${p1}.${p2}`;
            }
            return `${p1}`;
        });
}

const aplicaMascaraTituloEleitor = (el) => {
    el.value = el.value
        .replace(/\D/g, '')
        .replace(/(.{4})/g, '$1 ')
        .trim();
}

const dataTypes = {
    'tel': { regra: aplicaMascaraTelefone, tamanho: 15 },
    'cep': { regra: aplicaMascaraCep, tamanho: 9 },
    'cpf': { regra: aplicaMascaraCpf, tamanho: 14 },
    'eleitor': { regra: aplicaMascaraTituloEleitor, tamanho: 14 },
}
document.querySelectorAll('[data-type]').forEach(campo => {
    const dataType = campo.getAttribute('data-type');
    if (dataTypes.hasOwnProperty(dataType)) {
        const tipo = dataTypes[dataType];
        campo.setAttribute('maxlength', tipo.tamanho);
        if (campo.value) {
            tipo.regra(campo);
        }
        campo.addEventListener('input', (ev) => {
            tipo.regra(ev.target);
        });
    }
});
