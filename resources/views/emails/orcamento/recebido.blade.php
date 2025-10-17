@extends('layouts.email')

@section('content')
    <div class="container">
        <div class="header">
            <h1>Informações Recebidas</h1>
        </div>
        <div class="content">
            <p>Prezado(a) {{ $dados['nome'] ?? 'usuário' }},</p>
            <p>Recebemos suas informações com os seguintes detalhes:</p>
            <p><strong>Nome:</strong> {{ $dados['nome'] ?? 'N/A' }}</p>
            <p><strong>Telefone:</strong> {{ $dados['telefone'] ?? 'N/A' }}</p>
            <p><strong>Mensagem:</strong> {{ $dados['mensagem'] ?? 'N/A' }}</p>
            <p>Entraremos em contato com você em breve para fornecer mais informações ou esclarecimentos
                necessários.</p>
        </div>
        <div class="footer">
            <p>Este é um email automático. Por favor, não responda a esta mensagem.</p>
        </div>
    </div>
@endsection
