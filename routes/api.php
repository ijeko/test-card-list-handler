<?php

use App\Http\Controllers\CardProcessController;
use Illuminate\Support\Facades\Route;

Route::prefix('cards')->group(function () {
    Route::post('process', CardProcessController::class)
        ->name('cards.process')
        ->middleware('only-authorized');
});
