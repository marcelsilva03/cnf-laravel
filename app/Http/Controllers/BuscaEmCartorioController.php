<?php

namespace App\Http\Controllers;

use App\Models\ComunicadoDeErro;
use App\Models\Falecido;
use App\Models\Solicitacao;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use App\Services\LocalidadesService;
use App\Services\AbrangenciaService;
use App\Services\FalecidoHashService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class BuscaEmCartorioController extends Controller
{
    protected LocalidadesService $localidades;
    protected AbrangenciaService $abrangencia;
    protected \DateTime $dataDivisoraPrecoPesquisa;
    protected FalecidoHashService $falecidoHashService;

    protected array $precosPesquisa;
    public function __construct(
        LocalidadesService $localidadesService, 
        AbrangenciaService $abrangenciaService,
        FalecidoHashService $falecidoHashService
    ) {
        $this->localidades = $localidadesService;
        $this->dataDivisoraPrecoPesquisa = new \DateTime(config('constants.dataDivisoraPrecoPesquisa'));
        $this->precosPesquisa = config('constants.precosPesquisaEmRelacaoData');
        $this->abrangencia = $abrangenciaService;
        $this->falecidoHashService = $falecidoHashService;
    }

    protected function obterAssociacaoTextoEValor(): array
    {
        $textoEValor = [];
        foreach ($this->precosPesquisa as $relacao) {
            $textoEValor[$relacao['texto']] = $relacao['valor'];
        }
        return $textoEValor;
    }

    protected function obterValorPelaData(string $data): string
    {
        $recebida = new \DateTime($data);
        return ($recebida < $this->dataDivisoraPrecoPesquisa
            ? $this->precosPesquisa['antes']['valor']
            : $this->precosPesquisa['depois']['valor']
        );
    }

    protected function obterPrazoPelaData(string $data): string
    {
        $recebida = new \DateTime($data);
        return ($recebida < $this->dataDivisoraPrecoPesquisa
            ? $this->precosPesquisa['antes']['prazo']
            : $this->precosPesquisa['depois']['prazo']
        );
    }

    public function obterNomeDoProdutoPorData(string $data): string
    {
        $recebida = new \DateTime($data);
        $detalhe = ($recebida < $this->dataDivisoraPrecoPesquisa
            ? $this->precosPesquisa['antes']['texto']
            : $this->precosPesquisa['depois']['texto']
        );
        // return "Serviço de Pesquisa em Cartório (falecimento $detalhe)";
        return "Serviço de Pesquisa em Cartório";
    }

    protected function apenasDigitos(string $valor): string
    {
        return preg_replace("/\D/", "", $valor);
    }

    public function formularioBuscaAvancada(Request $request): View|Factory|Application
    {
        $hash = $request->query('hash');
        $dados = $hash 
            ? $this->falecidoHashService->findByHash($hash)
            : [];
        //dd($dados);

        // constantes e listas para popular o form
        $localFalecimento = config('constants.tipoLocalDeObito');
        $estadoCivil       = config('constants.estadosCivis');
        $abrangencia       = config('constants.abrangencia');
        $ufs               = $this->localidades->obterSiglasDosEstados();
        $precosPesquisa    = $this->obterAssociacaoTextoEValor();

        // monta a view
        $view = view('busca-avancada')
            ->with('localFalecimento', $localFalecimento)
            ->with('estadoCivil',       $estadoCivil)
            ->with('ufs',               $ufs)
            ->with('abrangencia',       $abrangencia)
            ->with('precos',            $precosPesquisa)
            ->with('falecido',          $dados);

        // se usuário logado, carrega dados adicionais
        $user = auth()->user();
        if (!$user) {
            return $view;
        }

        $user->load('perfil');
        $paramsUsuario = [
            'id'    => $user->id,
            'nome'  => $user->name,
            'email' => $user->email,
        ];
        if ($user->perfil && $user->perfil->fone_numero) {
            $paramsUsuario['tel'] = $user->perfil->fone_numero;
        }

        return $view->with('usuario', $paramsUsuario);
    }

    public function recebeComunicadoDeErro(Request $request): RedirectResponse
    {
        $email = $request->email_comunicante;
        $dadosComunicadoDeErro = [
            'uuid_falecido' => $request->uuid_falecido,
            'id_falecido' => intval($request->id_falecido),
            'nome_falecido'     => $request->nome_falecido,
            'cidade_falecido'   => $request->cidade_falecido,
            'uf_falecido'       => $request->uf_falecido,
            'nome_comunicante' => $request->nome_comunicante,
            'email_comunicante' => $email,
            'mensagem' => $request->mensagem,
            'tipo_erro' => $request->tipo_erro,
        ];
        $erro = new ComunicadoDeErro();
        $erro->preencher($dadosComunicadoDeErro);
        $sucesso = $erro->save();

        $mensagem = ($sucesso
            ? "Comunicado de erro bem sucedido. A equipe CNF vai analisar o caso e responderá com uma mensagem para $email."
            : 'Falha ao registrar erro. Entre em contato com a administração.'
        );
        $tipoDeMensagem = ($sucesso ? 'sucesso' : 'erro');
        $notificacao = [
            'mensagem' => $mensagem,
            'tipo' => $tipoDeMensagem
        ];
        session()->flash('notificacao', $notificacao);
        return redirect()->back();
    }

    public function paginaDePagamento(Request $request): View|Factory|Application|RedirectResponse
    {
        if ($request->isMethod('get')) {
            // Se via Get, redireciona para a tela inicial
            return redirect()->route('home')->with('erro', 'Acesso inválido');
        }

        $user = auth()->user();
        if ($user) {
            $user->load('perfil');
            if ($user->perfil) {
                $user->perfil->fone_numero = $request->telsol;
                $user->perfil->save();
            }
        }
        
        $dataObito = $request->dfalec;
        if (!$dataObito) {
            $notificacao = [
                'mensagem' => 'Antes de acessar a página de pagamento você deve selecionar o falecido para o qual deseja solicitar o serviço de pesquisa de dados de cartório.',
                'tipo' => 'informe'
            ];
            session()->flash('notificacao', $notificacao);
            return redirect()->to('/');
        }
        $estado_obito = $request->estado_obito;
        $cidade_obito = $request->cidade_obito;
        $cidadeComUF = "$cidade_obito/$estado_obito";
        $valorAPagar = $this->obterValorPelaData($dataObito);
        $dadosSolicitacao = [
            'sol_empresa' => $request->empresa,
            'sol_nome_sol' => $request->nomesol,
            'sol_tel_sol' => $this->apenasDigitos($request->telsol ?? ''),
            'sol_email_sol' => $request->emailsol,
            'sol_nome_fal' => $request->nome_fal,
            'sol_cpf_fal' => $this->apenasDigitos($request->cpf ?? ''),
            'sol_rg_fal' => $this->apenasDigitos($request->rg ?? ''),
            'sol_titulo_eleitor' => $this->apenasDigitos($request->eleitor ?? ''),
            'sol_nome_pai_fal' => $request->nomepai,
            'sol_nome_mae_fal' => $request->nomemae,
            'sol_data_nascimento' => $request->nascf,
            'sol_data_obito' => $dataObito,
            'sol_estado_cidade' => $cidadeComUF,
            'sol_local_obito_tipo' => $request->localfal,
            'sol_estado_civil' => $request->ecivil,
            'sol_obs' => $request->comentarios,
            'sol_id_abr' => $request->abrangencia,
            'sol_valor' => $valorAPagar,
            'sol_uf' => $estado_obito,
            'sol_cidade' => $cidade_obito
        ];

        // Associar a solicitação ao usuário logado, se houver
        if ($user) {
            $dadosSolicitacao['user_id'] = $user->id;
        }

        $solicitacao = new Solicitacao();
        $solicitacao->preencher($dadosSolicitacao);
        $sucesso = $solicitacao->save();
        if ($sucesso) {
            $solicitacao->refresh();
            $dadosBancariosBB = config('constants.dadosBancario');
            $emails = config('constants.emails');
            $solicitacaoArray = $solicitacao->toArray();
            $solicitacaoArray['prazo'] = $this->obterPrazoPelaData($dataObito);
            $solicitacaoArray['produto'] = $this->obterNomeDoProdutoPorData($dataObito);
            return view('pagamentoPesquisa')
                ->with('solicitacao', $solicitacaoArray)
                ->with('dadosBancariosBB', $dadosBancariosBB)
                ->with('emailComprovante', $emails['admin']);
        }

        $notificacao = [
            'mensagem' => 'Falha ao registrar solicitação. Entre em contato com a adminstração.',
            'tipo' => 'erro'
        ];
        session()->flash('notificacao', $notificacao);
        return redirect()->back()->withInput();
    }
}
