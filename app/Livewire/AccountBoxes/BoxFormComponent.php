<?php

namespace App\Livewire\AccountBoxes;

use App\Models\Customer;
use App\Models\Movement;
use App\Models\Person;
use App\Services\CashBalanceService;
use App\Services\PersonSupplierService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BoxFormComponent extends Component
{
    public $id;

    public $amount;

    public $type;

    public $date;

    public $description;

    public $number_check;

    public $personType = 'supplier';

    public $ci;

    public $full_name;

    public $person_id;

    public $phone;

    public $date_account;

    public $account_id;

    public function mount($date_account = null, $account_id = null, $id = 0)
    {
        $this->date_account = $date_account;
        $this->account_id = $account_id;
        $this->date = $date_account ? Carbon::parse($date_account)->format('Y-m-d') : now()->format('Y-m-d');

        if ($id) {
            $movement = Movement::with('person', 'box')->find($id);
            if ($movement) {
                $this->id = $movement->id;
                $this->amount = $movement->amount;
                $this->type = $movement->type;
                $this->date = $movement->date;
                $this->description = $movement->description;

                $this->number_check = null;

                if ($movement->person) {
                    $this->ci = $movement->person->ci;
                    $this->full_name = $movement->person->full_name;
                    $this->phone = $movement->person->phone;
                    $this->person_id = $movement->person->id;

                    $movement->person->load(['supplier', 'customer']);
                    $this->personType = $movement->person->customer ? 'customer' : ($movement->person->supplier ? 'supplier' : $this->personType);
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.account-boxes.box-form-component');
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
        $this->ci = $payload['ci'];
        $this->full_name = $payload['full_name'];
        $this->phone = $payload['phone'];
        $this->person_id = $payload['person_id'];

        $person = Person::with(['supplier', 'customer'])->find($payload['person_id']);
        if ($person) {
            $this->personType = $person->customer ? 'customer' : ($person->supplier ? 'supplier' : $this->personType);
        }
    }

    public function store()
    {
        $this->authorize('Crear caja chica');

        $this->validate([
            'personType' => 'required|in:supplier,customer',
            'ci' => 'required|string|max:15',
            'full_name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:15',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:D,C',
            'date' => 'required|date|before_or_equal:today',
            'description' => 'required|string|max:255',
            'number_check' => 'nullable|string|max:20',
        ]);

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

            $movement->box()->create([]);

            app(CashBalanceService::class)->recalculateFromDate($movement->date);
        });

        return redirect()->route('accounts.box', [
            'date' => $this->date_account,
            'id' => $this->account_id,
        ]);
    }

    public function update()
    {
        $this->authorize('Editar caja chica');

        $this->validate([
            'personType' => 'required|in:supplier,customer',
            'ci' => 'required|string|max:15',
            'full_name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:15',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:D,C',
            'date' => 'required|date|before_or_equal:today',
            'description' => 'required|string|max:255',
            'number_check' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () {
            $movement = Movement::with('person', 'box')->findOrFail($this->id);

            $person = $this->resolvePerson();

            $movement->update([
                'date' => $this->date,
                'description' => $this->description,
                'type' => $this->type,
                'amount' => $this->amount,
                'person_id' => $person->id,
            ]);

            app(CashBalanceService::class)->recalculateFromDate($this->date);
        });

        return redirect()->route('accounts.box', [
            'date' => $this->date_account,
            'id' => $this->account_id,
        ]);
    }

    public function clear()
    {
        $this->reset(['id', 'ci', 'full_name', 'phone', 'amount', 'type', 'date', 'description', 'number_check', 'person_id', 'personType']);
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

        Customer::firstOrCreate(
            ['person_id' => $person->id]
        );

        return $person;
    }
}
