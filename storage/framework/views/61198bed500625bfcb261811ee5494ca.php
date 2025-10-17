<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nova Solicitação Recebida</title>
</head>
<body style="font-family: Arial, sans-serif; font-size: 14px;">
    <p><strong>Solicitante:</strong> <?php echo e($sol->sol_nome_sol); ?></p>
    <p><strong>Telefone:</strong> <?php echo e($sol->sol_tel_sol); ?></p>
    <p><strong>E-mail:</strong> <?php echo e($sol->sol_email_sol); ?></p>
    <p><strong>Solicitação:</strong> <?php echo e($sol->sol_id); ?></p>
    <hr>
    <p><strong>Nome a pesquisar:</strong> <?php echo e($sol->sol_nome_fal); ?></p>
    <p><strong>CPF:</strong> <?php echo e($sol->sol_cpf_fal); ?></p>
    <p><strong>RG:</strong> <?php echo e($sol->sol_rg_fal); ?></p>
    <p><strong>Título de eleitor:</strong> <?php echo e($sol->sol_titulo_eleitor); ?></p>
    <p><strong>Nome do pai:</strong> <?php echo e($sol->sol_nome_pai_fal); ?></p>
    <p><strong>Nome da mãe:</strong> <?php echo e($sol->sol_nome_mae_fal); ?></p>
    <p><strong>Data de nascimento:</strong> <?php echo e($nasc ?? '-'); ?></p>
    <p><strong>Data de falecimento:</strong> <?php echo e($obito ?? '-'); ?></p>
    <p><strong>Estado:</strong> <?php echo e($local->ees_sigla); ?></p>
    <p><strong>Cidade:</strong> <?php echo e($local->ecd_nome); ?></p>
    <p><strong>Estado civil:</strong> <?php echo e($sol->sol_estado_civil); ?></p>
    <p><strong>Local de óbito:</strong> <?php echo e($sol->sol_local_obito_tipo); ?></p>
    <p><strong>Informações adicionais:</strong><br><?php echo e($sol->sol_obs); ?></p>
    <p><strong>Charge_Id:</strong> <?php echo e($sol->pag_token_transacao); ?></p>
    <p><a href="https://falecidosnobrasil.org.br/adminformacoes.php?id=<?php echo e($sol->sol_id); ?>&redirect=1">Ir para Solicitação</a></p>
</body>
</html><?php /**PATH /home/cnfbr/laravel_teste/resources/views/emails/solicitacao/solicitacao_pesquisa_equipe.blade.php ENDPATH**/ ?>