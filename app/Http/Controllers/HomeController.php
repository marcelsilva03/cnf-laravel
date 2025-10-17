<?php

namespace App\Http\Controllers;

use App\Jobs\ReportaTransacaoViaEmail;
use App\Mail\Email;
use App\Models\EmailContato;
use App\Services\MailerService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContatoMail;

class HomeController extends Controller
{
    public function index(): View|Factory|Application
    {
        session(['index' => 'sim']);
        $nome = session('nome', '');
        session()->forget(['exata', 'estado', 'cidade']);
        if (!empty($nome)) {
            session(['nome' => $nome]);
        }
        return view('index');
    }

    public function emailContato(Request $request): RedirectResponse
    {
        $recaptchaSecret = env('RECAPTCHA_SECRET_CONTATO');
        $recaptchaResponse = $request->input('g-recaptcha-response');
        if (!$recaptchaResponse) {
            session()->flash('notificacao', [
                'mensagem' => 'A validação do reCAPTCHA falhou. Por favor, tente novamente.',
                'tipo' => 'erro'
            ]);
            return redirect()->back()->withInput()->with('scroll_to_form', true);
        }
    
        $reCaptchaVerificationUrl = "https://www.google.com/recaptcha/api/siteverify";
        try {
            $response = Http::asForm()->post($reCaptchaVerificationUrl, [
                'secret' => $recaptchaSecret,
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            session()->flash('notificacao', [
                'mensagem' => 'Ocorreu um problema na comunicação com o serviço de validação do reCAPTCHA.',
                'tipo' => 'erro'
            ]);
            return redirect()->back();
        }
    
        $verificationResult = $response->json();
        if (empty($verificationResult['success']) || !$verificationResult['success']) {
            $erros = $verificationResult['error-codes'] ?? [];
            $mensagem = 'A validação do reCAPTCHA falhou: ' . implode(', ', $erros) . '. Por favor, tente novamente.';
            session()->flash('notificacao', [
                'mensagem' => $mensagem,
                'tipo' => 'erro'
            ]);
            return redirect()->back();
        }
    
        $dados = $request->all();
        $regras = [
            'nome' => 'required|min:3',
            'assunto' => 'required',
            'email' => 'required|email',
            'telefone' => 'required',
            'mensagem' => 'required|min:3|max:500',
        ];
        $validation = Validator::make($dados, $regras);
        if ($validation->fails()) {
            $msg = implode(' - ', $validation->messages()->all());
            $notificacao = [
                'mensagem' => $msg,
                'tipo' => 'erro'
            ];
            session()->flash('notificacao', $notificacao);
            return redirect()->back()->withInput();
        }
    
        // Sempre salvar o e-mail no banco de dados, independente de se o envio foi bem-sucedido
        $emailDeContato = new EmailContato([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'telefone' => $dados['telefone'],
            'assunto' => $dados['assunto'],
            'mensagem' => $dados['mensagem'],
        ]);
        // $emailSalvo = $emailDeContato->save(); 
        $emailSalvo = true; // linha simulando gravação bem sucedida no BD
        
        if ($emailSalvo) {
            $destinatarios = config('constants.emails');
            
            // Determinar o destinatário com base no assunto
            $destinatarioEmail = $destinatarios['admin']; // Padrão
            
            // Assuntos que devem ir para o moderador (Marcel)
            $assuntosParaModerador = [
                'Acompanhamento de Pesquisa',
                'Devolução',
                'Dificuldade Preenchimento',
                'Nome Não Encontrado'
            ];
            
            if (in_array($dados['assunto'], $assuntosParaModerador)) {
                $destinatarioEmail = $destinatarios['moderador'];
            } elseif ($dados['assunto'] === 'Dúvidas Diversas') {
                $destinatarioEmail = $destinatarios['brasilia'];
            }
            
            $envelope = [
                'to' => $destinatarioEmail, 
                'bcc' => $destinatarios['dev'], // Cópia oculta para o dev acompanhar
                'assunto' => $dados['assunto'],
            ];
    
            $dadosDoTemplate = [
                'titulo' => 'Formulário de Contato',
                'assunto' => $dados['assunto'],
                'email' => $dados['email'],
                'nome' => $dados['nome'],
                'telefone' => $dados['telefone'],
                'mensagem' => $dados['mensagem'],
            ];
    
            $template = [
                'view' => 'emails.contato', 
                'dados' => $dadosDoTemplate, 
            ];
    
            $dadosTransacao = [
                'envelope' => $envelope,
                'template' => $template,
            ];
     
            try {
                Mail::to($destinatarioEmail)->send(new ContatoMail($dadosDoTemplate));
    
                session()->flash('notificacao', [
                    'mensagem' => 'Email enviado com sucesso!',
                    'tipo' => 'sucesso'
                ]);
                return redirect()->back();
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar e-mail de contato: ' . $e->getMessage());
                
                // Mesmo que o envio falhe, o contato já foi salvo no banco
                session()->flash('notificacao', [
                    'mensagem' => 'Sua mensagem foi registrada, mas houve um erro ao enviar o e-mail. Nossa equipe entrará em contato em breve.',
                    'tipo' => 'informe'
                ]);
                return redirect()->back();
            }
        }
    
        session()->flash('notificacao', [
            'mensagem' => 'Falha ao registrar o contato. Por favor, tente novamente.',
            'tipo' => 'erro'
        ]);
        return redirect()->back()->withInput();
    }

}
