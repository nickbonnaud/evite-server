<?php

namespace App\Http\Controllers\Inviter;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller {
    
    private $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }
    
    public function login(LoginRequest $request) {
        $user = $this->authService->login($request->validated());
        $token = $this->authService->issueNewToken($user);

        return $this->apiResponse($user, $token);
    }

    public function refresh(Request $request) {
        $user = $request->user();
        $token = $this->authService->issueNewToken($user);
        
        return $this->apiResponse($user, $token);
    }

    private function apiResponse($user, $token) {
        return response()->json([
            'data' => [
                'token' => $token,
            ]
        ]);
    }
}
