<?php
require __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/navbar.php';
?>
<div class="container">
  <h1>Activities</h1>
  <p>Use this page to list upcoming events and activities. Extend by adding an `activities` table when needed.</p>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>