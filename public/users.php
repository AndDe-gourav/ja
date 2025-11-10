<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';
require_admin(); // Only admins can manage users

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'volunteer';
    
    if ($name && $email && $password) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?,?,?,?)");
            $stmt->execute([$name, $email, $hash, $role]);
            $_SESSION['flash'] = 'User created successfully!';
        } catch (Exception $e) {
            $_SESSION['flash'] = 'Error: ' . $e->getMessage();
        }
    }
    header('Location: users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = $_POST['id'] ?? 0;
    // Prevent admin from deleting themselves
    if ($id != current_user()['id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['flash'] = 'User deleted successfully!';
    } else {
        $_SESSION['flash'] = 'Error: Cannot delete your own account!';
    }
    header('Location: users.php');
    exit;
}

$users = [];
try {
    $users = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY role, name")->fetchAll();
} catch (Exception $e) {}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container">
  <h1>User Management</h1>
  <p style="color: var(--muted); margin-bottom: 20px;">Manage system users and their access levels. Create new volunteer accounts and control who can access different features.</p>
  <p style="background: var(--accent-light); padding: 12px; border-radius: 8px; color: var(--accent); font-weight: 600;">
    <strong>Admin Only:</strong> Only administrators can manage system users.
  </p>
  
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="success"><?= $_SESSION['flash'] ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <div class="panel">
    <h2>Create New User</h2>
    <form method="post">
      <input type="hidden" name="action" value="create">
      
      <label><strong>Full Name *</strong></label>
      <input name="name" required placeholder="Enter user's full name">
      
      <label><strong>Email Address *</strong></label>
      <input name="email" type="email" required placeholder="user@example.com">
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
          <label><strong>Password *</strong></label>
          <input name="password" type="password" required placeholder="Set initial password">
        </div>
        <div>
          <label><strong>Role *</strong></label>
          <select name="role" required>
            <option value="volunteer">Volunteer (Can manage students, donations, etc.)</option>
            <option value="admin">Administrator (Full access)</option>
          </select>
        </div>
      </div>
      
      <p style="color: var(--muted); font-size: 14px; margin-top: 8px;">
        <strong>Volunteer role:</strong> Can manage students, donations, assignments, books, and feedback<br>
        <strong>Admin role:</strong> Full access including user management and volunteer management
      </p>
      
      <button class="btn" type="submit">Create User</button>
    </form>
  </div>

  <div class="panel">
    <h2>All Users (<?php echo count($users); ?> total)</h2>
    <table class="striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($users as $u): ?>
        <tr>
          <td><?=$u['id']?></td>
          <td><strong><?=htmlspecialchars($u['name'])?></strong></td>
          <td><?=htmlspecialchars($u['email'])?></td>
          <td>
            <span style="background: <?=$u['role'] === 'admin' ? 'var(--accent)' : 'var(--accent-2)'?>; color: #fff; padding: 4px 8px; border-radius: 6px; font-weight: 600; font-size: 12px;">
              <?=get_role_name($u['role'])?>
            </span>
          </td>
          <td><?=$u['created_at']?></td>
          <td>
            <?php if ($u['id'] != current_user()['id']): ?>
              <form style="display:inline" method="post">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?=$u['id']?>">
                <button class="btn small" type="submit" onclick="return confirm('Delete this user? This action cannot be undone.')">Delete</button>
              </form>
            <?php else: ?>
              <span style="color: var(--muted); font-style: italic;">Current User</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
