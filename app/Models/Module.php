<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class Module
{
    private PDO $db;

    protected array $data = [];

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function getByCourseId($id, $page = 1, $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
    
        $stmt = $this->db->prepare('
            SELECT * FROM modules 
            WHERE course_id = :course_id 
            ORDER BY position 
            LIMIT :limit OFFSET :offset
        ');
        $stmt->bindValue(':course_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM modules WHERE course_id = :course_id');
        $stmt->bindValue(':course_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $total = $stmt->fetchColumn();
        
        return [
            'modules' => $modules,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];

    }

    public function createModule($data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO modules (course_id, title, description, position) 
            VALUES (:course_id, :title, :description, :position)
        ");
        return $stmt->execute([
            ':course_id' => $data['course_id'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':position' => $data['position'] ?? 0
        ]);
    }

    public function getNextPosition($courseId): mixed {
        $stmt = $this->db->prepare("
            SELECT MAX(position) + 1 
            FROM modules 
            WHERE course_id = ?
        ");
        $stmt->execute([$courseId]);
        return $stmt->fetchColumn() ?? 1;
    }

    public static function find(int $id): ?array
    {
        $db = (new self())->db;
        $stmt = $db->prepare("SELECT * FROM modules WHERE id = ?");
        $stmt->execute([$id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }
}