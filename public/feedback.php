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
    $_SESSION['flash'] = 'Feedback saved.';
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
  <h1>Feedback</h1>
  <form method="post">
    <label>Student (optional)</label>
    <select name="student_id">
      <option value="">-- none --</option>
      <?php foreach($pdo->query("SELECT id,name FROM students ORDER BY name")->fetchAll() as $st): ?>
        <option value="<?=$st['id']?>"><?=htmlspecialchars($st['name'])?></option>
      <?php endforeach;?>
    </select>
    <label>Message</label><textarea name="message" required></textarea>
    <button class="btn" type="submit">Send</button>
  </form>

  <h2>All Feedback</h2>
  <?php foreach ($rows as $r): ?>
    <div class="feedback-card">
      <div class="meta"><?=htmlspecialchars($r['submitted_by'])?> • <?=$r['created_at']?> • <?=htmlspecialchars($r['student_name'] ?? '')?></div>
      <div class="message"><?=nl2br(htmlspecialchars($r['message']))?></div>
    </div>
  <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>