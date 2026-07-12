<?php
session_start();

require_once 'config.php';
require_once 'includes.php';

// Autoload simple de controladores
spl_autoload_register(function ($class) {
    $paths = [
        'controllers/' . $class . '.php',
        'models/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Formato esperado: ?route=controlador/accion
$route = $_GET['route'] ?? 'evento/catalogo';
[$controllerName, $action] = array_pad(explode('/', $route, 2), 2, 'index');

$controllerClass = ucfirst($controllerName) . 'Controller';

if (!class_exists($controllerClass)) {
    http_response_code(404);
    echo 'Página no encontrada.';
    exit;
}

$controller = new $controllerClass();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo 'Acción no encontrada.';
    exit;
}

$controller->$action();
