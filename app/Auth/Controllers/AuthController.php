<?php

namespace App\Auth\Controllers;

use App\Auth\Middleware\AuthMiddleware;
use App\Auth\Services\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid JSON']);
            return;
        }

        $email = isset($data['email']) ? trim((string)$data['email']) : '';
        $password = isset($data['password']) ? (string)$data['password'] : '';

        if ($email === '' || $password === '') {
            http_response_code(400);
            echo json_encode(['message' => 'Email and password are required']);
            return;
        }

        $result = $this->authService->login($email, $password);
        if (empty($result['authenticated'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
            return;
        }

        http_response_code(200);
        echo json_encode([
            'token' => $result['token'],
            'user' => $result['user'],
        ]);
    }

    public function logout(): void
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!str_starts_with($header, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthenticated']);
            return;
        }

        $token = trim(substr($header, 7));
        if ($token === '') {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthenticated']);
            return;
        }

        $this->authService->logout($token);
        http_response_code(200);
        echo json_encode(['message' => 'Logged out']);
    }

    public function me(): void
    {
        $middleware = new AuthMiddleware();
        $auth = $middleware->authenticate();

        if (!$auth['ok']) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthenticated']);
            return;
        }

        http_response_code(200);
        echo json_encode($auth['user']);
    }
}

