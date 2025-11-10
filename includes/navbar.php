<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$logged_in = !empty($_SESSION['user']);
?>
<header class="site-header">
  <div class="left">
    <a class="logo" href="index.php">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <rect x="2" y="2" width="20" height="20" rx="4" fill="#111827"/>
        <path d="M7 12h10M7 8h10M7 16h6" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/>
      </svg>
      <span>Jagriti</span>
    </a>
  </div>

  <nav class="main-nav">
    <a href="index.php">Home</a>
    <a href="students.php">Students</a>
    <a href="donations.php">Donations</a>
    <a href="volunteers.php">Volunteers</a>
    <a href="activities.php">Activities</a>
    <a href="feedback.php">Feedback</a>
    <a href="assignments.php">Assignments</a>
    <a href="books.php">Books</a>
  </nav>

  <div class="right">
    <form class="search" action="students.php" method="get" role="search">
      <input name="q" placeholder="Search students..." aria-label="Search students">
      <button type="submit" aria-label="Search">🔍</button>
    </form>
    <div class="auth">
      <?php if ($logged_in): ?>
        <span class="hi">Hi, <?=htmlspecialchars($_SESSION['user']['name'])?></span>
        <a class="btn" href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn ghost" href="login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<hr>