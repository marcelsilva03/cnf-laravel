<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Template de Boas-vindas
        EmailTemplate::create([
            'name' => 'Boas-vindas',
            'subject' => 'Bem-vindo ao Sistema, {name}!',
            'content' => '<h1>Olá {name}!</h1>
<p>Agradecemos por se cadastrar em nosso sistema. Estamos muito felizes em ter você conosco!</p>
<p>Para começar a usar o sistema, você pode acessar sua conta através do botão abaixo.</p>
<p>Se precisar de ajuda, não hesite em nos contatar.</p>
<p>Atenciosamente,<br>Equipe do Sistema</p>',
            'is_html' => true,
            'action_text' => 'Acessar Sistema',
            'action_url' => '/dashboard',
            'variables' => [
                'name' => 'Nome do usuário',
                'email' => 'Email do usuário',
            ],
            'is_active' => true,
        ]);

        // Template de Recuperação de Senha
        EmailTemplate::create([
            'name' => 'Recuperação de Senha',
            'subject' => 'Recuperação de Senha - {name}',
            'content' => '<h1>Recuperação de Senha</h1>
<p>Olá {name},</p>
<p>Recebemos uma solicitação para redefinir sua senha. Se você não solicitou isso, pode ignorar este email.</p>
<p>Para redefinir sua senha, clique no botão abaixo:</p>
<p>Se o botão não funcionar, copie e cole o link abaixo no seu navegador:<br>{reset_url}</p>
<p>Este link expira em 60 minutos.</p>
<p>Atenciosamente,<br>Equipe do Sistema</p>',
            'is_html' => true,
            'action_text' => 'Redefinir Senha',
            'action_url' => '{reset_url}',
            'variables' => [
                'name' => 'Nome do usuário',
                'reset_url' => 'URL para redefinição de senha',
            ],
            'is_active' => true,
        ]);

        // Template de Notificação
        EmailTemplate::create([
            'name' => 'Notificação Geral',
            'subject' => '{title}',
            'content' => '<h1>{title}</h1>
<p>{message}</p>
<p>Atenciosamente,<br>Equipe do Sistema</p>',
            'is_html' => true,
            'action_text' => 'Ver Detalhes',
            'action_url' => '{action_url}',
            'variables' => [
                'title' => 'Título da notificação',
                'message' => 'Mensagem da notificação',
                'action_url' => 'URL da ação (opcional)',
            ],
            'is_active' => true,
        ]);

        // Template de Confirmação de Pedido
        EmailTemplate::create([
            'name' => 'Confirmação de Pedido',
            'subject' => 'Pedido #{order_number} Confirmado',
            'content' => '<h1>Pedido Confirmado</h1>
<p>Olá {customer_name},</p>
<p>Seu pedido #{order_number} foi confirmado com sucesso!</p>
<p><strong>Detalhes do Pedido:</strong></p>
<ul>
    <li>Data: {order_date}</li>
    <li>Valor Total: R$ {order_total}</li>
    <li>Status: {order_status}</li>
</ul>
<p>Você pode acompanhar seu pedido através do botão abaixo.</p>
<p>Atenciosamente,<br>Equipe do Sistema</p>',
            'is_html' => true,
            'action_text' => 'Ver Pedido',
            'action_url' => '/orders/{order_number}',
            'variables' => [
                'customer_name' => 'Nome do cliente',
                'order_number' => 'Número do pedido',
                'order_date' => 'Data do pedido',
                'order_total' => 'Valor total do pedido',
                'order_status' => 'Status do pedido',
            ],
            'is_active' => true,
        ]);

        // Template de Atualização de Status
        EmailTemplate::create([
            'name' => 'Atualização de Status',
            'subject' => 'Atualização: {item_name}',
            'content' => '<h1>Atualização de Status</h1>
<p>Olá {user_name},</p>
<p>O status de {item_name} foi atualizado para: <strong>{new_status}</strong></p>
<p><strong>Detalhes:</strong></p>
<ul>
    <li>Status Anterior: {old_status}</li>
    <li>Data da Atualização: {update_date}</li>
    <li>Responsável: {updated_by}</li>
</ul>
<p>Você pode ver mais detalhes clicando no botão abaixo.</p>
<p>Atenciosamente,<br>Equipe do Sistema</p>',
            'is_html' => true,
            'action_text' => 'Ver Detalhes',
            'action_url' => '{details_url}',
            'variables' => [
                'user_name' => 'Nome do usuário',
                'item_name' => 'Nome do item atualizado',
                'old_status' => 'Status anterior',
                'new_status' => 'Novo status',
                'update_date' => 'Data da atualização',
                'updated_by' => 'Nome do responsável pela atualização',
                'details_url' => 'URL para ver detalhes',
            ],
            'is_active' => true,
        ]);
    }
} 