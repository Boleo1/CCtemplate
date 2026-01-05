<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Controller for admin dashboard
class dashboardController extends Controller
{
    public function index()
    {
        $pageTitle = 'Dashboard';
        return view('dashboard.index', compact('pageTitle'));
    }
}
