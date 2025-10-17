@extends('layouts.app')

@section('title', 'Detalhes da Solicitação - CNF')

@section('content')
<section class="section-bg">
    <div class="container">
        <div class="section-title">
            <h2>Detalhes da Solicitação #{{ $solicitacao->sol_id }}</h2>
            <p>Informações completas sobre sua solicitação de pesquisa</p>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Status da Solicitação</h5>
                        <a href="{{ route('solicitacao.painel') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> Voltar para o Painel
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Data da Solicitação:</strong> {{ date('d/m/Y H:i', strtotime($solicitacao->created_at)) }}</p>
                                <p>
                                    <strong>Status:</strong>
                                    @if($solicitacao->status == \App\Models\Solicitacao::STATUS['PENDENTE'])
                                        <span class="badge bg-warning">Pendente</span>
                                    @elseif($solicitacao->status == \App\Models\Solicitacao::STATUS['APROVADA'])
                                        <span class="badge bg-info">Aprovada</span>
                                    @elseif($solicitacao->status == \App\Models\Solicitacao::STATUS['REJEITADA'])
                                        <span class="badge bg-danger">Rejeitada</span>
                                    @elseif($solicitacao->status == \App\Models\Solicitacao::STATUS['PAGA'])
                                        <span class="badge bg-success">Paga</span>
                                    @elseif($solicitacao->status == \App\Models\Solicitacao::STATUS['LIBERADA'])
                                        <span class="badge bg-primary">Liberada</span>
                                    @else
                                        <span class="badge bg-secondary">Desconhecido</span>
                                    @endif
                                </p>
                                <p><strong>Valor:</strong> R$ {{ number_format($solicitacao->sol_valor, 2, ',', '.') }}</p>
                            </div>
                            <div class="col-md-6">
                                @if($solicitacao->status == \App\Models\Solicitacao::STATUS['PENDENTE'])
                                    <div class="alert alert-warning">
                                        <p class="mb-0">Sua solicitação está pendente de aprovação.</p>
                                    </div>
                                @elseif($solicitacao->status == \App\Models\Solicitacao::STATUS['APROVADA'])
                                    <div class="alert alert-info">
                                        <p class="mb-0">Sua solicitação foi aprovada e está aguardando pagamento.</p>
                                    </div>
                                @elseif($solicitacao->status == \App\Models\Solicitacao::STATUS['REJEITADA'])
                                    <div class="alert alert-danger">
                                        <p class="mb-0">Sua solicitação foi rejeitada. Entre em contato para mais informações.</p>
                                    </div>
                                @elseif($solicitacao->status == \App\Models\Solicitacao::STATUS['PAGA'])
                                    <div class="alert alert-success">
                                        <p class="mb-0">Pagamento confirmado! Sua solicitação está sendo processada.</p>
                                    </div>
                                @elseif($solicitacao->status == \App\Models\Solicitacao::STATUS['LIBERADA'])
                                    <div class="alert alert-primary">
                                        <p class="mb-0">Sua solicitação foi liberada! Os resultados estão disponíveis.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Dados do Solicitante</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nome:</strong> {{ $solicitacao->sol_nome_sol }}</p>
                        <p><strong>Telefone:</strong> {{ $solicitacao->sol_tel_sol }}</p>
                        <p><strong>E-mail:</strong> {{ $solicitacao->sol_email_sol }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Dados do Falecido</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nome:</strong> {{ $solicitacao->sol_nome_fal }}</p>
                        <p><strong>CPF:</strong> {{ $solicitacao->sol_cpf_fal ? substr_replace(substr_replace(substr_replace($solicitacao->sol_cpf_fal, '.', 3, 0), '.', 7, 0), '-', 11, 0) : 'Não informado' }}</p>
                        <p><strong>RG:</strong> {{ $solicitacao->sol_rg_fal ?? 'Não informado' }}</p>
                        <p><strong>Título de Eleitor:</strong> {{ $solicitacao->sol_titulo_eleitor ?? 'Não informado' }}</p>
                        <p><strong>Nome do Pai:</strong> {{ $solicitacao->sol_nome_pai_fal ?? 'Não informado' }}</p>
                        <p><strong>Nome da Mãe:</strong> {{ $solicitacao->sol_nome_mae_fal ?? 'Não informado' }}</p>
                        <p><strong>Data de Nascimento:</strong> {{ $solicitacao->sol_data_nascimento ? date('d/m/Y', strtotime($solicitacao->sol_data_nascimento)) : 'Não informado' }}</p>
                        <p><strong>Data do Óbito:</strong> {{ $solicitacao->sol_data_obito ? date('d/m/Y', strtotime($solicitacao->sol_data_obito)) : 'Não informado' }}</p>
                        <p><strong>Estado Civil:</strong> {{ $solicitacao->sol_estado_civil ?? 'Não informado' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Informações Adicionais</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Estado/Cidade:</strong> {{ $solicitacao->sol_estado_cidade ?? 'Não informado' }}</p>
                        <p><strong>Local do Óbito:</strong> {{ $solicitacao->sol_local_obito_tipo ?? 'Não informado' }}</p>
                        <p><strong>Observações:</strong></p>
                        <div class="border p-3 bg-light">
                            {{ $solicitacao->sol_obs ?? 'Nenhuma observação informada.' }}
                        </div>
                    </div>
                </div>
            </div>

            @if($solicitacao->status == \App\Models\Solicitacao::STATUS['LIBERADA'])
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Resultados da Pesquisa</h5>
                    </div>
                    <div class="card-body">
                        <!-- Aqui entrariam os resultados da pesquisa quando disponíveis -->
                        <p>Os resultados da sua pesquisa estão disponíveis. Entre em contato com nossa equipe para mais detalhes.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection 