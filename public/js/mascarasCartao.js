const aplicaMascaraNumeroCartao = (ev) => {
    ev.target.value = ev.target.value
        .replace(/[^\d]/g, '')
        .replace(/(.{4})/g, '$1 ')
        .trim();
}

const aplicaMascaraValidadeCartao = (ev) => {
    ev.target.value = ev.target.value
        .replace(/[^\d]/g, '')
        .replace(/(\d{2})(\d{2})?/, (_, p1, p2) => {
            return p2 ? `${p1}/${p2}` : p1;
        });
}

const aplicaMascaraCVVCartao = (ev) => {
    ev.target.value = ev.target.value.replace(/[^\d]/g, '');
}

const camposNumeroCartao = document.querySelectorAll('[data-type=card-number]');
if (camposNumeroCartao) {
    camposNumeroCartao.forEach(campo => {
        campo.addEventListener('input', aplicaMascaraNumeroCartao)
    });
}

const camposValidadeCartao = document.querySelectorAll('[data-type=card-expiration-date]');
if (camposValidadeCartao) {
    camposValidadeCartao.forEach(campo => {
        campo.addEventListener('input', aplicaMascaraValidadeCartao)
    });
}

const camposCVVCartao = document.querySelectorAll('[data-type=card-cvv]');
if (camposCVVCartao) {
    camposCVVCartao.forEach(campo => {
        campo.addEventListener('input', aplicaMascaraCVVCartao)
    });
}
