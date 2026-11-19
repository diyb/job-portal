<?php
// Administraatori paneeli sisenemispunkt
declare(strict_types=1);

define('IS_ADMIN_APP', true);
require __DIR__ . '/../inc/database.php';
require __DIR__ . '/../inc/functions.php';

spl_autoload_register(function (string $class): void {
    foreach (['modelAdmin', 'controllerAdmin', '../model'] as $dir) {
        $file = __DIR__ . '/' . $dir . '/' . $class . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

$routes = require __DIR__ . '/routeAdmin/routing.php';
$page = (string)($_GET['page'] ?? 'dashboard');

if (!isset($routes[$page])) {
    http_response_code(404);
    exit('Lehte ei leitud.');
}

[$class, $method] = $routes[$page];
$opts = $routes[$page][2] ?? [];

if (empty($opts['public'])) {
    $u = current_user();
    if (!$u) {
        redirect(url('login'));
    }
    if ($u['role'] !== 'admin') {
        http_response_code(403);
        exit('Ligipääs ainult administraatoritele.');
    }
}
if (!empty($opts['post']) && !is_post()) {
    http_response_code(405);
    exit('Meetod pole lubatud.');
}
if (is_post()) {
    csrf_check();
}

(new $class())->$method();
