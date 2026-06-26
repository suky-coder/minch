<?php

namespace App\Models;

use App\Helpers\HasConsecutiveNumber;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasConsecutiveNumber;
    protected $fillable = ['number', 'movement_id'];

    public function movement()
    {
        return $this->belongsTo(Movement::class);
    }
    protected static function tablaDetalle(): string
    {
        return 'boxes';
    }
    public function getNumberLabelAttribute(): string
    {
        
        return 'DOC-' . str_pad($this->number, 8, '0', STR_PAD_LEFT);
    }
    public function getFormattedLastNumberAttribute(): string
    {
        return str_pad($this->number, 8, '0', STR_PAD_LEFT);
    }
}
