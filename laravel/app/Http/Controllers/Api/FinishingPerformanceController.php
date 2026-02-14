<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinishingPerformanceController extends Controller
{
    /**
     * Map line codes to finishing process table names.
     * Extend when dedicated finishing tables are added.
     */
    private const LINE_TABLE_MAP = [
        'wa'  => 'finishing_wa',
        'tr'  => 'finishing_tr',
        'kr'  => 'finishing_kr',
        'nr'  => 'finishing_nr',
        '3sz' => 'finishing_3sz',
    ];

    public function handle(Request $request)
    {
        $line  = strtolower($request->query('line', 'wa'));
        $date  = $request->query('date');
        $shift = $request->query('shift', 'auto');
        $limit = (int) $request->query('limit', 200);

        $table = self::LINE_TABLE_MAP[$line] ?? self::LINE_TABLE_MAP['wa'];

        try {
            $query = DB::table($table);

            if ($date) {
                $query->whereDate('process_time', $date);
            }

            if ($shift === 'morning') {
                $query->whereRaw("TIME(process_time) BETWEEN '07:15:00' AND '16:00:00'");
            } elseif ($shift === 'night') {
                $query->whereRaw(
                    "TIME(process_time) >= '19:00:00' OR TIME(process_time) <= '06:00:00'"
                );
            }

            $records = $query->orderBy('process_time', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'status' => 'success',
                'line'   => $line,
                'data'   => $records->toArray(),
                'count'  => $records->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
