<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 1. IMPORT YOUR MODELS
use App\Models\TrCounter;
use App\Models\WaCounter;
use App\Models\SzKrCounter;
use App\Models\NrCounter;

class CounterController extends Controller
{
    public function index()
    {
        // 2. FETCH DATA (Get the row where id=1 from each table)
        // We use 'first()' which gets the first matching record (safer than find(1) if IDs vary)
        $data = [
            'tr_counter'    => TrCounter::first(),
            'wa_counter'    => WaCounter::first(),
            'sz_kr_counter' => SzKrCounter::first(),
            'nr_counter'    => NrCounter::first(),
            'timestamp'     => now(),
            'status'        => 'success'
        ];

        // 3. RETURN AS JSON
        return response()->json(['success' => true, 'data' => $data]);
    }
}