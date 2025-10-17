$(document).ready(function() {
     // Função genérica para validar nome e sobrenome
     $('[data-type="nome_sobrenome"]').on('blur', function() {
        var nomeSobrenome = $(this).val();
        var errorMessage = $(this).next('.text-danger'); // Seleciona o próximo elemento com classe 'text-danger' (onde a mensagem de erro será exibida)
        
        // Regular expression para verificar se há pelo menos 2 palavras separadas por espaço
        var regex = /^[a-zA-ZÀ-ÿ]+\s+[a-zA-ZÀ-ÿ]+$/;

        if (regex.test(nomeSobrenome)) {
            errorMessage.text(""); 
            errorMessage.hide();   
            $(this).removeClass('is-invalid'); 
        } else {
            errorMessage.text("Por favor, digite seu nome e sobrenome.");
            errorMessage.show(); 
            $(this).addClass('is-invalid'); 
        }
    });

    $('[data-type="cpf"]').on('blur', function() {
        var cpf = $(this).val();
        var errorMessage = $(this).next('.text-danger');
        if (!validarCPF(cpf)) {
            errorMessage.show(); 
            $(this).addClass('is-invalid');
        } else {
            errorMessage.hide(); 
            $(this).removeClass('is-invalid');
        }
    });

    $('#telsol').on('input', function() {
        var telefone = $(this).val();

        // Remover caracteres não numéricos (apenas números)
        telefone = telefone.replace(/\D/g, '');

        // Validar se o telefone tem o formato esperado: (DDD) 000000000
        var regexDDD = /^([1-9]{2})\d{8,9}$/;  // DDD válido: 2 dígitos entre 1-9 + 8 ou 9 dígitos
        var regexZeros = /^0{10,11}$/; // Verifica se o número tem só zeros
        var errorElement = $(this).next('.text-danger')
        var isValid = true;

        // Verificar se o DDD é válido (não pode ser "00", "99", etc.)
        if (!regexDDD.test(telefone) || regexZeros.test(telefone)) {
            isValid = false;
        }

        // Mostrar ou esconder a mensagem de erro
        if (isValid) {
            errorElement.hide();
        } else {
            errorElement.show();
        }
    });

    $('#emailsol').on('input', function() {
        var email = $(this).val();

        // Expressão regular para validar e-mails
        var regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
        var errorElement = $('#email-error');
        var isValid = regexEmail.test(email); // Verifica se o e-mail é válido

        // Mostrar ou esconder a mensagem de erro
        if (isValid) {
            errorElement.hide();
        } else {
            errorElement.show();
        }
    });

    // Validação no envio do formulário
    $('form').on('submit', function(event) {
        var telefone = $('#telsol').val().replace(/\D/g, '');
        var regexDDD = /^([1-9]{2})\d{8,9}$/;
        var regexZeros = /^0{10,11}$/;
        var errorElementTelefone= $(this).next('.text-danger')

        if (!regexDDD.test(telefone) || regexZeros.test(telefone)) {
            errorElementTelefone.show();
            event.preventDefault();
        }

        var email = $('#emailsol').val();
        var regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        var errorElementEmail = $('#email-error');

        if (!regexEmail.test(email)) {
            errorElementEmail.show();
            event.preventDefault(); 
        }

        $('[data-type="cpf"]').each(function() {
            var cpf = $(this).val();
            if (!validarCPF(cpf)) {
                e.preventDefault(); 
                $('#cpf-error-message').show(); 
                $(this).addClass('is-invalid'); 
                $(this).focus(); 
            }
        });

    });

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
});
