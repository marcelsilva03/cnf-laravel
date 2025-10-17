<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Services\PessoaService;
use App\Services\LocalidadesService;
use App\Traits\HasCustomPagination;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class ResultadosController extends Controller
{
    use HasCustomPagination;

    protected LocalidadesService $localidades;

    public function __construct(LocalidadesService $localidadesService)
    {
        $this->localidades = $localidadesService;
    }

    public function validaReCaptcha(Request $request): RedirectResponse
    {
        $nomeSession  = session('nome', '');
        $exataSession = session('exata', false);
        $estadoSession = session('estado', '');
        $cidadeSession = session('cidade', '');

        // Captura filtros do request
        $nome   = $request->input('nome', session('nome', ''));
        $exata  = $request->boolean('nome-exato', session('exata', false));
        $estado = $request->input('estado', session('estado', null));
        $cidade = $request->input('cidade', session('cidade', null));

        // se mudou de estado, zera a cidade
        if ($estado !== $estadoSession) {
            $cidade = null;
        }

        // Origem determina reCAPTCHA v2 ou v3
        $origem = $request->input('recaptcha_version', 'BUSCA');

        // Validações básicas
        if (trim($nome) === '' || mb_strlen(trim($nome)) < 3) {
            session()->flash('notificacao', ['mensagem' => 'Informe um nome com pelo menos 3 caracteres.', 'tipo' => 'erro']);
            return redirect()->route('home');
        }
        
        $mudouNome   = $nome !== $nomeSession;
        $mudouExata  = $exata  !== $exataSession;
        $mudaFiltros = $mudouNome || $mudouExata;

        // se veio da Home (BUSCA) ou mudou nome/exata => limpa filtros
        if ($origem === 'BUSCA' || $mudaFiltros) {
            $estado = null;
            $cidade = null;
        }

        if ($origem === 'RESULTADO' && ! $mudaFiltros && empty($estado)) {
            session()->flash('notificacao', [
                'mensagem' => 'Selecione a UF para filtrar os resultados.',
                'tipo'     => 'erro'
            ]);
            // volta para a página de resultados, mantendo inputs
            return redirect()->back()->withInput();
        }

        // Atualiza sessão com filtros e origem
        session([
            'nome'              => $nome,
            'exata'             => $exata,
            'estado'            => $estado,
            'cidade'            => $cidade,
            'recaptcha_version' => $origem,
        ]);

        if (env('RECAPTCHA_DISABLED', false)) {
            // Sem reCAPTCHA: apenas pula
            return redirect()->route('resultados');
        }

        // Envio para Google
        $secret = ($origem === 'RESULTADO')
            ? env('RECAPTCHA_SECRET_RESULTADO')
            : env('RECAPTCHA_SECRET_BUSCA');
        $token  = $request->input('g-recaptcha-response');
        if (!$token) {
            session()->flash('notificacao', ['mensagem' => 'Falha na validação do reCAPTCHA.', 'tipo' => 'erro']);
            return redirect()->route('home');
        }
        try {
            $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            session()->flash('notificacao', ['mensagem' => 'Erro ao comunicar com reCAPTCHA.', 'tipo' => 'erro']);
            return redirect()->route('home');
        }
        $body = $resp->json();
        if (empty($body['success'])) {
            session()->flash('notificacao', ['mensagem' => 'reCAPTCHA inválido.', 'tipo' => 'erro']);
            return redirect()->route('home');
        }
        if ($origem === 'RESULTADO' && (empty($body['score']) || $body['score'] < 0.5)) {
            session()->flash('notificacao', ['mensagem' => 'reCAPTCHA score insuficiente.', 'tipo' => 'erro']);
            return redirect()->route('home');
        }

        return redirect()->route('resultados');
    }

    /**
     * Exibe resultados conforme filtros de sessão/REQUEST, controla paginação, histórico e hashes.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function index(Request $request): View|RedirectResponse
    {
        // Recupera origem e filtros
        $origem = session('recaptcha_version', 'BUSCA');
        $nome   = $request->input('nome', session('nome', ''));
        $exata  = $request->boolean('nome-exato', session('exata', false));
        $estado = $request->input('estado', session('estado', null));
        $cidade = $request->input('cidade', session('cidade', null));

        // Validações pré-busca
        if (trim($nome) === '' || mb_strlen(trim($nome)) < 3) {
            session()->flash('notificacao', ['mensagem' => 'Informe um nome com pelo menos 3 caracteres.', 'tipo' => 'erro']);
            return redirect()->route('home');
        }

        // Atualiza sessão com filtros atuais
        session(['nome' => $nome, 'exata' => $exata, 'estado' => $estado, 'cidade' => $cidade]);

        // Token de paginação
        if ($request->isMethod('post') || !$request->has('token')) {
            $token = Str::random(40);
            session(['token_busca_resultados' => $token]);
        } else {
            $token = $request->input('token');
        }
        if ($request->filled('token') && $token !== session('token_busca_resultados')) {
            session()->flash('notificacao', ['mensagem' => 'Acesso inválido à paginação.', 'tipo' => 'erro']);
            return redirect()->route('home');
        }

        // Consultas
        $todosResultados = PessoaService::buscarPorNomeSemPaginacao($nome, $exata);
        $resultados      = PessoaService::buscarPorNome($nome, $estado, $cidade, $exata);

        // Histórico e limite diário
        $ip     = $request->ip();
        $hoje   = now()->toDateString();
        $count  = DB::table('historico')->where('ip', $ip)->whereDate('criada', $hoje)->count();
        if ($count >= 50) {
            abort(429, 'Limite de consultas atingido. Tente novamente amanhã.');
        }

        $index         = session('index', 'sim');
        $resultadoFlag = session('resultado');
        $exataFlag     = $exata ? 'true' : 'false';
        $consulta      = null;

        if ($index === 'sim') {
            $consulta = $exataFlag === 'true' ? '1' : '2';
            session(['resultado' => 'sim']);
        }
        if (session('resultado') === 'sim' && $cidade !== null) {
            $consulta = $exataFlag === 'true' ? '3' : '4';
        }
        if (session('resultado') === 'sim' && $estado !== null && $cidade === null) {
            $consulta = $exataFlag === 'true' ? '5' : '6';
        }
        if ($index === 'nao' && session('resultado') === 'sim' && is_null($estado) && is_null($cidade)) {
            $consulta = $exataFlag === 'true' ? '7' : '8';
        }
        DB::table('historico')->insert([
            'nome'        => $nome,
            'estado'      => $estado ?: '',
            'cidade'      => $cidade ?: '',
            'nr_consulta' => $consulta, // se desejar manter lógica anterior, ajustar aqui
            'qtd'         => $resultados->total(),
            'pagina'      => $request->query('page', 1),
            'ip'          => $ip,
        ]);

        // Batch insert hashes
        if ($resultados->isNotEmpty()) {
            $hashes = $resultados->map(fn($item) => ['hash' => $item->hash, 'pes_id' => $item->pes_id])->toArray();
            DB::table('tabela_hashes')->insert($hashes);
        }

        // Monta dados para view
        $ufs     = $todosResultados->pluck('pes_uf')->unique()->sort()->values()->all();
        $cidades = $estado
            ? $todosResultados->where('pes_uf', $estado)->pluck('pes_cidade')->unique()->sort()->values()->all()
            : [];

        return view('resultados', [
            'nome'            => $nome,
            'nomeSanitizado'  => strtoupper(remover_acentos($nome)),
            'exata'           => $exata,
            'estado'          => $estado,
            'cidade'          => $cidade,
            'totalResultados' => $todosResultados->count(),
            'resultados'      => $resultados,
            'token'           => $token,
            'paginacao'       => [
                'total'           => $resultados->total(),
                'ordinalPrimeiro' => $resultados->firstItem(),
                'ordinalUltimo'   => $resultados->lastItem(),
                'paginas'         => $resultados->lastPage(),
                'paginaAtual'     => $resultados->currentPage(),
                'proxima'         => $resultados->nextPageUrl(),
                'anterior'        => $resultados->previousPageUrl(),
            ],
            'ufs'             => $ufs,
            'cidades'         => $cidades,
        ]);
    }  

    public function byCPF(Request $request): View|Factory|Application|RedirectResponse
    {
        $cpf = $request->query('cpf');
        if (empty($cpf)) {
            return redirect()->route('home');
        }

        $resultadoCpf = DB::table('pessoa_1')->where('fal_doc', $cpf)->get();
        if ($resultadoCpf->isEmpty()) {
            return view('semResultadosCPF');
        }

        return view('resultadosCPF')->with('resultados', $resultadoCpf);
    }

    public function searchNameUF(Request $request)
    {
        if (!$request->isMethod('post')) {
            abort(405);
        }

        if (env('RECAPTCHA_DISABLED', false)) {
            session([
                'nome'   => $request->input('nome'),
                'estado' => $request->input('estado'),
                'cidade' => $request->input('cidade'),
            ]);
            return response()->json(['message' => 'Sucesso'], 200);
        }

        $data        = $request->all();
        $secretKey   = env('RECAPTCHA_SECRET_RESULTADO');
        $responseKey = $data['g-recaptcha-response'] ?? '';
        $verifyUrl   = 'https://www.google.com/recaptcha/api/siteverify';
        $verifyResp  = file_get_contents("{$verifyUrl}?secret={$secretKey}&response={$responseKey}&remoteip={$request->ip()}");
        $body        = json_decode($verifyResp);

        if (!empty($body->success) && $body->success) {
            session([
                'nome'   => $data['nome'],
                'estado' => $data['estado'],
                'cidade' => $data['cidade'],
            ]);
            return response()->json(['message' => 'Sucesso'], 200);
        }

        session()->flash('notificacao', ['mensagem' => 'Falha no reCAPTCHA.', 'tipo' => 'erro']);
        return response()->json(['message' => 'Falhou'], 500);
    }
}
?>