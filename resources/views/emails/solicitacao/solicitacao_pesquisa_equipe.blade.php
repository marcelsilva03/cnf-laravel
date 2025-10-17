<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nova Solicitação Recebida</title>
</head>
<body style="font-family: Arial, sans-serif; font-size: 14px;">
    <p><strong>Solicitante:</strong> {{ $sol->sol_nome_sol }}</p>
    <p><strong>Telefone:</strong> {{ $sol->sol_tel_sol }}</p>
    <p><strong>E-mail:</strong> {{ $sol->sol_email_sol }}</p>
    <p><strong>Solicitação:</strong> {{ $sol->sol_id }}</p>
    <hr>
    <p><strong>Nome a pesquisar:</strong> {{ $sol->sol_nome_fal }}</p>
    <p><strong>CPF:</strong> {{ $sol->sol_cpf_fal }}</p>
    <p><strong>RG:</strong> {{ $sol->sol_rg_fal }}</p>
    <p><strong>Título de eleitor:</strong> {{ $sol->sol_titulo_eleitor }}</p>
    <p><strong>Nome do pai:</strong> {{ $sol->sol_nome_pai_fal }}</p>
    <p><strong>Nome da mãe:</strong> {{ $sol->sol_nome_mae_fal }}</p>
    <p><strong>Data de nascimento:</strong> {{ $nasc ?? '-' }}</p>
    <p><strong>Data de falecimento:</strong> {{ $obito ?? '-' }}</p>
    <p><strong>Estado:</strong> {{ $local->ees_sigla }}</p>
    <p><strong>Cidade:</strong> {{ $local->ecd_nome }}</p>
    <p><strong>Estado civil:</strong> {{ $sol->sol_estado_civil }}</p>
    <p><strong>Local de óbito:</strong> {{ $sol->sol_local_obito_tipo }}</p>
    <p><strong>Informações adicionais:</strong><br>{{ $sol->sol_obs }}</p>
    <p><strong>Charge_Id:</strong> {{ $sol->pag_token_transacao }}</p>
    <p><a href="https://falecidosnobrasil.org.br/adminformacoes.php?id={{ $sol->sol_id }}&redirect=1">Ir para Solicitação</a></p>
</body>
</html>