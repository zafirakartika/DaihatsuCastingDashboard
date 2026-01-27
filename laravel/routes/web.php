<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/traceability', function () {
    return view('traceability');
})->name('traceability');

// Route for casting-performance-wa.html
Route::get('/casting-performance', function () {
    return redirect('../pages/casting-performance-wa.html');
})->name('casting-performance');

