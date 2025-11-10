<?php
require __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user'])) { $_SESSION['flash'] = 'Login required.'; header('Location: ../public/login.php'); exit; }
$action = $_POST['action'] ?? '';
if ($action === 'create') {
    $name = $_POST['name'] ?? '';
    $roll = $_POST['roll'] ?? '';
    $age = intval($_POST['age'] ?? 0);
    $class = $_POST['class'] ?? '';
    $contact = $_POST['parent_contact'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $stmt = $pdo->prepare('INSERT INTO students (name, roll, age, class, parent_contact, notes) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$name, $roll, $age, $class, $contact, $notes]);
    $_SESSION['flash']='Student added.';
    header('Location: ../public/students.php');
    exit;
} elseif ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        $stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
        $stmt->execute([$id]);
        $_SESSION['flash']='Student removed.';
    }
    header('Location: ../public/students.php');
    exit;
}
http_response_code(400);
echo 'Unknown action.';