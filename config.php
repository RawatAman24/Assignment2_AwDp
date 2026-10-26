<?php
// ---------- Configuration ----------
define('DB_HOST', 'localhost');
define('DB_NAME', 'trendykart');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SITE_NAME', 'TrendyKart');
define('BASE_URL', '/trendykart');   // change if you put the project in a different folder
define('UPLOAD_DIR', __DIR__ . '/uploads/');

if (session_status() === PHP_SESSION_NONE) session_start();

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connection failed. Check config.php');
}
require_once __DIR__ . '/includes/functions.php';
