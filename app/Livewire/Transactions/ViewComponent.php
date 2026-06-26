<?php

namespace App\Livewire\Transactions;

use App\Models\Account;
use App\Models\Movement;
use App\Models\Transaction;
use App\Models\Supplier;
use App\Services\MovementBalanceService;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use TallStackUi\Traits\Interactions;

class ViewComponent extends Component
{
    use Interactions;

    public $start, $end, $dateT, $account, $moneda;
    public $idAccount, $amount, $id, $type, $date, $doc, $description;
    public $person_id; // <-- Nuevo campo para el proveedor

    public function mount($id, $date)
    {
        $this->date = $date;
        $this->dateT = Carbon::createFromFormat('Y-m', $date);
        Carbon::setLocale('es');
        $this->dateT = ucfirst($this->dateT->translatedFormat('F')) . ' ' . $this->dateT->format('Y');

        $account = Account::find($id);
        $this->account = $account->account_number;
        $this->moneda = $account->currency_type == 'BOB' ? 'BOLIVIANOS' : ($account->currency_type == 'USD' ? 'DOLARES' : 'EUROS');
        $this->idAccount = $id;
        $this->start = Carbon::createFromFormat('Y-m', $date)->startOfMonth();
        $this->end = $this->start->copy()->endOfMonth();
    }

    public function render()
    {
        $transactions = Movement::whereHas('transaction', function ($query) {
            $query->where('account_id', $this->idAccount);
        })
        ->with(['transaction' => function ($query) {
            $query->where('account_id', $this->idAccount);
        }, 'person']) // Cargar la persona relacionada
        ->whereBetween('date', [$this->start, $this->end])
        ->orderBy('date', 'asc')
        ->select('*')
        ->selectRaw('SUM(CASE WHEN type IN ("D", "B") THEN amount ELSE -amount END) OVER (ORDER BY date) as balance')
        ->paginate(10);

        // Obtener lista de proveedores para el selector (todos los que tienen persona)
        $suppliers = Supplier::with('person')->get();

        return view('livewire.transactions.view-component', compact('transactions', 'suppliers'));
    }

    #[On('load::transaction')]
    public function edit(Transaction $transaction)
    {
        $movement = $transaction->movement; // Obtener el movimiento asociado

        $this->id = $transaction->id;
        $this->amount = $movement->amount;
        $this->type = $movement->type;
        $this->date = $movement->date;
        $this->doc = $transaction->number_check; // Asumo que es el campo de referencia
        $this->description = $movement->description;
        $this->person_id = $movement->person_id; // Cargar el proveedor asociado

        $this->js("window.\$tsui.open.modal('modal-id')");
    }

    public function update()
    {
        // Validar
        $this->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:D,C,B',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'person_id' => 'nullable|exists:people,id', // Validar que exista en people
        ]);

        // Obtener la transacción y su movimiento
        $transaction = Transaction::find($this->id);
        $movement = $transaction->movement;

        $movement->update([
            'date' => $this->date,
            'type' => $this->type,
            'amount' => $this->amount,
            'description' => $this->description,
            'person_id' => $this->person_id, // Asignar proveedor
        ]);

        // Actualizar la transacción (si tiene número de cheque)
        $transaction->update([
            'number_check' => $this->doc,
        ]);

        app(MovementBalanceService::class)->recalculateFromDate($movement->date, $this->idAccount);

        $this->toast()
            ->expandable(false)
            ->success('Movimiento actualizado', 'Registro modificado correctamente')
            ->send();

        $this->clear();
    }

    public function delete(Movement $transaction)
    {
        $date = $transaction->date;
        $transaction->delete();

        app(MovementBalanceService::class)->recalculateFromDate($date, $this->idAccount);

        $this->toast()
            ->expandable(false)
            ->success('Registro eliminado', 'El movimiento fue eliminado correctamente')
            ->send();
    }

    public function clear()
    {
        $this->reset(['id', 'amount', 'type', 'date', 'doc', 'description', 'person_id']);
        $this->resetValidation();
        $this->dispatch('close-modal');
    }
}
