<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';

require_login();

// Only students can access this page
if (!is_student()) {
    $_SESSION['flash'] = 'This page is for students only.';
    header('Location: index.php');
    exit;
}

$student = get_student_record();
if (!$student) {
    $_SESSION['flash'] = 'Student record not found. Please contact administration.';
    header('Location: logout.php');
    exit;
}

// Get student's assignments
$stmt = $pdo->prepare("SELECT * FROM assignments ORDER BY due_date ASC");
$stmt->execute();
$assignments = $stmt->fetchAll();

// Get available books
$stmt = $pdo->prepare("SELECT * FROM books ORDER BY title ASC LIMIT 10");
$stmt->execute();
$books = $stmt->fetchAll();

// Get student's feedback
$stmt = $pdo->prepare("SELECT * FROM feedback WHERE student_id = ? ORDER BY created_at DESC");
$stmt->execute([$student['id']]);
$feedback = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="hero-banner">
  <img src="assets/images/jagriti-logo.jpeg" alt="Jagriti Logo">
  <h1>Welcome, <?= htmlspecialchars($student['name']) ?>!</h1>
  <p>Your Learning Journey with Jagriti</p>
</div>

<div class="container">
  <?php if (isset($_SESSION['flash'])): ?>
    <div class="flash"><?= htmlspecialchars($_SESSION['flash']) ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <div class="card-grid">
    <div class="stat">
      <div style="font-size: 48px; margin-bottom: 12px;">👤</div>
      <div class="label">Student ID</div>
      <div class="num" style="font-size: 24px; margin-top: 8px;"><?= htmlspecialchars($student['roll']) ?></div>
    </div>
    
    <div class="stat">
      <div style="font-size: 48px; margin-bottom: 12px;">📚</div>
      <div class="label">Class</div>
      <div class="num" style="font-size: 24px; margin-top: 8px;"><?= htmlspecialchars($student['class']) ?></div>
    </div>
    
    <div class="stat">
      <div style="font-size: 48px; margin-bottom: 12px;">📝</div>
      <div class="label">Assignments</div>
      <div class="num" style="font-size: 24px; margin-top: 8px;"><?= count($assignments) ?></div>
    </div>
  </div>

  <!-- My Profile Section -->
  <div class="card">
    <div class="section-header">
      <span>📋</span>
      <h2>My Profile</h2>
    </div>
    
    <div class="info-grid">
      <div class="info-item">
        <strong>Full Name</strong>
        <p><?= htmlspecialchars($student['name']) ?></p>
      </div>
      <div class="info-item">
        <strong>Roll Number</strong>
        <p><?= htmlspecialchars($student['roll']) ?></p>
      </div>
      <div class="info-item">
        <strong>Age</strong>
        <p><?= htmlspecialchars($student['age']) ?> years</p>
      </div>
      <div class="info-item">
        <strong>Class</strong>
        <p><?= htmlspecialchars($student['class']) ?></p>
      </div>
      <div class="info-item">
        <strong>Parent Contact</strong>
        <p><?= htmlspecialchars($student['parent_contact']) ?></p>
      </div>
      <div class="info-item">
        <strong>Address</strong>
        <p><?= htmlspecialchars($student['address'] ?? 'Not provided') ?></p>
      </div>
    </div>
  </div>

  <!-- My Assignments Section -->
  <div class="card">
    <div class="section-header">
      <span>📝</span>
      <h2>My Assignments</h2>
    </div>
    
    <?php if (empty($assignments)): ?>
      <p class="empty-state">No assignments yet. Check back later! 📚</p>
    <?php else: ?>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Description</th>
              <th>Due Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($assignments as $assignment): 
                $dueDate = new DateTime($assignment['due_date']);
                $today = new DateTime();
                $isPastDue = $dueDate < $today;
            ?>
              <tr>
                <td><strong><?= htmlspecialchars($assignment['title']) ?></strong></td>
                <td><?= htmlspecialchars($assignment['description']) ?></td>
                <td><?= htmlspecialchars(date('F j, Y', strtotime($assignment['due_date']))) ?></td>
                <td>
                  <?php if ($isPastDue): ?>
                    <span style="color: #dc2626; font-weight: bold;">⏰ Past Due</span>
                  <?php else: ?>
                    <span style="color: #16a34a; font-weight: bold;">✓ Active</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <!-- Available Books Section -->
  <div class="card">
    <div class="section-header">
      <span>📚</span>
      <h2>Library Books</h2>
    </div>
    
    <?php if (empty($books)): ?>
      <p class="empty-state">No books available in the library yet. 📖</p>
    <?php else: ?>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Author</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($books as $book): ?>
              <tr>
                <td><strong><?= htmlspecialchars($book['title']) ?></strong></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['notes'] ?? 'Available for reading') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <!-- My Feedback Section -->
  <div class="card">
    <div class="section-header">
      <span>💬</span>
      <h2>My Feedback</h2>
    </div>
    
    <?php if (empty($feedback)): ?>
      <p class="empty-state">No feedback yet. Keep up the great work! 🌟</p>
    <?php else: ?>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Message</th>
              <th>Submitted By</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($feedback as $fb): ?>
              <tr>
                <td><?= htmlspecialchars($fb['message']) ?></td>
                <td><?= htmlspecialchars($fb['submitted_by']) ?></td>
                <td><?= htmlspecialchars(date('M j, Y', strtotime($fb['created_at']))) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
