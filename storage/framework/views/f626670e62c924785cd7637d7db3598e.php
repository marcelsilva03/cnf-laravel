<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmação de Solicitação de Pesquisa</title>
</head>
<body style="font-family: Arial, sans-serif; font-size: 16px;">
    <p>Prezado(a) <?php echo e($sol->sol_nome_sol); ?>,</p>
    <p>Recebemos sua solicitação de pesquisa de óbito conforme os dados abaixo:</p>
    <ul style="font-size: 14px;">
        <li><strong>Solicitação:</strong> <?php echo e($sol->sol_id); ?></li>
        <li><strong>Nome a pesquisar:</strong> <?php echo e($sol->sol_nome_fal); ?></li>
        <li><strong>CPF:</strong> <?php echo e($sol->sol_cpf_fal); ?></li>
        <li><strong>RG:</strong> <?php echo e($sol->sol_rg_fal); ?></li>
        <li><strong>Título de eleitor:</strong> <?php echo e($sol->sol_titulo_eleitor); ?></li>
        <li><strong>Nome do pai:</strong> <?php echo e($sol->sol_nome_pai_fal); ?></li>
        <li><strong>Nome da mãe:</strong> <?php echo e($sol->sol_nome_mae_fal); ?></li>
        <li><strong>Data de nascimento:</strong> <?php echo e($nasc ?? '-'); ?></li>
        <li><strong>Data de falecimento:</strong> <?php echo e($obito ?? '-'); ?></li>
        <li><strong>Estado:</strong> <?php echo e($local->ees_sigla); ?></li>
        <li><strong>Cidade:</strong> <?php echo e($local->ecd_nome); ?></li>
        <li><strong>Estado civil:</strong> <?php echo e($sol->sol_estado_civil); ?></li>
        <li><strong>Local de óbito:</strong> <?php echo e($sol->sol_local_obito_tipo); ?></li>
        <li><strong>Informações adicionais:</strong> <?php echo e($sol->sol_obs); ?></li>
    </ul>
    <p>Esta pesquisa destina-se a informar o nome do cartório que registrou o óbito, os meios de contato do mesmo (e-mail e telefone) e os dados do registro como os números do livro, folha e termo.</p>
    <p>Ao mesmo tempo, lhe informaremos o valor para a solicitação da segunda via da certidão, caso deseje a intermediação desse serviço por nossa equipe.</p>
    <p>O prazo para a conclusão da pesquisa é de no máximo 30 (trinta) dias e caso necessite corrigir ou adicionar alguma informação relevante à mesma, por favor entre em contato através do e-mail pesquisa@falecidosnobrasil.org.br.</p>
    <p>Agradecemos por utilizar nossos serviços de pesquisa e estaremos à disposição para esclarecer qualquer dúvida.</p>
    <p>Atenciosamente,<br>Equipe de Pesquisa<br>CNF Brasil - Cadastro Nacional de Falecidos<br>www.falecidosnobrasil.org.br - pesquisa@falecidosnobrasil.org.br</p>
</body>
</html><?php /**PATH /home/cnfbr/laravel_teste/resources/views/emails/solicitacao/solicitacao_pesquisa_usuario.blade.php ENDPATH**/ ?>