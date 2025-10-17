<?php

namespace App\Http\Controllers;

use App\Jobs\ReportaTransacaoViaEmail;
use App\Models\SolicitacaoOrcamento;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class BuscaAPIController extends Controller
{
    public function index(): View|Factory|Application
    {
        return view('buscaapi');
    }

    public function recebeSolicitacaoOrcamento(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'api_nome' => 'required',
            'api_email' => 'required|email',
            'api_telefone' => 'required',
            'api_message' => 'required',
        ]);

        // Salvar a solicitação no banco de dados
        $solicitacao = new SolicitacaoOrcamento([
            'nome' => $validated['api_nome'],
            'email' => $validated['api_email'],
            'telefone' => $validated['api_telefone'],
            'mensagem' => $validated['api_message'],
            'status' => SolicitacaoOrcamento::STATUS['PENDENTE'],
        ]);
        
        $solicitacao->save();

        $configMail = config('constants.emails');
        $dadosEmail = [
            'envelope' => [
                'to' => $validated['api_email'],
                'assunto' => 'Solicitação de orçamento',
                'cc' => $configMail['destinatarios']['admin'] ?? [],
                'bcc' => $configMail['destinatarios']['dev'] ?? []
            ],
            'template' => [
                'view' => $configMail['templates']['orcamento'] ?? 'default_template',
                'dados' => [
                    'nome' => $validated['api_nome'],
                    'telefone' => $validated['api_telefone'],
                    'mensagem' => $validated['api_message'],
                    'id_solicitacao' => $solicitacao->id,
                ]
            ]
        ];

        ReportaTransacaoViaEmail::dispatch($dadosEmail);

        $notificacao = [
            'mensagem' => 'Recebemos sua solicitação e retornaremos em breve com o orçamento.',
            'tipo' => 'informe'
        ];

        session()->flash('notificacao', $notificacao);
        return redirect()->to(route('home'));
    }
}
