<?php
require __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user'])) { $_SESSION['flash'] = 'Login required.'; header('Location: ../public/login.php'); exit; }
$action = $_POST['action'] ?? '';
if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        $stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
        $stmt->execute([$id]);
        $_SESSION['flash']='Book removed.';
    }
    header('Location: ../public/books.php');
    exit;
}
http_response_code(400);
echo 'Unknown action.';