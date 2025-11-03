<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\eventsController;
use App\Http\Controllers\eventsRequestController;
use App\Http\Controllers\Admin\dashboardController;
use App\Http\Controllers\Admin\adminEventController;

Route::get('/', function () {
    $pageTitle = 'Home';
    return view('home');
})->name('home');

Route::get('/about', function () {
    $pageTitle = 'About Us';
    return view('about');
});

Route::get('/calendar', function () {
    $pageTitle = 'Calendar';
    return view('calendar');
});

Route::get('/events', [eventsController::class, 'index'])->name('events.index');
Route::post('/events',[eventsController::class, 'submit'])->name('events.submit');

Route::post('/events/request',[eventsRequestController::class, 'submit'])->name('events.request.submit');

Route::get('/contact', function () { return view('contact'); });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('dashboard')
    ->middleware(['auth'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [dashboardController::class, 'index'])->name('index');

        Route::get('/events', [adminEventController::class, 'index'])->name('events.index');
        Route::patch('/events/{event}', [adminEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [adminEventController::class, 'destroy'])->name('events.destroy');

        Route::post('/events/reorder', [adminEventController::class, 'reorder'])->name('events.reorder');

        Route::get('/requests', [eventsRequestController::class, 'index'])->name('requests.index');
        Route::patch('/requests/{request}/moderate', [eventsRequestController::class, 'moderate'])->name('requests.moderate');

    });

require __DIR__.'/auth.php';
