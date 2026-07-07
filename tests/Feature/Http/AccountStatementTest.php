<?php

use App\Models\Person;
use App\Models\Supplier;
use App\Models\User;
use App\Livewire\AccountStatements\AccountStatementComponent;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('renders account statement page', function () {
    Livewire::test(AccountStatementComponent::class)
        ->assertOk();
});

test('can switch between supplier and customer tabs', function () {
    Livewire::test(AccountStatementComponent::class)
        ->set('activeTab', 'customer')
        ->assertSet('activeTab', 'customer')
        ->set('activeTab', 'supplier')
        ->assertSet('activeTab', 'supplier');
});
