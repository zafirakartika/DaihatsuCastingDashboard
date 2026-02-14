<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// --- ALPC Overview ---
Route::get('/alpc-overview', function () {
    return view('alpc-overview');
})->name('alpc-overview');

// --- Casting Performance Pages ---
Route::get('/casting-performance-tr', function () {
    return view('casting-performance-tr', ['current_page' => 'casting-performance-tr']);
})->name('casting-performance-tr');

Route::get('/casting-performance-wa', function () {
    return view('casting-performance-wa', ['current_page' => 'casting-performance-wa']);
})->name('casting-performance-wa');

// --- General ALPC Pages (Linked from Overview) ---
// These are needed so the "Navigate" buttons in the Overview modal work.
// Even if you haven't migrated the files yet, these routes must exist.
Route::get('/general-alpc-tr', function () {
    return view('general-alpc-tr'); // Ensure this view exists or create a blank one
})->name('general-alpc-tr');

Route::get('/general-alpc-wa', function () {
    return view('general-alpc-wa');
})->name('general-alpc-wa');

Route::get('/general-alpc-3sz', function () {
    return view('general-alpc-3sz');
})->name('general-alpc-3sz');

Route::get('/general-alpc-kr', function () {
    return view('general-alpc-kr');
})->name('general-alpc-kr');

Route::get('/general-alpc-nr', function () {
    return view('general-alpc-nr');
})->name('general-alpc-nr');