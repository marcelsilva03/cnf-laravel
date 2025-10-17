<?php

use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\BuscaEmCartorioController;
use App\Http\Controllers\ComunicarObitoController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomenagensController;
use App\Http\Controllers\ResultadosController;
use App\Http\Controllers\BuscaAPIController;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Admin Panel Routes - Redirect login to admin
Route::middleware(['web'])->group(function () {
    Route::get('/admin-login', function () {
        return redirect()->to('/admin/login');
    })->name('admin-login');
});

Route::get('/comunicarobito', [ComunicarObitoController::class, 'index'])->name('comunicar-obito');
Route::post('/submit-form', [ComunicarObitoController::class, 'submitForm'])->name('submit.form');

Route::get('/pedido-de-certidao', [PedidoCertidaoController::class, 'index'])->name('pedido-certidao');
Route::post('/pedido-de-certidao', [PedidoCertidaoController::class, 'store'])->name('pedido-certidao.store');


Route::get('/resultados', [ResultadosController::class, 'index'])->name('resultados');
Route::post('/resultados', [ResultadosController::class, 'validaReCaptcha'])->name('resultados-recaptcha');
Route::post('/search-name-uf', [ResultadosController::class, 'searchNameUF'])->name('search-name-uf');
Route::get('/resultados-cpf', [ResultadosController::class, 'byCPF']);
Route::post('/busca', [HomeController::class, 'buscar'])->name('busca-processar');

Route::get('/homenagens', [HomenagensController::class, 'index'])->name('home-homenagens');
Route::get('/homenagens/nova', [HomenagensController::class, 'registrar'])->name('registrador-de-nova-homenagem');
Route::post('/homenagens/nova', [HomenagensController::class, 'recebeRegistro'])->name('receptor-de-nova-homenagem');
Route::get('/homenagens/resultados', [HomenagensController::class, 'buscar'])->name('resultados-busca-por-homenagens');
Route::get('/homenagens/{uuid}', [HomenagensController::class, 'porUUID'])->name('lista-de-homenagens-do-falecido');
Route::get('/homenagens/{uuid}/{code}', [HomenagensController::class, 'detalhesHomenagem'])->name('homenagem.detalhes');

Route::get('/busca-avancada', [BuscaEmCartorioController::class, 'formularioBuscaAvancada'])->name('formulario-pesquisa');
Route::post('/pagamento-pesquisa', [BuscaEmCartorioController::class, 'paginaDePagamento'])->name('pagamento-pesquisa-post');
Route::get('/pagamento-pesquisa', [BuscaEmCartorioController::class, 'paginaDePagamento'])->name('pagamento-pesquisa-get');
Route::get('/pagamento/sucesso', [PagamentoController::class, 'sucesso'])->name('pagamento.sucesso');

Route::get('/politica-de-privacidade', fn () => view('politicasDePrivacidade'));

Route::post('/comunicar-erro', [BuscaEmCartorioController::class, 'recebeComunicadoDeErro'])->name('receptor-comunicado-de-erro');
Route::post('/contact/submit', [HomeController::class, 'emailContato'])->name('contact.submit');

Route::get('/buscaapi', [BuscaAPIController::class, 'index'])->name('buscaapi.solicite-orcamento');
Route::post('/buscaapi/form-orcamento', [BuscaAPIController::class, 'recebeSolicitacaoOrcamento'])->name('buscaapi.recebe-solicite-orcamento');

// Rotas para visualização de logs (sem autenticação para melhorar o fluxo)
Route::middleware(['web'])->group(function () {
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/stats', [LogController::class, 'stats'])->name('logs.stats');
    Route::post('/logs/clear', [LogController::class, 'clear'])->name('logs.clear');
});

