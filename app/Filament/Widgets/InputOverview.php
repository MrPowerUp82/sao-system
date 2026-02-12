<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;
use App\Models\Contract;
use App\Models\Addition;
use App\Models\GeneralizedTransition;
use Carbon\Carbon;

class InputOverview extends BaseWidget
{
    public $start_date;
    public $end_date;
    public $temporaryTransitions = [];

    public function mount($start_date = null, $end_date = null, $temporaryTransitions = []): void
    {
        $this->temporaryTransitions = $temporaryTransitions;
        if ($start_date) {
            $start_date = Carbon::parse($start_date);
        }
        if ($end_date) {
            $end_date = Carbon::parse($end_date);
        }
    }
    
    protected function getTemporaryTransactionsCollection()
    {
        return collect($this->temporaryTransitions)->map(function ($item) {
            $item['start_date'] = Carbon::parse($item['start_date']);
            if (isset($item['end_date'])) {
                $item['end_date'] = Carbon::parse($item['end_date']);
            }
            return (object) $item;
        });
    }
    protected function getStats(): array
    {
        $start_date = $this->start_date ?? Carbon::create(now()->year, now()->month, 1);
        $end_date = $this->end_date ?? now()->endOfMonth();
        $tempTransactions = $this->getTemporaryTransactionsCollection();
        
        $transactions_1_v = GeneralizedTransition::whereDate('start_date', '>=', $start_date)
            ->where('input', 1)
            ->where('type', 'v')
            ->where('fix', 0)
            ->get()
            ->map(function ($transactions) {
                return $transactions->total_value;
            });
        $temp_1_v = $tempTransactions->filter(fn($t) => $t->start_date >= $start_date && $t->input == 1 && $t->type == 'v' && ($t->fix ?? 0) == 0)
            ->map(fn($t) => $t->total_value);
        $transactions_1_v = $transactions_1_v->merge($temp_1_v)->toArray();
        
        $transactions_1_p = GeneralizedTransition::whereDate('start_date', '<=', $end_date)
            ->whereDate('end_date', '>=', $start_date)
            ->where('input', 1)
            ->where('type', 'p')
            ->where('fix', 0)
            ->get()
            ->map(function ($transactions) {
                return $transactions->installment_value;
            });
        $temp_1_p = $tempTransactions->filter(fn($t) => isset($t->end_date) && $t->start_date <= $end_date && $t->end_date >= $start_date && $t->input == 1 && $t->type == 'p' && ($t->fix ?? 0) == 0)
            ->map(fn($t) => $t->installment_value ?? 0);
        $transactions_1_p = $transactions_1_p->merge($temp_1_p)->toArray();
        
        $transactions_0_v = GeneralizedTransition::whereDate('start_date', '>=', $start_date)
            ->where('input', 0)
            ->where('type', 'v')
            ->where('fix', 0)
            ->get()
            ->map(function ($transactions) {
                return $transactions->total_value;
            });
        $temp_0_v = $tempTransactions->filter(fn($t) => $t->start_date >= $start_date && $t->input == 0 && $t->type == 'v' && ($t->fix ?? 0) == 0)
            ->map(fn($t) => $t->total_value);
        $transactions_0_v = $transactions_0_v->merge($temp_0_v)->toArray();
        
        $transactions_0_p = GeneralizedTransition::whereDate('start_date', '<=', $end_date)
            ->whereDate('end_date', '>=', $start_date)
            ->where('input', 0)
            ->where('type', 'p')
            ->where('fix', 0)
            ->get()
            ->map(function ($transactions) {
                return $transactions->installment_value;
            });
        $temp_0_p = $tempTransactions->filter(fn($t) => isset($t->end_date) && $t->start_date <= $end_date && $t->end_date >= $start_date && $t->input == 0 && $t->type == 'p' && ($t->fix ?? 0) == 0)
            ->map(fn($t) => $t->installment_value ?? 0);
        $transactions_0_p = $transactions_0_p->merge($temp_0_p)->toArray();
        
        $transactions_1_v_sum = GeneralizedTransition::whereDate('start_date', '>=', $start_date)
            ->where('input', 1)
            ->where('type', 'v')
            ->where('fix', 0)
            ->get()
            ->sum('total_value');
        $temp_1_v_sum = $tempTransactions->filter(fn($t) => $t->start_date >= $start_date && $t->input == 1 && $t->type == 'v' && ($t->fix ?? 0) == 0)
            ->sum('total_value');
        $transactions_1_v_sum += $temp_1_v_sum;
        
        $transactions_1_p_sum = GeneralizedTransition::whereDate('start_date', '<=', $end_date)
            ->whereDate('end_date', '>=', $start_date)
            ->where('input', 1)
            ->where('type', 'p')
            ->where('fix', 0)
            ->get()
            ->sum('installment_value');
        $temp_1_p_sum = $tempTransactions->filter(fn($t) => isset($t->end_date) && $t->start_date <= $end_date && $t->end_date >= $start_date && $t->input == 1 && $t->type == 'p' && ($t->fix ?? 0) == 0)
            ->sum('installment_value');
        $transactions_1_p_sum += $temp_1_p_sum;
        
        $transactions_0_v_sum = GeneralizedTransition::whereDate('start_date', '>=', $start_date)
            ->where('input', 0)
            ->where('type', 'v')
            ->where('fix', 0)
            ->get()
            ->sum('total_value');
        $temp_0_v_sum = $tempTransactions->filter(fn($t) => $t->start_date >= $start_date && $t->input == 0 && $t->type == 'v' && ($t->fix ?? 0) == 0)
            ->sum('total_value');
        $transactions_0_v_sum += $temp_0_v_sum;
        
        $transactions_0_p_sum = GeneralizedTransition::whereDate('start_date', '<=', $end_date)
            ->whereDate('end_date', '>=', $start_date)
            ->where('input', 0)
            ->where('type', 'p')
            ->where('fix', 0)
            ->get()
            ->sum('installment_value');
        $temp_0_p_sum = $tempTransactions->filter(fn($t) => isset($t->end_date) && $t->start_date <= $end_date && $t->end_date >= $start_date && $t->input == 0 && $t->type == 'p' && ($t->fix ?? 0) == 0)
            ->sum('installment_value');
        $transactions_0_p_sum += $temp_0_p_sum;

        $transactions_1_f = GeneralizedTransition::where('input', 1)
            ->where('fix', 1)
            ->get()
            ->map(function ($transactions) {
                return $transactions->total_value;
            });
        $temp_1_f = $tempTransactions->filter(fn($t) => $t->input == 1 && ($t->fix ?? 0) == 1)
            ->map(fn($t) => $t->total_value);
        $transactions_1_f = $transactions_1_f->merge($temp_1_f)->toArray();
        
        $transactions_0_f = GeneralizedTransition::where('input', 0)
            ->where('fix', 1)
            ->get()
            ->map(function ($transactions) {
                return $transactions->total_value;
            });
        $temp_0_f = $tempTransactions->filter(fn($t) => $t->input == 0 && ($t->fix ?? 0) == 1)
            ->map(fn($t) => $t->total_value);
        $transactions_0_f = $transactions_0_f->merge($temp_0_f)->toArray();
        
        $transactions_1_f_sum = GeneralizedTransition::where('input', 1)
            ->where('fix', 1)
            ->get()
            ->sum('total_value');
        $temp_1_f_sum = $tempTransactions->filter(fn($t) => $t->input == 1 && ($t->fix ?? 0) == 1)
            ->sum('total_value');
        $transactions_1_f_sum += $temp_1_f_sum;
        
        $transactions_0_f_sum = GeneralizedTransition::where('input', 0)
            ->where('fix', 1)
            ->get()
            ->sum('total_value');
        $temp_0_f_sum = $tempTransactions->filter(fn($t) => $t->input == 0 && ($t->fix ?? 0) == 1)
            ->sum('total_value');
        $transactions_0_f_sum += $temp_0_f_sum;

        $total = $transactions_1_v_sum + $transactions_1_p_sum + $transactions_1_f_sum - $transactions_0_v_sum - $transactions_0_p_sum - $transactions_0_f_sum;
        return [
            Stat::make("Soma Total das Mensalidades:", "R$ {$total}")
                // ->description('32k increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([
                    $transactions_1_v_sum,
                    -1 * $transactions_0_v_sum,
                    $transactions_1_p_sum,
                    -1 * $transactions_0_p_sum,
                    $transactions_1_f_sum,
                    -1 * $transactions_0_f_sum
                ])
                ->color($total > 0 ? 'success' : 'danger'),
            Stat::make("Valor Total de Entradas à vista:", "R$ {$transactions_1_v_sum}")
                // ->description('32k increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($transactions_1_v)
                ->color('success'),
            Stat::make("Valor Total de Parcelas de Entradas:", "R$ {$transactions_1_p_sum}")
                // ->description('32k increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($transactions_1_p)
                ->color('success'),
            Stat::make("Valor Total de Entradas:", "R$ " . ($transactions_1_v_sum + $transactions_1_p_sum + $transactions_1_f_sum))
                // ->description('32k increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart(array_merge($transactions_1_v, $transactions_1_p, $transactions_1_f))
                ->color('success'),
            Stat::make("Valor Total de Saídas à vista:", "R$ {$transactions_0_v_sum}")
                // ->description('32k increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($transactions_0_v)
                ->color('danger'),
            Stat::make("Valor Total de Parcelas de Saídas:", "R$ {$transactions_0_p_sum}")
                // ->description('32k increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($transactions_0_p)
                ->color('danger'),
            Stat::make("Valor Total de Entradas Fixas:", "R$ {$transactions_1_f_sum}")
                // ->description('32k increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($transactions_1_f)
                ->color('success'),
            Stat::make("Valor Total de Saídas Fixas:", "R$ {$transactions_0_f_sum}")
                // ->description('32k increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($transactions_0_f)
                ->color('danger'),
            Stat::make("Valor Total de Saídas:", "R$ " . ($transactions_0_v_sum + $transactions_0_p_sum + $transactions_0_f_sum))
                // ->description('32k increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart(array_merge($transactions_0_v, $transactions_0_p, $transactions_0_f))
                ->color('danger'),
        ];
    }
}
