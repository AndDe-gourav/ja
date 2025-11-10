<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';

// Redirect students to their portal
if (is_logged_in() && is_student()) {
    header('Location: student_portal.php');
    exit;
}

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
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
      <img src="assets/images/jagriti-logo.jpeg" alt="Jagriti" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--accent);">
      <h1 style="margin: 0;">Empower Every Student's Journey</h1>
    </div>
    <p class="lead">Transform the way you manage student support with Jagriti - a complete platform for tracking students, donations, volunteers, assignments, and feedback. Built for educators, NGOs, and community organizations.</p>
    <div class="cta-row">
      <a class="btn" href="students.php">Manage Students</a>
      <a class="btn ghost" href="donations.php">Track Donations</a>
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
      <rect x="20" y="20" width="560" height="400" rx="16" fill="#fff" stroke="#e7e5e4" stroke-width="3" />
      <g transform="translate(60,60)">
        <rect width="200" height="120" rx="12" fill="#a85308" opacity="0.95"></rect>
        <rect x="220" width="200" height="40" rx="8" fill="#fef3c7"></rect>
        <rect x="220" y="60" width="200" height="20" rx="6" fill="#e7e5e4"></rect>
        <circle cx="40" cy="170" r="30" fill="#c2630f"></circle>
        <circle cx="120" cy="190" r="25" fill="#d97706"></circle>
        <rect x="180" y="160" width="240" height="60" rx="10" fill="#fafaf9" stroke="#a85308" stroke-width="2"></rect>
      </g>
    </svg>
  </div>
</main>

<section class="container features">
  <h2>Everything You Need in One Place</h2>
  <div class="grid-3">
    <div class="feature-card">
      <div class="icon">👩‍🎓</div>
      <h3>Student Management</h3>
      <p>Track student profiles, contact details, academic information, and progress reports all in one organized system.</p>
    </div>
    <div class="feature-card">
      <div class="icon">💰</div>
      <h3>Donation Tracking</h3>
      <p>Record and manage all donations - both monetary and material. Keep transparent records for donors and generate reports easily.</p>
    </div>
    <div class="feature-card">
      <div class="icon">🤝</div>
      <h3>Volunteer Network</h3>
      <p>Build and manage your volunteer community. Track skills, availability, and contributions to maximize impact.</p>
    </div>
    <div class="feature-card">
      <div class="icon">📚</div>
      <h3>Assignment System</h3>
      <p>Create, distribute, and track assignments efficiently. Monitor due dates and student submissions in real-time.</p>
    </div>
    <div class="feature-card">
      <div class="icon">💬</div>
      <h3>Feedback Collection</h3>
      <p>Gather valuable feedback from students, parents, and volunteers to continuously improve your programs.</p>
    </div>
    <div class="feature-card">
      <div class="icon">📖</div>
      <h3>Library Management</h3>
      <p>Maintain a catalog of books and educational resources. Track lending and returns with ease.</p>
    </div>
  </div>
</section>

<section class="container about">
  <div class="about-card">
    <h2>About Jagriti</h2>
    <p>Jagriti means "awakening" in Hindi, and that's exactly what we aim to do - awaken the potential in every student through better management and support systems.</p>
    <p>Built with simplicity and effectiveness in mind, Jagriti is perfect for small to medium-sized educational organizations, NGOs, and community programs. Our platform helps you focus on what matters most: empowering students to succeed.</p>
    <ul>
      <li><strong>Lightweight & Fast</strong> - No complex setup required, runs smoothly on any server</li>
      <li><strong>Secure by Design</strong> - Password encryption, session management, and prepared SQL statements</li>
      <li><strong>Easy to Customize</strong> - Built with simple PHP, HTML, and CSS for easy modifications</li>
      <li><strong>Data-Driven Insights</strong> - Track metrics and generate reports to measure your impact</li>
      <li><strong>Community Focused</strong> - Designed by educators for educators and social workers</li>
    </ul>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
