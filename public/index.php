<?php

require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Routes/Router.php';
require_once __DIR__ . '/../app/Helpers/helpers.php';

use App\Router\Router;

// Автозавантаження класів
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    // Перевірка простору імен
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // Отримаємо відносний шлях до класу
    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});


$router = new Router();

// Ініціалізація роутера
$router->get('/', 'HomeController@index');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');
$router->post('/register', 'RegisterController@registerUser');
$router->get('/confirm', 'ConfirmController@confirmEmail');
$router->post('/confirmRegister', 'ConfirmController@addUser');
$router->get('/dashboardUser', 'HomeController@dashboardUser');

$router->get('/modules/list', 'ModulesController@listModules');
$router->get('/modules/add', 'ModulesController@addModule');
$router->post('/modules/new', 'ModulesController@newModule');
$router->get('/modules/edit', 'ModulesController@editModule');
$router->post('/modules/update', 'ModulesController@updateModule');

//Admin rout
$router->get('/dashboardAdmin', 'AdminController@dashboard');
$router->get('/admin/load', 'AdminController@loadContent');
$router->post('/admin/update', 'AdminController@updateUser');



// Обробка запиту
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);