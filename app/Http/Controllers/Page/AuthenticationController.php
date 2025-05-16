<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthenticationService;
use Inertia\Inertia;
use function App\Helpers\toast;
use function App\Helpers\storeConfig;
use App\Enums\ToastTypesEnum;

class AuthenticationController extends Controller
{
    public function login_page(Request $request)
    {
           return Inertia::render('Authentication/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::check()) {
            return redirect()->route('page.dashboard');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $userName = Auth::user()->name;

            //Sending Alert
            toast(ToastTypesEnum::Success, "Welcome back, {$userName} !");

            return redirect()->route('page.dashboard');
        }else{
            //Sending Alert
            toast(ToastTypesEnum::Error, "Invalid Credential!");
            return redirect()->route('page.login');
        }

        return back()->withErrors([
            'general' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        toast(ToastTypesEnum::Success, "Logged Out Successfully !");

        return redirect()->route('page.login'); 
    }
}
