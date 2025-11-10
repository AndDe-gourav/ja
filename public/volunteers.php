<?php
require __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO volunteers (name, email, phone, skills) VALUES (?,?,?,?)");
    $stmt->execute([$name, $email, $phone, $skills]);
    $_SESSION['flash'] = 'Volunteer added.';
    header('Location: volunteers.php'); exit;
}

$rows = [];
try {
    $rows = $pdo->query("SELECT * FROM volunteers ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container">
  <h1>Volunteers</h1>
  <form method="post">
    <input type="hidden" name="action" value="add">
    <label>Name</label><input name="name" required>
    <label>Email</label><input name="email">
    <label>Phone</label><input name="phone">
    <label>Skills</label><textarea name="skills"></textarea>
    <button class="btn" type="submit">Add Volunteer</button>
  </form>

  <h2>All Volunteers</h2>
  <table class="striped">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th></tr></thead>
    <tbody>
      <?php foreach($rows as $r): ?>
      <tr>
        <td><?=$r['id']?></td>
        <td><?=htmlspecialchars($r['name'])?></td>
        <td><?=htmlspecialchars($r['email'])?></td>
        <td><?=htmlspecialchars($r['phone'])?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>