<?php

namespace App\Livewire\AccountBoxes;


use App\Models\Movement;
use App\Services\CashBalanceService;
use App\Services\PersonSupplierService;
use App\Models\Box;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BoxFormComponent extends Component
{
    public $id;
    public $amount, $type, $date, $description, $number_check;
    public $ci, $full_name, $person_id, $phone;
    public $date_account, $account_id;

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
    }

    public function onSupplierSelected($payload)
    {
        $this->ci = $payload['ci'];
        $this->full_name = $payload['full_name'];
        $this->phone = $payload['phone'];
        $this->person_id = $payload['person_id'];
    }

    public function store()
    {
        $this->validate([
            'ci' => 'required|string|max:15',
            'full_name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:15',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:D,C',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'number_check' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () {
            $supplier = app(PersonSupplierService::class)->resolve(
                $this->ci,
                $this->full_name,
                $this->phone
            );

            $movement = Movement::create([
                'date'        => $this->date,
                'description' => $this->description,
                'type'        => $this->type,
                'amount'      => $this->amount,
                'person_id'   => $supplier->person_id,
                'user_id'     => auth()->id(),
            ]);

            $movement->box()->create([]);

            app(CashBalanceService::class)->recalculateFromDate($movement->date);
        });

        return redirect()->route('accounts.box', [
            'date' => $this->date_account,
            'id'   => $this->account_id
        ]);
    }

    public function update()
    {
        $this->validate([
            'ci' => 'required|string|max:15',
            'full_name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:15',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:D,C',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'number_check' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () {
            $movement = Movement::with('person', 'box')->findOrFail($this->id);

            $supplier = app(PersonSupplierService::class)->resolve(
                $this->ci,
                $this->full_name,
                $this->phone
            );

            $movement->update([
                'date'        => $this->date,
                'description' => $this->description,
                'type'        => $this->type,
                'amount'      => $this->amount,
                'person_id'   => $supplier->person_id,
            ]);

            app(CashBalanceService::class)->recalculateFromDate($this->date);
        });

        return redirect()->route('accounts.box', [
            'date' => $this->date_account,
            'id'   => $this->account_id
        ]);
    }

    public function clear()
    {
        $this->reset(['id', 'ci', 'full_name', 'phone', 'amount', 'type', 'date', 'description', 'number_check', 'person_id']);
        $this->resetValidation();
        $this->dispatch('close-modal');
    }
}