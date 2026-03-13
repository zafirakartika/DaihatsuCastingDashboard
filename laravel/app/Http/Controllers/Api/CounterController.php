<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Import your models
use App\Models\TrCounter;
use App\Models\WaCounter;
use App\Models\SzKrCounter;
use App\Models\NrCounter;

class CounterController extends Controller
{
    private const TABLE_MAP = [
        'tr'   => 'tr_counter',
        'wa'   => 'wa_counter',
        'szkr' => 'sz_kr_counter',
        'nr'   => 'nr_counter',
    ];

    public function index()
    {
        $data = [
            'tr_counter'    => TrCounter::first(),
            'wa_counter'    => WaCounter::first(),
            'sz_kr_counter' => SzKrCounter::first(),
            'nr_counter'    => NrCounter::first(),
            'timestamp'     => now(),
            'status'        => 'success'
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function history(Request $request)
    {
        $line  = $request->query('line', 'tr');
        $shift = $request->query('shift', 'all');
        $month = $request->query('month');
        $year  = $request->query('year');
        $date  = $request->query('date');

        $table = self::TABLE_MAP[$line] ?? 'tr_counter';

        try {
            $query = DB::table($table)->where('id', '>', 1);

            if ($date) {
                // Morning shift ends at ~20:55 on the selected date.
                // Night shift ends at ~07:10 the NEXT day (21:00–07:00 spans midnight).
                // So "night of date D" lives in the DB as DATE = D+1, hour = 7.
                $nextDate = \Carbon\Carbon::parse($date)->addDay()->toDateString();

                if ($shift === 'morning') {
                    $query->whereDate('datetime', $date)->where('hour', 20);
                } elseif ($shift === 'night') {
                    $query->whereDate('datetime', $nextDate)->where('hour', 7);
                } else {
                    // All shifts: morning of $date  OR  night of $date (next-day record)
                    $query->where(function ($q) use ($date, $nextDate) {
                        $q->where(function ($q2) use ($date) {
                            $q2->whereDate('datetime', $date)->where('hour', 20);
                        })->orWhere(function ($q2) use ($nextDate) {
                            $q2->whereDate('datetime', $nextDate)->where('hour', 7);
                        });
                    });
                }
            } else {
                if ($year)  $query->where('year', $year);
                if ($month) $query->where('month', $month);

                // Morning shift = records saved at 20:xx (end of morning shift)
                // Night shift   = records saved at 07:xx (end of night shift)
                if ($shift === 'morning') {
                    $query->where('hour', 20);
                } elseif ($shift === 'night') {
                    $query->where('hour', 7);
                }
            }

            $records = $query->orderBy('datetime', 'desc')->limit(500)->get();

            return response()->json([
                'success' => true,
                'data'    => $records,
                'count'   => $records->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}