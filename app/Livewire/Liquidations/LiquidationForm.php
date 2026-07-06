<?php

namespace App\Livewire\Liquidations;

use Livewire\Component;

class LiquidationForm extends Component
{
    public $id = 0;

    public $metal = 'zn';

    public $ci;

    public $supplier_id;

    public $customer_id;

    public $full_name;

    public $nim;

    public $nit;

    public $concession;

    public $mine;

    public $municipality;

    public $name;

    public $contribution;

    public $lote;

    public $date;

    public $lab_quimico;

    public $number_lab;

    public $codigo;

    public $NIM = 0;

    public $Zn = 0;

    public $nimPb = 0;

    public $Pb = 0;

    public $refinacion = 0;

    public $maquila = 0;

    public $base = 0;

    public $dm = 0;

    public $AgUSD = 0;

    public $agNIM = 0;

    public function render()
    {
        return view('livewire.liquidations.liquidation-form');
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
        $this->supplier_id = null;
    }

    public function onSupplierSelected($payload)
    {
        $this->ci = $payload['ci'];
        $this->full_name = $payload['full_name'];
        $this->customer_id = $payload['id'];
        $this->nim = $payload['NIM'];
        $this->nit = $payload['NIT'];
        $this->concession = $payload['concession'];
        $this->mine = $payload['mine'];
        $this->municipality = $payload['municipality'];
        $this->name = $payload['name'];
        $this->contribution = $payload['contribution'] ?? null;
    }
}
