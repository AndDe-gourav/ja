<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';

if (is_logged_in()) {
    header('Location: index.php'); exit;
}
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($pass, $user['password'])) {
            unset($user['password']);
            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit;
        } else {
            $err = "Invalid email or password. Please try again.";
        }
    } catch (Exception $e) {
        $err = "Login error. Please try again.";
    }
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container auth-form">
  <div class="card auth-card">
    <h2>Welcome to Jagriti</h2>
    <p style="color: var(--muted); text-align: center; margin-bottom: 24px;">Sign in to access the student support management system</p>
    
    <?php if ($err) echo "<div class='error'>".htmlspecialchars($err)."</div>"; ?>
    
    <form method="post" action="login.php">
      <label><strong>Email Address</strong></label>
      <input type="email" name="email" required placeholder="your.email@example.com" autocomplete="email">
      
      <label><strong>Password</strong></label>
      <input type="password" name="password" required placeholder="Enter your password" autocomplete="current-password">
      
      <div class="row" style="margin-top: 16px;">
        <button class="btn" type="submit">Sign In</button>
        <a class="btn ghost" href="index.php">Back to Home</a>
      </div>
    </form>
    
    <div style="margin-top: 24px; padding-top: 24px; border-top: 2px solid var(--border);">
      <p style="color: var(--muted); font-size: 14px; margin-bottom: 16px; font-weight: 600;">Default Test Accounts:</p>
      
      <div style="background: var(--accent-light); padding: 12px; border-radius: 8px; margin-bottom: 12px;">
        <p style="margin: 0; font-size: 13px;"><strong style="color: var(--accent);">Administrator Account</strong></p>
        <p style="margin: 4px 0 0 0; font-size: 13px;">
          Email: <code style="color: var(--accent);">admin@jagriti.local</code><br>
          Password: <code style="color: var(--accent);">admin123</code>
        </p>
        <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--muted);">
          Full access: Can manage users, volunteers, and all data
        </p>
      </div>
      
      <div style="background: #fff7ed; padding: 12px; border-radius: 8px; border: 1px solid var(--border);">
        <p style="margin: 0; font-size: 13px;"><strong style="color: var(--accent-2);">Volunteer Account</strong></p>
        <p style="margin: 4px 0 0 0; font-size: 13px;">
          Email: <code style="color: var(--accent-2);">volunteer@jagriti.local</code><br>
          Password: <code style="color: var(--accent-2);">volunteer123</code>
        </p>
        <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--muted);">
          Limited access: Can manage students, donations, assignments, books, feedback
        </p>
      </div>
      
      <p style="color: var(--muted); font-size: 12px; margin-top: 12px; text-align: center;">
        ⚠️ Please change these passwords after first login
      </p>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
