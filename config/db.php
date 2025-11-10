<?php
// config/db.php
// Update DB credentials as needed
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'jagriti');
define('DB_USER', 'root');
define('DB_PASS', '');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Try to connect to the named database. If it doesn't exist, $pdo will be set to null.
// setup.php will create the database when needed.
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Database may not exist yet. Don't expose raw error to users.
    $pdo = null;
}
?>