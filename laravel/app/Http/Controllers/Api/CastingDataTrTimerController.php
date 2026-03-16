<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CastingDataTrTimerController extends Controller
{
    const VALID_LPCS = [1, 2, 3, 4, 6];
    const DEFAULT_LPC = 6;

    private function resolveTable(Request $request): string
    {
        $lpc = (int) $request->query('lpc', self::DEFAULT_LPC);
        if (!in_array($lpc, self::VALID_LPCS, true)) {
            $lpc = self::DEFAULT_LPC;
        }
        return 'tr_loger_lpc' . $lpc . '_timer';
    }

    private function datetimeExpr(): string
    {
        return "CONCAT(year,'-',LPAD(month,2,'0'),'-',LPAD(day,2,'0'),' ',LPAD(hour,2,'0'),':',LPAD(min,2,'0'),':',LPAD(sec,2,'0'))";
    }

    public function handle(Request $request)
    {
        $action = $request->query('action', 'latest');

        switch ($action) {
            case 'latest':    return $this->latest($request);
            case 'trend':     return $this->trend($request);
            case 'recent':    return $this->recent($request);
            case 'statistics':return $this->statistics($request);
            case 'tables':    return $this->tables($request);
            default:
                return response()->json(['status' => 'error', 'message' => 'Unknown action'], 400);
        }
    }

    public function latest(Request $request)
    {
        try {
            $record = DB::table($this->resolveTable($request))
                ->selectRaw('*, ' . $this->datetimeExpr() . ' as datetime')
                ->orderByRaw('year DESC, month DESC, day DESC, hour DESC, min DESC, sec DESC')
                ->first();
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
            $limit     = (int) $request->query('limit', 1000);
            $date      = $request->query('date');
            $startTime = $request->query('start_time', '07:15:00');
            $endTime   = $request->query('end_time', '16:00:00');
            $dtExpr    = $this->datetimeExpr();
            $table     = $this->resolveTable($request);

            $query = DB::table($table)->selectRaw('*, ' . $dtExpr . ' as datetime');

            if ($date) {
                if ($startTime > $endTime) {
                    $nextDate = date('Y-m-d', strtotime($date . ' +1 day'));
                    $query->whereRaw($dtExpr . ' BETWEEN ? AND ?', [$date . ' ' . $startTime, $nextDate . ' ' . $endTime]);
                } else {
                    $query->whereRaw($dtExpr . ' BETWEEN ? AND ?', [$date . ' ' . $startTime, $date . ' ' . $endTime]);
                }
            } else {
                $latest = DB::table($table)
                    ->selectRaw($dtExpr . ' as datetime')
                    ->orderByRaw('year DESC, month DESC, day DESC, hour DESC, min DESC, sec DESC')
                    ->first();
                if ($latest) {
                    $latestDate = date('Y-m-d', strtotime($latest->datetime));
                    $query->whereRaw($dtExpr . ' BETWEEN ? AND ?', [$latestDate . ' ' . $startTime, $latestDate . ' ' . $endTime]);
                } else {
                    return response()->json(['status' => 'success', 'data' => []]);
                }
            }

            $rows = $query->orderByRaw('year ASC, month ASC, day ASC, hour ASC, min ASC, sec ASC')->limit($limit)->get();
            return response()->json(['status' => 'success', 'data' => $rows->toArray()]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function recent(Request $request)
    {
        try {
            $limit     = (int) $request->query('limit', 50);
            $date      = $request->query('date');
            $startTime = $request->query('start_time', '07:15:00');
            $endTime   = $request->query('end_time', '20:50:00');
            $dtExpr    = $this->datetimeExpr();

            $query = DB::table($this->resolveTable($request))->selectRaw('*, ' . $dtExpr . ' as datetime');

            if ($date) {
                if ($startTime > $endTime) {
                    $nextDate = date('Y-m-d', strtotime($date . ' +1 day'));
                    $query->whereRaw($dtExpr . ' BETWEEN ? AND ?', [$date . ' ' . $startTime, $nextDate . ' ' . $endTime]);
                } else {
                    $query->whereRaw($dtExpr . ' BETWEEN ? AND ?', [$date . ' ' . $startTime, $date . ' ' . $endTime]);
                }
            }

            $rows = $query->orderByRaw('year DESC, month DESC, day DESC, hour DESC, min DESC, sec DESC')->limit($limit)->get();
            return response()->json(['status' => 'success', 'data' => $rows->toArray()]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function statistics(Request $request)
    {
        try {
            $avg = DB::table($this->resolveTable($request))->selectRaw('
                AVG(r_lower_gate1_temp_1) as avg_r_lower_gate1_temp,
                AVG(r_lower_gate2_temp_1) as avg_r_lower_gate2_temp,
                AVG(r_lower_main1_temp_1) as avg_r_lower_main1_temp,
                AVG(r_lower_main2_temp_1) as avg_r_lower_main2_temp,
                AVG(l_upper_main_temp_1)  as avg_l_upper_main_temp,
                AVG(l_lower_gate1_temp_1) as avg_l_lower_gate1_temp,
                AVG(l_lower_gate2_temp_1) as avg_l_lower_gate2_temp,
                AVG(l_lower_main1_temp_1) as avg_l_lower_main1_temp,
                AVG(l_lower_main2_temp_1) as avg_l_lower_main2_temp,
                AVG(pressure_room_temp_1) as avg_pressure_room_temp,
                AVG(hoolding_room_temp_1) as avg_hoolding_room_temp,
                AVG(r_upper_sp_flow_1)           as avg_r_upper_sp_flow,
                AVG(r_upper_flow_1)              as avg_r_upper_flow,
                AVG(l_upper_sp_flow_1)           as avg_l_upper_sp_flow,
                AVG(l_upper_flow_1)              as avg_l_upper_flow,
                AVG(r_lower_cooling_air1_flow_1) as avg_r_lower_cooling_air1_flow,
                AVG(l_lower_cooling_air1_flow_1) as avg_l_lower_cooling_air1_flow,
                AVG(r_lower_cooling_air2_flow_1) as avg_r_lower_cooling_air2_flow,
                AVG(l_lower_cooling_air2_flow_1) as avg_l_lower_cooling_air2_flow
            ')->first();
            return response()->json(['status' => 'success', 'data' => $avg]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function tables(Request $request)
    {
        try {
            $dtExpr = $this->datetimeExpr();
            $dates = DB::table($this->resolveTable($request))
                ->selectRaw("CONCAT(year,'-',LPAD(month,2,'0'),'-',LPAD(day,2,'0')) as date")
                ->groupByRaw('year, month, day')
                ->orderByRaw('year DESC, month DESC, day DESC')
                ->limit(30)
                ->pluck('date');
            return response()->json(['status' => 'success', 'data' => $dates]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
