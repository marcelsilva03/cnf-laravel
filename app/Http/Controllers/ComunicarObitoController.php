<?php

namespace App\Http\Controllers;

use App\Jobs\ReportaTransacaoViaEmail;
use App\Mail\TransactionalEmail;
use App\Models\ComunicadoDeObito;
use App\Services\LocalidadesService;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class ComunicarObitoController extends Controller
{
    protected LocalidadesService $localidades;
    public function __construct(LocalidadesService $localidadesService)
    {
        $this->localidades = $localidadesService;
    }

    protected function apenasDigitos(string $valor): string
    {
        return preg_replace('/\D/', '', $valor);
    }

    public function index(): View|Factory|Application
    {
        $estadosCivil = config('constants.estadosCivis');
        $tipoLocaisDeObito = config('constants.tipoLocalDeObito');
        $ufs = $this->localidades->obterSiglasDosEstados();
        return view('comunicarobito')
            ->with(compact('estadosCivil'))
            ->with(compact('ufs'))
            ->with(compact('tipoLocaisDeObito'));
    }

    public function submitForm(Request $request): RedirectResponse
    {
        // dd($request->only(['sexo', 'local_obito_tipo', 'estado_civil'])); // para visualizar na tela os dados que são submetidos
        // foi adicionado livro folha e termo, adicionar aqui nos campos adequados
        $declarouVericidade = $request->filled('comunicarobito');
        if (!$declarouVericidade) {
            $notificacao = [
                'mensagem' => 'É necessário declarar que as informações do óbito são verídicas e assumir responsabilidade sobre elas.',
                'tipo' => 'aviso'
            ];
            session()->flash('notificacao', $notificacao);
            return redirect()->back()->withInput();
        }
        
        $request->validate(['local_obito_tipo' => ['nullable', 'in:0,1,2,3',],]);
        $local_obito_tipo = $request->input('local_obito_tipo', '');

        $ufObito = $request->ufobito;
        $cidadeobito = $request->cidadeobito;
        $dadosComunicadoDeObito = [
            'nome_sol' => $request->nome_sol,
            'fone_sol' => $this->apenasDigitos($request->fone_sol ?? ''),
            'email_sol' => $request->email_sol,
            'nome_fal' => $request->nome_fal,
            'cpf_fal' => $this->apenasDigitos($request->cpf_fal ?? ''),
            'rg_fal' => $this->apenasDigitos($request->rg_fal ?? ''),
            'titulo_eleitor' => $this->apenasDigitos($request->titulo_eleitor ?? ''),
            'nome_pai_fal' => $request->nome_pai_fal,
            'nome_mae_fal' => $request->nome_mae_fal,
            'cidade_estado_obito' => "$cidadeobito/$ufObito",
            'cartorio_id' => $request->cartorio_id,
            'data_nascimento' => $request->data_nascimento,
            'data_obito' => $request->data_obito,
            'local_obito_tipo' => $local_obito_tipo,
            'estado_civil' => $request->estado_civil,
            'sexo' => $request->sexo,
            'obs' => $request->obs,
            'livro' => $request->livro,
            'folha' => $request->folha,
            'termo' => $request->termo,
            'status' => '0',
        ];
        //dd($dadosComunicadoDeObito);

        $comunicadoDeObito = new ComunicadoDeObito();
        $comunicadoDeObito->preencher($dadosComunicadoDeObito);
        if ($comunicadoDeObito->save()) {
            $mensagem = 'Comunicado de óbito bem sucedido. Aguardando aprovação da moderação.';
            $tipoDeMensagem = 'sucesso';

            $destinatarios = config('constants.emails');
            $solicitante = [
                'nome' => $dadosComunicadoDeObito['nome_sol'],
                'email' => $dadosComunicadoDeObito['email_sol'],
                'telefone' => $dadosComunicadoDeObito['fone_sol'],
            ];
            $obito = [
                'nome' => $dadosComunicadoDeObito['nome_fal'],
                'cpf' => $dadosComunicadoDeObito['cpf_fal'],
                'rg' => $dadosComunicadoDeObito['rg_fal'],
                'titulo_de_eleitor' => $dadosComunicadoDeObito['titulo_eleitor'],
                'nome_do_pai' => $dadosComunicadoDeObito['nome_pai_fal'],
                'nome_da_mae' => $dadosComunicadoDeObito['nome_mae_fal'],
                'cidade_uf_do_obito' => $dadosComunicadoDeObito['cidade_estado_obito'],
                'data_de_nascimento' => $dadosComunicadoDeObito['data_nascimento'],
                'data_de_obito' => $dadosComunicadoDeObito['data_obito'],
                'estado_civil' => $dadosComunicadoDeObito['estado_civil'],
                'local_do_falecimento' => $dadosComunicadoDeObito['local_obito_tipo'],
                'informacoes_adicionais' => $dadosComunicadoDeObito['obs'],
                'livro' => $dadosComunicadoDeObito['livro'],
                'folha' => $dadosComunicadoDeObito['folha'],
                'termo' => $dadosComunicadoDeObito['termo'],
            ];
            $template = [
                'titulo' => 'CNF | Solicitação bem sucedida',
                'assunto' => 'Comunicado de óbito',
                'dados_solicitante' => $solicitante,
                'dados_do_obito' => $obito,
            ];

            $dadosTransacao = [
                'envelope' => [
                    'to' => $template['dados_solicitante']['email'],
                    'cc' => [$destinatarios['auxiliar'], $destinatarios['admin']],
                    #'bcc' => [$destinatarios['dev']],
                    'assunto' => $template['assunto'],
                ],
                'template' => [
                    'view' => 'emails.comunicadodeobito.recebido',
                    'dados' => $template,
                ],
            ];
            ReportaTransacaoViaEmail::dispatch($dadosTransacao);
        } else {
            $mensagem = 'Falha ao registrar óbito...';
            $tipoDeMensagem = 'erro';
        }

        $notificacao = [
            'mensagem' => $mensagem,
            'tipo' => $tipoDeMensagem
        ];
        session()->flash('notificacao', $notificacao);
        return redirect()->to(route('home'));
    }

}
