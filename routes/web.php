<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Event controls
    Route::post('events/{event}/start-intro', [App\Http\Controllers\Admin\EventController::class, 'startIntro'])->name('events.start-intro');
    Route::post('events/{event}/start-quiz', [App\Http\Controllers\Admin\EventController::class, 'startQuiz'])->name('events.start-quiz');
    Route::post('events/{event}/next-question', [App\Http\Controllers\Admin\EventController::class, 'nextQuestion'])->name('events.next-question');
    Route::post('events/{event}/prev-question', [App\Http\Controllers\Admin\EventController::class, 'prevQuestion'])->name('events.prev-question');
    Route::post('events/{event}/unblur-question', [App\Http\Controllers\Admin\EventController::class, 'unblurQuestion'])->name('events.unblur-question');
    Route::post('events/{event}/reveal-answer', [App\Http\Controllers\Admin\EventController::class, 'revealAnswer'])->name('events.reveal-answer');
        Route::post('events/{event}/award-points', [App\Http\Controllers\Admin\EventController::class, 'awardPoints'])->name('events.award-points');
        Route::patch('events/{event}/award-points/{eventAnswer}', [App\Http\Controllers\Admin\EventController::class, 'updateAwardedPoints'])->name('events.award-points.update');
        Route::post('events/{event}/stop-timer', [App\Http\Controllers\Admin\EventController::class, 'stopTimer'])->name('events.stop-timer');

        Route::get('events/questions', [App\Http\Controllers\Admin\EventController::class, 'questionsIndex'])->name('events.questions.index');
        Route::get('events/{event}/questions', [App\Http\Controllers\Admin\EventController::class, 'editQuestions'])->name('events.questions.edit');
        Route::put('events/{event}/questions', [App\Http\Controllers\Admin\EventController::class, 'updateQuestions'])->name('events.questions.update');
        Route::get('events/{event}/questions/template', [App\Http\Controllers\Admin\EventController::class, 'downloadQuestionsTemplate'])->name('events.questions.template');
        Route::post('events/{event}/questions/import', [App\Http\Controllers\Admin\EventController::class, 'importQuestions'])->name('events.questions.import');

        Route::resource('events', App\Http\Controllers\Admin\EventController::class)->whereNumber('event');
        Route::resource('questions', App\Http\Controllers\Admin\QuestionController::class);
    Route::resource('contestants', App\Http\Controllers\Admin\ContestantController::class);
});

Route::get('display/{event}', [App\Http\Controllers\HomeController::class, 'display'])->name('display');
Route::get('display/{event}/state', [App\Http\Controllers\HomeController::class, 'displayState'])->name('display.state');
