<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class Quiz
{
    private PDO $db;

    protected array $data = [];

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function getByCourseId($id): array
    {
        $stmt = $this->db->query('SELECT * FROM quizzes');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}