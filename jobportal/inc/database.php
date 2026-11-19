<?php
// Andmebaasi ühendus (PDO). Muuda andmed vastavalt oma keskkonnale.
const DB_HOST = 'localhost';
const DB_NAME = 'jobportal';
const DB_USER = 'root';
const DB_PASS = '';

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
            exit('Andmebaasiga ühendamine ebaõnnestus. Kontrolli inc/database.php seadeid ja impordi database.sql.');
        }
    }
    return $pdo;
}
