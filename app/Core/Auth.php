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

    public static function requireRole($requiredRole)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['role'])) {
            header('HTTP/1.1 403 Forbidden');
            echo 'Access denied: Not logged in.';
            exit;
        }

        // Зробимо гнучке порівняння
        if ($_SESSION['role'] != $requiredRole) {
            header('HTTP/1.1 403 Forbidden');
            echo 'Access denied: Insufficient permissions.';
            exit;
        }
    }

    public static function login(User $user): void {
        $_SESSION['user_id'] = $user->getId();
    }

    public static function logout(): void {
        unset($_SESSION['user_id']);
    }
}
