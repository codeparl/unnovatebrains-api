<?php

namespace App\Auth\Services;

use App\Auth\Models\User;
use App\Database\Database;

class AuthService
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::pdo();
    }

    public function login(string $email, string $password): array
    {
        $user = User::findByEmail($email);
        if (!$user) {
            return ['authenticated' => false];
        }

        if (!password_verify($password, $user->password)) {
            return ['authenticated' => false];
        }

        $token = bin2hex(random_bytes(32));

        $stmt = $this->pdo->prepare(
            'INSERT INTO api_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)'
        );
        $expiresAt = null;
        $stmt->execute([
            ':user_id' => $user->id,
            ':token' => $token,
            ':expires_at' => $expiresAt,
        ]);

        return [
            'authenticated' => true,
            'token' => $token,
            'user' => [
                'id' => (int)$user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
        ];
    }

    public function logout(string $token): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM api_tokens WHERE token = :token');
        $stmt->execute([':token' => $token]);
    }

    public function getUserByToken(string $token): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM api_tokens WHERE token IS NOT NULL');
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            if (hash_equals($row['token'], $token)) {
                if (!empty($row['expires_at'])) {
                    $expiresAt = new \DateTime($row['expires_at']);
                    if ($expiresAt < new \DateTime()) {
                        return null;
                    }
                }

                return User::findById((int)$row['user_id']);
            }
        }

        return null;
    }

    public function me(User $user): array
    {
        return [
            'id' => (int)$user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }
}

