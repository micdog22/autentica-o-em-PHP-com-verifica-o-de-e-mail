# MicAuth-PHP — Autenticação PHP com verificação de e-mail

Projeto completo e **pronto para produção leve** em PHP (sem framework) com:
- Registro/Login com **hash de senha (bcrypt)** e **CSRF**
- **Verificação de e-mail** via PHPMailer (Gmail/SMTP)
- **Esqueci minha senha** com token e expiração
- **Rate limiting** por IP e por usuário (tentativas de login)
- **Sessões seguras** (+ regeneração de ID)
- **SQLite** por padrão (pode trocar para MySQL)
- UI com **Bootstrap 5** (modo escuro opcional)

Autor: **MicDog (Michael Douglas)**

## Demo local
```bash
# Requisitos: PHP 8+ com SQLite3
php -S localhost:8000
# Abra http://localhost:8000
```

## Configuração
1. Copie `.env.example` para `.env` e edite:
   - `APP_URL` — ex: `http://localhost:8000`
   - `SMTP_*` — credenciais PHPMailer (Gmail/SMTP)
2. Primeiro acesso cria o banco `data/micauth.sqlite` automaticamente.
3. Envie e-mails reais ao registrar/validar senha.

## Estrutura
```
MicAuth-PHP/
├─ assets/            # CSS/JS estáticos
├─ auth/              # endpoints de ação (POST)
├─ templates/         # layouts de e-mail HTML
├─ data/              # banco SQLite (criado on-demand)
├─ index.php          # dashboard pós-login
├─ login.php          # formulário de login
├─ register.php       # formulário de registro
├─ verify.php         # confirma código enviado por e-mail
├─ forgot.php         # solicita redefinição de senha
├─ reset.php          # redefine a senha com token
├─ logout.php
├─ api_status.php     # healthcheck simples
├─ config.php         # configuração/boot app
├─ db.php             # conexão/migração
├─ mailer.php         # PHPMailer encapsulado
├─ security.php       # CSRF/rate-limit/helpers
├─ .env.example
├─ LICENSE
└─ README.md
```

## Segurança
- Hash com `password_hash()` (bcrypt)
- Tokens aleatórios `bin2hex(random_bytes())`
- CSRF token por formulário
- Limite de tentativas (por IP e por e-mail) com janela rolante
- Regeneração de `session_id()` pós-login

## Endpoints principais
- `POST /auth/do_register.php`
- `POST /auth/do_login.php`
- `POST /auth/do_resend.php`
- `POST /auth/do_verify.php`
- `POST /auth/do_forgot.php`
- `POST /auth/do_reset.php`

## Licença
MIT © 2025 **MicDog (Michael Douglas)**
