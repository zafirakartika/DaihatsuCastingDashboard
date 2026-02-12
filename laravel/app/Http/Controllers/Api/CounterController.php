<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Import your models
use App\Models\TrCounter;
use App\Models\WaCounter;
use App\Models\SzKrCounter;
use App\Models\NrCounter;

class CounterController extends Controller
{
    public function index()
    {
        // Fetch the first record from each table
        // We use first() to get the single row of counters
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
}