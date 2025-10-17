<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SolicitacaoController extends Controller
{
    /**
     * Exibe o painel de controle com resumo das solicitações
     */
    public function painel(): View|Factory|Application
    {
        $userId = Auth::id();
        
        // Obter contagem de solicitações por status
        $estatisticas = Solicitacao::contarPorStatus($userId);
        
        // Obter todas as solicitações do usuário
        $solicitacoes = Solicitacao::obterPorUsuario($userId);
        
        return view('usuario.solicitacoes.painel', [
            'estatisticas' => $estatisticas,
            'solicitacoes' => $solicitacoes
        ]);
    }
    
    /**
     * Exibe os detalhes de uma solicitação específica
     */
    public function detalhes($id): View|Factory|Application
    {
        $userId = Auth::id();
        
        // Buscar a solicitação e verificar se pertence ao usuário logado
        $solicitacao = Solicitacao::where('sol_id', $id)
                                  ->where('user_id', $userId)
                                  ->first();
        
        if (!$solicitacao) {
            session()->flash('notificacao', [
                'mensagem' => 'Solicitação não encontrada ou você não tem permissão para acessá-la.',
                'tipo' => 'erro'
            ]);
            return redirect()->route('solicitacao.painel');
        }
        
        return view('usuario.solicitacoes.detalhes', [
            'solicitacao' => $solicitacao
        ]);
    }
} 