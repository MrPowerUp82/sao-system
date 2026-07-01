# Plano de Preparação para SaaS: SAO Financial System

Plano detalhado para converter o protótipo local do SAO Financial System em uma plataforma comercializável no modelo SaaS (Software as a Service).

## Project Type
- **Type:** WEB (Laravel 10 + Inertia React)

---

## 🛑 Socratic Gate: Perguntas para Definição (Feedback Requerido)

> [!IMPORTANT]
> Por favor, responda a estas perguntas no chat antes de iniciarmos a execução física das tarefas (elas guiarão a arquitetura detalhada).

### 1. **Gateway de Pagamento (Billing)**
*   **Pergunta:** Qual gateway de pagamento você prefere integrar para gerenciar as assinaturas recorrentes?
*   **Opções:**
    *   **Opção A (Recomendada):** Stripe (Padrão ouro global, com ótimo suporte no Laravel via Laravel Cashier).
    *   **Opção B:** Lemon Squeezy ou Paddle (Fácil gerenciamento tributário global).
    *   **Opção C:** Gateways nacionais (ex: ASAAS, Iugu, Kiwify, Hotmart) caso o foco seja exclusivamente o mercado brasileiro.
*   **Padrão se não informado:** Stripe (via Laravel Cashier).

### 2. **Modelo de Planos e Limites (Paywall)**
*   **Pergunta:** Quais serão as regras e limites para os usuários gratuitos vs. pagos?
*   **Opções:**
    *   **Plano Free:** Limite de 20 transações (Loot/Damage) por mês, 1 guilde cadastrada no máximo, sem acesso ao assistente Y.U.I.
    *   **Plano Pro (R$ 19,70/mês):** Acesso ilimitado a transações, guildas infinitas, uso ilimitado do chat assistente Y.U.I., e itens cosméticos lendários liberados.
*   **Padrão se não informado:** Limitar itens de inventário e transações para contas gratuitas.

### 3. **Segurança e Onboarding**
*   **Pergunta:** Devemos ativar a verificação de e-mail obrigatória no momento do cadastro para evitar bots e contas fantasmas no SaaS?
*   **Opções:**
    *   **Opção A:** Sim, enviar e-mail de ativação via provedor SMTP antes de permitir o acesso ao HUD do jogador.
    *   **Opção B:** Não, permitir entrada direta e notificar com banner para verificar depois.
*   **Padrão se não informado:** Opção A (Segurança recomendada para SaaS).

---

## Success Criteria

1. **Assinaturas Funcionais:** Checkout do usuário conectado, criação de assinatura ativa no banco e tratamento de webhooks de cancelamento/atraso.
2. **Paywalls Ativos:** Bloqueio automático de criação de transações ou novos itens no inventário caso o usuário atinja o limite do plano Free.
3. **Onboarding Seguro:** Remoção definitiva das rotas de teste/login forçado por e-mail (`/login/email`) e ativação da verificação de e-mail.
4. **Emails Operacionais:** Fluxo de e-mails transacionais (boas-vindas, redefinição de senha, confirmação de conta) configurado para servidor externo.

---

## Tech Stack

*   **Backend:** Laravel 10
*   **Frontend:** React 19 + Inertia.js
*   **Billing Engine:** Laravel Cashier (Stripe SDK)
*   **Database:** MySQL / PostgreSQL
*   **Mailing:** Mailgun / Amazon SES / Postmark

---

## File Structure Plan (Novos Arquivos)

```
sao-system/
├── app/
│   ├── Http/
│   │   └── Middleware/
│   │       └── [NEW] EnforceSaasLimits.php
│   ├── Services/
│   │   └── [NEW] SaasLimitService.php
│   └── Providers/
│       └── [NEW] SaasServiceProvider.php
├── config/
│   └── [NEW] cashier.php
├── resources/js/player/Pages/
│   └── [NEW] Subscription/
│       ├── Pricing.jsx
│       └── Portal.jsx
```

---

## Task Breakdown

### 🛠️ P0: Fundação & Banco de Dados (Database Architect / Security Auditor)

