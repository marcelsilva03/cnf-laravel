@extends('layouts.email')

@section('title', '{{ $dados["titulo"] }}')

@section('content')
    <div class="container">
        <h2>{{ $dados["assunto"] }}</h2>
        <p>Prezado(a) <strong>{{ $dados["dados_solicitante"]["nome"] }}</strong>,</p>
        <p>Informamos que o comunicado de óbito foi enviado com sucesso e está aguardando aprovação por parte da
            moderação. Abaixo você encontra os detalhes do comunicado:</p>
        <h4>Dados do Solicitante:</h4>
        <ul>
            <li><strong>Nome:</strong> {{ $dados["dados_solicitante"]["nome"] }}</li>
            <li><strong>Email:</strong> {{ $dados["dados_solicitante"]["email"] }}</li>
            <li><strong>Telefone:</strong> {{ $dados["dados_solicitante"]["telefone"] }}</li>
        </ul>
        <h4>Dados do Falecido:</h4>
        <ul>
            @foreach($dados["dados_do_obito"] as $label => $value)
                <li><strong>{{ $label }}:</strong> {{ $value }}</li>
            @endforeach
        </ul>
        <p>Caso tenha dúvidas, por favor, não hesite em nos contatar.</p>
        <p>Atenciosamente,</p>
        <p><strong>Equipe de Atendimento</strong></p>
        <hr>
        <footer>
            Este é um email automático. Por favor, não responda a este endereço.
        </footer>
    </div>
@endsection
