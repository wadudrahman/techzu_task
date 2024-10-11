<?php

use App\Helpers\EnumHelper;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('list');
});

Route::group(['middleware' => ['auth']], function () {
    // Event Routes
    Route::group(['prefix' => 'events'], function () {
        Route::get('/add', [EventController::class, 'showAddEvent'])
            ->name('event.add');
        Route::get('/{status?}', [EventController::class, 'showEventList'])
            ->whereIn('status', [
                strtolower(EnumHelper::COMPLETED),
                strtolower(EnumHelper::UPCOMING)
            ])
            ->name('event.list');
        Route::get('/{uuid}', [EventController::class, 'getEventByUuid'])
            ->name('event.show');
        Route::get('/{uuid}/cancel', [EventController::class, 'cancelEvent'])
            ->name('event.cancel');
    });

});

