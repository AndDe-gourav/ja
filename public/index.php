<?php
require __DIR__ . '/../config/db.php';
session_start();
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';

$counts = ['students'=>'—','donations'=>'—','volunteers'=>'—','feedback'=>'—','assignments'=>'—'];
if ($pdo) {
    try {
        $tables = ['students','donations','volunteers','feedback','assignments'];
        foreach ($tables as $t) {
            $stmt = $pdo->query("SELECT COUNT(*) as c FROM {$t}");
            $counts[$t] = intval($stmt->fetchColumn());
        }
    } catch (Exception $e) {}
}
?>
<main class="container hero">
  <div class="hero-left">
    <h1>Student support made simple.</h1>
    <p class="lead">Manage students, donations, volunteers, assignments and feedback in one lightweight app for small organisations and college projects.</p>
    <div class="cta-row">
      <a class="btn" href="students.php">Manage Students</a>
      <a class="btn ghost" href="donations.php">Record Donations</a>
    </div>

    <div class="stats">
      <div class="stat">
        <div class="num"><?php echo $counts['students']; ?></div>
        <div class="label">Students</div>
      </div>
      <div class="stat">
        <div class="num"><?php echo $counts['donations']; ?></div>
        <div class="label">Donations</div>
      </div>
      <div class="stat">
        <div class="num"><?php echo $counts['volunteers']; ?></div>
        <div class="label">Volunteers</div>
      </div>
      <div class="stat">
        <div class="num"><?php echo $counts['assignments']; ?></div>
        <div class="label">Assignments</div>
      </div>
    </div>
  </div>

  <div class="hero-right">
    <svg viewBox="0 0 600 440" aria-hidden="true" class="illustration">
      <rect x="20" y="20" width="560" height="400" rx="16" fill="#fff" stroke="#e6e9ee" />
      <g transform="translate(60,60)">
        <rect width="200" height="120" rx="12" fill="#111827" opacity="0.95"></rect>
        <rect x="220" width="200" height="40" rx="8" fill="#f3f4f6"></rect>
        <rect x="220" y="60" width="200" height="20" rx="6" fill="#f3f4f6"></rect>
        <circle cx="40" cy="170" r="30" fill="#fde68a"></circle>
      </g>
    </svg>
  </div>
</main>

<section class="container features">
  <h2>What you can do</h2>
  <div class="grid-3">
    <div class="feature-card">
      <div class="icon">👩‍🎓</div>
      <h3>Student Management</h3>
      <p>Add, edit and track students with contact and class details.</p>
    </div>
    <div class="feature-card">
      <div class="icon">🤝</div>
      <h3>Donations</h3>
      <p>Record cash and item donations, keep a running log and receipts.</p>
    </div>
    <div class="feature-card">
      <div class="icon">🧑‍🏫</div>
      <h3>Volunteers</h3>
      <p>Manage volunteers, their skills and contact details.</p>
    </div>
  </div>
</section>

<section class="container about">
  <div class="about-card">
    <h2>About Jagriti</h2>
    <p>Jagriti is a compact student support system built with plain PHP, HTML and CSS — ideal for college projects and small NGO use. It focuses on practical features and a simple UI so administrators and volunteers can manage operations quickly.</p>
    <ul>
      <li>Lightweight — runs on XAMPP / LAMP</li>
      <li>Secure basics — prepared statements and hashed passwords</li>
      <li>Easily extensible — add modules for reports, messaging, and more</li>
    </ul>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>