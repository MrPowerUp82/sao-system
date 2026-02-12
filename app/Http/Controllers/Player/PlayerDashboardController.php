<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\GeneralizedTransition;
use App\Models\FinancialGoal;
use App\Services\XpService;
use Carbon\Carbon;
use Inertia\Inertia;

class PlayerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // HP Calculation (monthly balance)
        $monthlyIncome = GeneralizedTransition::where('user_id', $user->id)
            ->where('input', 1)
            ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->where(function ($q2) use ($startOfMonth) {
                    $q2->where('type', 'v')->whereDate('start_date', '>=', $startOfMonth);
                })->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) {
                    $q2->where('type', 'p')
                        ->whereDate('start_date', '<=', $endOfMonth)
                        ->whereDate('end_date', '>=', $startOfMonth);
                })->orWhere('fix', 1);
            })
            ->get()
            ->sum(fn($t) => $t->type === 'p' ? ($t->installment_value ?? 0) : $t->total_value);

        $monthlyExpense = GeneralizedTransition::where('user_id', $user->id)
            ->where('input', 0)
            ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->where(function ($q2) use ($startOfMonth) {
                    $q2->where('type', 'v')->whereDate('start_date', '>=', $startOfMonth);
                })->orWhere(function ($q2) use ($startOfMonth, $endOfMonth) {
                    $q2->where('type', 'p')
                        ->whereDate('start_date', '<=', $endOfMonth)
                        ->whereDate('end_date', '>=', $startOfMonth);
                })->orWhere('fix', 1);
            })
            ->get()
            ->sum(fn($t) => $t->type === 'p' ? ($t->installment_value ?? 0) : $t->total_value);

        $balance = $monthlyIncome - $monthlyExpense;
        $hpPercentage = $monthlyIncome > 0
            ? max(0, min(100, round(($balance / $monthlyIncome) * 100)))
            : ($balance >= 0 ? 100 : 0);

        // Recent trades
        $recentTrades = GeneralizedTransition::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'input' => (int) $t->input,
                'type' => $t->type,
                'total_value' => (float) $t->total_value,
                'installment_value' => $t->installment_value ? (float) $t->installment_value : null,
                'fix' => (int) $t->fix,
                'start_date' => $t->start_date?->format('Y-m-d'),
                'tags' => $t->tags,
                'created_at' => $t->created_at->format('d/m/Y'),
            ]);

        // Active floors
        $activeFloors = FinancialGoal::where('user_id', $user->id)
            ->whereIn('status', ['active', 'cleared'])
            ->orderBy('floor_number')
            ->take(5)
            ->get()
            ->map(fn($g) => [
                'id' => $g->id,
                'name' => $g->name,
                'target_amount' => (float) $g->target_amount,
                'current_amount' => (float) $g->current_amount,
                'floor_number' => $g->floor_number,
                'icon' => $g->icon,
                'status' => $g->status,
                'progress' => $g->getProgressPercentage(),
            ]);

        // XP progress
        $xpProgress = $user->getXpProgress();

        return Inertia::render('Dashboard', [
            'stats' => [
                'hp_percentage' => $hpPercentage,
                'monthly_income' => round($monthlyIncome, 2),
                'monthly_expense' => round($monthlyExpense, 2),
                'balance' => round($balance, 2),
                'month_label' => $now->translatedFormat('F Y'),
            ],
            'xp' => $xpProgress,
            'recent_trades' => $recentTrades,
            'active_floors' => $activeFloors,
        ]);
    }
}
