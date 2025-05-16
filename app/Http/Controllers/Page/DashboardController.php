<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request) {

	    return Inertia::render('Dashboard', [
		    'routeBack' => route('page.dashboard'),
			'current_time' => now()->format('d.m.Y H:i:s'),
			"user_info" => Auth::user()
	    ]);
    }
}
