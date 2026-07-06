<?php

namespace App\Filament\Widgets;

use App\Models\Plan;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Laravel\Cashier\Subscription;

class SaasOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPlayers = User::count();
        $newPlayers30d = User::where('created_at', '>=', now()->subDays(30))->count();
        $verifiedPlayers = User::whereNotNull('email_verified_at')->count();

        $activeSubscriptions = Subscription::query()
            ->whereIn('stripe_status', ['active', 'trialing'])
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->count();

        $planPrice = (float) (Plan::where('is_active', true)->value('price') ?? 0);
        $mrr = $activeSubscriptions * $planPrice;

        // Novos jogadores por semana (últimas 8 semanas) para o mini-gráfico
        $signupChart = collect(range(7, 0))
            ->map(function (int $weeksAgo) {
                $start = now()->subWeeks($weeksAgo)->startOfWeek();

                return User::whereBetween('created_at', [$start, $start->copy()->endOfWeek()])->count();
            })
            ->all();

        return [
            Stat::make('Jogadores', number_format($totalPlayers, 0, ',', '.'))
                ->description("+{$newPlayers30d} nos últimos 30 dias")
                ->descriptionIcon('heroicon-m-user-plus')
                ->chart($signupChart)
                ->color('info'),
            Stat::make('E-mails Verificados', number_format($verifiedPlayers, 0, ',', '.'))
                ->description($totalPlayers > 0 ? round($verifiedPlayers / $totalPlayers * 100) . '% da base' : 'Sem jogadores')
                ->descriptionIcon('heroicon-m-envelope-open')
                ->color($totalPlayers > 0 && $verifiedPlayers / $totalPlayers >= 0.5 ? 'success' : 'warning'),
            Stat::make('Assinaturas Ativas', number_format($activeSubscriptions, 0, ',', '.'))
                ->description($totalPlayers > 0 ? round($activeSubscriptions / $totalPlayers * 100) . '% de conversão' : 'Sem jogadores')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('success'),
            Stat::make('MRR Estimado', 'R$ ' . number_format($mrr, 2, ',', '.'))
                ->description('Assinaturas ativas × preço do plano')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($mrr > 0 ? 'success' : 'gray'),
        ];
    }
}
