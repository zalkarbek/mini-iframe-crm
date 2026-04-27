<?php

use App\Http\Controllers\API\TicketController;
use App\Models\Ticket;

Route::prefix('tickets')
    ->name('tickets.')
    ->controller(TicketController::class)
    ->group(function (): void {

        Route::get('/', 'index')
            ->middleware(['auth:sanctum'])
            ->can('view', Ticket::class)
            ->name('index');

        Route::post('/', 'store')
            ->name('store');

        Route::get('/statistics', 'statistics')
            ->middleware(['auth:sanctum'])
            ->can('modifyStatistic', Ticket::class)
            ->name('statistics');
    });
