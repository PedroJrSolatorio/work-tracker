<?php

use App\Http\Controllers\WorkSessionController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [WorkSessionController::class, 'index'])->name('tracker.index');
Route::post('/session', [WorkSessionController::class, 'store'])->name('tracker.store');
Route::post('/session/{id}/start', [WorkSessionController::class, 'start'])->name('tracker.start');
Route::post('/session/{id}/pause', [WorkSessionController::class, 'pause'])->name('tracker.pause');
Route::put('/session/{id}/target', [WorkSessionController::class, 'update'])->name('tracker.update');
Route::get('/stats', [WorkSessionController::class, 'stats'])->name('tracker.stats');
Route::get('/session/{id}', [WorkSessionController::class, 'show'])->name('tracker.show');
