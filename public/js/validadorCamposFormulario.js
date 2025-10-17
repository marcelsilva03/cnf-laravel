document.querySelectorAll('form').forEach(form => {
    const button = form.querySelector('[type=submit]');
    if (button) {
        button.addEventListener('click', () => {
            const camposAindaNecessarios = [];
            const formData = new FormData(form);
            formData.forEach((valor, chave) => {
                const formField = form.querySelector(`[name=${chave}]`);
                if (formField.getAttribute('type') !== 'hidden'
                    && !/g-recaptcha/i.test(chave)
                    && chave !== '_token'
                ) {
                    const elementoPai = formField.parentElement;
                    const label = elementoPai.querySelector('label');
                    let texto = label.innerHTML;
                    texto = texto.replace(':', '');

                    if (valor === '' && formField.hasAttribute('required')) {
                        camposAindaNecessarios.push(texto);
                        label.classList.add('text-danger');
                        formField.classList.add('border-danger');
                        formField.classList.add('text-danger');
                    } else {
                        label.classList.remove('text-danger');
                        formField.classList.remove('border-danger');
                        formField.classList.remove('text-danger');
                    }
                }
            });
            if (camposAindaNecessarios.length > 0) {
                const campos = camposAindaNecessarios.join(', ');
                const mensagem = camposAindaNecessarios.length > 1
                    ? `Os campos ${campos} devem ser preenchidos.`
                    : `O campo ${campos} deve ser preenchido.`;
                notificar(mensagem, 'erro');
            }
        });
    }
});
