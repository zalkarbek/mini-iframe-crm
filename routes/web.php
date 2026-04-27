<?php

use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

Route::view('/widget', 'widget.index')->name('widget.index');
Route::view('/feedback-widget', 'widget.index')->name('widget.feedback');

Route::prefix('manager')->name('manager.')->group(function () {

    Route::view('/login', 'pages.manager.login')
        ->name('login');

    Route::get('/tickets', [ManagerController::class, 'tickets'])
        ->name('tickets');

    Route::get('/tickets/{ticketId}', [ManagerController::class, 'ticketShow'])
        ->name('tickets.show');

    Route::get('/dashboard', [ManagerController::class, 'dashboard'])
        ->name('dashboard');

    Route::post('/logout', function () {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('manager.login');
    })->middleware('auth')->name('logout');
});

Route::get('/media/{media}/download', function (Media $media) {
    return response()->download($media->getPath(), $media->file_name);
})->name('media.download');