// Guest routes
Route::middleware(['web', 'guest'])->group(function () {
    Route::get('/registro', [UsuarioController::class, 'paginaDeRegistro'])->name('usuario.registro');
    Route::post('/recebe-registro', [UsuarioController::class, 'recebeRegistro'])->name('usuario.recebe-registro');
    Route::get('/login', [UsuarioController::class, 'paginaDeLogin'])->name('login');
    Route::post('/login', [UsuarioController::class, 'recebeLogin'])->name('usuario.recebe-login');
    Route::get('/esqueci-minha-senha', [UsuarioController::class, 'paginaDeEsqueciSenha'])->name('usuario.esqueci-senha');
    Route::post('/recebe-esqueci-minha-senha', [UsuarioController::class, 'recebeEsqueciSenha'])->name('usuario.recebe-esqueci-senha');
    Route::get('/definicao-de-senha', [UsuarioController::class, 'paginaDeDefinicaoDeSenha'])->name('usuario.definicao-senha');
    Route::post('/recebe-definicao-de-senha', [UsuarioController::class, 'recebeDefinicaoDeSenha'])->name('usuario.recebe-definicao-senha');
});

// Auth routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/dashboard/logout', [UsuarioController::class, 'logout'])->name('filament.dashboard.auth.logout');
    Route::get('/logout', [UsuarioController::class, 'logout'])->name('usuario.logout');
    
    // Rotas para o painel de solicitações
    Route::get('/solicitacoes', [SolicitacaoController::class, 'painel'])->name('solicitacao.painel');
    Route::get('/solicitacoes/{id}', [SolicitacaoController::class, 'detalhes'])->name('solicitacao.detalhes');
    
    
    // Rota de emergência para logout forçado
    Route::get('/force-logout', function (Request $request) {
        \Log::info('Force logout initiated', ['user_id' => auth()->id()]);
        
        // Logout completo
        Auth::logout();
        
        // Limpar completamente a sessão
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flush();
        $request->session()->regenerate(true);
        
        // Limpar cookies relacionados
        foreach ($_COOKIE as $name => $value) {
            if (str_contains($name, 'laravel_session') || str_contains($name, 'remember_')) {
                setcookie($name, '', time() - 3600, '/');
            }
        }
        
        session()->flash('notificacao', [
            'mensagem' => 'Logout forçado realizado. Sessão completamente limpa.',
            'tipo' => 'sucesso'
        ]);
        
        return redirect()->route('home');
    })->name('usuario.force-logout');
    
    //Route::get('/dashboard/email-templates/{templateId}/visualizar', [EmailTemplateController::class, 'visualizar'])->name('email.templates.visualizar');
});

Route::redirect('/dashboard/login', '/login');

