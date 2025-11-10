<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';
require_admin(); // Only admins can manage volunteers

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO volunteers (name, email, phone, skills) VALUES (?,?,?,?)");
    $stmt->execute([$name, $email, $phone, $skills]);
    $_SESSION['flash'] = 'Volunteer added successfully!';
    header('Location: volunteers.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = $_POST['id'] ?? 0;
    $stmt = $pdo->prepare("DELETE FROM volunteers WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['flash'] = 'Volunteer removed successfully!';
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
  <h1>Volunteer Network</h1>
  <p style="color: var(--muted); margin-bottom: 20px;">Build and manage your volunteer community. Track their skills, contact information, and availability to maximize your program's impact.</p>
  <p style="background: var(--accent-light); padding: 12px; border-radius: 8px; color: var(--accent); font-weight: 600;">
    <strong>Admin Only:</strong> Only administrators can manage volunteers.
  </p>
  
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="success"><?= $_SESSION['flash'] ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <div class="panel">
    <h2>Add New Volunteer</h2>
    <form method="post">
      <input type="hidden" name="action" value="add">
      
      <label><strong>Volunteer Name *</strong></label>
      <input name="name" required placeholder="Full name of the volunteer">
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
          <label><strong>Email Address</strong></label>
          <input name="email" type="email" placeholder="volunteer@example.com">
        </div>
        <div>
          <label><strong>Phone Number</strong></label>
          <input name="phone" type="tel" placeholder="+1 234 567 8900">
        </div>
      </div>
      
      <label><strong>Skills & Expertise</strong></label>
      <textarea name="skills" rows="3" placeholder="List their skills, qualifications, areas of interest, and availability (e.g., Teaching, Tutoring Math, Weekend availability)"></textarea>
      
      <button class="btn" type="submit">Add Volunteer</button>
    </form>
  </div>

  <div class="panel">
    <h2>All Volunteers (<?php echo count($rows); ?> total)</h2>
    <?php if (empty($rows)): ?>
      <p style="color: var(--muted); padding: 20px; text-align: center;">No volunteers registered yet. Use the form above to add your first volunteer.</p>
    <?php else: ?>
    <table class="striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Skills & Expertise</th>
          <th>Joined</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rows as $r): ?>
        <tr>
          <td><?=$r['id']?></td>
          <td><strong><?=htmlspecialchars($r['name'])?></strong></td>
          <td><?=htmlspecialchars($r['email'] ?: '-')?></td>
          <td><?=htmlspecialchars($r['phone'] ?: '-')?></td>
          <td><?=htmlspecialchars($r['skills'] ?: '-')?></td>
          <td><?=$r['created_at']?></td>
          <td>
            <form style="display:inline" method="post">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?=$r['id']?>">
              <button class="btn small" type="submit" onclick="return confirm('Remove this volunteer?')">Remove</button>
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
