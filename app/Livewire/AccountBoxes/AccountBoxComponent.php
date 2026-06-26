<?php

namespace App\Livewire\AccountBoxes;

use App\Models\Movement;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;

class AccountBoxComponent extends Component
{
    use Interactions, WithPagination;

    public $selectedDate;

    public function mount()
    {
        $this->selectedDate = Carbon::now()->format('Y-m');
    }

    public function render()
    {
        $start = Carbon::createFromFormat('Y-m', $this->selectedDate)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $movements = Movement::whereHas('box')
            ->with(['box', 'person'])
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->select('*')
            ->selectRaw('SUM(CASE WHEN type IN ("D", "B") THEN amount ELSE -amount END) OVER (ORDER BY date) as balance')
            ->paginate(10);

        return view('livewire.account-boxes.account-box-component', compact('movements'));
    }

    public function delete(Movement $movement)
    {
        $movement->delete();

        $this->toast()
            ->expandable(false)
            ->success('Registro eliminado', 'El movimiento fue eliminado correctamente')
            ->send();
    }
}
