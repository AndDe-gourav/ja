<?php
require_once __DIR__ . '/auth.php';
$logged_in = is_logged_in();
$user = current_user();
?>
<header class="site-header">
  <div class="left">
    <a class="logo" href="index.php">
      <img src="assets/images/jagriti-logo.jpeg" alt="Jagriti" width="40" height="40" style="border-radius: 50%; object-fit: cover;">
      <span>Jagriti</span>
    </a>
  </div>

  <nav class="main-nav">
    <?php if ($logged_in && is_student()): ?>
      <!-- Student Navigation -->
      <a href="student_portal.php">My Dashboard</a>
    <?php else: ?>
      <!-- Admin and Volunteer Navigation -->
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
    <?php endif; ?>
  </nav>

  <div class="right">
    <?php if ($logged_in && !is_student() && can_manage_students()): ?>
    <form class="search" action="students.php" method="get" role="search">
      <input name="q" placeholder="Search students..." aria-label="Search students">
      <button type="submit" aria-label="Search">🔍</button>
    </form>
    <?php endif; ?>
    
    <div class="auth">
      <?php if ($logged_in): ?>
        <span class="hi" title="<?=get_role_name($user['role'])?>">
          <?=htmlspecialchars($user['name'])?> <span style="color: #f97316; font-weight: 600;">(<?=get_role_name($user['role'])?>)</span>
        </span>
        <a class="btn" href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn ghost" href="login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<hr>
