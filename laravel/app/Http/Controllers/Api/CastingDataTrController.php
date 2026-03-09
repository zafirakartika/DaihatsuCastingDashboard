<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CastingDataTrController extends Controller
{
    // TR Line supports LPCs 1–6, each stored in its own table: tr_loger_lpc1 … tr_loger_lpc6
    const VALID_LPCS = [1, 2, 3, 4, 5, 6];
    const DEFAULT_LPC = 2;

    // Resolve table name from ?lpc param (validated whitelist – no injection risk)
    private function resolveTable(Request $request): string
    {
        $lpc = (int) $request->query('lpc', self::DEFAULT_LPC);
        if (!in_array($lpc, self::VALID_LPCS, true)) {
            $lpc = self::DEFAULT_LPC;
        }
        return 'tr_loger_lpc' . $lpc;
    }

    // Compatibility handler that uses `action` query param
    public function handle(Request $request)
    {
        $action = $request->query('action', 'latest');

        switch ($action) {
            case 'latest':
                return $this->latest($request);
            case 'trend':
                return $this->trend($request);
            case 'recent':
                return $this->recent($request);
            case 'statistics':
                return $this->statistics($request);
            case 'tables':
                return $this->tables($request);
            default:
                return response()->json(['status' => 'error', 'message' => 'Unknown action'], 400);
        }
    }

    public function latest(Request $request)
    {
        try {
            $table = $this->resolveTable($request);
            $record = DB::table($table)->orderBy('datetime', 'desc')->first();
            if (!$record) {
                return response()->json(['status' => 'error', 'message' => 'No data found'], 404);
            }
            return response()->json(['status' => 'success', 'data' => (array) $record]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function trend(Request $request)
    {
        try {
            $table = $this->resolveTable($request);
            $limit = (int) $request->query('limit', 1000);
            $date = $request->query('date');
            $startTime = $request->query('start_time', '07:15:00');
            $endTime = $request->query('end_time', '16:00:00');

            $query = DB::table($table);

            if ($date) {
                $startDateTime = $date . ' ' . $startTime;
                $endDateTime = $date . ' ' . $endTime;
                $rows = $query->whereBetween('datetime', [$startDateTime, $endDateTime])
                    ->orderBy('datetime', 'asc')
                    ->limit($limit)
                    ->get();
            } else {
                $latestRecord = DB::table($table)->orderBy('datetime', 'desc')->first();
                if ($latestRecord) {
                    $latestDate = date('Y-m-d', strtotime($latestRecord->datetime));
                    $startDateTime = $latestDate . ' ' . $startTime;
                    $endDateTime = $latestDate . ' ' . $endTime;
                    $rows = DB::table($table)
                        ->whereBetween('datetime', [$startDateTime, $endDateTime])
                        ->orderBy('datetime', 'asc')
                        ->limit($limit)
                        ->get();
                } else {
                    $rows = collect([]);
                }
            }

            return response()->json(['status' => 'success', 'data' => $rows->toArray()]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function recent(Request $request)
    {
        try {
            $table = $this->resolveTable($request);
            $limit = (int) $request->query('limit', 50);
            $rows = DB::table($table)
                ->orderBy('datetime', 'desc')
                ->limit($limit)
                ->get();
            return response()->json(['status' => 'success', 'data' => $rows->toArray()]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function statistics(Request $request)
    {
        try {
            $table = $this->resolveTable($request);
            $avg = DB::table($table)->selectRaw('
                AVG(l_gate_front) as avg_l_gate_front,
                AVG(l_gate_rear) as avg_l_gate_rear,
                AVG(l_chamber_1) as avg_l_chamber_1,
                AVG(l_chamber_2) as avg_l_chamber_2,
                AVG(r_gate_front) as avg_r_gate_front,
                AVG(r_gate_rear) as avg_r_gate_rear,
                AVG(r_chamber_1) as avg_r_chamber_1,
                AVG(r_chamber_2) as avg_r_chamber_2
            ')->first();
            return response()->json(['status' => 'success', 'data' => $avg]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function tables(Request $request)
    {
        try {
            $table = $this->resolveTable($request);
            $dates = DB::table($table)
                ->selectRaw('DATE(datetime) as date')
                ->distinct()
                ->orderBy('date', 'desc')
                ->limit(30)
                ->pluck('date');
            return response()->json(['status' => 'success', 'data' => $dates]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
