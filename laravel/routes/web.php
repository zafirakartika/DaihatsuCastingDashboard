<?php

use Illuminate\Support\Facades\Route;

// 1. Landing Page (Index)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Main Dashboards
Route::get('/production-dashboard', function () {
    return view('production-dashboard'); 
})->name('production-dashboard');

Route::get('/alpc-overview', function () {
    return view('alpc-overview');
})->name('alpc-overview');

// 3. ALPC Line 1 Routes
Route::get('/general-alpc-tr', function () {
    return view('general-alpc-tr');
})->name('general-alpc-tr');

Route::get('/casting-performance-tr', function () {
    return view('casting-performance-tr');
})->name('casting-performance-tr');

// 4. ALPC Line 2 Routes
Route::get('/general-alpc-wa', function () {
    return view('general-alpc-wa');
})->name('general-alpc-wa');

Route::get('/casting-performance-wa', function () {
    return view('casting-performance-wa');
})->name('casting-performance-wa');

Route::get('/finishing-performance-wa', function () {
    return view('finishing-performance-wa');
})->name('finishing-performance-wa');

// 5. Other Pages
Route::get('/traceability', function () {
    return view('traceability');
})->name('traceability');