<?php
// Rakenduse sisenemispunkt (front controller)
declare(strict_types=1);

require __DIR__ . '/inc/database.php';
require __DIR__ . '/inc/functions.php';

spl_autoload_register(function (string $class): void {
    foreach (['model', 'controller'] as $dir) {
        $file = __DIR__ . '/' . $dir . '/' . $class . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

$routes = require __DIR__ . '/route/routing.php';
$page = (string)($_GET['page'] ?? 'start');

if (!isset($routes[$page])) {
    http_response_code(404);
    render('404', ['title' => 'Lehte ei leitud']);
    exit;
}

[$class, $method] = $routes[$page];
$opts = $routes[$page][2] ?? [];

if (isset($opts['role'])) {
    require_role($opts['role']);
}
if (!empty($opts['post']) && !is_post()) {
    http_response_code(405);
    exit('Meetod pole lubatud.');
}
if (is_post()) {
    csrf_check();
}

(new $class())->$method();
