$(document).ready(function() {
    // Função genérica para validar nome e sobrenome
    $('[data-type="nome_sobrenome"]').on('blur', function() {
        var nomeSobrenome = $(this).val();
        var errorMessage = $(this).next('.text-danger'); // Seleciona o próximo elemento com classe 'text-danger' (onde a mensagem de erro será exibida)
        
        // Regular expression para verificar se há pelo menos 2 palavras separadas por espaço
        var regex = /^[a-zA-ZÀ-ÿ]+\s+[a-zA-ZÀ-ÿ]+$/;

        if (regex.test(nomeSobrenome)) {
            errorMessage.text(""); // Limpa a mensagem de erro
            errorMessage.hide();   // Esconde a mensagem de erro
            $(this).removeClass('is-invalid'); // Remove a classe de erro
        } else {
            errorMessage.text("Por favor, digite seu nome e sobrenome.");
            errorMessage.show(); // Exibe a mensagem de erro
            $(this).addClass('is-invalid'); // Adiciona a classe de erro
        }
    });
});
