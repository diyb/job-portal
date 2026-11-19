<?php
// Andmebaasi ühendus (PDO). Muuda andmed vastavalt oma keskkonnale.
// Vaikeseaded on Dockeri jaoks (andmebaasi konteiner "db", root/root).
// XAMPP-is muuda: host 'localhost', parool ''. Vajadusel saab need üle kirjutada keskkonnamuutujatega.
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'jobportal');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'root');

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            $detail = getenv('APP_DEBUG') ? "\n\nPõhjus: " . $e->getMessage() . "\nHost: " . DB_HOST . ', andmebaas: ' . DB_NAME . ', kasutaja: ' . DB_USER : '';
            exit('Andmebaasiga ühendamine ebaõnnestus. Kontrolli inc/database.php seadeid ja impordi database.sql.' . $detail);
        }
    }
    return $pdo;
}
