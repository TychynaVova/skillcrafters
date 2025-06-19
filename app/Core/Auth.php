<?php

namespace Core;

use App\Models\User;

class Auth
{
    public static function user(): ?User {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return User::find($_SESSION['user_id']);
    }

    public static function check(): bool {
        return self::user() !== null;
    }

    public static function requireRole(int|array $allowedRoles): void {
        $user = self::user();

        if (!$user || !in_array($user->getRoleId(), (array)$allowedRoles, true)) {
            http_response_code(403);
            exit('Access denied');
        }
    }

    public static function login(User $user): void {
        $_SESSION['user_id'] = $user->getId();
    }

    public static function logout(): void {
        unset($_SESSION['user_id']);
    }
}
