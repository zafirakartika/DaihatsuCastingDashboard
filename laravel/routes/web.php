<?php

use Illuminate\Support\Facades\Route;

// ============================================================
// HOME / DASHBOARD
// ============================================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ============================================================
// ALPC OVERVIEW
// ============================================================
Route::get('/alpc-overview', function () {
    return view('alpc-overview');
})->name('alpc-overview');

// ============================================================
// ALPC TR (Line 1)
// ============================================================
Route::get('/general-alpc-tr', function () {
    return view('general-alpc-tr');
})->name('general-alpc-tr');

Route::get('/casting-performance-tr', function () {
    return view('casting-performance-tr', ['current_page' => 'casting-performance-tr']);
})->name('casting-performance-tr');

Route::get('/finishing-performance-tr', function () {
    return view('finishing-performance-tr', ['current_page' => 'finishing-performance-tr']);
})->name('finishing-performance-tr');

// ============================================================
// ALPC 3SZ (Line 1)
// ============================================================
Route::get('/general-alpc-3sz', function () {
    return view('general-alpc-3sz');
})->name('general-alpc-3sz');

Route::get('/casting-performance-3sz', function () {
    return view('casting-performance-3sz', ['current_page' => 'casting-performance-3sz']);
})->name('casting-performance-3sz');

// ============================================================
// ALPC KR (Line 1)
// ============================================================
Route::get('/general-alpc-kr', function () {
    return view('general-alpc-kr');
})->name('general-alpc-kr');

Route::get('/casting-performance-kr', function () {
    return view('casting-performance-kr', ['current_page' => 'casting-performance-kr']);
})->name('casting-performance-kr');

Route::get('/finishing-performance-kr', function () {
    return view('finishing-performance-kr', ['current_page' => 'finishing-performance-kr']);
})->name('finishing-performance-kr');

// ============================================================
// ALPC NR (Line 2)
// ============================================================
Route::get('/general-alpc-nr', function () {
    return view('general-alpc-nr');
})->name('general-alpc-nr');

Route::get('/casting-performance-nr', function () {
    return view('casting-performance-nr', ['current_page' => 'casting-performance-nr']);
})->name('casting-performance-nr');

Route::get('/finishing-performance-nr', function () {
    return view('finishing-performance-nr', ['current_page' => 'finishing-performance-nr']);
})->name('finishing-performance-nr');

// ============================================================
// ALPC WA (Line 2)
// ============================================================
Route::get('/general-alpc-wa', function () {
    return view('general-alpc-wa');
})->name('general-alpc-wa');

Route::get('/casting-performance-wa', function () {
    return view('casting-performance-wa', ['current_page' => 'casting-performance-wa']);
})->name('casting-performance-wa');

Route::get('/finishing-performance-wa', function () {
    return view('finishing-performance-wa', ['current_page' => 'finishing-performance-wa']);
})->name('finishing-performance-wa');

// ============================================================
// TRACEABILITY
// ============================================================
Route::get('/traceability', function () {
    return view('traceability');
})->name('traceability');

Route::get('/traceability-wa', function () {
    return view('traceability-wa');
})->name('traceability-wa');

Route::get('/traceability-tr', function () {
    return view('traceability-tr');
})->name('traceability-tr');

Route::get('/traceability-kr', function () {
    return view('traceability-kr');
})->name('traceability-kr');

Route::get('/traceability-nr', function () {
    return view('traceability-nr');
})->name('traceability-nr');

Route::get('/traceability-3sz', function () {
    return view('traceability-3sz');
})->name('traceability-3sz');

// ============================================================
// API ROUTES (proxied through Laravel instead of raw PHP files)
// ============================================================
Route::prefix('api')->group(function () {
    Route::get('/casting-data', [\App\Http\Controllers\Api\CastingDataController::class, 'handle']);
});