<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService {

    public function login($credentials) {
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user != null && $this->authenticate($user, $credentials['password'])) {
            return $user;
        }
    }

    public function issueNewToken($user) {
        $this->revokeToken($user);
        return $this->createToken($user);
    }

    private function authenticate($user, $password) {
        return Hash::check($password, $user->password);
    }

    private function revokeToken($user) {
        $user->tokens()->delete();
    }

    private function createToken($user) {
        return $user->createToken($user->id)->plainTextToken;
    }
}