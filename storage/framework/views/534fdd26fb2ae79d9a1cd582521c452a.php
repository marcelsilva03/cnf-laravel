<!-- resources/views/emails/contato.blade.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($dados['assunto']); ?></title>
</head>
<body>
    <h1><?php echo e($dados['titulo']); ?></h1>
    <p><strong>Nome:</strong> <?php echo e($dados['nome']); ?></p>
    <p><strong>Email:</strong> <?php echo e($dados['email']); ?></p>
    <p><strong>Telefone:</strong> <?php echo e($dados['telefone']); ?></p>
    <p><strong>Mensagem:</strong> <?php echo e($dados['mensagem']); ?></p>
</body>
</html>
<?php /**PATH /home/cnfbr/laravel_teste/resources/views/emails/contato.blade.php ENDPATH**/ ?>