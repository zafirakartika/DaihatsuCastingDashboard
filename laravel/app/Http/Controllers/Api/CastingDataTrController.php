<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrLoger;
use Illuminate\Support\Facades\DB;

class CastingDataTrController extends Controller
{
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
            $record = TrLoger::orderBy('datetime', 'desc')->first();
            if (!$record) {
                return response()->json(['status' => 'error', 'message' => 'No data found'], 404);
            }
            return response()->json(['status' => 'success', 'data' => $record->toArray()]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function trend(Request $request)
    {
        try {
            $limit = (int) $request->query('limit', 1000);
            $date = $request->query('date'); // Format: Y-m-d (e.g., 2024-11-25)
            $startTime = $request->query('start_time', '07:15:00');
            $endTime = $request->query('end_time', '16:00:00');

            $query = TrLoger::query();

            // Filter by date and time range if provided
            if ($date) {
                $startDateTime = $date . ' ' . $startTime;
                $endDateTime = $date . ' ' . $endTime;

                $query->whereBetween('datetime', [$startDateTime, $endDateTime]);

                // Get records for specific date, sorted chronologically
                $rows = $query->orderBy('datetime', 'asc')
                    ->limit($limit)
                    ->get()
                    ->values();
            } else {
                // No date filter: get the most recent data and sort chronologically
                $latestRecord = TrLoger::orderBy('datetime', 'desc')->first();

                if ($latestRecord) {
                    // Get all records from the same day as the latest record
                    $latestDate = date('Y-m-d', strtotime($latestRecord->datetime));
                    $startDateTime = $latestDate . ' ' . $startTime;
                    $endDateTime = $latestDate . ' ' . $endTime;

                    $rows = TrLoger::whereBetween('datetime', [$startDateTime, $endDateTime])
                        ->orderBy('datetime', 'asc')
                        ->limit($limit)
                        ->get()
                        ->values();
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
            $limit = (int) $request->query('limit', 50);
            $rows = TrLoger::orderBy('datetime', 'desc')
                ->limit($limit)
                ->get()
                ->values();
            return response()->json(['status' => 'success', 'data' => $rows->toArray()]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function statistics(Request $request)
    {
        $avg = TrLoger::selectRaw('
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
    }

    public function tables(Request $request)
    {
        // TR table doesn't have id_part, return empty array or dates
        $dates = TrLoger::selectRaw('DATE(datetime) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->limit(30)
            ->pluck('date');
        return response()->json(['status' => 'success', 'data' => $dates]);
    }

    public function store(Request $request)
    {
        try {
            // Validate incoming data
            $validated = $request->validate([
                'datetime' => 'required|date',
                'ms' => 'nullable|integer',
                'l_gate_front' => 'nullable|numeric',
                'l_gate_rear' => 'nullable|numeric',
                'l_chamber_1' => 'nullable|numeric',
                'l_chamber_2' => 'nullable|numeric',
                'r_gate_front' => 'nullable|numeric',
                'r_gate_rear' => 'nullable|numeric',
                'r_chamber_1' => 'nullable|numeric',
                'r_chamber_2' => 'nullable|numeric',
            ]);

            // Save to database
            $record = TrLoger::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Data saved successfully',
                'data' => $record
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
