<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Jagriti — Student Support</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="site-wrap">
<?php
if (!empty($_SESSION['flash'])) {
    echo '<div class="flash">'.htmlspecialchars($_SESSION['flash']).'</div>';
    unset($_SESSION['flash']);
}
?>