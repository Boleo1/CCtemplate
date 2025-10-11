<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\eventFeedController;

Route::get('/events', [eventFeedController::class, 'index']);

?>