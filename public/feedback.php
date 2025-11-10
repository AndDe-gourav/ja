<?php
require __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $student_id = $_POST['student_id'] ?: null;
    $message = $_POST['message'];
    $submitted_by = $_SESSION['user']['name'] ?? 'unknown';
    $stmt = $pdo->prepare("INSERT INTO feedback (student_id, message, submitted_by) VALUES (?,?,?)");
    $stmt->execute([$student_id ?: null, $message, $submitted_by]);
    $_SESSION['flash'] = 'Feedback submitted successfully!';
    header('Location: feedback.php'); exit;
}
$rows = [];
try {
    $rows = $pdo->query("SELECT f.*, s.name as student_name FROM feedback f LEFT JOIN students s ON f.student_id = s.id ORDER BY f.created_at DESC")->fetchAll();
} catch (Exception $e) {}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container">
  <h1>Feedback & Suggestions</h1>
  <p style="color: var(--muted); margin-bottom: 20px;">Collect valuable feedback from students, parents, and team members to continuously improve your programs and services.</p>
  
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="success"><?= $_SESSION['flash'] ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <div class="panel">
    <h2>Submit Feedback</h2>
    <form method="post">
      <label><strong>Related Student (Optional)</strong></label>
      <select name="student_id">
        <option value="">-- General Feedback / Not Student-Specific --</option>
        <?php foreach($pdo->query("SELECT id,name FROM students ORDER BY name")->fetchAll() as $st): ?>
          <option value="<?=$st['id']?>"><?=htmlspecialchars($st['name'])?></option>
        <?php endforeach;?>
      </select>
      
      <label><strong>Your Feedback / Message *</strong></label>
      <textarea name="message" required rows="5" placeholder="Share your thoughts, suggestions, concerns, or compliments..."></textarea>
      
      <button class="btn" type="submit">Submit Feedback</button>
    </form>
  </div>

  <div class="panel">
    <h2>All Feedback (<?php echo count($rows); ?> total)</h2>
    <?php if (empty($rows)): ?>
      <p style="color: var(--muted); padding: 20px; text-align: center;">No feedback submitted yet. Use the form above to submit the first feedback.</p>
    <?php else: ?>
      <?php foreach ($rows as $r): ?>
        <div style="background: var(--card); border-left: 4px solid var(--accent); padding: 16px; margin-bottom: 12px; border-radius: 8px; box-shadow: 0 2px 8px var(--shadow);">
          <div style="color: var(--muted); font-size: 14px; margin-bottom: 8px;">
            <strong style="color: var(--accent);"><?=htmlspecialchars($r['submitted_by'])?></strong>
            <?php if ($r['student_name']): ?>
              regarding <strong><?=htmlspecialchars($r['student_name'])?></strong>
            <?php endif; ?>
            • <?=$r['created_at']?>
          </div>
          <div style="color: var(--text); line-height: 1.6;"><?=nl2br(htmlspecialchars($r['message']))?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
