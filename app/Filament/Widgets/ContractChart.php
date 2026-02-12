<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Contract;
use App\Models\Addition;
use App\Models\GeneralizedTransition;
use Carbon\Carbon;

class ContractChart extends ChartWidget
{
    public $start_date;
    public $end_date;
    public $temporaryTransitions = [];

    public function mount($start_date = null, $end_date = null, $temporaryTransitions = []): void
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->temporaryTransitions = $temporaryTransitions;
    }
    protected static ?string $heading = 'Registros X Mês';

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

    protected function getData(): array
    {
        $year = $this->start_date ? Carbon::parse($this->start_date)->year : now()->year;
        $tempTransactions = $this->getTemporaryTransactionsCollection();
        
        $transactions_1_f = GeneralizedTransition::whereYear('start_date', $year)
            ->where('input', 1)
            ->where('fix', 1)
            ->get()
            ->groupBy(function ($transactions) {
                return $transactions->start_date->format('F');
            })
            ->map(function ($transactions) {
                return $transactions->count();
            });
        $temp_1_f = $tempTransactions->filter(fn($t) => $t->start_date->year == $year && $t->input == 1 && ($t->fix ?? 0) == 1)
            ->groupBy(function ($t) {
                return $t->start_date->format('F');
            })
            ->map(fn($group) => $group->count());
            
        $transactions_0_f = GeneralizedTransition::whereYear('start_date', $year)
            ->where('input', 0)
            ->where('fix', 1)
            ->get()
            ->groupBy(function ($transactions) {
                return $transactions->start_date->format('F');
            })
            ->map(function ($transactions) {
                return $transactions->count();
            });
        $temp_0_f = $tempTransactions->filter(fn($t) => $t->start_date->year == $year && $t->input == 0 && ($t->fix ?? 0) == 1)
            ->groupBy(function ($t) {
                return $t->start_date->format('F');
            })
            ->map(fn($group) => $group->count());
            
        $transactions_1_v = GeneralizedTransition::whereYear('start_date', $year)
            ->where('input', 1)
            ->where('type', 'v')
            ->where('fix', 0)
            ->get()
            ->groupBy(function ($transactions) {
                return $transactions->start_date->format('F');
            })
            ->map(function ($transactions) {
                return $transactions->count();
            });
        $temp_1_v = $tempTransactions->filter(fn($t) => $t->start_date->year == $year && $t->input == 1 && $t->type == 'v' && ($t->fix ?? 0) == 0)
            ->groupBy(function ($t) {
                return $t->start_date->format('F');
            })
            ->map(fn($group) => $group->count());
            
        $transactions_1_p = GeneralizedTransition::whereYear('start_date', $year)
            ->where('input', 1)
            ->where('type', 'p')
            ->where('fix', 0)
            ->get()
            ->groupBy(function ($transactions) {
                return $transactions->start_date->format('F');
            })
            ->map(function ($transactions) {
                return $transactions->count();
            });
        $temp_1_p = $tempTransactions->filter(fn($t) => $t->start_date->year == $year && $t->input == 1 && $t->type == 'p' && ($t->fix ?? 0) == 0)
            ->groupBy(function ($t) {
                return $t->start_date->format('F');
            })
            ->map(fn($group) => $group->count());
            
        $transactions_0_v = GeneralizedTransition::whereYear('start_date', $year)
            ->where('input', 0)
            ->where('type', 'v')
            ->where('fix', 0)
            ->get()
            ->groupBy(function ($transactions) {
                return $transactions->start_date->format('F');
            })
            ->map(function ($transactions) {
                return $transactions->count();
            });
        $temp_0_v = $tempTransactions->filter(fn($t) => $t->start_date->year == $year && $t->input == 0 && $t->type == 'v' && ($t->fix ?? 0) == 0)
            ->groupBy(function ($t) {
                return $t->start_date->format('F');
            })
            ->map(fn($group) => $group->count());
            
        $transactions_0_p = GeneralizedTransition::whereYear('start_date', $year)
            ->where('input', 0)
            ->where('type', 'p')
            ->where('fix', 0)
            ->get()
            ->groupBy(function ($transactions) {
                return $transactions->start_date->format('F');
            })
            ->map(function ($transactions) {
                return $transactions->count();
            });
        $temp_0_p = $tempTransactions->filter(fn($t) => $t->start_date->year == $year && $t->input == 0 && $t->type == 'p' && ($t->fix ?? 0) == 0)
            ->groupBy(function ($t) {
                return $t->start_date->format('F');
            })
            ->map(fn($group) => $group->count());
        $labels = [];
        $data3 = [];
        $data4 = [];
        $data5 = [];
        $data6 = [];
        $data7 = [];
        $data8 = [];
        foreach (range(1, 12) as $month) {
            $monthName = now()->month($month)->format('F');
            $labels[] = $monthName;
            $data3[] = $transactions_1_v->get($monthName, 0) + $temp_1_v->get($monthName, 0);
            $data4[] = $transactions_1_p->get($monthName, 0) + $temp_1_p->get($monthName, 0);
            $data5[] = $transactions_0_v->get($monthName, 0) + $temp_0_v->get($monthName, 0);
            $data6[] = $transactions_0_p->get($monthName, 0) + $temp_0_p->get($monthName, 0);
            $data7[] = $transactions_1_f->get($monthName, 0) + $temp_1_f->get($monthName, 0);
            $data8[] = $transactions_0_f->get($monthName, 0) + $temp_0_f->get($monthName, 0);
        }
        // foreach ($contracts as $month => $count) {
        //     $labels[] = $month;
        //     $data[] = $count;
        // }
        // foreach ($additions as $month => $count) {
        //     $data2[] = $count;
        // }
        return [
            'datasets' => [
                [
                    'label' => 'Entradas à vista',
                    'data' => $data3,
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#FF6384',
                ],
                [
                    'label' => 'Entradas parceladas',
                    'data' => $data4,
                    'backgroundColor' => '#4BC0C0',
                    'borderColor' => '#4BC0C0',
                ],
                [
                    'label' => 'Saídas à vista',
                    'data' => $data5,
                    'backgroundColor' => '#9966FF',
                    'borderColor' => '#9966FF',
                ],
                [
                    'label' => 'Saídas parceladas',
                    'data' => $data6,
                    'backgroundColor' => '#FF9F40',
                    'borderColor' => '#FF9F40',
                ],
                [
                    'label' => 'Entradas Fixas',
                    'data' => $data7,
                    'backgroundColor' => '#FFCD56',
                    'borderColor' => '#FFCD56',
                ],
                [
                    'label' => 'Saídas Fixas',
                    'data' => $data8,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                ],
            ],
            'labels' => $labels,

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
