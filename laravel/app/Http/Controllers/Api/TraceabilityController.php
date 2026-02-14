<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TraceabilityController extends Controller
{
    /**
     * Map line codes to their cyh (traceability) table names.
     */
    private const LINE_TABLE_MAP = [
        'wa'  => 'wa_loger_cyh_wa',
        'tr'  => 'tr_loger_cyh_tr',
        '3sz' => 'tr_loger_cyh_tr',   // 3SZ shares TR table until dedicated table exists
        'kr'  => 'kr_loger_cyh_kr',   // placeholder — update when table is created
        'nr'  => 'nr_loger_cyh_nr',   // placeholder — update when table is created
    ];

    public function handle(Request $request)
    {
        $action = $request->query('action', 'recent');

        switch ($action) {
            case 'recent':
                return $this->recent($request);
            case 'search':
                return $this->search($request);
            case 'statistics':
                return $this->statistics($request);
            default:
                return response()->json(['status' => 'error', 'message' => 'Unknown action'], 400);
        }
    }

    private function getTable(Request $request): string
    {
        $line = strtolower($request->query('line', 'wa'));
        return self::LINE_TABLE_MAP[$line] ?? self::LINE_TABLE_MAP['wa'];
    }

    public function recent(Request $request)
    {
        try {
            $table  = $this->getTable($request);
            $limit  = (int) $request->query('limit', 100);
            $date   = $request->query('date');
            $shift  = $request->query('shift');
            $cavity = $request->query('cavity');

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

            if ($cavity && $cavity !== 'all') {
                $query->where('cavity', $cavity);
            }

            if ($limit > 0) {
                $query->limit($limit);
            }

            $records = $query->orderBy('datetime_stamp', 'desc')
                ->orderBy('no_shot', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
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

    public function search(Request $request)
    {
        try {
            $table      = $this->getTable($request);
            $searchTerm = $request->query('term');
            $limit      = (int) $request->query('limit', 100);

            if (!$searchTerm) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Search term is required',
                ], 400);
            }

            $records = DB::table($table)
                ->where('id_part', 'LIKE', "%{$searchTerm}%")
                ->orderBy('datetime_stamp', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'status' => 'success',
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

    public function statistics(Request $request)
    {
        try {
            $table = $this->getTable($request);
            $date  = $request->query('date');

            $query = DB::table($table);

            if ($date) {
                $query->whereDate('datetime_stamp', $date);
            }

            $totalParts    = $query->count();
            $uniqueIdParts = (clone $query)->distinct('id_part')->count('id_part');

            $cavityStats = DB::table($table)
                ->select(DB::raw('SUBSTRING(id_part, 11, 1) as cavity, COUNT(*) as count'))
                ->when($date, fn($q) => $q->whereDate('datetime_stamp', $date))
                ->groupBy('cavity')
                ->get();

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'total_parts'         => $totalParts,
                    'unique_id_parts'     => $uniqueIdParts,
                    'cavity_distribution' => $cavityStats,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
