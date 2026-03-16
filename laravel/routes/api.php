<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CounterController;
use App\Http\Controllers\Api\CastingDataController;
use App\Http\Controllers\Api\CastingDataTrController;
use App\Http\Controllers\Api\TraceabilityController;
use App\Http\Controllers\Api\GeneralAlpcController;
use App\Http\Controllers\Api\FinishingPerformanceController;

// Counter data (LPC counters)
Route::get('/counters', [CounterController::class, 'index']);
Route::get('/counters/history', [CounterController::class, 'history']);

// Casting data (WA-focused, action-based for backwards compatibility)
Route::get('/casting-data', [CastingDataController::class, 'handle']);

// Casting data TR -- supports ?lpc=1..6&action=latest|trend|recent|statistics
Route::get('/casting-data-tr', [CastingDataTrController::class, 'handle']);

// Casting data TR Timer -- uses tr_logger_lpc6_timer table
Route::get('/casting-data-tr-timer', [\App\Http\Controllers\Api\CastingDataTrTimerController::class, 'handle']);

// General ALPC dashboard data -- supports ?line=wa|tr|3sz|kr|nr
Route::get('/general-alpc', [GeneralAlpcController::class, 'handle']);

// Finishing performance data -- supports ?line=wa|tr|kr|nr|3sz
Route::get('/finishing-data', [FinishingPerformanceController::class, 'handle']);

// Traceability data -- supports ?line=wa|tr|kr|nr|3sz&action=recent|search|statistics
Route::get('/traceability', [TraceabilityController::class, 'handle']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
