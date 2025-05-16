<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Call AuthenticationService
use App\Services\AuthenticationService;

class AuthenticationController extends Controller
{
    protected AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $data = $this->authService->register($request->all());
        return response()->json($data);
    }

    public function login(Request $request)
    {
        $data = $this->authService->login($request->all());
        return response()->json($data);
    }

}
