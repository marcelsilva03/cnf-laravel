$(document).ready(function() {
    // Função para validar o e-mail
    function validarEmail(campo) {
        var email = campo.val();
        var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;  // Expressão regular para validar o formato do e-mail

        // Verifica se o e-mail corresponde ao formato
        return regex.test(email);
    }

    // Validação ao sair do campo (blur)
    $('#email').on('blur', function() {
        var campo = $(this);
        var valido = validarEmail(campo);  // Valida o e-mail

        if (!valido) {
            // Exibe a mensagem de erro
            campo.addClass('is-invalid');
            campo.siblings('.invalid-feedback').text('Por favor, insira um e-mail válido.');
        } else {
            // Limpa a mensagem de erro
            campo.removeClass('is-invalid');
            campo.siblings('.invalid-feedback').text('');
        }
    });

    // Validação ao enviar o formulário
    $('form').on('submit', function(e) {
        var valido = true;

        // Verifica se o e-mail é válido
        var campoEmail = $('#email');
        if (!validarEmail(campoEmail)) {
            valido = false;
            campoEmail.addClass('is-invalid');
            campoEmail.siblings('.invalid-feedback').text('Por favor, insira um e-mail válido.');
        } else {
            campoEmail.removeClass('is-invalid');
            campoEmail.siblings('.invalid-feedback').text('');
        }

        // Impede o envio do formulário se o e-mail for inválido
        if (!valido) {
            e.preventDefault();
        }
    });
});
