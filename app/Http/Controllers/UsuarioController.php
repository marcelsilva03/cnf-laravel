<?php

namespace App\Http\Controllers;

use App\Jobs\ReportaTransacaoViaEmail;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{

    public function logout(Request $request)
    {
        // Log do usuário que está fazendo logout
        $user = Auth::user();
        if ($user) {
            Log::info('User logout initiated', ['user_id' => $user->id, 'email' => $user->email]);
        }

        // Fazer logout do usuário
        Auth::logout();
        
        // Invalidar completamente a sessão
        $request->session()->invalidate();
        
        // Regenerar o token CSRF
        $request->session()->regenerateToken();
        
        // Limpar todos os dados de sessão relacionados a autenticação
        $request->session()->forget([
            'password_hash_web',
            'login.id',
            'login.remember',
            'intended_url'
        ]);
        
        // Forçar limpeza de cache de autenticação
        $request->session()->flush();
        
        // Regenerar ID da sessão para evitar fixação
        $request->session()->regenerate(true);

        session()->flash('notificacao', [
            'mensagem' => 'Você saiu da sua conta com sucesso. Sessão completamente limpa.',
            'tipo' => 'sucesso'
        ]);

        Log::info('User logout completed successfully');

        return redirect()->route('home');
    }

    public function paginaDeRegistro(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('usuario.registro');
    }

    public function recebeRegistro(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nome_completo' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'telefone' => 'required|string|max:15',
                'senha' => 'required|string|min:8',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('notificacao', [
                'mensagem' => 'Dados incorretos . Verifique as informações e tente novamente!',
                'tipo' => 'erro'
            ]);
            return redirect()->back()->withInput();
        }

        try {
            $role = UserRole::where('name', UserRole::DEFAULT_ROLE_NAME)->first();
            
            if (!$role) {
                session()->flash('notificacao', [
                    'mensagem' => 'Erro interno: Role padrão não encontrada. Contate o administrador.',
                    'tipo' => 'erro'
                ]);
                return redirect()->back()->withInput();
            }

            $user = new User();
            $user->name = $validatedData['nome_completo'];
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['senha']);
            $user->role_id = $role->id;
            $user->status = User::STATUS['ATIVO'];
            
            if (!$user->save()) {
                throw new \Exception('Falha ao salvar usuário no banco de dados.');
            }

            $perfil = $user->perfil()->create([
                'fone_numero' => $validatedData['telefone'],
            ]);

            if (!$perfil) {
                // Se falhou ao criar perfil, remover usuário criado
                $user->delete();
                throw new \Exception('Falha ao criar perfil do usuário.');
            }

            session()->flash('notificacao', [
                'mensagem' => 'Registro concluído com sucesso!',
                'tipo' => 'sucesso'
            ]);
            return redirect()->route('home');

        } catch (\Exception $e) {
            Log::error('Erro ao registrar usuário: ' . $e->getMessage(), [
                'email' => $validatedData['email'],
                'nome' => $validatedData['nome_completo']
            ]);
            
            session()->flash('notificacao', [
                'mensagem' => 'Ocorreu um erro ao tentar registrar o usuário: ' . $e->getMessage(),
                'tipo' => 'erro'
            ]);
            return redirect()->back()->withInput();
        }
    }


    public function paginaDeLogin(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('usuario.login');
    }

    public function recebeLogin(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'senha' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('notificacao', [
                'mensagem' => 'Dados de login inválidos. Por favor, verifique as informações e tente novamente.',
                'tipo' => 'erro'
            ]);
            return redirect()->back()->withInput();
        }

        $dados = [
            'email' => $credentials['email'],
            'password' => $credentials['senha'],
        ];
        Log::info('User login attempt', ['username' => $credentials['email']]);
        Log::info('Before attempting login for user: ' . $credentials['email']);
        if (Auth::attempt($dados)) {
            Log::info('Login successful for user: ' . $credentials['email']);
            session()->flash('notificacao', [
                'mensagem' => 'Login realizado com sucesso!',
                'tipo' => 'sucesso'
            ]);
            return redirect()->to('/admin');
        }
        Log::info('Login failed for user: ' . $credentials['email']);
        session()->flash('notificacao', [
            'mensagem' => 'Credenciais inválidas.',
            'tipo' => 'erro'
        ]);
        return redirect()->back()->withInput();
    }

    public function paginaDeEsqueciSenha(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('usuario.esqueci-senha');
    }

    public function recebeEsqueciSenha(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('notificacao', [
                'tipo' => 'erro',
                'mensagem' => 'Dados inválidos. Por favor, verifique as informações e tente novamente.'
            ]);
            return redirect()->back()->withInput();
        }

        if (!User::where('email', $validatedData['email'])->exists()) {
            session()->flash('notificacao', [
                'tipo' => 'erro',
                'mensagem' => 'O e-mail fornecido não está registrado.'
            ]);
            return redirect()->back()->withInput();
        }
        $user = User::where('email', $validatedData['email'])->first();
        $token = Password::createToken($user);
        $configMail = config('constants.emails');
        $dadosEmail = [
            'envelope' => [
                'to' => $validatedData['email'],
                'assunto' => 'Recuperação de senha',
                'cc' => $configMail['destinatarios']['admin'] ?? [],
                'bcc' => $configMail['destinatarios']['dev'] ?? []
            ],
            'template' => [
                'view' => $configMail['templates']['usuario.senha-link'],
                'dados' => [
                    'link' => route('usuario.definicao-senha', ['token' => $token, 'email' => $validatedData['email']]),
                ]
            ]
        ];
        ReportaTransacaoViaEmail::dispatch($dadosEmail);

        session()->flash('notificacao', [
                'tipo' => 'informe',
                'mensagem' => 'Enviamos um link para redefinir sua senha. Verifique seu email.'
            ]);
        return redirect()->route('login');
    }

    public function paginaDeDefinicaoDeSenha(Request $request): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$email || !$token) {
            session()->flash('notificacao', ['tipo' => 'erro', 'mensagem' => 'Token ou e-mail não fornecido.']);
            return redirect()->back()->withInput();
        }


        $repositorio = Password::getRepository();
        $user = User::where('email', $email)->first();
        if (!$user) {
            session()->flash('notificacao', ['tipo' => 'erro', 'mensagem' => 'E-mail não encontrado.']);
            return redirect()->back()->withInput();
        }

        if (!$repositorio->exists($user, $token)) {
            session()->flash('notificacao', ['tipo' => 'erro', 'mensagem' => 'Token inválido ou expirado.']);
            return redirect()->back()->withInput();
        }

        return view('usuario.definicao-senha', ['token' => $token, 'email' => $email]);
    }

    public function recebeDefinicaoDeSenha(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8',
                'token' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('notificacao', [
                'tipo' => 'erro',
                'mensagem' => 'Dados inválidos. Por favor, verifique as informações e tente novamente.'
            ]);
            return redirect()->back()->withInput();
        }

        $status = Password::reset(
            $validatedData,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                session()->flash('notificacao', [
                    'tipo' => 'sucesso',
                    'mensagem' => 'Senha atualizada com sucesso!'
                ]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login');
        }

        session()->flash('notificacao', [
            'tipo' => 'erro',
            'mensagem' => 'Não foi possível redefinir a senha.'
        ]);
        return redirect()->back()->withInput();
    }

}
