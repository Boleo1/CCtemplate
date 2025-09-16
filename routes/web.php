<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/calendar', function () {
    return view('calendar');
});

Route::get('/events', function () {
    return view('events');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');