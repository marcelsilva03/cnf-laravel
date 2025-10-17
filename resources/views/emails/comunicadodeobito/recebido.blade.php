@extends('layouts.email')

@section('title', 'Confirmação de Comunicado de Óbito')

@section('content')
    <div class="container">
        <h2>{{ $assunto }}</h2>
        <p>Prezado(a) <strong>{{ $dados_solicitante['nome'] }}</strong>,</p>
        <p>Recebemos o seu comunicado de óbito referente ao seguinte falecido:</p>
        <ul>
            <li><strong>Nome do Falecido:</strong> {{ $dados_do_obito['nome'] }}</li>
            <li><strong>Data do Óbito:</strong> {{ $dados_do_obito['data_de_obito'] }}</li>
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
@endsection
