<?php

use App\Http\Controllers\WorkSessionController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [WorkSessionController::class, 'index'])->name('tracker.index');
Route::post('/session', [WorkSessionController::class, 'store'])->name('tracker.store');
