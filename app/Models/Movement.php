<?php

namespace App\Models;

use App\Helpers\NumberHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Movement extends Model
{
    protected $fillable = [
        'date',
        'description',
        'type',
        'amount',
        'document',
        'number_vol',
        'person_id',
        'user_id',
    ];

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function box(): HasOne
    {
        return $this->hasOne(Box::class);
    }

    public function getCalculateLabelAttribute()
    {
        return NumberHelper::toLiteral($this->amount);
    }

    public function getFormattedLastNumberAttribute()
    {
        return $this->transaction ? $this->transaction->number_check : '';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function getSupplierAttribute()
    {
        if ($this->person?->supplier) {
            return $this->person->supplier;
        }

        return $this->person;
    }
}
