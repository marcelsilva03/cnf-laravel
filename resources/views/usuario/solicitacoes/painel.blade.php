@extends('layouts.app')

@section('title', 'Painel de Solicitações - CNF')

@section('content')
<section class="section-bg">
    <div class="container">
        <div class="section-title">
            <h2>Painel de Solicitações</h2>
            <p>Acompanhe o status de suas solicitações de pesquisa</p>
        </div>

        <div class="row">
            <!-- Cards de estatísticas -->
            <div class="col-12 mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Total de Solicitações</h5>
                                <p class="card-text display-4">{{ $estatisticas['total'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Pendentes</h5>
                                <p class="card-text display-4">{{ $estatisticas['pendentes'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Pagas</h5>
                                <p class="card-text display-4">{{ $estatisticas['pagas'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Liberadas</h5>
                                <p class="card-text display-4">{{ $estatisticas['liberadas'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de solicitações -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Minhas Solicitações</h5>
                    </div>
                    <div class="card-body">
                        @if(count($solicitacoes) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome do Falecido</th>
                                            <th>Data da Solicitação</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($solicitacoes as $solicitacao)
                                            <tr>
                                                <td>{{ $solicitacao->sol_id }}</td>
                                                <td>{{ $solicitacao->sol_nome_fal }}</td>
                                                <td>{{ date('d/m/Y', strtotime($solicitacao->created_at)) }}</td>
                                                <td>
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
                                                </td>
                                                <td>
                                                    <a href="{{ route('solicitacao.detalhes', $solicitacao->sol_id) }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> Ver Detalhes
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <p class="mb-0">Você ainda não possui solicitações de pesquisa.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 