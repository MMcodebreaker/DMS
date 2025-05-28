<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    //

    public function showLinkRequestForm()
    {
        return Inertia::render('Authentication/ForgotPassword');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));


        // Add logic to send the reset link to registered user email;
        
        return back()->with([
            'status' => __($status)
        ]);
    }
}
