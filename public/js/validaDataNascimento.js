$(document).ready(function() {
    // Função genérica para validar data de nascimento
    $('[data-type="data-nascimento"]').on('blur', function() {
        var dataNascimento = $(this).val();
        var errorMessage = $(this).next('.text-danger'); // Seleciona o próximo elemento com classe 'text-danger' (onde a mensagem de erro será exibida)
        
        // Regular expression para verificar o formato da data (DD/MM/YYYY) ou (YYYY-MM-DD)
        var regex = /^(?:\d{2}\/\d{2}\/\d{4}|\d{4}-\d{2}-\d{2})$/;
        if (regex.test(dataNascimento)) {
            console.log(dataNascimento.includes('/'));
            var dateOfBirth;
            
            // Verifica se o formato é DD/MM/YYYY ou YYYY-MM-DD
            if (dataNascimento.includes('/')) {
                // Formato DD/MM/YYYY
                var parts = dataNascimento.split('/');
                var day = parseInt(parts[0], 10);
                var month = parseInt(parts[1], 10) - 1; // Mês começa de 0 no JavaScript
                var year = parseInt(parts[2], 10);
                dateOfBirth = new Date(year, month, day);
            } else {
                // Formato YYYY-MM-DD
                dateOfBirth = new Date(dataNascimento); // O JavaScript pode lidar diretamente com o formato YYYY-MM-DD
            }

            var today = new Date();
             
            // Ajuste: Comparando data sem levar em conta o horário
            var dateOfBirthString = dateOfBirth.toISOString().split('T')[0];  // Formato ISO sem hora
            var todayString = today.toISOString().split('T')[0];  // Formato ISO sem hora

            // Calculando a idade a partir da data de nascimento
            var age = today.getFullYear() - dateOfBirth.getFullYear();
            var m = today.getMonth() - dateOfBirth.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dateOfBirth.getDate())) {
                age--;
            }

            // Verifica se a idade é maior ou igual a 18 anos
            if (age >= 18) {
                errorMessage.text(""); // Limpa a mensagem de erro
                errorMessage.hide();   // Esconde a mensagem de erro
                $(this).removeClass('is-invalid'); // Remove a classe de erro
            } else {
                errorMessage.text("Você deve ser maior de 18 anos para realizar o pagamento.");
                errorMessage.show(); // Exibe a mensagem de erro
                $(this).addClass('is-invalid'); // Adiciona a classe de erro
            }
        } else {
            errorMessage.text("Formato de data inválido. Use DD/MM/YYYY.");
            errorMessage.show(); // Exibe a mensagem de erro
            $(this).addClass('is-invalid'); // Adiciona a classe de erro
        }
    });
});
