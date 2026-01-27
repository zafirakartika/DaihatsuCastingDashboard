<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaLoger;
use Illuminate\Support\Facades\DB;
use App\Events\CastingDataUpdated;

class CastingDataController extends Controller
{
    // Compatibility handler that uses `action` query param like your old PHP API
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
            $record = WaLoger::orderBy('datetime_stamp', 'desc')->first();
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

            $query = WaLoger::query();

            // Filter by date and time range if provided
            if ($date) {
                $startDateTime = $date . ' ' . $startTime;
                $endDateTime = $date . ' ' . $endTime;

                $query->whereBetween('datetime_stamp', [$startDateTime, $endDateTime]);

                // Get records for specific date, sorted chronologically
                $rows = $query->orderBy('datetime_stamp', 'asc')
                    ->limit($limit)
                    ->get()
                    ->values();
            } else {
                // No date filter: get the most recent data and sort chronologically
                // First, get the latest datetime to determine the most recent day
                $latestRecord = WaLoger::orderBy('datetime_stamp', 'desc')->first();

                if ($latestRecord) {
                    // Get all records from the same day as the latest record
                    $latestDate = date('Y-m-d', strtotime($latestRecord->datetime_stamp));
                    $startDateTime = $latestDate . ' ' . $startTime;
                    $endDateTime = $latestDate . ' ' . $endTime;

                    $rows = WaLoger::whereBetween('datetime_stamp', [$startDateTime, $endDateTime])
                        ->orderBy('datetime_stamp', 'asc')
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
            $rows = WaLoger::orderBy('id_part', 'desc')
                ->orderBy('datetime_stamp', 'desc')
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
        $avg = WaLoger::selectRaw('
            AVG(r_lower_gate1) as avg_r_gate,
            AVG(r_lower_main1) as avg_r_main,
            AVG(l_lower_gate1) as avg_l_gate,
            AVG(l_lower_main1) as avg_l_main
        ')->first();

        return response()->json(['status' => 'success', 'data' => $avg]);
    }

    public function tables(Request $request)
    {
        // Example: return distinct part ids or tables meta
        $parts = WaLoger::select('id_part')->distinct()->pluck('id_part');
        return response()->json(['status' => 'success', 'data' => $parts]);
    }

    /**
     * Store new casting data from MES and broadcast to WebSocket
     * This endpoint will be called by your MES system when new data arrives
     */
    public function store(Request $request)
    {
        try {
            // Validate incoming data
            $validated = $request->validate([
                'id_part' => 'required|string',
                'datetime_stamp' => 'required|date',
                'r_lower_gate1' => 'nullable|numeric',
                'r_lower_main1' => 'nullable|numeric',
                'l_lower_gate1' => 'nullable|numeric',
                'l_lower_main1' => 'nullable|numeric',
                'cooling_water' => 'nullable|numeric',
            ]);

            // Extract date/time components
            $timestamp = strtotime($validated['datetime_stamp']);
            $validated['year'] = date('Y', $timestamp);
            $validated['month'] = date('m', $timestamp);
            $validated['day'] = date('d', $timestamp);
            $validated['hour'] = date('H', $timestamp);
            $validated['min'] = date('i', $timestamp);
            $validated['created_at'] = now();

            // Save to database
            $record = WaLoger::create($validated);

            // Broadcast the new data via WebSocket
            broadcast(new CastingDataUpdated($record->toArray()));

            return response()->json([
                'status' => 'success',
                'message' => 'Data saved and broadcasted',
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
