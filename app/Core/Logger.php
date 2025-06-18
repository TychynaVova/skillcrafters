<?php

namespace App\Core;

class Logger {
    public static function logRequest(): void {
        $log = $_SERVER['REQUEST_URI'] . ' ' . $_SERVER['REQUEST_METHOD'];

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $log .= ' | GET params: ' . json_encode($_GET, JSON_UNESCAPED_UNICODE);
        } else {
            $input = file_get_contents('php://input');
            $log .= ' | Body: ' . $input;
        }

        $log .= "\n";
        file_put_contents(__DIR__ . '/../../log.txt', $log, FILE_APPEND);
    }
}