<?php
require_once __DIR__ . '/auth.php';
$logged_in = is_logged_in();
$user = current_user();
?>
<header class="site-header">
  <div class="left">
    <a class="logo" href="index.php">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <rect x="2" y="2" width="20" height="20" rx="4" fill="#f97316"/>
        <path d="M7 12h10M7 8h10M7 16h6" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/>
      </svg>
      <span>Jagriti</span>
    </a>
  </div>

  <nav class="main-nav">
    <a href="index.php">Home</a>
    
    <?php if ($logged_in && can_manage_students()): ?>
      <a href="students.php">Students</a>
    <?php endif; ?>
    
    <?php if ($logged_in && can_manage_donations()): ?>
      <a href="donations.php">Donations</a>
    <?php endif; ?>
    
    <?php if ($logged_in && can_manage_volunteers()): ?>
      <a href="volunteers.php">Volunteers</a>
    <?php endif; ?>
    
    <?php if ($logged_in): ?>
      <a href="activities.php">Activities</a>
    <?php endif; ?>
    
    <?php if ($logged_in && can_manage_feedback()): ?>
      <a href="feedback.php">Feedback</a>
    <?php endif; ?>
    
    <?php if ($logged_in && can_manage_assignments()): ?>
      <a href="assignments.php">Assignments</a>
    <?php endif; ?>
    
    <?php if ($logged_in && can_manage_books()): ?>
      <a href="books.php">Books</a>
    <?php endif; ?>
    
    <?php if ($logged_in && is_admin()): ?>
      <a href="users.php">Users</a>
    <?php endif; ?>
  </nav>

  <div class="right">
    <?php if ($logged_in && can_manage_students()): ?>
    <form class="search" action="students.php" method="get" role="search">
      <input name="q" placeholder="Search students..." aria-label="Search students">
      <button type="submit" aria-label="Search">🔍</button>
    </form>
    <?php endif; ?>
    
    <div class="auth">
      <?php if ($logged_in): ?>
        <span class="hi" title="<?=get_role_name($user['role'])?>">
          <?=htmlspecialchars($user['name'])?> (<?=get_role_name($user['role'])?>)
        </span>
        <a class="btn" href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn ghost" href="login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<hr>
