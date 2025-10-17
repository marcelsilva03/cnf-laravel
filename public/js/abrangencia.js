$(document).ready(function() {
    //Por default Estados e Cidades ficam ocultas.
    $('.estadual').hide();
    $('#localfal').closest('.col-md-3').removeClass('col-md-3').addClass('col-md-6');
    $('#ecivil').closest('.col-md-3').removeClass('col-md-3').addClass('col-md-6');
    // Função que será executada ao mudar o valor do campo 'abrangencia'
    $('#abrangencia').on('change', function() {
        var text = $('#abrangencia option:selected').text();

        if (text === 'Nacional') {
            // Limpar os valores dos selects de estado e cidade
            $('#estados').val('');
            $('#cidades').val('');
            // Esconde os campos de Estado e Cidade
            $('#estados').closest('.form-group').hide();
            $('#cidades').closest('.form-group').hide();

            // Altera as classes do Local do Falecimento e Estado Civil
            $('#localfal').closest('.col-md-3').removeClass('col-md-3').addClass('col-md-6');
            $('#ecivil').closest('.col-md-3').removeClass('col-md-3').addClass('col-md-6');
        } else {
            // Mostra novamente os campos de Estado e Cidade
            $('#estados').closest('.form-group').show();
            $('#cidades').closest('.form-group').show();

            // Restaura as classes do Local do Falecimento e Estado Civil
            $('#localfal').closest('.col-md-6').removeClass('col-md-6').addClass('col-md-3');
            $('#ecivil').closest('.col-md-6').removeClass('col-md-6').addClass('col-md-3');
        }
    });

    // Acionar a função no carregamento inicial para tratar o valor atual de "Abrangência"
    if ($('#abrangencia option:selected').text() === 'Nacional') {
        $('#estados').closest('.form-group').hide();
        $('#cidades').closest('.form-group').hide();
        $('#localfal').closest('.col-md-3').removeClass('col-md-3').addClass('col-md-6');
        $('#ecivil').closest('.col-md-3').removeClass('col-md-3').addClass('col-md-6');
    }
});
