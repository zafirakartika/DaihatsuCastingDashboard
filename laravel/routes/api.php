<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CounterController;
use App\Http\Controllers\Api\CastingDataController; // Import your controller

Route::get('/counters', [CounterController::class, 'index']);

// Add this route to handle your chart data requests
Route::get('/casting-data', [CastingDataController::class, 'handle']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});