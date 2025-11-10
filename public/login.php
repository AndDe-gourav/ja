<?php
require __DIR__ . '/../config/db.php';
session_start();
if (!empty($_SESSION['user'])) {
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
    
    <div style="margin-top: 24px; padding-top: 24px; border-top: 2px solid var(--border); text-align: center;">
      <p style="color: var(--muted); font-size: 14px; margin: 0;">
        <strong>Default Admin Credentials:</strong><br>
        Email: <code style="color: var(--accent);">admin@jagriti.local</code><br>
        Password: <code style="color: var(--accent);">admin123</code>
      </p>
      <p style="color: var(--muted); font-size: 12px; margin-top: 8px;">
        Please change these credentials after your first login
      </p>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
