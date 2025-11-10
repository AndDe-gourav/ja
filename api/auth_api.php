<?php
require __DIR__ . '/../config/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
try {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']);
        $_SESSION['user'] = $user;
        header('Location: ../public/index.php');
        exit;
    } else {
        $_SESSION['flash'] = 'Login failed.';
        header('Location: ../public/login.php');
        exit;
    }
} catch (Exception $e) {
    $_SESSION['flash'] = 'Login error.';
    header('Location: ../public/login.php');
    exit;
}