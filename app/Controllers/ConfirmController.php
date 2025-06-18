<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\EmailVerificationToken;
use App\Database\Database;
use App\Core\View;

class ConfirmController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function confirmEmail($input)
    {

        if (isset($input['token'])) {
            $token = $input['token'];

            // Проверка токена в базе данных
            $stmt = $this->db->prepare("SELECT * FROM email_verification_tokens WHERE token = ?");
            $stmt->execute([$token]);
            $user = $stmt->fetch();


            if (!$user) {
                View::render("home", [
                    'status' => 'error',
                    'message' => 'Не действительная ссылка!'
                ]);
                return; 
            }

            if (new \DateTime($user['expires_at']) < new \DateTime()) {

                $stmt = $this->db->prepare("DELETE FROM email_verification_tokens WHERE token = ?");
                $stmt->execute([$token]);

                View::render("home", [
                    'status' => 'error',
                    'message' => 'Токен истёк. Пожалуйста, зарегистрируйтесь снова.'
                ]);
                return;
            }


            // Показываем форму для ввода пароля
            View::render('home', ['token' => $token, 'action' => 'confirmEmail']);
        }
    }

    public function addUser($input)
    {

        $firstName = trim($input['first_name'] ?? '');
        $lastName = trim($input['last_name'] ?? '');
        $nickName = trim($input['nick_name'] ?? '');
        $password = $input['password'] ?? '';
        $token = $input['token'] ?? '';

        if (!$token || !$password || !$firstName || !$lastName || !$nickName) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Недостатньо даних']);
            exit;
        }

        $stmt = $this->db->prepare("SELECT email FROM email_verification_tokens WHERE token = ?");
        $stmt->execute([$token]);
        $email = $stmt->fetchColumn();

        if (!$email) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Недійсний або прострочений токен']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);


        $stmt = $this->db->prepare("
            INSERT INTO users (first_name, last_name, nick_name, email, password, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $success = $stmt->execute([$firstName, $lastName, $nickName, $email, $hashedPassword]);

        if (!$success) {
            View::render("home", [
                    'status' => 'error',
                    'message' => 'Помилка при створенні користувача. Пожалуйста, зарегистрируйтесь снова.'
                ]);
            return;
        }

        // Удаление существующего токена
        $stmt = $this->db->prepare("DELETE FROM email_verification_tokens WHERE token = ?");
        $stmt->execute([$token]);

        // тут логіка перевірки токена, створення користувача і т.п.
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Реєстрація завершена успішно']);
        exit;
    }
}
