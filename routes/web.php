<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\eventsController;
use App\Http\Controllers\eventsRequestController;

Route::get('/', function () {
    $pageTitle = 'Home';
    return view('home');
});

Route::get('/calendar', function () {
    $pageTitle = 'Calendar';
    return view('calendar');
});

Route::get('/events', [eventsController::class, 'index'])->name('events.index');
Route::post('/events',[eventsController::class, 'submit'])->name('events.submit');

Route::post('/events/request',[eventsRequestController::class, 'submit'])->name('events.request.submit');

Route::get('/contact', function () { return view('contact'); });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
