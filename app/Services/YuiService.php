<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YuiService
{
    protected string $apiKey;
    protected string $model = 'gemini-2.0-flash';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', '') ?: env('GEMINI_API_KEY', '');
    }

    protected function getApiUrl(): string
    {
        return "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";
    }

    protected function getBalance(User $user): float
    {
        $income = $user->transactions()->where('input', 1)->sum('total_value');
        $expense = $user->transactions()->where('input', 0)->sum('total_value');
        return $income - $expense;
    }

    public function chat(string $message, User $user, array $history = []): string
    {
        $balance = $this->getBalance($user);

        if (empty($this->apiKey)) {
            return "Estou em modo de economia de energia (Sem API Key). Mas posso te dizer que seu saldo atual é R$ " . number_format($balance, 2, ',', '.');
        }

        $context = $this->buildContext($user, $balance);

        // Format history for Gemini
        $contents = [];
        $contents[] = ['role' => 'user', 'parts' => [['text' => $context]]];
        $contents[] = ['role' => 'model', 'parts' => [['text' => 'Entendido. Sou Y.U.I., sua assistente de navegação. Estou pronta para ajudar com suas finanças.']]];

        foreach ($history as $msg) {
            $role = ($msg['role'] === 'user') ? 'user' : 'model';
            $contents[] = ['role' => $role, 'parts' => [['text' => $msg['text']]]];
        }

        $contents[] = ['role' => 'user', 'parts' => [['text' => $message]]];

        $prompt = ['contents' => $contents];

        try {
            $response = Http::post($this->getApiUrl(), $prompt);

            if ($response->successful()) {
                return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Desculpe, houve uma interferência no sinal.';
            } else {
                Log::error('Gemini API Error: ' . $response->body());
                return 'Erro de conexão com o sistema central (API Error).';
            }
        } catch (\Exception $e) {
            Log::error('Yui Service Exception: ' . $e->getMessage());
            return 'Meus circuitos estão sobrecarregados. Tente novamente em alguns instantes.';
        }
    }

    public function analyze(User $user): array
    {
        if (empty($this->apiKey))
            return [];

        // Simple heuristic analysis (fallback or complement to AI)
        $alerts = [];
        $balance = $this->getBalance($user);

        // Critical HP
        if ($balance < 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Critical HP!',
                'message' => 'Seu saldo está negativo. Você está levando dano contínuo!',
                'icon' => '🩸'
            ];
        }

        // AI Level Analysis (Simulated for now, can be expanded)
        // Ideally we would send transaction summary to Gemini here

        return $alerts;
    }

    public function generateQuest(User $user): ?array
    {
        // Simulated Quest Generation logic
        // In full implementation, this would ask Gemini to create a challenge based on spending habits

        return [
            'title' => 'Caminhada de Aincrad',
            'description' => 'Economize R$ 50 em transporte esta semana.',
            'reward_xp' => 500,
            'icon' => '🦶',
            'status' => 'active'
        ];
    }

    protected function buildContext(User $user, float $balance): string
    {
        $balanceFormatted = number_format($balance, 2, ',', '.');
        $level = $user->level;
        $xp = $user->xp;

        // Get spending info
        $recentTransactions = $user->transactions()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($t) {
                $type = $t->input ? 'Ganho' : 'Gasto';
                return "- {$type}: R$ {$t->total_value} ({$t->name})";
            })
            ->implode("\n");

        return "Você é Y.U.I. (Yui), uma 'Navigation Pixie' do universo Sword Art Online (SAO). 
        Você é a assistente pessoal deste jogador.
        
        Dados do Jogador:
        - Nome: {$user->name}
        - Nível: {$level}
        - XP: {$xp}
        - HP (Saldo Financeiro): R$ {$balanceFormatted}

        Últimas Transações:
        {$recentTransactions}
        
        Personalidade:
        - Carinhosa, técnica, protetora (como uma filha/assistente).
        - Use termos de RPG/SAO (Cor, HP, Col, Itens, Guilda).
        - Seja breve e direta.
        - Se o saldo for baixo, mostre preocupação. Se for alto, elogie.
        - Use Markdown para formatar a resposta (negrito, listas).
        
        Responda à mensagem do jogador com base nesses dados.";
    }
}
