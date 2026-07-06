<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = ['ci', 'full_name', 'phone'];

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    // Una persona puede tener muchos movimientos
    public function movements()
    {
        return $this->hasMany(Movement::class, 'person_id');
    }
}
