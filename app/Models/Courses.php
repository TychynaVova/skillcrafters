<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class Courses
{
    private PDO $db;

    protected array $data = [];

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function getAllCourses(): array
    {
        $stmt = $this->db->query('SELECT * FROM courses');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $db = (new self())->db;
        $stmt = $db->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE courses SET 
            title = :title,
            slug = :slug,
            description = :description,
            image = :image,
            status = :status,
            price = :price,
            is_featured = :is_featured,
            level = :level,
            duration = :duration,
            language = :language,
            updated_at = NOW()
        WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':description' => $data['description'],
            ':image' => $data['image'],
            ':status' => $data['status'],
            ':price' => $data['price'],
            ':is_featured' => $data['is_featured'],
            ':level' => $data['level'],
            ':duration' => $data['duration'],
            ':language' => $data['language'],
            ':id' => $id
        ]);
    }

}
