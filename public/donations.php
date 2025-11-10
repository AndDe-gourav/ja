<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';
require_login();

if (!can_manage_donations()) {
    $_SESSION['flash'] = 'Access denied. You do not have permission to manage donations.';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $donor = $_POST['donor_name'] ?? '';
    $amount = $_POST['amount'] ?? null;
    $item = $_POST['item'] ?? null;
    $note = $_POST['note'] ?? null;
    $stmt = $pdo->prepare("INSERT INTO donations (donor_name, amount, item, note) VALUES (?,?,?,?)");
    $stmt->execute([$donor, $amount ?: null, $item ?: null, $note]);
    $_SESSION['flash']='Donation recorded successfully!';
    header('Location: donations.php');
    exit;
}

$rows = [];
$total_amount = 0;
try {
    $rows = $pdo->query("SELECT * FROM donations ORDER BY created_at DESC")->fetchAll();
    $stmt = $pdo->query("SELECT SUM(amount) as total FROM donations WHERE amount IS NOT NULL");
    $result = $stmt->fetch();
    $total_amount = $result['total'] ?? 0;
} catch (Exception $e) {}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container">
  <h1>Donation Tracking</h1>
  <p style="color: var(--muted); margin-bottom: 20px;">Record and manage all donations - both monetary contributions and material items. Keep transparent records for accountability.</p>
  
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="success"><?= $_SESSION['flash'] ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <div class="panel">
    <h2>Record New Donation</h2>
    <form method="post">
      <input type="hidden" name="action" value="add">
      
      <label><strong>Donor Name / Organization *</strong></label>
      <input name="donor_name" required placeholder="Name of the person or organization">
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
          <label><strong>Cash Amount</strong></label>
          <input name="amount" type="number" step="0.01" min="0" placeholder="Enter amount (optional)">
        </div>
        <div>
          <label><strong>Item Donated</strong></label>
          <input name="item" placeholder="e.g., Books, Uniforms, Furniture">
        </div>
      </div>
      
      <label><strong>Additional Notes</strong></label>
      <textarea name="note" rows="3" placeholder="Purpose of donation, special instructions, or acknowledgment details"></textarea>
      
      <button class="btn" type="submit">Record Donation</button>
    </form>
  </div>

  <div class="panel">
    <h2>Donation History</h2>
    <div style="background: var(--accent-light); padding: 16px; border-radius: 10px; margin-bottom: 16px;">
      <p style="margin: 0; font-weight: 600; color: var(--accent);">Total Cash Donations: $<?= number_format($total_amount, 2) ?></p>
      <p style="margin: 4px 0 0 0; font-size: 14px; color: var(--muted);"><?= count($rows) ?> total donations recorded</p>
    </div>
    
    <?php if (empty($rows)): ?>
      <p style="color: var(--muted); padding: 20px; text-align: center;">No donations recorded yet. Use the form above to record your first donation.</p>
    <?php else: ?>
    <table class="striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Donor</th>
          <th>Cash Amount</th>
          <th>Item</th>
          <th>Note</th>
          <th>Date Received</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($rows as $r): ?>
        <tr>
          <td><?=$r['id']?></td>
          <td><strong><?=htmlspecialchars($r['donor_name'])?></strong></td>
          <td><?=($r['amount'] !== null ? '$' . number_format($r['amount'],2) : '-')?></td>
          <td><?=htmlspecialchars($r['item'] ?: '-')?></td>
          <td><?=htmlspecialchars($r['note'] ?: '-')?></td>
          <td><?=$r['created_at']?></td>
        </tr>
      <?php endforeach;?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
