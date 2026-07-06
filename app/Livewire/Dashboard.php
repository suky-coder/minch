<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Cooperative;
use App\Models\Movement;
use App\Models\Retention;
use App\Models\Supplier;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        // ── KPI Cards ──
        $totalSuppliers = Supplier::count();
        $totalCooperatives = Cooperative::count();
        $totalMovements = Movement::count();

        $retencionesMes = Retention::whereBetween('date', [$monthStart, $monthEnd])->count();
        $montoRetencionesMes = Retention::whereBetween('date', [$monthStart, $monthEnd])->sum('amount');

        // Cash balance (Box movements: D + B - C)
        $boxDebit = Movement::whereHas('box')->whereIn('type', ['D', 'B'])->sum('amount');
        $boxCredit = Movement::whereHas('box')->where('type', 'C')->sum('amount');
        $saldoCaja = $boxDebit - $boxCredit;

        // Bank balance (Transaction movements: D + B - C)
        $bankDebit = Movement::whereHas('transaction')->whereIn('type', ['D', 'B'])->sum('amount');
        $bankCredit = Movement::whereHas('transaction')->where('type', 'C')->sum('amount');
        $saldoBancos = $bankDebit - $bankCredit;

        // ── Monthly movement chart (last 12 months) ──
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i));
        }

        $monthlyData = $months->map(function ($month) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            return [
                'month' => $month->format('M Y'),
                'debit' => Movement::where('type', 'D')->whereBetween('date', [$start, $end])->sum('amount'),
                'credit' => Movement::where('type', 'C')->whereBetween('date', [$start, $end])->sum('amount'),
            ];
        });

        $movementChartData = [
            'categories' => $monthlyData->pluck('month')->toArray(),
            'debit' => $monthlyData->pluck('debit')->toArray(),
            'credit' => $monthlyData->pluck('credit')->toArray(),
        ];

        // ── Balance per account ──
        $accounts = Account::with('transactions.movement')->get();
        $balanceByAccount = [];
        foreach ($accounts as $account) {
            $debit = $account->transactions->flatMap->movement->where('type', 'D')->sum('amount')
                + $account->transactions->flatMap->movement->where('type', 'B')->sum('amount');
            $credit = $account->transactions->flatMap->movement->where('type', 'C')->sum('amount');
            $balanceByAccount[$account->name] = $debit - $credit;
        }

        $balanceChartData = [
            'categories' => array_keys($balanceByAccount),
            'series' => array_values($balanceByAccount),
        ];

        // ── Retention pie chart (S vs G amounts this month) ──
        $retByType = Retention::whereBetween('date', [$monthStart, $monthEnd])
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $retentionPieData = [
            'labels' => $retByType->keys()->map(fn ($t) => $t === 'S' ? 'Servicios' : 'Bienes')->toArray(),
            'series' => $retByType->values()->map(fn ($v) => (float) $v)->toArray(),
            'colors' => ['#4361EE', '#3A0CA3'],
        ];

        // ── Monthly retention amounts (last 6 months) ──
        $retMonths = collect();
        for ($i = 5; $i >= 0; $i--) {
            $retMonths->push(Carbon::now()->subMonths($i));
        }

        $retMonthly = $retMonths->map(function ($month) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            return [
                'month' => $month->format('M Y'),
                'total' => Retention::whereBetween('date', [$start, $end])->sum('amount'),
            ];
        });

        $retentionBarData = [
            'categories' => $retMonthly->pluck('month')->toArray(),
            'series' => $retMonthly->pluck('total')->toArray(),
        ];

        // ── Recent movements ──
        $recentMovements = Movement::with('person', 'transaction')
            ->latest()
            ->limit(5)
            ->get();

        // ── Recent retentions ──
        $recentRetentions = Retention::with('supplier.person')
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.dashboard', compact(
            'totalSuppliers',
            'totalCooperatives',
            'totalMovements',
            'retencionesMes',
            'montoRetencionesMes',
            'saldoCaja',
            'saldoBancos',
            'movementChartData',
            'balanceChartData',
            'retentionPieData',
            'retentionBarData',
            'recentMovements',
            'recentRetentions',
        ));
    }
}
