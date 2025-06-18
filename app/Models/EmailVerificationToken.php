<?php

namespace App\Models;

use App\Database\Database;

class EmailVerificationToken
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function saveToken($email, $token, $expires_at)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO email_verification_tokens (email, token, expires_at) VALUES (?, ?, ?)"
        );
        $stmt->execute([$email, $token, $expires_at]);
    }

    public function getTokenData($token)
    {
        $stmt = $this->db->prepare("SELECT * FROM email_verification_tokens WHERE token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function deleteToken($token)
    {
        $stmt = $this->db->prepare("DELETE FROM email_verification_tokens WHERE token = ?");
        $stmt->execute([$token]);
    }
}
