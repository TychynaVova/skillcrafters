<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class User
{
    private PDO $db;

    protected array $data = [];

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    /**
     * Поиск пользователя по ID
     */
    public static function find(int $id): ?array
    {
        $db = (new self())->db;
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    /**
     * Поиск пользователя по email
     */
    public static function findByEmail(string $email): ?self
    {
        $user = new self();
        $stmt = $user->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }

        $user->data = $data;
        return $user;
    }

    /**
     * Получить всех пользователей
     */
    public function getAllUsers(): array
    {
        $stmt = $this->db->query('SELECT * FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Создать нового пользователя
     */
    public function createUser(string $email, string $password): bool
    {
        $stmt = $this->db->prepare('INSERT INTO users (email, password) VALUES (?, ?)');
        return $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE users SET 
            first_name = :first_name,
            last_name = :last_name,
            nick_name = :nick_name,
            email = :email,
            role_id = :role_id,
            status = :status,
            blocked_reason = :blocked_reason,
            updated_at = NOW()
        WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':nick_name' => $data['nick_name'],
            ':email' => $data['email'],
            ':role_id' => $data['role_id'],
            ':status' => $data['status'],
            ':blocked_reason' => $data['blocked_reason'],
            ':id' => $id
        ]);
    }

    // ==== Геттеры и утилиты ====

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    public function getData(): array
    {
        return $this->data;
    }
    
    public function getId(): ?int
    {
        return $this->data['id'] ?? null;
    }

    public function getPassword(): string
    {
        return $this->data['password'] ?? '';
    }

    public function getEmail(): ?string
    {
        return $this->data['email'] ?? null;
    }

    public function getRoleId(): int
    {
        return (int)($this->data['role_id'] ?? 4);
    }

    public function isAdmin(): bool
    {
        return $this->getRoleId() === 1;
    }

    public function isRepresentative(): bool
    {
        return $this->getRoleId() === 2;
    }

    public function isReferee(): bool
    {
        return $this->getRoleId() === 3;
    }
}
