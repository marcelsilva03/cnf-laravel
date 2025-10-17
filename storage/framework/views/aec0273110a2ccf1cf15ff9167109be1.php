<div class="container mb-4" data-aos="fade-up">
    <div class=" row justify-content-center">
        <div class=" card col-md-10 cardCnf">
            <div class="card-title  section-title-alert1">
                <i class="bi bi-exclamation-circle"></i><span class="alerta-titulo"> ATENÇÃO!</span>
            </div>
            <div class="card-body alerta-sub text-center">
                <p class="card-text">Este é um serviço de PESQUISA, <br>NÃO É UM PEDIDO DE CERTIDÃO.
                <div class="row justify-content-center align-items-center">
                    <div class="cardcusto pt-lg-1">
                        <!-- <h5>Data do falecimento: <?php echo e((new DateTime($solicitacao['sol_data_obito']))->format('d/m/Y')); ?></h5> -->
                        <h5>Solicitação nº <strong><?php echo e($solicitacao['sol_id']); ?></strong></h5>
                        <p>Custo da pesquisa:</p>
                        <h4><?php echo e($solicitacao['sol_valor']); ?></h4>
                    </div>
                </div>
                <hr>
                <h5>O pagamento é opcional e a pesquisa se inicia a partir da confirmação de pagamento.<br>
                    O resultado da pesquisa será enviado ao email <strong><?php echo e($solicitacao['sol_email_sol']); ?></strong> em
                    até <strong><?php echo e($solicitacao['prazo']); ?></strong>.</h5>
            </div>
        </div>
    </div>
</div>

<?php /**PATH /home/cnfbr/laravel_teste/resources/views/partials/alertaPagamentoPesquisa.blade.php ENDPATH**/ ?>