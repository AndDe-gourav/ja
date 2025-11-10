<?php
require __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO books (title, author, notes) VALUES (?,?,?)");
    $stmt->execute([$title, $author, $notes]);
    $_SESSION['flash'] = 'Book saved.';
    header('Location: books.php'); exit;
}

$rows = [];
try {
    $rows = $pdo->query("SELECT * FROM books ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container">
  <h1>Books</h1>
  <form method="post">
    <input type="hidden" name="action" value="add">
    <label>Title</label><input name="title" required>
    <label>Author</label><input name="author">
    <label>Notes</label><textarea name="notes"></textarea>
    <button class="btn" type="submit">Add Book</button>
  </form>

  <h2>All Books</h2>
  <table class="striped">
    <thead><tr><th>ID</th><th>Title</th><th>Author</th></tr></thead>
    <tbody>
      <?php foreach($rows as $r): ?>
      <tr>
        <td><?=$r['id']?></td>
        <td><?=htmlspecialchars($r['title'])?></td>
        <td><?=htmlspecialchars($r['author'])?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>