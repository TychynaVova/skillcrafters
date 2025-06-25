<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\View;
use App\Models\EmailVerificationToken;
use Logger\SimpleLogger;

class AuthController extends Controller
{

    private SimpleLogger $logger;

    public function __construct()
    {
        $this->logger = new SimpleLogger(__DIR__ . '/../../logs');
    }

    // Обработка регистрации пользователя
    public function registerUser($email)
    {

        // Проверка на валидность email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'success', 'error' => 'Неверный формат email'];
        }

        // Генерация токена
        $token = $this->generateToken();

        // Вставляем токен в базу данных с временем истечения через 24 часа
        $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $emailVerificationTokenModel = new EmailVerificationToken();
        $emailVerificationTokenModel->saveToken($email, $token, $expires_at);

        // Отправка письма с ссылкой на подтверждение
        $this->sendConfirmationEmail($email, $token);
        return ['status' => 'success', 'message' => 'Письмо с подтверждением было отправлено на ваш email'];
    }

    private function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));  // Генерируем уникальный токен
    }

    private function sendConfirmationEmail($email, $token)
    {
        $subject = "Подтверждение регистрации на сайте";
        $message = "Здравствуйте! Чтобы завершить регистрацию, пройдите по следующей ссылке: ";
        $message .= "http://skillcrafters.loc/confirm?token=" . $token;

        // Заголовки письма
        $headers = "From: no-reply@skillcrafters.loc\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Отправляем письмо
        mail($email, $subject, $message, $headers);
    }

    public function login(array $input)
    {
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $this->logger->error("Спроба входу з неіснуючим email", ['email' => $email, 'input' => json_encode($input, JSON_UNESCAPED_UNICODE)]);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Користувача не знайдено.']);
            exit;
        }

        if (!password_verify($password, $user->getPassword())) {
            $this->logger->error("Невірний пароль при вході", ['email' => $email]);
            return ['status' => 'error', 'message' => 'Невірний email або пароль.'];
        }

        session_start();
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['role'] = $user->getRoleId() ?? 'user';

        $this->logger->info("Користувач увійшов успішно", [
            'id'    => $user->getId(),
            'email' => $user->getEmail(),
            'role'  => $user->getRoleId()
        ]);

        // Роутинг залежно від ролі
        switch ($_SESSION['role']) {
            case '1':
                $redirectUrl = '/dashboardAdmin';
                break;
            case '2':
                $redirectUrl = '/dashboardTeam';
                break;
            case '3':
                $redirectUrl = '/dashboardReferee';
                break;
            default:
                $redirectUrl = '/dashboardUser';
        }

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Вхід успішний',
            'redirect' => $redirectUrl,
        ]);
    }

    public function logout() {
        $_SESSION = [];
        session_unset();
        session_destroy();

        echo json_encode(['success' => true]);
        exit;
    }
}
