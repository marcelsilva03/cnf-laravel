<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmação de Solicitação de Pesquisa</title>
</head>
<body style="font-family: Arial, sans-serif; font-size: 16px;">
    <p>Prezado(a) {{ $sol->sol_nome_sol }},</p>
    <p>Recebemos sua solicitação de pesquisa de óbito conforme os dados abaixo:</p>
    <ul style="font-size: 14px;">
        <li><strong>Solicitação:</strong> {{ $sol->sol_id }}</li>
        <li><strong>Nome a pesquisar:</strong> {{ $sol->sol_nome_fal }}</li>
        <li><strong>CPF:</strong> {{ $sol->sol_cpf_fal }}</li>
        <li><strong>RG:</strong> {{ $sol->sol_rg_fal }}</li>
        <li><strong>Título de eleitor:</strong> {{ $sol->sol_titulo_eleitor }}</li>
        <li><strong>Nome do pai:</strong> {{ $sol->sol_nome_pai_fal }}</li>
        <li><strong>Nome da mãe:</strong> {{ $sol->sol_nome_mae_fal }}</li>
        <li><strong>Data de nascimento:</strong> {{ $nasc ?? '-' }}</li>
        <li><strong>Data de falecimento:</strong> {{ $obito ?? '-' }}</li>
        <li><strong>Estado:</strong> {{ $local->ees_sigla }}</li>
        <li><strong>Cidade:</strong> {{ $local->ecd_nome }}</li>
        <li><strong>Estado civil:</strong> {{ $sol->sol_estado_civil }}</li>
        <li><strong>Local de óbito:</strong> {{ $sol->sol_local_obito_tipo }}</li>
        <li><strong>Informações adicionais:</strong> {{ $sol->sol_obs }}</li>
    </ul>
    <p>Esta pesquisa destina-se a informar o nome do cartório que registrou o óbito, os meios de contato do mesmo (e-mail e telefone) e os dados do registro como os números do livro, folha e termo.</p>
    <p>Ao mesmo tempo, lhe informaremos o valor para a solicitação da segunda via da certidão, caso deseje a intermediação desse serviço por nossa equipe.</p>
    <p>O prazo para a conclusão da pesquisa é de no máximo 30 (trinta) dias e caso necessite corrigir ou adicionar alguma informação relevante à mesma, por favor entre em contato através do e-mail pesquisa@falecidosnobrasil.org.br.</p>
    <p>Agradecemos por utilizar nossos serviços de pesquisa e estaremos à disposição para esclarecer qualquer dúvida.</p>
    <p>Atenciosamente,<br>Equipe de Pesquisa<br>CNF Brasil - Cadastro Nacional de Falecidos<br>www.falecidosnobrasil.org.br - pesquisa@falecidosnobrasil.org.br</p>
</body>
</html>