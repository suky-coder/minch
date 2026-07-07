<?php

use App\Models\Taxe;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('renders the tax list', function () {
    Taxe::factory()->create(['name' => 'RC-IVA']);
    Taxe::factory()->create(['name' => 'IUE']);

    Livewire::test(\App\Livewire\Taxe\TaxeComponent::class)
        ->assertOk()
        ->assertSee('RC-IVA')
        ->assertSee('IUE');
});

test('can create a new tax', function () {
    Livewire::test(\App\Livewire\Taxe\TaxeComponent::class)
        ->set('name', 'TEST TAX')
        ->set('initials', 'TST')
        ->set('number', '999')
        ->set('applied_discount', 10.00)
        ->set('type', 'S')
        ->call('store')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('taxes', ['name' => 'TEST TAX']);
});

test('can edit a tax', function () {
    $taxe = Taxe::factory()->create(['name' => 'Original']);

    Livewire::test(\App\Livewire\Taxe\TaxeComponent::class)
        ->dispatch('load::taxe', $taxe->id)
        ->set('name', 'Updated')
        ->call('update')
        ->assertHasNoErrors();

    expect($taxe->fresh()->name)->toBe('Updated');
});

test('can delete a tax', function () {
    $taxe = Taxe::factory()->create();

    Livewire::test(\App\Livewire\Taxe\TaxeComponent::class)
        ->call('delete', $taxe->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('taxes', ['id' => $taxe->id]);
});