Route::middleware('only.my.ip')->group(function() {
    // todas as rotas de teste aqui
    Route::get('/', [HomeController::class, 'index'])->name('home');

// Admin Panel Routes - Redirect login to admin
Route::middleware(['web'])->group(function () {
    Route::get('/admin-login', function () {
        return redirect()->to('/admin/login');
    })->name('admin-login');
});

Route::get('/comunicarobito', [ComunicarObitoController::class, 'index'])->name('comunicar-obito');
Route::post('/submit-form', [ComunicarObitoController::class, 'submitForm'])->name('submit.form');

Route::get('/pedido-de-certidao', [PedidoCertidaoController::class, 'index'])->name('pedido-certidao');
Route::post('/pedido-de-certidao', [PedidoCertidaoController::class, 'store'])->name('pedido-certidao.store');


Route::get('/resultados', [ResultadosController::class, 'index'])->name('resultados');
Route::post('/resultados', [ResultadosController::class, 'validaReCaptcha'])->name('resultados-recaptcha');
Route::post('/search-name-uf', [ResultadosController::class, 'searchNameUF'])->name('search-name-uf');
Route::get('/resultados-cpf', [ResultadosController::class, 'byCPF']);
Route::post('/busca', [HomeController::class, 'buscar'])->name('busca-processar');

Route::get('/homenagens', [HomenagensController::class, 'index'])->name('home-homenagens');
Route::get('/homenagens/nova', [HomenagensController::class, 'registrar'])->name('registrador-de-nova-homenagem');
Route::post('/homenagens/nova', [HomenagensController::class, 'recebeRegistro'])->name('receptor-de-nova-homenagem');
Route::get('/homenagens/resultados', [HomenagensController::class, 'buscar'])->name('resultados-busca-por-homenagens');
Route::get('/homenagens/{uuid}', [HomenagensController::class, 'porUUID'])->name('lista-de-homenagens-do-falecido');
Route::get('/homenagens/{uuid}/{code}', [HomenagensController::class, 'detalhesHomenagem'])->name('homenagem.detalhes');

Route::get('/busca-avancada', [BuscaEmCartorioController::class, 'formularioBuscaAvancada'])->name('formulario-pesquisa');
Route::post('/pagamento-pesquisa', [BuscaEmCartorioController::class, 'paginaDePagamento'])->name('pagamento-pesquisa-post');
Route::get('/pagamento-pesquisa', [BuscaEmCartorioController::class, 'paginaDePagamento'])->name('pagamento-pesquisa-get');
Route::get('/pagamento/sucesso', [PagamentoController::class, 'sucesso'])->name('pagamento.sucesso');

Route::get('/politica-de-privacidade', fn () => view('politicasDePrivacidade'));

Route::post('/comunicar-erro', [BuscaEmCartorioController::class, 'recebeComunicadoDeErro'])->name('receptor-comunicado-de-erro');
Route::post('/contact/submit', [HomeController::class, 'emailContato'])->name('contact.submit');

Route::get('/buscaapi', [BuscaAPIController::class, 'index'])->name('buscaapi.solicite-orcamento');
Route::post('/buscaapi/form-orcamento', [BuscaAPIController::class, 'recebeSolicitacaoOrcamento'])->name('buscaapi.recebe-solicite-orcamento');

// Rotas para visualização de logs (sem autenticação para melhorar o fluxo)
Route::middleware(['web'])->group(function () {
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/stats', [LogController::class, 'stats'])->name('logs.stats');
    Route::post('/logs/clear', [LogController::class, 'clear'])->name('logs.clear');
});

// Guest routes
Route::middleware(['web', 'guest'])->group(function () {
    Route::get('/registro', [UsuarioController::class, 'paginaDeRegistro'])->name('usuario.registro');
    Route::post('/recebe-registro', [UsuarioController::class, 'recebeRegistro'])->name('usuario.recebe-registro');
    Route::get('/login', [UsuarioController::class, 'paginaDeLogin'])->name('login');
    Route::post('/login', [UsuarioController::class, 'recebeLogin'])->name('usuario.recebe-login');
    Route::get('/esqueci-minha-senha', [UsuarioController::class, 'paginaDeEsqueciSenha'])->name('usuario.esqueci-senha');
    Route::post('/recebe-esqueci-minha-senha', [UsuarioController::class, 'recebeEsqueciSenha'])->name('usuario.recebe-esqueci-senha');
    Route::get('/definicao-de-senha', [UsuarioController::class, 'paginaDeDefinicaoDeSenha'])->name('usuario.definicao-senha');
    Route::post('/recebe-definicao-de-senha', [UsuarioController::class, 'recebeDefinicaoDeSenha'])->name('usuario.recebe-definicao-senha');
});

// Auth routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/dashboard/logout', [UsuarioController::class, 'logout'])->name('filament.dashboard.auth.logout');
    Route::get('/logout', [UsuarioController::class, 'logout'])->name('usuario.logout');
    
    // Rotas para o painel de solicitações
    Route::get('/solicitacoes', [SolicitacaoController::class, 'painel'])->name('solicitacao.painel');
    Route::get('/solicitacoes/{id}', [SolicitacaoController::class, 'detalhes'])->name('solicitacao.detalhes');
    
    
    // Rota de emergência para logout forçado
    Route::get('/force-logout', function (Request $request) {
        \Log::info('Force logout initiated', ['user_id' => auth()->id()]);
        
        // Logout completo
        Auth::logout();
        
        // Limpar completamente a sessão
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flush();
        $request->session()->regenerate(true);
        
        // Limpar cookies relacionados
        foreach ($_COOKIE as $name => $value) {
            if (str_contains($name, 'laravel_session') || str_contains($name, 'remember_')) {
                setcookie($name, '', time() - 3600, '/');
            }
        }
        
        session()->flash('notificacao', [
            'mensagem' => 'Logout forçado realizado. Sessão completamente limpa.',
            'tipo' => 'sucesso'
        ]);
        
        return redirect()->route('home');
    })->name('usuario.force-logout');
    
    //Route::get('/dashboard/email-templates/{templateId}/visualizar', [EmailTemplateController::class, 'visualizar'])->name('email.templates.visualizar');
});

Route::redirect('/dashboard/login', '/login');
});
