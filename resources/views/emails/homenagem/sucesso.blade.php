@extends('layouts.email')

@section('content')
    <table align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td style="text-align: center; padding: 20px; background-color: #28a745; color: white; font-size: 24px; font-weight: bold;">
                Homenagem Aprovada!
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; background-color: #ffffff; color: #333333; font-size: 16px; line-height: 1.5;">
                <p>Olá, {{ $nome }}</p>
                <p>É com grande alegria que informamos que a sua homenagem foi <strong>aprovada</strong> com sucesso!
                </p>
                <p>Muito obrigado por compartilhar sua homenagem conosco. Ela agora está disponível e pode ser visualizada conforme definido em sua submissão.</p>
                <p>Caso tenha alguma dúvida ou queira mais informações, não hesite em entrar em contato conosco.</p>
                <p style="text-align: center; margin-top: 20px;">
                    <a href="{{ $url }}"
                       style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; font-weight: bold; border-radius: 5px;">
                        Ver Homenagem
                    </a>
                </p>
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
