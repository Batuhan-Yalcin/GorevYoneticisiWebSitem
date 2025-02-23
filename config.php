<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_HOST', 'localhost');
define('DB_NAME', 'todo_app');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    error_log("Veritabanı bağlantı hatası: " . $e->getMessage());
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?> 