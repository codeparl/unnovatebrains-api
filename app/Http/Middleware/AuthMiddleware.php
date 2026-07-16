<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthService;

class AuthMiddleware
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function authenticate(): array
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!str_starts_with($header, 'Bearer ')) {
            return ['ok' => false];
        }

        $token = trim(substr($header, 7));
        if ($token === '') {
            return ['ok' => false];
        }

        $user = $this->authService->getUserByToken($token);
        if (!$user) {
            return ['ok' => false];
        }

        return ['ok' => true, 'user' => $this->authService->me($user)];
    }
}

