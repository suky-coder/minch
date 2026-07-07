<?php

namespace App\Livewire\Transactions;

use App\Models\Movement;
use App\Services\MovementBalanceService;
use App\Services\PersonSupplierService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TransactionFormComponent extends Component
{
    public $id;

    public $amount;

    public $type;

    public $date;

    public $doc;

    public $description;

    public $number_check;

    public $payment_type = 'CH';

    public $ci;

    public $full_name;

    public $phone;

    public $person_id;

    public $date_account;

    public $account_id;

    public function mount($date_account, $account_id, $id = 0)
    {
        $this->date_account = $date_account;
        $this->date = $date_account;
        $this->account_id = $account_id;

        if ($id) {
            $movement = Movement::with(['person', 'transaction'])->find($id);
            if ($movement) {
                $this->id = $movement->id;
                $this->amount = $movement->amount;
                $this->type = $movement->type;
                $this->date = $movement->date;
                $this->description = $movement->description;
                $this->number_check = $movement->transaction?->number_check;
                $this->payment_type = $movement->transaction?->payment_type ?? 'CH';
                $this->doc = $movement->transaction?->number_check;

                if ($movement->person) {
                    $this->person_id = $movement->person->id;
                    $this->ci = $movement->person->ci;
                    $this->full_name = $movement->person->full_name;
                    $this->phone = $movement->person->phone;
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.transactions.transaction-form-component');
    }

    protected function getListeners()
    {
        return [
            'supplier-selected' => 'onSupplierSelected',
            'supplier-ci-manual' => 'onSupplierCiManual',
        ];
    }

    public function onSupplierCiManual($payload)
    {
        $this->ci = $payload['ci'];
        $this->person_id = null;
        $this->full_name = '';
        $this->phone = '';
    }

    public function onSupplierSelected($payload)
    {
        $this->person_id = $payload['person_id'];
        $this->ci = $payload['ci'];
        $this->full_name = $payload['full_name'];
        $this->phone = $payload['phone'];
    }

    public function store()
    {
        $this->validate($this->rules());

        DB::transaction(function () {
            $personSupplier = app(PersonSupplierService::class);
            $supplier = $personSupplier->resolve($this->ci, $this->full_name, $this->phone);

            $movement = Movement::create([
                'date' => $this->date,
                'description' => $this->description,
                'type' => $this->type,
                'amount' => $this->amount,
                'person_id' => $supplier->person_id,
                'user_id' => auth()->id(),
            ]);

            $movement->transaction()->create([
                'account_id' => $this->account_id,
                'payment_type' => $this->payment_type,
                'number_check' => $this->number_check ?: null,
            ]);

            app(MovementBalanceService::class)->recalculateFromDate($movement->date, $this->account_id);
        });

        return redirect()->route('transactions.view', [
            'date' => $this->date_account,
            'id' => $this->account_id,
        ]);
    }

    public function update()
    {
        $this->validate($this->rules());

        DB::transaction(function () {
            $movement = Movement::with('transaction')->findOrFail($this->id);
            $personSupplier = app(PersonSupplierService::class);
            $supplier = $personSupplier->resolve($this->ci, $this->full_name, $this->phone);

            $movement->update([
                'date' => $this->date,
                'description' => $this->description,
                'type' => $this->type,
                'amount' => $this->amount,
                'person_id' => $supplier->person_id,
            ]);

            $movement->transaction()->update([
                'account_id' => $this->account_id,
                'payment_type' => $this->payment_type,
                'number_check' => $this->number_check ?: null,
            ]);

            app(MovementBalanceService::class)->recalculateFromDate($movement->date, $this->account_id);
        });

        return redirect()->route('transactions.view', [
            'date' => $this->date_account,
            'id' => $this->account_id,
        ]);
    }

    protected function rules(): array
    {
        return [
            'ci' => 'required|string|max:15',
            'full_name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:15',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:D,C',
            'date' => 'required|date|before_or_equal:today',
            'description' => 'required|string|max:255',
            'number_check' => 'nullable|string|max:20',
            'payment_type' => 'required|in:CH,T',
        ];
    }

    public function clear()
    {
        $this->reset(['ci', 'full_name', 'phone', 'amount', 'type', 'date', 'description', 'number_check', 'id', 'person_id', 'payment_type']);
        $this->resetValidation();
        $this->dispatch('close-modal');
    }
}
