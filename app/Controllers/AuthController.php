<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\EmailVerificationToken;

class AuthController extends Controller
{

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
}
