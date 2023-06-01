<?php

namespace App\Http\Controllers\Inviter;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller {
    
    private $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }
    
    public function login(LoginRequest $request) {
        $user = $this->authService->login($request->validated());
        if ($user == null) return $this->errorResponse("Invalid Login", ['credentials' => ['Failed to login User']], Response::HTTP_UNAUTHORIZED);
        
        $token = $this->authService->issueNewToken($user);
        if ($token == null) return $this->errorResponse('Unable to create credentials', ['token' => ['Failed to create Auth Token']], Response::HTTP_UNAUTHORIZED);

        return $this->apiResponse($user, $token);
    }

    public function refresh(Request $request) {
        $user = $request->user();

        $token = $this->authService->issueNewToken($user);
        if ($token == null) return $this->errorResponse('Unable to create credentials', ['token' => ['Failed to create Auth Token']], Response::HTTP_INTERNAL_SERVER_ERROR);
        
        return $this->apiResponse($user, $token);
    }

    private function apiResponse($user, $token) {
        return response()->json([
            'data' => [
                'token' => $token,
            ]
        ]);
    }

    private function errorResponse($error, $errorsList, $code) {
        return response()->json([
            'message' => $error,
            'errors' => $errorsList
        ], $code);
    }
}
