<div class="section-title">
    <h4>PESQUISA DE REGISTRO DE ÓBITO COM INDICAÇÃO DE CARTÓRIO REGISTRADOR</h4>
</div>
<div class="container mb-4" data-aos="fade-up">
    <div class="row justify-content-center">
        <div class=" card col-md-6 cardCnf">
            <div class="card-title  section-title-alert1">
                <i class="bi bi-exclamation-circle"></i><span class="alerta-titulo"> ATENÇÃO!</span>
            </div>
            <div class="card-body alerta-sub text-center">
                <p class="card-text">LEIA ATENTAMENTE AS REGRAS ABAIXO.<br>
                    Este é um serviço de PESQUISA, <br>
                    NÃO É UM PEDIDO DE CERTIDÃO.</p>
            </div>
        </div>
    </div>
</div>
<div class="container align-items-center justify-content-center mb-5" data-aos="fade-up">
<!--     <div class="section-title">
        <h4>CUSTO DA PESQUISA:</h4>
    </div> -->
    <div class="d-flex flex-column flex-md-row justify-content-center align-items-center">
<!--         @foreach(array_slice($precos, 0, 1, true) as $periodo => $valor)
            <div class="pt-4 mx-4 my-3 bg-light text-center content" data-aos="fade-right">
                <h5 class="px-4">{{ $periodo }}</h5>
                <h4 class="text-danger keep-visible">{{ $valor }}</h4>
            </div>
        @endforeach -->
            <div class="pt-4 mx-4 my-3 bg-light text-center content" data-aos="fade-right">
                <h5 class="px-4">CUSTO DA PESQUISA:</h5>
                <h4 class="text-danger keep-visible">{{ $valor }}</h4>
            </div>
    </div>
</div>
<div class="container align-items-center justify-content-center mb-5" data-aos="fade-up">
    <div class="row">
        <div class="mb-3">
            <p class="regrasp">
                @if(!empty($falecido['nome']))
                    <strong>SÓ PEÇA A PESQUISA CASO TENHA CERTEZA DE QUE A PESSOA QUE PROCURA É FALECIDA.</strong> Se suspeitar de erro em nosso cadastro, por favor entre em contato conosco para esclarecermos qualquer dúvida.<br><br>
                @endif                   
                    <strong>Você receberá em seu e-mail o nome do cartório que registrou o óbito, os meios de contato do mesmo (email e telefone) e dados do registro (números do livro, folha e termo).</strong> No mesmo email informaremos o valor para solicitar a segunda via da certidão, caso desejar que intermediemos esse serviço também.<br><br>
                @if(!empty($falecido['nome']))
                    <strong>Alguns registros que aparecem em nosso cadastro estão incompletos,</strong>
                    faltando nome de cartório, números de livro, folha e termo. Por isso, o prazo para concluir a pesquisa
                    pode levar <strong>até 30 dias, para completar as informações.</strong><br><br>
                @else
                    <strong>Prazo para conclusão da pesquisa: Até 30 dias.</strong><br><br>
                @endif 
                Preencha o máximo de dados possíveis, para descartarmos eventuais homônimos e
                informarmos o registro correto. 
                @if(!empty($falecido['nome'])) 
                    Eventualmente, os dados apresentados em nosso site, como data de falecimento ou cidade, podem sofrer alterações após serem conferidos.</p>
                @endif
        </div>
    </div>
</div>
