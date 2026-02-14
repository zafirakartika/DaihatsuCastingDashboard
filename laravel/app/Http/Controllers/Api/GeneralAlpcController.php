<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralAlpcController extends Controller
{
    /**
     * Map line codes to their primary database table names.
     * Extend this map when new line tables are added to the DB.
     */
    private const LINE_TABLE_MAP = [
        'wa'  => 'wa_loger_wa',
        'tr'  => 'tr_loger_tr',
        '3sz' => 'tr_loger_tr',   // 3SZ shares TR table until dedicated table exists
        'kr'  => 'sz_kr_loger',   // placeholder — update when table is created
        'nr'  => 'nr_loger',      // placeholder — update when table is created
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
                $query->whereDate('datetime_stamp', $date);
            }

            if ($shift === 'morning') {
                $query->whereRaw("TIME(datetime_stamp) BETWEEN '07:15:00' AND '16:00:00'");
            } elseif ($shift === 'night') {
                $query->whereRaw(
                    "TIME(datetime_stamp) >= '19:00:00' OR TIME(datetime_stamp) <= '06:00:00'"
                );
            }

            $records = $query->orderBy('datetime_stamp', 'desc')
                ->limit($limit)
                ->get();

            // Aggregate summary statistics
            $total    = $records->count();
            $good     = $records->where('judge', 'OK')->count();
            $rejected = $total - $good;

            return response()->json([
                'status' => 'success',
                'line'   => $line,
                'summary' => [
                    'total_shots'    => $total,
                    'good_shots'     => $good,
                    'rejected_shots' => $rejected,
                    'good_rate'      => $total > 0 ? round($good / $total * 100, 2) : 0,
                    'reject_rate'    => $total > 0 ? round($rejected / $total * 100, 2) : 0,
                ],
                'data'  => $records->toArray(),
                'count' => $total,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
