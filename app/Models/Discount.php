<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    protected $guarded = ['id'];

    public function retention(): BelongsTo
    {
        return $this->belongsTo(Retention::class);
    }

    public function taxe(): BelongsTo
    {
        return $this->belongsTo(Taxe::class);
    }
}
