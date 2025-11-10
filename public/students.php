<?php
require __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

$q = $_GET['q'] ?? '';
$params = [];
$sql = "SELECT * FROM students";
if ($q) {
    $sql .= " WHERE name LIKE ? OR roll LIKE ?";
    $params[] = "%$q%";
    $params[] = "%$q%";
}
$sql .= " ORDER BY created_at DESC LIMIT 200";
$students = [];
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $students = $stmt->fetchAll();
} catch (Exception $e) {}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container">
  <h1>Students</h1>
  <div class="panel">
    <h3>Add Student</h3>
    <form method="post" action="../api/student_api.php">
      <input type="hidden" name="action" value="create">
      <label>Name</label><input name="name" required>
      <label>Roll</label><input name="roll">
      <label>Age</label><input name="age" type="number" min="1">
      <label>Class</label><input name="class">
      <label>Parent Contact</label><input name="parent_contact">
      <label>Notes</label><textarea name="notes"></textarea>
      <div class="row"><button class="btn" type="submit">Add Student</button></div>
    </form>
  </div>

  <div class="panel">
    <h3>All Students</h3>
    <table class="striped">
      <thead><tr><th>ID</th><th>Name</th><th>Roll</th><th>Age</th><th>Class</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach($students as $s): ?>
        <tr>
          <td><?=$s['id']?></td>
          <td><?=htmlspecialchars($s['name'])?></td>
          <td><?=htmlspecialchars($s['roll'])?></td>
          <td><?=intval($s['age'])?></td>
          <td><?=htmlspecialchars($s['class'])?></td>
          <td>
            <form style="display:inline" method="post" action="../api/student_api.php">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?=$s['id']?>">
              <button class="btn small" type="submit" onclick="return confirm('Delete?')">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>

  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>