<?php

namespace App\Services;

use App\Models\Movement;
use Carbon\Carbon;

class MovementBalanceService
{
    public function recalculateFromDate(Carbon|string $date, int $accountId): void
    {
        $modifiedMonth = Carbon::parse($date)->startOfMonth();
        $nextMonth = $modifiedMonth->copy()->addMonth()->startOfMonth();

        $this->upsertBalanceMovement(
            $accountId,
            $nextMonth,
            $modifiedMonth->copy()->startOfMonth(),
            $modifiedMonth->copy()->endOfMonth()
        );

        $balanceRecords = Movement::query()
            ->join('transactions', 'movements.id', '=', 'transactions.movement_id')
            ->where('transactions.account_id', $accountId)
            ->where('movements.type', 'B')
            ->where('movements.date', '>', $modifiedMonth->copy()->endOfMonth())
            ->orderBy('movements.date')
            ->select('movements.*')
            ->get();

        foreach ($balanceRecords as $balanceRecord) {
            $balanceMonth = Carbon::parse($balanceRecord->date)->startOfMonth();
            $previousMonthStart = $balanceMonth->copy()->subMonth()->startOfMonth();
            $previousMonthEnd = $balanceMonth->copy()->subMonth()->endOfMonth();

            $balanceRecord->amount = $this->calculateMonthBalance(
                $accountId,
                $previousMonthStart,
                $previousMonthEnd
            );
            $balanceRecord->save();
        }
    }

    private function upsertBalanceMovement(
        int $accountId,
        Carbon $balanceMonth,
        Carbon $from,
        Carbon $to
    ): void {
        $amount = $this->calculateMonthBalance($accountId, $from, $to);

        $existing = Movement::query()
            ->join('transactions', 'movements.id', '=', 'transactions.movement_id')
            ->where('transactions.account_id', $accountId)
            ->where('movements.type', 'B')
            ->whereBetween('movements.date', [
                $balanceMonth->copy()->startOfMonth(),
                $balanceMonth->copy()->endOfMonth(),
            ])
            ->select('movements.*')
            ->first();

        if ($existing) {
            $existing->amount = $amount;
            $existing->save();

            return;
        }

        $balanceMovement = Movement::create([
            'date' => $balanceMonth->toDateString(),
            'description' => 'SALDO ANTERIOR',
            'type' => 'B',
            'amount' => $amount,
            'person_id' => null,
            'user_id' => auth()->id(),
        ]);

        $balanceMovement->transaction()->create([
            'account_id' => $accountId,
        ]);
    }

    private function calculateMonthBalance(int $accountId, Carbon $from, Carbon $to): float
    {
        $totalDebit = Movement::query()
            ->join('transactions', 'movements.id', '=', 'transactions.movement_id')
            ->where('transactions.account_id', $accountId)
            ->whereIn('movements.type', ['D', 'B'])
            ->whereBetween('movements.date', [$from, $to])
            ->sum('movements.amount');

        $totalCredit = Movement::query()
            ->join('transactions', 'movements.id', '=', 'transactions.movement_id')
            ->where('transactions.account_id', $accountId)
            ->where('movements.type', 'C')
            ->whereBetween('movements.date', [$from, $to])
            ->sum('movements.amount');

        return (float) ($totalDebit - $totalCredit);
    }
}
