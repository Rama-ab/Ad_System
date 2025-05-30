<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request){

        $data = $this->authService->register($request->validated());
        return response()->json([
            'message' => 'Registration successful',
            'user'    => $data['user'],
            'token'   => $data['token'],
        ], 201);
    }

    public function login(LoginRequest $request){

        $data = $this->authService->login($request->validated());
        
        if (!$data) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        return response()->json([
            'message' => 'Login successful',
            'user'    => $data['user'],
            'token'   => $data['token'],
        ]);
    }

    public function logout(Request $request){
        
        $this->authService->logout($request->user());
        return response()->json(['message' => 'Logged out successfully']);
    }
}
