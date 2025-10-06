<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\eventFeedController;

Route::get('/events', [eventFeedController::class, 'index']); // public read feed
?>