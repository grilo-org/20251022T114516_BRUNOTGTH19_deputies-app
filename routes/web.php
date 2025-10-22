<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeputyController;

Route::get('/', function () {
    return redirect()->route('deputies.index');
});

Route::get('/deputies', [DeputyController::class, 'index'])->name('deputies.index');
Route::get('/deputies/{deputy}', [DeputyController::class, 'show'])->name('deputies.show');