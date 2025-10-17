@extends('layouts.email')

@section('content')
    <table align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td style="text-align: center; padding: 20px; background-color: #ffc107; color: white; font-size: 24px; font-weight: bold;">
                Homenagem Recebida!
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; background-color: #ffffff; color: #333333; font-size: 16px; line-height: 1.5;">
                <p>Olá, {{ $nome }}</p>
                <p>É com grande alegria que confirmamos o recebimento da sua homenagem. Ela agora está <strong>aguardando
                        moderação</strong> e será analisada em breve.
                </p>
                <p>Muito obrigado por compartilhar sua homenagem conosco. Assim que a moderação for concluída,
                    entraremos em contato para informar o status.</p>
                <p>Caso tenha alguma dúvida ou queira mais informações, não hesite em entrar em contato conosco.</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; text-align: center; background-color: #f8f9fa; color: #6c757d; font-size: 14px;">
                <p>Equipe {{ config('app.name') }}</p>
                <p>Este é um email automático, por favor, não responda a esta mensagem.</p>
            </td>
        </tr>
    </table>
@endsection
