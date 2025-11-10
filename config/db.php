<?php
// config/db.php
// SQLite configuration for Replit environment
define('DB_PATH', __DIR__ . '/../data/jagriti.sqlite');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Ensure the data directory exists
$dataDir = dirname(DB_PATH);
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// Connect to SQLite database (creates file if it doesn't exist)
try {
    $pdo = new PDO('sqlite:' . DB_PATH, null, null, $options);
    // Enable foreign keys in SQLite
    $pdo->exec('PRAGMA foreign_keys = ON;');
} catch (PDOException $e) {
    // Database connection failed
    $pdo = null;
    error_log('Database connection failed: ' . $e->getMessage());
}
?>
