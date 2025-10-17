<!-- resources/views/emails/contato.blade.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>{{ $dados['assunto'] }}</title>
</head>
<body>
    <h1>{{ $dados['titulo'] }}</h1>
    <p><strong>Nome:</strong> {{ $dados['nome'] }}</p>
    <p><strong>Email:</strong> {{ $dados['email'] }}</p>
    <p><strong>Telefone:</strong> {{ $dados['telefone'] }}</p>
    <p><strong>Mensagem:</strong> {{ $dados['mensagem'] }}</p>
</body>
</html>
