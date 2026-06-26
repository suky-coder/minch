<?php

namespace App\Livewire\Accounts;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;
use Livewire\Attributes\On;

class AccountComponent extends Component
{
    use WithPagination, Interactions;
    public $id, $name, $account_number, $currency_type, $balance,$color ,$initials;
    public ?int $quantity = 10;

    public ?string $search = null;
    public function rules(){
        return [
            'name'=>['required','min:3','max:120','string'],
            'account_number'=>['required','min:5','max:20',Rule::unique('accounts','account_number')->ignore($this->id)],
            'initials'=>['required','min:1','max:7'],
            'color'=>['min:3','max:15'],
            'currency_type'=>['required','in:USD,EUR,BOB']
        ];
    }
    public function with(): array
    {
        return [
            'headers' => [
                ['index' => 'id', 'label' => '#'],
                ['index' => 'name', 'label' => 'Nombre'],
                ['index' => 'account_number', 'label' => 'Número'],
                ['index' => 'currency_type' , 'label' => 'Moneda'],
                ['index' => 'action1','label' => 'Sigla'],
                ['index' => 'action',],
            ],
            'rows' => Account::query()
                ->when($this->search, function (Builder $query) {
                    return $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('account_number', 'like', "%{$this->search}%");
                })
                ->paginate($this->quantity)
                ->withQueryString()
        ];
    }

    public function render()
    {
        return view('livewire.accounts.account-component');
    }
    public function delete(Account $account)
    {
        $account->delete();
        $this->toast()
            ->expandable(false)
            ->success('Registro eliminado', 'La cuenta fue eliminada')
            ->send();
    }
    public function store()
    {
        $this->validate();
        $account=Account::create([
            'name' => $this->name,
            'account_number' => $this->account_number,
            'color' => $this->color,
            'initials' => $this->initials,
            'currency_type' => $this->currency_type,
            ]);
        $this->toast()
            ->expandable(false)
            ->success('Registro almacenado', 'La cuenta fue registrada correctamente')
            ->send();
        $this->clear();
    }
    public function update()
    {
        $this->validate();
        $taxe = Account::find($this->id);
        $taxe->update([
            'name' => $this->name,
            'account_number' => $this->account_number,
            'color' => $this->color,
            'initials' => $this->initials,
            'currency_type' => $this->currency_type,
        ]);
        $this->toast()
            ->expandable(false)
            ->success('Registro actualizado', 'La cuenta fue actualizada')
            ->send();
        $this->clear();
    }
    #[On('load::account')]
    public function edit(Account $account)
    {
        $this->id = $account->id;
        $this->color = $account->color;
        $this->initials = $account->initials;
        $this->name = $account->name;
        $this->account_number = $account->account_number;
        $this->currency_type = $account->currency_type;
        $this->js("window.\$tsui.open.modal('modal-id')");
    }
    public function clear()
    {
        $this->dispatch('close-modal');
        $this->resetValidation();
        $this->reset(['id', 'name', 'account_number', 'currency_type','balance','initials']);
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
}
