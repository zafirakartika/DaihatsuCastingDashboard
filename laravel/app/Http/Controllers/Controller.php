<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrCounter;
use App\Models\WaCounter;
use App\Models\SzKrCounter;
use App\Models\NrCounter;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index()
    {
        // Fetch row where id=1, or fail gracefully
        $data = [
            'tr_counter'    => TrCounter::find(1),
            'wa_counter'    => WaCounter::find(1),
            'sz_kr_counter' => SzKrCounter::find(1),
            'nr_counter'    => NrCounter::find(1),
            'timestamp'     => now(),
            'status'        => 'success'
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }
}