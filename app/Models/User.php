<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class User {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getAllUsers() {
        $stmt = $this->db->query('SELECT * FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createUser($email, $password) {
        $stmt = $this->db->prepare('INSERT INTO users (email, password) VALUES (?, ?)');
        $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Вернёт массив или false
    }
}
