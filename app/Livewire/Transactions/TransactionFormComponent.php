<?php

namespace App\Livewire\Transactions;

use App\Models\Movement;
use App\Models\Person;
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

    public $personType = 'supplier';

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

                    $movement->person->load(['supplier', 'customer']);
                    $this->personType = $movement->person->customer ? 'customer' : ($movement->person->supplier ? 'supplier' : $this->personType);
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

        $person = Person::with(['supplier', 'customer'])->find($payload['person_id']);
        if ($person) {
            $this->personType = $person->customer ? 'customer' : ($person->supplier ? 'supplier' : $this->personType);
        }
    }

    public function store()
    {
        $this->validate($this->rules());

        DB::transaction(function () {
            $person = $this->resolvePerson();

            $movement = Movement::create([
                'date' => $this->date,
                'description' => $this->description,
                'type' => $this->type,
                'amount' => $this->amount,
                'person_id' => $person->id,
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
            $person = $this->resolvePerson();

            $movement->update([
                'date' => $this->date,
                'description' => $this->description,
                'type' => $this->type,
                'amount' => $this->amount,
                'person_id' => $person->id,
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
            'personType' => 'required|in:supplier,customer',
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
        $this->reset(['ci', 'full_name', 'phone', 'amount', 'type', 'date', 'description', 'number_check', 'id', 'person_id', 'payment_type', 'personType']);
        $this->resetValidation();
        $this->dispatch('close-modal');
    }

    private function resolvePerson(): Person
    {
        if ($this->personType === 'supplier') {
            $supplier = app(PersonSupplierService::class)->resolve(
                $this->ci,
                $this->full_name,
                $this->phone
            );

            return $supplier->person;
        }

        $person = Person::firstOrCreate(
            ['ci' => $this->ci],
            ['full_name' => $this->full_name, 'phone' => $this->phone]
        );

        if ($person->wasRecentlyCreated === false) {
            $person->update(array_filter([
                'full_name' => $this->full_name,
                'phone' => $this->phone,
            ]));
        }

        \App\Models\Customer::firstOrCreate(
            ['person_id' => $person->id]
        );

        return $person;
    }
}
