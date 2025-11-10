<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';
require_login();

if (!can_manage_assignments()) {
    $_SESSION['flash'] = 'Access denied. You do not have permission to manage assignments.';
    header('Location: index.php');
    exit;
}

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
    $_SESSION['flash'] = 'Assignment created successfully!';
    header('Location: assignments.php'); exit;
}

$rows = [];
try {
    $rows = $pdo->query("SELECT * FROM assignments ORDER BY due_date ASC, created_at DESC")->fetchAll();
} catch (Exception $e) {}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container">
  <h1>Assignment Management</h1>
  <p style="color: var(--muted); margin-bottom: 20px;">Create, distribute, and track assignments efficiently. Monitor due dates and attach necessary files for students.</p>
  
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="success"><?= $_SESSION['flash'] ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <div class="panel">
    <h2>Create New Assignment</h2>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add">
      
      <label><strong>Assignment Title *</strong></label>
      <input name="title" required placeholder="e.g., Math Homework Chapter 5">
      
      <label><strong>Description / Instructions</strong></label>
      <textarea name="description" rows="4" placeholder="Provide detailed instructions, requirements, and expectations for this assignment..."></textarea>
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
          <label><strong>Due Date</strong></label>
          <input type="date" name="due_date">
        </div>
        <div>
          <label><strong>Attach File (Optional)</strong></label>
          <input type="file" name="file">
        </div>
      </div>
      
      <button class="btn" type="submit">Create Assignment</button>
    </form>
  </div>

  <div class="panel">
    <h2>All Assignments (<?php echo count($rows); ?> total)</h2>
    <?php if (empty($rows)): ?>
      <p style="color: var(--muted); padding: 20px; text-align: center;">No assignments created yet. Use the form above to create your first assignment.</p>
    <?php else: ?>
    <table class="striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Due Date</th>
          <th>Attachment</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rows as $r): ?>
        <tr>
          <td><?=$r['id']?></td>
          <td><strong><?=htmlspecialchars($r['title'])?></strong></td>
          <td><?=htmlspecialchars(substr($r['description'], 0, 100)) . (strlen($r['description']) > 100 ? '...' : '')?></td>
          <td><?=htmlspecialchars($r['due_date'] ?: '-')?></td>
          <td>
            <?php if ($r['file_path']): ?>
              <a href="<?=htmlspecialchars($r['file_path'])?>" target="_blank" class="btn small">Download</a>
            <?php else: ?>
              -
            <?php endif;?>
          </td>
          <td><?=$r['created_at']?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
