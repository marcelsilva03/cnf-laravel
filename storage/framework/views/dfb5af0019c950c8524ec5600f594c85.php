<?php $__env->startSection('title', 'Confirmação de Comunicado de Óbito'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <h2><?php echo e($assunto); ?></h2>
        <p>Prezado(a) <strong><?php echo e($dados_solicitante['nome']); ?></strong>,</p>
        <p>Recebemos o seu comunicado de óbito referente ao seguinte falecido:</p>
        <ul>
            <li><strong>Nome do Falecido:</strong> <?php echo e($dados_do_obito['nome']); ?></li>
            <li><strong>Data do Óbito:</strong> <?php echo e($dados_do_obito['data_de_obito']); ?></li>
        </ul>
        <p>Entraremos em contato caso sejam necessárias informações adicionais. Caso tenha dúvidas, por favor, não
            hesite em
            nos contatar.</p>
        <p>Atenciosamente,</p>
        <p><strong>Equipe de Atendimento</strong></p>
        <hr>
        <footer>
            Este é um email automático. Por favor, não responda a este endereço.
        </footer>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.email', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cnfbr/laravel_teste/resources/views/emails/comunicadodeobito/recebido.blade.php ENDPATH**/ ?>