<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\EmailVerificationToken;
use App\Database\Database;

class SetPasswordController extends Controller {
    
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function setPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
            $token = $_POST['token'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm-password'];

            // Проверка, совпадают ли пароли
            if ($password !== $confirmPassword) {
                echo "Пароли не совпадают.";
                exit;
            }

            // Хеширование пароля
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Получаем email из токена
            $stmt = $this->db->prepare("SELECT email FROM email_verification_tokens WHERE token = ?");
            $stmt->execute([$token]);
            $user = $stmt->fetch();

            if (!$user) {
                echo "Неверный токен.";
                exit;
            }

            // Сохраняем пользователя в базу данных
            $email = $user['email'];
            $userModel = new User();
            $userModel->createUser($email, $hashedPassword);

            // Удаляем токен из базы
            $stmt = $this->db->prepare("DELETE FROM email_verification_tokens WHERE token = ?");
            $stmt->execute([$token]);

            echo "Регистрация завершена, вы можете войти.";
        }
    }
}
