# StudyBounty

Uma plataforma de bounties para estudos onde estudantes podem publicar tarefas acadêmicas e receber respostas de outros estudantes mediante pagamento.

## Funcionalidades

- **Publicar Bounties**: Crie tarefas acadêmicas com prazo e preço
- **Submissões**: Estudantes podem enviar soluções
- **Sistema de Pagamento**: Carteira virtual integrada com Stripe
- **Avaliações**: Sistema de ratings para submissões aceitas
- **Painel Admin**: Gerenciamento via Filament
- **Login Social**: Google, Microsoft e GitHub

## Tecnologias

- Laravel 12
- Filament 3 (Admin Panel)
- Stripe (Pagamentos)
- SQLite (Banco de dados)
- TailwindCSS

## Instalação

```bash
# Instalar dependências
composer install
npm install

# Criar banco de dados
touch database/database.sqlite

# Migrar banco
php artisan migrate

# Iniciar servidor
php artisan serve
```

## Configuração

Copie o arquivo `.env.example` para `.env` e configure:

```env
# Stripe (obrigatório para pagamentos)
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# OAuth (opcional)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
MICROSOFT_CLIENT_ID=
MICROSOFT_CLIENT_SECRET=
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
```

## Acesso

- **Frontend**: http://localhost:8000
- **Admin**: http://localhost:8000/admin

### Usuário Admin
- Email: admin@studybounty.com
- Senha: admin123

## Licença

MIT
