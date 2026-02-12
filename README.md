# SAO Financial System ⚔️

Sistema financeiro gamificado inspirado no **Sword Art Online (SAO)**. Transforme seu controle de finanças pessoal em um RPG onde seu saldo é HP, suas metas são andares de Aincrad e seus investimentos são itens lendários.

![SAO HUD](https://i.imgur.com/placeholder.png)

## 🚀 Tecnologias

- **Backend**: Laravel 10 + FilamentPHP (Admin)
- **Frontend**: Inertia.js + React
- **Design system**: Custom CSS (Glassmorphism + Animations)
- **Database**: MySQL

---

## 🎮 Funcionalidades RPG

### 1. Player HUD (`/player`)
O painel principal imita a interface de um jogador de SAO:
- **HP Bar (Saldo)**: Verde (>50%), Amarelo (<50%) ou Vermelho (<20%).
- **XP Bar (Nível)**: Baseado no volume financeiro movimentado.
- **Stat Cards**: Loot (Entradas), Damage (Saídas), Balance (Saldo), Level.

### 2. Trade Log
Registro de transações com terminologia RPG:
- **Loot**: Receitas/Depósitos
- **Damage**: Despesas/Saques
- **Passive Effect**: Contas fixas/recorrentes

### 3. Aincrad Floor Map (`/player/floor-map`)
Metas financeiras visualizadas como uma torre de 100 andares.
- **Clear Floor**: Atingir a meta financeira libera o andar e dá XP bônus (+100 XP).
- **Progress Bar**: Visualização do progresso de cada meta.

### 4. Inventory System (`/player/inventory`)
Gerenciamento de ativos e passivos como itens de RPG:
- **⚔️ Weapon**: Cartões de Crédito
- **🛡️ Armor**: Seguros
- **💎 Material**: Investimentos (Cripto, Ações)
- **💍 Accessory**: Contas Bancárias
- **🧪 Consumable**: Assinaturas (Netflix, Gympass)
- *Features*: Raridade (Common a Legendary) com glow, filtros por slot.

### 5. Guild System (`/player/guild`)
Grupos financeiros (Família, Amigos) com ranking e estatísticas compartilhadas.
- **Invite Code**: Convite fácil para novos membros.
- **Roles**: Guild Master, Officer, Member.
- **Ranking**: Leaderboard interno baseado em Level/XP.

---

## 🔊 Imersão
- **Sound Effects**: 11 sons sintetizados via Web Audio API (Loot, Damage, Level Up, Floor Clear).
- **Level Up Animation**: Overlay fullscreen com partículas ao subir de nível.

---

## 🛠️ Como Instalar e Rodar

### Pré-requisitos
- PHP 8.1+
- Node.js & NPM
- MySQL

### Instalação

1. **Clone o repositório**
   ```bash
   git clone https://github.com/seu-usuario/sao-system.git
   cd sao-system
   ```

2. **Instale dependências**
   ```bash
   composer install
   npm install
   ```

3. **Configure o ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   # Configure o banco de dados no .env
   ```

4. **Rode as Migrations e Seeds (Dados de Teste)**
   ```bash
   # Isso cria o usuário 'kirito@sao.test' com dados de exemplo
   php artisan migrate:refresh --seed --class=SaoSeeder
   ```

5. **Inicie os servidores**
   ```bash
   # Terminal 1
   php artisan serve

   # Terminal 2
   npm run dev
   ```

6. **Acesse**
   - **Login**: `kirito@sao.test`
   - **Senha**: `password`
   - **URL**: `http://localhost:8000/player`

---

## 📜 Estrutura de Pastas (Player Area)

- `app/Http/Controllers/Player/`: Lógica dos controllers (Dashboard, Trade, Inventory, Guild)
- `resources/js/player/`: Frontend React
  - `Components/`: UI reutilizável (HpBar, SaoPanel, SoundManager)
  - `Pages/`: Views principais (Dashboard, Inventory, Guild)
  - `Layouts/`: Layout padrão com Sidebar e Topbar
- `resources/css/player.css`: Design System global

---

## 🛡️ Licença
Projeto Open Source sob licença MIT. **Link Start!** ⚔️
