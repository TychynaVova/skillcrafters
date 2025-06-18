<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\EmailVerificationToken; 

class RegisterController extends Controller
{

    public function registerUser($input)
    {
        $email = $input['email'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Неверный формат email'];
        }

        $token = $this->generateToken();
        $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $emailVerificationTokenModel = new EmailVerificationToken();
        $emailVerificationTokenModel->saveToken($email, $token, $expires_at);

        $this->sendConfirmationEmail($email, $token);

        return ['status' => 'success', 'message' => 'Письмо с подтверждением было отправлено на ваш email. Подтвердите регистрацию.'];
    }


    private function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));  // Генерируем уникальный токен
    }

    private function sendConfirmationEmail($email, $token)
    {
        $confirmationLink = BASE_URL."confirm?token=" . urlencode($token);
        $templatePath = __DIR__ . '/../../public/emails/email_template.html';

        if (!file_exists($templatePath)) {
            error_log("Email template not found: $templatePath");
            return false;
        }

        // Загружаем HTML-шаблон и вставляем ссылку
        $htmlTemplate = file_get_contents($templatePath);
        $htmlMessage = str_replace('{{CONFIRMATION_LINK}}', $confirmationLink, $htmlTemplate);

        // Преобразуем переносы строк (важно для Windows sendmail)
        $htmlMessage = str_replace("\n.", "\n..", $htmlMessage);

        // Заголовки письма
        $from = 'no-reply@skillcrafters.loc';
        $fromName = 'Skillcrafters';

        $subject = 'Подтверждение регистрации на Skillcrafters';
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . sprintf('%s <%s>', $fromName, $from),
            'Reply-To: ' . $from,
            'X-Mailer: PHP/' . phpversion(),
        ];

        $headersString = implode("\r\n", $headers);

        // Некоторые серверы требуют четко указанный envelope sender через параметр -f
        $additionalParams = '-f ' . escapeshellarg($from);

        // Отправка
        $success = mail($email, $encodedSubject, $htmlMessage, $headersString, $additionalParams);

        if (!$success) {
            error_log("mail() failed to send confirmation email to $email");
            return false;
        }

        return true;
    }
}
