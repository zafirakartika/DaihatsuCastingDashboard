<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaLogerCyh;
use Illuminate\Support\Facades\DB;

class TraceabilityController extends Controller
{
    // Handle traceability requests
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

    // Get recent traceability records
    public function recent(Request $request)
    {
        try {
            $limit = (int) $request->query('limit', 100);
            $date = $request->query('date'); // Format: Y-m-d

            $query = WaLogerCyh::query();

            // Filter by date if provided
            if ($date) {
                $query->whereDate('datetime_stamp', $date);
            }

            $records = $query->orderBy('datetime_stamp', 'desc')
                ->orderBy('no_shot', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $records->toArray(),
                'count' => $records->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Search for specific part ID or patterns
    public function search(Request $request)
    {
        try {
            $searchTerm = $request->query('term');
            $limit = (int) $request->query('limit', 100);

            if (!$searchTerm) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Search term is required'
                ], 400);
            }

            $records = WaLogerCyh::where('id_part', 'LIKE', "%{$searchTerm}%")
                ->orderBy('datetime_stamp', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $records->toArray(),
                'count' => $records->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get statistics about traceability data
    public function statistics(Request $request)
    {
        try {
            $date = $request->query('date');

            $query = WaLogerCyh::query();

            if ($date) {
                $query->whereDate('datetime_stamp', $date);
            }

            $totalParts = $query->count();
            $uniqueIdParts = $query->distinct('id_part')->count('id_part');

            // Get cavity distribution
            $cavityStats = DB::table('wa_loger_cyh_wa')
                ->select(DB::raw('SUBSTRING(id_part, 11, 1) as cavity, COUNT(*) as count'))
                ->when($date, function ($q) use ($date) {
                    return $q->whereDate('datetime_stamp', $date);
                })
                ->groupBy('cavity')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_parts' => $totalParts,
                    'unique_id_parts' => $uniqueIdParts,
                    'cavity_distribution' => $cavityStats
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
