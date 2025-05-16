<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticationService
{
    public function register($data)
    {
        if (User::where('email', $data['email'])->exists()) {
            return response()->json([
                'message' => 'User with this email already exists.'
            ], 409); 
        }

        $formData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ];
  
        $formData['password'] = bcrypt($data['password']);
  
        $user = User::create($formData);        
  
        return response()->json([ 
            'user' => $user, 
            'token' => $user->createToken('passportToken')->accessToken
        ], 200);
          
    }
  
    public function login($data)
    {
        $credentials = [
            'email'    => $data['email'],
            'password' => $data['password'],
        ];
  
        if (Auth::attempt($credentials)) 
        {
            $token = Auth::user()->createToken('passportToken')->accessToken;
             
            return response()->json([
                'user' => Auth::user(), 
                'token' => $token
            ], 200);
        }
  
        return response()->json([
            'error' => 'Unauthorized'
        ], 401);
  
    }
}