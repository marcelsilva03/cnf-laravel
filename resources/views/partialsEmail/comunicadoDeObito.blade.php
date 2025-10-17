<?php
$solicitante = $template['dados_solicitante'];
$obito = $template['dados_do_obito'];
?>
<div>
    <p>Olá, {{ $solicitante['nome'] }}. Estamos enviando este email para confirmar o recebimento do comunicado de óbito
        vinculado ao email {{ $solicitante['email'] }} e telefone {{ $solicitante['telefone'] }}.</p>
    <p>Agora é com nossa equipe de moderadores que fará a avaliação necessária antes de oficializar e disponibilizar os
        dados do falecido no sistema do CNF.</p>
    <h2>Dados do óbito</h2>
    <ul>
        @foreach($obito as $campo => $valor)
            <li>
                <strong>{{ $campo }}</strong>
                <br/>{{ $valor }}
            </li>
        @endforeach
    </ul>
</div>
