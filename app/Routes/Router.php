<?php

namespace App\Router;

class Router
{
    private $routes = [];

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute($method, $uri, $action)
    {
        $this->routes[$method][$uri] = $action;
    }

    public function dispatch($uri, $method)
{
    $uri = parse_url($uri, PHP_URL_PATH);

    if (isset($this->routes[$method][$uri])) {
        [$controllerName, $methodName] = explode('@', $this->routes[$method][$uri]);

        $controllerClass = "App\\Controllers\\$controllerName";
        if (class_exists($controllerClass)) {

            // Подключаем зависимости
            $database = new \App\Database\Database();
            $logger = new \Logger\SimpleLogger(__DIR__ . '/../../logs', 'info');

            // Передаём зависимости в нужный контроллер
            if ($controllerClass === "App\\Controllers\\ConfirmController") {
                $controller = new $controllerClass($database, $logger);
            } else {
                $controller = new $controllerClass();
            }

            $input = $method === 'GET' ? $_GET : json_decode(file_get_contents('php://input'), true);
            $response = call_user_func([$controller, $methodName], $input);

            if (is_array($response) || is_object($response)) {
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                echo $response;
            }
            return;
        } else {
            http_response_code(500);
            echo "Контроллер не найден: " . $controllerClass;
            exit;
        }
    } else {
        http_response_code(404);
        echo "Маршрут не найден: " . $method . " " . $uri;
        exit;
    }
}
}
