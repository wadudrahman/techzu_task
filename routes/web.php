<?php

use App\Helpers\EnumHelper;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('list');
});

// Event Routes
Route::group(['prefix' => 'events'], function () {
    Route::get('/add', [EventController::class, 'showAddEvent'])->name('showAddEvent');
    Route::post('/add', [EventController::class, 'addEvent'])->name('addEvent');
    Route::get('/list/{status?}', [EventController::class, 'showEventList'])
        ->whereIn('status', [
            strtolower(EnumHelper::COMPLETED),
            strtolower(EnumHelper::UPCOMING)
        ])
        ->name('list');
    Route::get('/uuid/{uuid}', [EventController::class, 'getEventByUuid'])->name('show');
    Route::delete('/uuid/{uuid}/delete', [EventController::class, 'deleteEvent'])->name('delete');
});
