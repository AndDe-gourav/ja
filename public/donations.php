<?php
require __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $donor = $_POST['donor_name'] ?? '';
    $amount = $_POST['amount'] ?? null;
    $item = $_POST['item'] ?? null;
    $note = $_POST['note'] ?? null;
    $stmt = $pdo->prepare("INSERT INTO donations (donor_name, amount, item, note) VALUES (?,?,?,?)");
    $stmt->execute([$donor, $amount ?: null, $item ?: null, $note]);
    $_SESSION['flash']='Donation recorded';
    header('Location: donations.php');
    exit;
}

$rows = [];
try {
    $rows = $pdo->query("SELECT * FROM donations ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container">
  <h1>Donations</h1>
  <form method="post">
    <input type="hidden" name="action" value="add">
    <label>Donor name</label><input name="donor_name" required>
    <label>Amount (optional)</label><input name="amount" type="number" step="0.01">
    <label>Item (optional)</label><input name="item">
    <label>Note</label><textarea name="note"></textarea>
    <button class="btn" type="submit">Record</button>
  </form>

  <h2>Recent donations</h2>
  <table class="striped">
    <thead><tr><th>ID</th><th>Donor</th><th>Amount</th><th>Item</th><th>When</th></tr></thead>
    <tbody>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?=$r['id']?></td>
        <td><?=htmlspecialchars($r['donor_name'])?></td>
        <td><?=($r['amount'] !== null ? number_format($r['amount'],2) : '-')?></td>
        <td><?=htmlspecialchars($r['item'])?></td>
        <td><?=$r['created_at']?></td>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>