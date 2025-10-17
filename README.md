# CNF – Cadastro Nacional de Falecidos (Laravel)

Este repositório contém o código-fonte do sistema CNF em Laravel.  

---

## Requisitos mínimos

- PHP 8.2 ou superior
- Composer
- MySQL 8 ou superior
- Node.js (opcional, apenas para compilação de assets com Vite)
- Extensões PHP comuns: mbstring, pdo, tokenizer, xml, gd

No Windows, pode ser utilizado o Laragon para instalar PHP, MySQL e Apache de forma simplificada.

---

## Instalação local

1. Clonar o repositório:
   ```bash
   git clone https://github.com/marcelsilva03/cnf-laravel.git
   cd cnf-laravel
   ```

2. Instalar as dependências:
   ```bash
   composer install
   ```

3. Criar o arquivo `.env` a partir do modelo:
   ```bash
   cp .env.modelo .env
   ```

4. Gerar a chave da aplicação:
   ```bash
   php artisan key:generate
   ```

5. Criar o banco de dados local:
   - Acesse o MySQL e crie um banco com o nome `cnf_local`
   - Ajuste no `.env` os campos `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD` conforme o seu ambiente local

6. Criar as tabelas (se já existirem migrations):
   ```bash
   php artisan migrate
   ```

   Caso ainda não existam migrations, aguarde a disponibilização dos arquivos de estrutura do banco.

7. (Opcional) Popular o banco com dados de teste, caso existam seeders:
   ```bash
   php artisan db:seed
   ```

8. Iniciar o servidor local:
   ```bash
   php artisan serve
   ```

   Acesse no navegador:
   ```
   http://127.0.0.1:8000
   ```

---

## Atualizando o projeto

Quando houver atualizações no repositório remoto:

```bash
git pull origin main
composer install
php artisan migrate
```

---

## Enviando alterações

1. Criar uma nova branch para cada ajuste:
   ```bash
   git checkout -b feature/nome-da-alteracao
   ```

2. Fazer as modificações necessárias e confirmar:
   ```bash
   git add .
   git commit -m "Descrição breve do ajuste realizado"
   ```

3. Enviar para o repositório remoto:
   ```bash
   git push origin feature/nome-da-alteracao
   ```

4. No GitHub, abrir um Pull Request e descrever as mudanças realizadas.

---

## Estrutura básica do projeto

```
app/            → código principal do Laravel (controllers, models, etc.)
database/       → migrations e seeders
public/         → arquivos acessíveis via navegador
resources/      → views Blade, CSS, JS
routes/         → rotas da aplicação
.env.modelo    → modelo de configuração local
```

---

## Segurança

- Nunca enviar o arquivo `.env` real para o repositório.
- Nunca utilizar credenciais reais de banco, Pix ou e-mail neste ambiente.
- Os testes locais devem ser realizados com dados falsos.
- Certificados e chaves (.p12, .pem, .key, .crt) não devem ser versionados.

---

## Para o administrador: gerar migrations a partir do banco real (opcional)

Caso seja necessário criar migrations automaticamente a partir do banco de dados de produção ou homologação:

1. Criar um usuário MySQL de leitura:
   ```sql
   CREATE USER 'cnf_readonly'@'%' IDENTIFIED BY 'senha_segura';
   GRANT SELECT, SHOW VIEW ON cnfbr_cnf3.* TO 'cnf_readonly'@'%';
   FLUSH PRIVILEGES;
   ```

2. Instalar o pacote de geração:
   ```bash
   composer require --dev kitloong/laravel-migrations-generator
   ```

3. Executar o comando de geração (ajustando a conexão no `.env`):
   ```bash
   php artisan migrate:generate --connection=cnf_source --skip-views
   ```

As migrations serão criadas dentro de `database/migrations/` e poderão ser commitadas normalmente, sem incluir dados reais.

---

