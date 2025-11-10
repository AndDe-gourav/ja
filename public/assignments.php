<?php
require __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $title = $_POST['title'] ?? '';
    $desc = $_POST['description'] ?? '';
    $due = $_POST['due_date'] ?? null;
    $filePath = null;
    if (!empty($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $fname = basename($_FILES['file']['name']);
        $target = $uploadDir . '/' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/','_', $fname);
        move_uploaded_file($_FILES['file']['tmp_name'], $target);
        $filePath = 'uploads/' . basename($target);
    }
    $stmt = $pdo->prepare("INSERT INTO assignments (title, description, due_date, file_path) VALUES (?,?,?,?)");
    $stmt->execute([$title, $desc, $due ?: null, $filePath]);
    $_SESSION['flash'] = 'Assignment saved.';
    header('Location: assignments.php'); exit;
}

$rows = [];
try {
    $rows = $pdo->query("SELECT * FROM assignments ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container">
  <h1>Assignments</h1>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    <label>Title</label><input name="title" required>
    <label>Description</label><textarea name="description"></textarea>
    <label>Due date</label><input type="date" name="due_date">
    <label>Attach file (optional)</label><input type="file" name="file">
    <button class="btn" type="submit">Save</button>
  </form>

  <h2>All Assignments</h2>
  <table class="striped">
    <thead><tr><th>ID</th><th>Title</th><th>Due</th><th>File</th></tr></thead>
    <tbody>
      <?php foreach($rows as $r): ?>
      <tr>
        <td><?=$r['id']?></td>
        <td><?=htmlspecialchars($r['title'])?></td>
        <td><?=htmlspecialchars($r['due_date'])?></td>
        <td><?php if ($r['file_path']): ?><a href="<?=htmlspecialchars($r['file_path'])?>" target="_blank">Download</a><?php else: ?>-<?php endif;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>