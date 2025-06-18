<?php

namespace App\Router;

class Router
{
    private $routes = [];

    public function get($uri, $action) {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action) {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute($method, $uri, $action) {
        $this->routes[$method][$uri] = $action;
    }

    public function dispatch($uri, $method) {
        $uri = parse_url($uri, PHP_URL_PATH);

        if (isset($this->routes[$method][$uri])) {
            [$controllerName, $methodName] = explode('@', $this->routes[$method][$uri]);

            $controllerClass = "App\\Controllers\\$controllerName";
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();

                // Передаём GET параметри як $input
                $input = $method === 'GET' ? $_GET : json_decode(file_get_contents('php://input'), true);
                return call_user_func([$controller, $methodName], $input);
            } else {
                http_response_code(500);
                echo "Контроллер не найден: ".$controllerClass;
                exit;
            }
        } else {
            http_response_code(404);
            echo "Маршрут не найден: ".$method ." " . $uri;
            exit;
        }
    }
}
