<?php

namespace App\Http\Controllers;

use App\Models\Retention;
use Illuminate\Http\Request;
use App\Exports\RetentionsExport;
use App\Models\Taxe;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function retentionMonth($date, $type)
    {
        $start = Carbon::createFromFormat('Y-m', $date)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $retentions = Retention::with(['supplier', 'discounts'])
            ->whereBetween('date', [$start, $end])
            ->where('type', $type)
            ->get();
        $taxes = Taxe::where('type', $type)->orWhere('type', 'A')->get();
        return Excel::download(new RetentionsExport($retentions, $taxes, $type), 'retenciones_' . now()->format('Ymd_His') . '.xlsx');
    }
}
