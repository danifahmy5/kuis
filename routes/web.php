<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventQuestionController;
use App\Http\Controllers\Admin\LeaderboardController;

Route::get('/', function () {
    return view('welcome');
});

// Dummy admin login - replace with a real auth controller
Route::get('/admin/login', function() {
    // For now, just log in the first admin
    auth('admin')->loginUsingId(1);
    return redirect('/admin/events');
})->name('admin.login');


Route::prefix('admin')
    ->middleware('auth:admin')
    ->as('admin.')
    ->group(function () {
        Route::resource('events', EventController::class);

        Route::post('events/{event}/start', [EventController::class, 'start'])->name('events.start');
        Route::post('events/{event}/stop', [EventController::class, 'stop'])->name('events.stop');

        Route::post('events/{event}/questions/{seq}/show', [EventQuestionController::class, 'show'])->name('events.questions.show');
        Route::post('events/{event}/questions/{seq}/mark', [EventQuestionController::class, 'mark'])->name('events.questions.mark');

        Route::get('events/{event}/leaderboard', [LeaderboardController::class, 'show'])->name('events.leaderboard');
        Route::get('events/{event}/export/csv', [LeaderboardController::class, 'export'])->name('events.export.csv');
});

use App\Http\Controllers\DisplayController;

Route::get('/display/{event}', [DisplayController::class, 'show'])->name('display');
