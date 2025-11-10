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

<div class="hero-section" style="background: linear-gradient(135deg, #f97316 0%, #fb923c 100%); padding: 3rem 0; margin-bottom: 2rem; border-radius: 12px;">
  <div class="container" style="text-align: center; color: white;">
    <img src="assets/images/jagriti-logo.jpeg" alt="Jagriti Logo" style="width: 120px; height: 120px; border-radius: 50%; margin-bottom: 1rem; border: 4px solid white; object-fit: cover;">
    <h1 style="font-size: 2.5rem; margin: 0;">Welcome, <?= htmlspecialchars($student['name']) ?>! 🌅</h1>
    <p style="font-size: 1.2rem; margin: 0.5rem 0 0 0; opacity: 0.95;">Your Learning Journey with Jagriti</p>
  </div>
</div>

<div class="container">
  <?php if (isset($_SESSION['flash'])): ?>
    <div class="flash"><?= htmlspecialchars($_SESSION['flash']) ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <div class="card-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="background: linear-gradient(135deg, #f97316 0%, #fb923c 100%); color: white; text-align: center; padding: 2rem;">
      <div style="font-size: 3rem; margin-bottom: 0.5rem;">👤</div>
      <h3 style="margin: 0; font-size: 1.1rem; opacity: 0.9;">Student ID</h3>
      <p style="font-size: 1.8rem; font-weight: bold; margin: 0.5rem 0 0 0;"><?= htmlspecialchars($student['roll']) ?></p>
    </div>
    
    <div class="card" style="background: linear-gradient(135deg, #fb923c 0%, #fdba74 100%); color: white; text-align: center; padding: 2rem;">
      <div style="font-size: 3rem; margin-bottom: 0.5rem;">📚</div>
      <h3 style="margin: 0; font-size: 1.1rem; opacity: 0.9;">Class</h3>
      <p style="font-size: 1.8rem; font-weight: bold; margin: 0.5rem 0 0 0;"><?= htmlspecialchars($student['class']) ?></p>
    </div>
    
    <div class="card" style="background: linear-gradient(135deg, #fdba74 0%, #fed7aa 100%); color: #78350f; text-align: center; padding: 2rem;">
      <div style="font-size: 3rem; margin-bottom: 0.5rem;">📝</div>
      <h3 style="margin: 0; font-size: 1.1rem; opacity: 0.9;">Assignments</h3>
      <p style="font-size: 1.8rem; font-weight: bold; margin: 0.5rem 0 0 0;"><?= count($assignments) ?></p>
    </div>
  </div>

  <!-- My Profile Section -->
  <div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; align-items: center; margin-bottom: 1.5rem; border-bottom: 3px solid #f97316; padding-bottom: 0.5rem;">
      <span style="font-size: 1.5rem; margin-right: 0.5rem;">📋</span>
      <h2 style="margin: 0; color: #f97316;">My Profile</h2>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
      <div>
        <strong style="color: #78350f;">Full Name:</strong>
        <p style="margin: 0.25rem 0 0 0;"><?= htmlspecialchars($student['name']) ?></p>
      </div>
      <div>
        <strong style="color: #78350f;">Roll Number:</strong>
        <p style="margin: 0.25rem 0 0 0;"><?= htmlspecialchars($student['roll']) ?></p>
      </div>
      <div>
        <strong style="color: #78350f;">Age:</strong>
        <p style="margin: 0.25rem 0 0 0;"><?= htmlspecialchars($student['age']) ?> years</p>
      </div>
      <div>
        <strong style="color: #78350f;">Class:</strong>
        <p style="margin: 0.25rem 0 0 0;"><?= htmlspecialchars($student['class']) ?></p>
      </div>
      <div>
        <strong style="color: #78350f;">Parent Contact:</strong>
        <p style="margin: 0.25rem 0 0 0;"><?= htmlspecialchars($student['parent_contact']) ?></p>
      </div>
      <div>
        <strong style="color: #78350f;">Address:</strong>
        <p style="margin: 0.25rem 0 0 0;"><?= htmlspecialchars($student['address'] ?? 'Not provided') ?></p>
      </div>
    </div>
  </div>

  <!-- My Assignments Section -->
  <div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; align-items: center; margin-bottom: 1.5rem; border-bottom: 3px solid #f97316; padding-bottom: 0.5rem;">
      <span style="font-size: 1.5rem; margin-right: 0.5rem;">📝</span>
      <h2 style="margin: 0; color: #f97316;">My Assignments</h2>
    </div>
    
    <?php if (empty($assignments)): ?>
      <p style="color: #78350f; text-align: center; padding: 2rem;">No assignments yet. Check back later! 📚</p>
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
  <div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; align-items: center; margin-bottom: 1.5rem; border-bottom: 3px solid #f97316; padding-bottom: 0.5rem;">
      <span style="font-size: 1.5rem; margin-right: 0.5rem;">📚</span>
      <h2 style="margin: 0; color: #f97316;">Library Books</h2>
    </div>
    
    <?php if (empty($books)): ?>
      <p style="color: #78350f; text-align: center; padding: 2rem;">No books available in the library yet. 📖</p>
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
    <div style="display: flex; align-items: center; margin-bottom: 1.5rem; border-bottom: 3px solid #f97316; padding-bottom: 0.5rem;">
      <span style="font-size: 1.5rem; margin-right: 0.5rem;">💬</span>
      <h2 style="margin: 0; color: #f97316;">My Feedback</h2>
    </div>
    
    <?php if (empty($feedback)): ?>
      <p style="color: #78350f; text-align: center; padding: 2rem;">No feedback yet. Keep up the great work! 🌟</p>
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
