<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Add this route for your new page
Route::get('/lpc-counters', function () {
    return view('lpc-counters');
});