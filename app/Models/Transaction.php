<?php

namespace App\Models;

use App\Helpers\HasConsecutiveNumber;
use App\Helpers\NumberHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasConsecutiveNumber;
    protected static function tablaDetalle(): string
    {
        return 'transactions';
    }
    protected $guarded = ['id'];
    /*  public function getbalanceAttribute(){
        $transactions= Transaction::whereDate('date', '<=',$this->date)->get();
        $debe=$transactions->where('stats','D')->sum('amount');
        $haber=$transactions->where('stats','C')->sum('amount');
        return $debe-$haber;
    } */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
    public function movement(): BelongsTo
    {
        return $this->belongsTo(Movement::class);
    }
    public function getcalculateLabelAttribute()
    {
        $literal = NumberHelper::toLiteral($this->amount);
        return $literal;
    }
    public function getdateLabelAttribute()
    {
        Carbon::setLocale('es');

        $fecha = Carbon::parse($this->date);
        $literal = $fecha->translatedFormat('d \d\\e F \d\\e Y');
        return $literal;
    }




    // app/Models/Movement.php

 /*    protected static function booted()
    {
        static::creating(function (Movement $movement) {
            $movement->number = self::getNextNumber(
                $movement->account_id,
                $movement->type
            );
        });
    } */

    private static function getNextNumber(int $accountId, string $type): int
    {
        return DB::transaction(function () use ($accountId, $type) {
            // B y D comparten secuencia, C va sola
            $types = in_array($type, ['B', 'D']) ? ['B', 'D'] : ['C'];

            $max = DB::table('movements')
                ->where('account_id', $accountId)
                ->whereIn('type', $types)
                ->lockForUpdate()
                ->max('number');

            return ($max ?? 0) + 1;
        });
    }
    public function getFormattedLastNumberAttribute(): string
    {
        return str_pad($this->number, 8, '0', STR_PAD_LEFT);
    }
    public function getNumberLabelAttribute(): string{
        if($this->number_check){
            return 'CH-'.$this->number_check;
        }else{
          return 'DOC-'. str_pad($this->number, 8, '0', STR_PAD_LEFT);
        }
    }
}
