<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

// ============================================================
// HOME / DASHBOARD
// ============================================================
Route::get('/', [PageController::class, 'home'])->name('home');

// ============================================================
// PRODUCTION DASHBOARD
// ============================================================
Route::get('/production-dashboard', [PageController::class, 'productionDashboard'])->name('production-dashboard');

// ============================================================
// ALPC OVERVIEW & LPC COUNTERS
// ============================================================
Route::get('/alpc-overview', [PageController::class, 'alpcOverview'])->name('alpc-overview');
Route::get('/lpc-counters', [PageController::class, 'lpcCounters'])->name('lpc-counters');

// ============================================================
// ALPC TR (Line 1)
// ============================================================
Route::get('/general-alpc-tr', [PageController::class, 'generalAlpcTr'])->name('general-alpc-tr');
Route::get('/casting-performance-tr', [PageController::class, 'castingPerformanceTr'])->name('casting-performance-tr');
Route::get('/finishing-performance-tr', [PageController::class, 'finishingPerformanceTr'])->name('finishing-performance-tr');

// ============================================================
// ALPC 3SZ (Line 1)
// ============================================================
Route::get('/general-alpc-3sz', [PageController::class, 'generalAlpc3sz'])->name('general-alpc-3sz');
Route::get('/casting-performance-3sz', [PageController::class, 'castingPerformance3sz'])->name('casting-performance-3sz');
Route::get('/finishing-performance-3sz', [PageController::class, 'finishingPerformance3sz'])->name('finishing-performance-3sz');

// ============================================================
// ALPC KR (Line 1)
// ============================================================
Route::get('/general-alpc-kr', [PageController::class, 'generalAlpcKr'])->name('general-alpc-kr');
Route::get('/casting-performance-kr', [PageController::class, 'castingPerformanceKr'])->name('casting-performance-kr');
Route::get('/finishing-performance-kr', [PageController::class, 'finishingPerformanceKr'])->name('finishing-performance-kr');

// ============================================================
// ALPC NR (Line 2)
// ============================================================
Route::get('/general-alpc-nr', [PageController::class, 'generalAlpcNr'])->name('general-alpc-nr');
Route::get('/casting-performance-nr', [PageController::class, 'castingPerformanceNr'])->name('casting-performance-nr');
Route::get('/finishing-performance-nr', [PageController::class, 'finishingPerformanceNr'])->name('finishing-performance-nr');

// ============================================================
// ALPC WA (Line 2)
// ============================================================
Route::get('/general-alpc-wa', [PageController::class, 'generalAlpcWa'])->name('general-alpc-wa');
Route::get('/casting-performance-wa', [PageController::class, 'castingPerformanceWa'])->name('casting-performance-wa');
Route::get('/finishing-performance-wa', [PageController::class, 'finishingPerformanceWa'])->name('finishing-performance-wa');

// ============================================================
// TRACEABILITY
// ============================================================
Route::get('/traceability', [PageController::class, 'traceability'])->name('traceability');
Route::get('/traceability-wa', [PageController::class, 'traceabilityWa'])->name('traceability-wa');
Route::get('/traceability-tr', [PageController::class, 'traceabilityTr'])->name('traceability-tr');
Route::get('/traceability-kr', [PageController::class, 'traceabilityKr'])->name('traceability-kr');
Route::get('/traceability-nr', [PageController::class, 'traceabilityNr'])->name('traceability-nr');
Route::get('/traceability-3sz', [PageController::class, 'traceability3sz'])->name('traceability-3sz');

// ============================================================
// MANAGEMENT / ANALYTICS DASHBOARDS
// ============================================================
Route::get('/management-dashboard', [PageController::class, 'managementDashboard'])->name('management-dashboard');
Route::get('/quality-dashboard', [PageController::class, 'qualityDashboard'])->name('quality-dashboard');
Route::get('/pcl-dashboard', [PageController::class, 'pclDashboard'])->name('pcl-dashboard');
Route::get('/maintenance-dashboard', [PageController::class, 'maintenanceDashboard'])->name('maintenance-dashboard');

// ============================================================
// API ROUTES (proxied through Laravel instead of raw PHP files)
// ============================================================
Route::prefix('api')->group(function () {
    Route::get('/casting-data', [\App\Http\Controllers\Api\CastingDataController::class, 'handle']);
});
