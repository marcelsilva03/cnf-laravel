$(document).ready(function() {
    // Função para validar CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, ''); // Remove caracteres não numéricos

        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
            return false; // CPF com todos os números iguais é inválido
        }

        let soma = 0;
        let resto;

        // Valida primeiro dígito
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        resto = soma % 11;
        if (resto < 2 ? resto = 0 : resto = 11 - resto);
        if (parseInt(cpf.charAt(9)) !== resto) {
            return false;
        }

        // Valida segundo dígito
        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = soma % 11;
        if (resto < 2 ? resto = 0 : resto = 11 - resto);
        if (parseInt(cpf.charAt(10)) !== resto) {
            return false;
        }

        return true;
    }

    // Aplicando a validação quando o campo for perdido (blur) ou ao tentar submeter o formulário
    $('form').on('submit', function(e) {
        // Valida todos os campos com data-type="cpf"
        $('[data-type="cpf"]').each(function() {
            var cpf = $(this).val();
            if (!validarCPF(cpf)) {
                e.preventDefault(); // Impede o envio do formulário
                $('#cpf-error-message').show(); // Exibe a mensagem de erro abaixo do campo
                $(this).addClass('is-invalid'); // Adiciona a classe de erro ao campo
                $(this).focus(); // Foca no campo para o usuário corrigir
            }
        });
    });

    // Validação ao sair do campo
    $('[data-type="cpf"]').on('blur', function() {
        var cpf = $(this).val();
        if (!validarCPF(cpf)) {
            $('#cpf-error-message').show(); // Exibe a mensagem de erro
            $(this).addClass('is-invalid'); // Adiciona a classe de erro ao campo
        } else {
            $('#cpf-error-message').hide(); // Esconde a mensagem de erro se o CPF for válido
            $(this).removeClass('is-invalid'); // Remove a classe de erro do campo
        }
    });
});
