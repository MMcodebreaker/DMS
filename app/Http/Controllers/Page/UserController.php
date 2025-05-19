<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;


class UserController extends Controller
{
    //

    public function index(Request $request) {
	    return Inertia::render('Users');
    }
}
