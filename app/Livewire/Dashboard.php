<?php

namespace App\Livewire;

use App\Models\Movement;
use App\Models\Supplier;
use App\Models\Account;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Estadísticas básicas
        $totalSuppliers = Supplier::count();
        $totalMovements = Movement::count();
        $totalDebit = Movement::where('type', 'D')->sum('amount');
        $totalCredit = Movement::where('type', 'C')->sum('amount');
        $totalBalance = $totalDebit - $totalCredit;

        // Movimientos por mes (últimos 6 meses)
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month);
        }

        $monthlyData = $months->map(function ($month) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $debit = Movement::where('type', 'D')
                ->whereBetween('date', [$start, $end])
                ->sum('amount');

            $credit = Movement::where('type', 'C')
                ->whereBetween('date', [$start, $end])
                ->sum('amount');

            return [
                'month' => $month->format('M Y'),
                'debit' => $debit,
                'credit' => $credit,
            ];
        });

        // Datos para el gráfico de barras
        $chartData = [
            'categories' => $monthlyData->pluck('month')->toArray(),
            'debit' => $monthlyData->pluck('debit')->toArray(),
            'credit' => $monthlyData->pluck('credit')->toArray(),
        ];

        // Gráfico de torta (distribución por tipo)
        $totalD = Movement::where('type', 'D')->count();
        $totalC = Movement::where('type', 'C')->count();
        $totalB = Movement::where('type', 'B')->count();

        $pieData = [
            'labels' => ['Debe', 'Haber', 'Saldo'],
            'series' => [$totalD, $totalC, $totalB],
            'colors' => ['#22c55e', '#ef4444', '#3b82f6'],
        ];

        // Saldo por cuenta
        $accounts = Account::with('transactions.movement')->get();
        $balanceByAccount = [];
        foreach ($accounts as $account) {
            $debit = $account->transactions->flatMap->movement->where('type', 'D')->sum('amount');
            $credit = $account->transactions->flatMap->movement->where('type', 'C')->sum('amount');
            $balance = $debit - $credit;
            $balanceByAccount[$account->name] = $balance;
        }

        // Datos para gráfico de barras horizontales
        $barData = [
            'categories' => array_keys($balanceByAccount),
            'series' => array_values($balanceByAccount),
        ];

        // Últimos 5 movimientos
        $recentMovements = Movement::with('person', 'transaction')
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.dashboard', compact(
            'totalSuppliers',
            'totalMovements',
            'totalDebit',
            'totalCredit',
            'totalBalance',
            'chartData',
            'pieData',
            'barData',
            'recentMovements'
        ));
    }
}