$(document).ready(function() {
    // Função para validar o número de WhatsApp
    function validarWhatsapp(campo) {
        var whatsapp = campo.val();
        var regex = /^\(\d{2}\)\s\d{5}\d{4}$/;  // Expressão regular para validar o formato (00) 000000000

        // Verifica se o WhatsApp corresponde ao formato
        return regex.test(whatsapp);
    }

    // Validação ao sair do campo (blur)
    $('#whatsapp').on('blur', function() {
        var campo = $(this);
        var valido = validarWhatsapp(campo);  // Valida o WhatsApp

        if (!valido) {
            // Exibe a mensagem de erro
            campo.addClass('is-invalid');
            campo.siblings('.invalid-feedback').text('Por favor, insira um número de WhatsApp válido no formato (00) 000000000.');
        } else {
            // Limpa a mensagem de erro
            campo.removeClass('is-invalid');
            campo.siblings('.invalid-feedback').text('');
        }
    });

    // Validação ao enviar o formulário
    $('form').on('submit', function(e) {
        var valido = true;

        // Verifica se o WhatsApp é válido
        var campoWhatsapp = $('#whatsapp');
        if (!validarWhatsapp(campoWhatsapp)) {
            valido = false;
            campoWhatsapp.addClass('is-invalid');
            campoWhatsapp.siblings('.invalid-feedback').text('Por favor, insira um número de WhatsApp válido no formato (XX) XXXXX-XXXX.');
        } else {
            campoWhatsapp.removeClass('is-invalid');
            campoWhatsapp.siblings('.invalid-feedback').text('');
        }

        // Impede o envio do formulário se o WhatsApp for inválido
        if (!valido) {
            e.preventDefault();
        }
    });
});
