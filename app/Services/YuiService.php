<?php

namespace App\Services;

use App\Models\User;

class YuiService
{
    protected function getBalance(User $user): float
    {
        $income = $user->transactions()->where('input', 1)->sum('total_value');
        $expense = $user->transactions()->where('input', 0)->sum('total_value');
        return $income - $expense;
    }

    public function chat(string $message, User $user, array $history = []): string
    {
        $balance = $this->getBalance($user);
        $balanceFormatted = 'R$ ' . number_format($balance, 2, ',', '.');
        
        $msgLower = mb_strtolower($message);
        
        // 1. Trigger: Saldo / HP
        if ($this->containsAny($msgLower, ['saldo', 'money', 'dinheiro', 'col', 'hp', 'carteira', 'balance'])) {
            if ($balance < 0) {
                return "Cuidado, Papa! Seu HP (Saldo) está crítico: **{$balanceFormatted}**! Você está negativo e levando dano contínuo. Precisa de novos loots (receitas) urgente! 🩸";
            }
            return "Olá, Papa! Seu saldo atual (HP) é **{$balanceFormatted}** Cols. Seu status está seguro e pronto para os próximos andares! ⚔️";
        }
        
        // 2. Trigger: Gastos / Transações / Danos
        if ($this->containsAny($msgLower, ['gasto', 'despesa', 'saida', 'damage', 'combate', 'transac', 'danos', 'loot'])) {
            $recentTransactions = $user->transactions()
                ->latest()
                ->take(5)
                ->get();
                
            if ($recentTransactions->isEmpty()) {
                return "Não encontrei registros de combates recentes no seu log, Papa. Tudo limpo! 🛡️";
            }
            
            $list = $recentTransactions->map(function ($t) {
                $type = $t->input ? '🟢 Loot' : '🔴 Damage';
                $val = 'R$ ' . number_format($t->total_value, 2, ',', '.');
                return "- **{$type}**: {$val} (*{$t->name}*)";
            })->implode("\n");
            
            return "Aqui está o relatório das suas últimas ações de combate (transações), Papa:\n\n{$list}\n\nContinue monitorando seus gastos!";
        }
        
        // 3. Trigger: Quest
        if ($this->containsAny($msgLower, ['quest', 'missao', 'missão', 'desafio'])) {
            return "Sua Daily Quest ativa é: **Caminhada de Aincrad** (Economize R$ 50 em transporte esta semana). Você receberá **500 XP** ao completá-la! Vá na aba **Quests** para submeter. 📜";
        }
        
        // 4. Trigger: Dica
        if ($this->containsAny($msgLower, ['dica', 'conselho', 'sugestao', 'sugestão', 'ajuda', 'help'])) {
            $tips = [
                "Sempre defina um limite de gastos (Floor Target) para manter seu HP no verde, Papa!",
                "Evite gastar seus Cols em poções de luxo (compras por impulso) se o seu HP estiver abaixo de 20%!",
                "Realizar trades diários ajuda a manter a estabilidade do sistema e garante XP extra!",
                "Fazer parte de uma guilda divide os custos das raids financeiras. Compartilhe suas metas!",
                "Guardar pelo menos 15% dos ganhos na poupança serve como um 'Teleport Crystal' de emergência!"
            ];
            $tip = $tips[array_rand($tips)];
            return "Aqui vai uma dica de sobrevivência para Aincrad, Papa:\n\n💡 *\"{$tip}\"*";
        }
        
        // 5. Trigger: Greetings
        if ($this->containsAny($msgLower, ['oi', 'ola', 'olá', 'yui', 'bom dia', 'boa tarde', 'boa noite', 'hello'])) {
            return "Olá, Papa! Estou aqui para ajudar na sua navegação pelo sistema. Você quer ver seu **Saldo**, checar seus **Gastos**, ver sua **Quest** ou receber uma **Dica**? 🧚‍♀️";
        }
        
        // Default response based on balance status
        if ($balance < 0) {
            return "Papa, seu HP está no vermelho! (**{$balanceFormatted}**). Por favor, evite novos combates (gastos) ou busque novos loots imediatamente! 🩸";
        }
        if ($balance > 5000) {
            return "Seu HP está excelente, Papa! (**{$balanceFormatted}**). Você é um verdadeiro espadachim da linha de frente! Como posso te ajudar na navegação hoje? ⚔️";
        }
        
        return "Entendido, Papa! Saldo atual: **{$balanceFormatted}**. Como posso ajudar você na navegação financeira de Aincrad hoje? 🧚‍♀️";
    }

    public function analyze(User $user): array
    {
        $alerts = [];
        $balance = $this->getBalance($user);

        // Critical HP Alert if balance is negative
        if ($balance < 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Critical HP!',
                'message' => 'Seu saldo está negativo. Você está levando dano contínuo!',
                'icon' => '🩸'
            ];
        }

        return $alerts;
    }

    public function generateQuest(User $user): ?array
    {
        return [
            'title' => 'Caminhada de Aincrad',
            'description' => 'Economize R$ 50 em transporte esta semana.',
            'reward_xp' => 500,
            'icon' => '🦶',
            'status' => 'active'
        ];
    }

    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }
}
