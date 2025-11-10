<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';
require_admin(); // Only admins can manage volunteers

$action = $_POST['action'] ?? '';

if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        $stmt = $pdo->prepare('DELETE FROM volunteers WHERE id = ?');
        $stmt->execute([$id]);
        $_SESSION['flash']='Volunteer removed successfully.';
    }
    header('Location: ../public/volunteers.php');
    exit;
}

http_response_code(400);
echo 'Unknown action.';
?>
