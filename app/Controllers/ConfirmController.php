<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\EmailVerificationToken;
use App\Database\Database;
use App\Core\View;
use Psr\Log\LoggerInterface;

class ConfirmController extends Controller
{
    private $db;
    private LoggerInterface $logger;

    public function __construct(Database $database, LoggerInterface $logger)
    {
        $this->db = $database->getConnection();
        $this->logger = $logger;
    }

    public function confirmEmail($input)
    {
        if (isset($input['token'])) {
            $token = $input['token'];

            $stmt = $this->db->prepare("SELECT * FROM email_verification_tokens WHERE token = ?");
            $stmt->execute([$token]);
            $user = $stmt->fetch();

            if (!$user) {
                $this->logger->warning('Попытка подтверждения email с недействительным токеном', ['token' => $token]);
                View::render("home", [
                    'status' => 'error',
                    'message' => 'Не действительная ссылка!'
                ]);
                return;
            }

            if (new \DateTime($user['expires_at']) < new \DateTime()) {
                $this->logger->info('Токен подтверждения email истёк', ['token' => $token]);

                $stmt = $this->db->prepare("DELETE FROM email_verification_tokens WHERE token = ?");
                $stmt->execute([$token]);

                View::render("home", [
                    'status' => 'error',
                    'message' => 'Токен истёк. Пожалуйста, зарегистрируйтесь снова.'
                ]);
                return;
            }

            View::render('home', ['token' => $token, 'action' => 'confirmEmail']);
        } else {
            $this->logger->warning('Отсутствует параметр token при подтверждении email', ['input' => $input]);
        }
    }

    public function addUser($input)
    {
        $firstName = trim($input['first_name'] ?? '');
        $lastName = trim($input['last_name'] ?? '');
        $nickName = trim($input['nick_name'] ?? '');
        $password = $input['password'] ?? '';
        $token = $input['token'] ?? '';
        $roleId = $input['role_id'] ?? '4';

        if (!$token || !$password || !$firstName || !$lastName || !$nickName) {
            $this->logger->error('Недостаточно данных для регистрации пользователя', ['input' => $input]);

            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Недостатньо даних']);
            exit;
        }

        $stmt = $this->db->prepare("SELECT email FROM email_verification_tokens WHERE token = ?");
        $stmt->execute([$token]);
        $email = $stmt->fetchColumn();

        if (!$email) {
            $this->logger->error('Недействительный или просроченный токен при регистрации пользователя', ['token' => $token]);

            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Недійсний або прострочений токен']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("
            INSERT INTO users (first_name, last_name, nick_name, email, password, role_id, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $success = $stmt->execute([$firstName, $lastName, $nickName, $email, $hashedPassword, $roleId]);

        if (!$success) {
            $this->logger->error('Ошибка при создании пользователя', ['email' => $email]);
            View::render("home", [
                'status' => 'error',
                'message' => 'Помилка при створенні користувача. Пожалуйста, зарегистрируйтесь снова.'
            ]);
            return;
        }

        $stmt = $this->db->prepare("DELETE FROM email_verification_tokens WHERE token = ?");
        $stmt->execute([$token]);

        $this->logger->info('Пользователь успешно зарегистрирован', ['email' => $email, 'nickName' => $nickName]);

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Реєстрація завершена успішно']);
        exit;
    }
}
