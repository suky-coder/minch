<?php

use App\Models\Discount;
use App\Models\Retention;
use App\Models\Taxe;

test('belongs to retention', function () {
    $discount = Discount::factory()->create();

    expect($discount->retention)->not->toBeNull();
});

test('belongs to taxe', function () {
    $discount = Discount::factory()->create();

    expect($discount->taxe)->not->toBeNull();
});
