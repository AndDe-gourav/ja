<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';
require_login();

if (!can_manage_books()) {
    $_SESSION['flash'] = 'Access denied. You do not have permission to manage books.';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO books (title, author, notes) VALUES (?,?,?)");
    $stmt->execute([$title, $author, $notes]);
    $_SESSION['flash'] = 'Book added to library successfully!';
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
  <h1>Library Management</h1>
  <p style="color: var(--muted); margin-bottom: 20px;">Maintain a comprehensive catalog of books and educational resources. Track your library collection and make it accessible to students.</p>
  
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="success"><?= $_SESSION['flash'] ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <div class="panel">
    <h2>Add New Book</h2>
    <form method="post">
      <input type="hidden" name="action" value="add">
      
      <label><strong>Book Title *</strong></label>
      <input name="title" required placeholder="Enter the complete book title">
      
      <label><strong>Author Name</strong></label>
      <input name="author" placeholder="Author or publisher name">
      
      <label><strong>Additional Notes</strong></label>
      <textarea name="notes" rows="3" placeholder="ISBN, category, condition, location in library, or any other relevant information..."></textarea>
      
      <button class="btn" type="submit">Add to Library</button>
    </form>
  </div>

  <div class="panel">
    <h2>Library Catalog (<?php echo count($rows); ?> books)</h2>
    <?php if (empty($rows)): ?>
      <p style="color: var(--muted); padding: 20px; text-align: center;">No books in the library yet. Use the form above to add your first book.</p>
    <?php else: ?>
    <table class="striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Author</th>
          <th>Notes</th>
          <th>Added On</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rows as $r): ?>
        <tr>
          <td><?=$r['id']?></td>
          <td><strong><?=htmlspecialchars($r['title'])?></strong></td>
          <td><?=htmlspecialchars($r['author'] ?: '-')?></td>
          <td><?=htmlspecialchars($r['notes'] ?: '-')?></td>
          <td><?=$r['created_at']?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
