<?php

namespace App\Core;
use Exception;

class View {
    public static function render($view, $data = []) {
        
        // Путь к файлу вида
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (file_exists($viewPath)) {
            extract($data);
            require_once($viewPath);
        } else {
            // Ошибка, если файл не найден
            throw new Exception("View not found: " . $viewPath);
        }
    }

    public function renderPartial(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }
}

