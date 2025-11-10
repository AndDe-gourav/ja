<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';
require_login(); // Require login first

// Check permissions (admin or volunteer can manage students)
if (!can_manage_students()) {
    $_SESSION['flash'] = 'Access denied. You do not have permission to manage students.';
    header('Location: index.php');
    exit;
}

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
  <h1>Student Management</h1>
  <p style="color: var(--muted); margin-bottom: 20px;">Track and manage student information, contact details, and academic records in one centralized system.</p>
  
  <div class="panel">
    <h2>Add New Student</h2>
    <form method="post" action="../api/student_api.php">
      <input type="hidden" name="action" value="create">
      
      <label><strong>Full Name *</strong></label>
      <input name="name" required placeholder="Enter student's full name">
      
      <label><strong>Roll Number / ID</strong></label>
      <input name="roll" placeholder="Student identification number">
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
          <label><strong>Age</strong></label>
          <input name="age" type="number" min="1" max="100" placeholder="Student age">
        </div>
        <div>
          <label><strong>Class / Grade</strong></label>
          <input name="class" placeholder="e.g., Grade 10, Year 2">
        </div>
      </div>
      
      <label><strong>Parent / Guardian Contact</strong></label>
      <input name="parent_contact" placeholder="Phone number or email address">
      
      <label><strong>Additional Notes</strong></label>
      <textarea name="notes" rows="3" placeholder="Any special information, medical conditions, or other important details"></textarea>
      
      <div class="row">
        <button class="btn" type="submit">Add Student</button>
      </div>
    </form>
  </div>

  <div class="panel">
    <h2>All Students (<?php echo count($students); ?> total)</h2>
    <?php if (empty($students)): ?>
      <p style="color: var(--muted); padding: 20px; text-align: center;">No students added yet. Use the form above to add your first student.</p>
    <?php else: ?>
    <table class="striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Roll Number</th>
          <th>Age</th>
          <th>Class</th>
          <th>Parent Contact</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($students as $s): ?>
        <tr>
          <td><?=$s['id']?></td>
          <td><strong><?=htmlspecialchars($s['name'])?></strong></td>
          <td><?=htmlspecialchars($s['roll'] ?: '-')?></td>
          <td><?=($s['age'] ? intval($s['age']) : '-')?></td>
          <td><?=htmlspecialchars($s['class'] ?: '-')?></td>
          <td><?=htmlspecialchars($s['parent_contact'] ?: '-')?></td>
          <td>
            <form style="display:inline" method="post" action="../api/student_api.php">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?=$s['id']?>">
              <button class="btn small" type="submit" onclick="return confirm('Are you sure you want to remove this student?')">Remove</button>
            </form>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
