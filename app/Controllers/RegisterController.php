<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\EmailVerificationToken;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RegisterController extends Controller
{

    public function registerUser(array $input): array
    {
        // Проверяем наличие email в input
        if (empty($input['email'])) {
            return ['status' => 'error', 'message' => 'Email не передан'];
        }

        $email = trim($input['email']);

        // Валидация email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Неверный формат email'];
        }

        // Проверка, существует ли пользователь с таким email
        $user = new User();
        $existingUser = $user->findByEmail($email);
        if ($existingUser) {
            return ['status' => 'error', 'message' => 'Пользователь с таким email уже зарегистрирован'];
        }

        // Генерация токена для подтверждения
        $token = $this->generateToken();
        $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Сохраняем токен в отдельной таблице или в users — зависит от реализации
        $emailVerificationTokenModel = new EmailVerificationToken();
        $saved = $emailVerificationTokenModel->saveToken($email, $token, $expires_at);

        if (!$saved) {
            return ['status' => 'error', 'message' => 'Ошибка при сохранении токена'];
        }

        // Отправляем письмо подтверждения
        $sent = $this->sendConfirmationEmail($email, $token);

        if (!$sent) {
            return ['status' => 'error', 'message' => 'Ошибка при отправке письма подтверждения'];
        }

        return [
            'status' => 'success',
            'message' => 'Письмо с подтверждением было отправлено на ваш email. Подтвердите регистрацию.'
        ];
    }


    private function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));  // Генерируем уникальный токен
    }

    private function sendConfirmationEmail($email, $token)
    {
        $confirmationLink = BASE_URL . "confirm?token=" . urlencode($token);
        $templatePath = __DIR__ . '/../../public/emails/email_template.html';
        $imgLink = BASE_URL . 'public/img/logo_black.png';

        if (!file_exists($templatePath)) {
            error_log("Email template not found: $templatePath");
            return false;
        }

        $htmlTemplate = file_get_contents($templatePath);
        $htmlMessage = str_replace(
            ['{{CONFIRMATION_LINK}}', '{{LOGO_LINK}}'],  // що замінюємо
            [$confirmationLink, $imgLink],                 // на що замінюємо
            $htmlTemplate
        );

        $mail = new PHPMailer(true);

        try {
            // Настройки SMTP
            $mail->isSMTP();
            $mail->Host = 'mail.skillcrafters2.tychina.kiev.ua';          // <-- замените на ваш SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'notifications@skillcrafters2.tychina.kiev.ua';  // <-- логин SMTP
            $mail->Password = 'jR8hT7gM5y';              // <-- пароль SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Или PHPMailer::ENCRYPTION_STARTTLS
            $mail->Port = 465; // Или 587 (в зависимости от SSL/TLS)

            // От кого
            $mail->setFrom('notifications@skillcrafters2.tychina.kiev.ua', 'Skillcrafters');
            $mail->addReplyTo('notifications@skillcrafters2.tychina.kiev.ua', 'Skillcrafters');

            // Кому
            $mail->addAddress($email);

            // Контент
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Subject = 'Подтверждение регистрации на Skillcrafters';
            $mail->Body = $htmlMessage;

            // Отправка
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("PHPMailer error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