#### Task 0.1: Instalar Laravel Cashier e criar Migrations de Billing
*   **Agent:** `database-architect`
*   **Skill:** `database-design`
*   **Dependencies:** Nenhuma
*   **INPUT:** `composer.json` atual.
*   **OUTPUT:** Biblioteca `laravel/cashier` instalada e migrations de tabelas do Stripe criadas (`subscriptions`, `subscription_items`).
*   **VERIFY:** Executar `php artisan migrate` localmente e conferir tabelas de faturamento no banco.

---

### ⚙️ P1: Backend e Lógica de Negócio (Backend Specialist)

#### Task 1.1: Implementar o serviço de Limites SaaS (`SaasLimitService`)
*   **Agent:** `backend-specialist`
*   **Skill:** `clean-code`
*   **Dependencies:** Task 0.1
*   **INPUT:** Contagem de transações, itens no inventário e participação em guildas por usuário.
*   **OUTPUT:** `SaasLimitService` criado para validar se um `User` excedeu os limites do plano atual.
*   **VERIFY:** Criar teste de unidade validando se o serviço retorna `true` para usuários Free com mais de 20 transações.

#### Task 1.2: Middleware de Restrição e Paywalls
*   **Agent:** `backend-specialist`
*   **Skill:** `api-patterns`
*   **Dependencies:** Task 1.1
*   **INPUT:** Requisições para rotas `/player/trade` (post), `/player/inventory` (post).
*   **OUTPUT:** `EnforceSaasLimits` middleware interceptando criações e retornando erro 403 / redirect com mensagem de "Limite de Plano Atingido".
*   **VERIFY:** Tentar cadastrar transação em usuário Free mockado com limite excedido e verificar o bloqueio.

#### Task 1.3: Autenticação Segura de Produção e Emails
*   **Agent:** `security-auditor`
*   **Skill:** `vulnerability-scanner`
*   **Dependencies:** Nenhuma
*   **INPUT:** `routes/web.php` e `app/Http/Controllers/Player/AuthController.php`.
*   **OUTPUT:** Rota de backdoor `/login/email` removida; verificação de e-mail ativada no registro e na autenticação.
*   **VERIFY:** Tentar acessar `/login/email` e receber erro 404. Tentar registrar conta e ser redirecionado para a página de verificação de e-mail.

---

### 🎨 P2: Frontend & Interface de Assinatura (Frontend Specialist)

#### Task 2.1: Página de Pricing e Checkout
*   **Agent:** `frontend-specialist`
*   **Skill:** `frontend-design`
*   **Dependencies:** Task 0.1
*   **INPUT:** Elementos de UI do SAO (glassmorphism, laranja link start).
*   **OUTPUT:** Página interativa `Subscription/Pricing.jsx` integrada ao Stripe Checkout para início da assinatura.
*   **VERIFY:** Renderizar a tela de pricing, clicar em assinar e verificar redirecionamento para o Stripe Sandbox.

#### Task 2.2: Portal do Cliente (Manage Subscription)
*   **Agent:** `frontend-specialist`
*   **Skill:** `frontend-design`
*   **Dependencies:** Task 2.1
*   **INPUT:** Painel do jogador no React.
*   **OUTPUT:** Botão no perfil do jogador para acessar o Stripe Customer Portal, permitindo atualizar cartão ou cancelar assinatura.
*   **VERIFY:** Clicar em "Gerenciar Assinatura" e simular o redirecionamento com sucesso.

---

## Phase X: Final Verification

> 🔴 **Regra de Saída:** Não marcar o projeto como concluído antes que todos os testes passem.

### Automated Tests
- [x] Executar suíte de testes: `php artisan test`
- [x] Executar lint e análise estática: `npm run build`

### Manual Verification
- [x] Cadastrar novo player e certificar que a verificação de e-mail é enviada.
- [x] Simular um upgrade via Stripe Test Cards e checar se o plano Pro é atualizado na conta.
- [x] Verificar se os limites do plano Free são aplicados na criação de itens e transações. (Atualizado: plano único pago obrigatório, sem limites gratuitos)

---

## ✅ PHASE X COMPLETE
- Lint: ✅ Pass
- Security: ✅ No critical issues
- Build: ✅ Success
- Date: 2026-07-01
