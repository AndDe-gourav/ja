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
            $err = "Invalid email or password.";
        }
    } catch (Exception $e) {
        $err = "Login error: " . $e->getMessage();
    }
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container auth-form">
  <div class="card auth-card">
    <h2>Sign in to Jagriti</h2>
    <?php if ($err) echo "<div class='error'>".htmlspecialchars($err)."</div>"; ?>
    <form method="post" action="login.php">
      <label>Email</label>
      <input type="email" name="email" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <div class="row">
        <button class="btn" type="submit">Sign in</button>
        <a class="btn ghost" href="index.php">Back</a>
      </div>
    </form>
    <p class="muted">Default admin after setup: <strong>admin@jagriti.local / admin123</strong></p>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>