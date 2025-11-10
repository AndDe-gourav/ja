<?php
// setup.php - run once from the browser to create the tables and default admin
require __DIR__ . '/config/db.php';

if ($pdo === null) {
    die('Database connection failed. Please check config/db.php');
}

$queries = [];

// users - SQLite compatible
$queries[] = "CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(10) NOT NULL DEFAULT 'staff' CHECK(role IN ('admin','staff')),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";

// students
$queries[] = "CREATE TABLE IF NOT EXISTS students (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(150) NOT NULL,
  roll VARCHAR(50) DEFAULT NULL,
  age INTEGER DEFAULT NULL,
  class VARCHAR(50) DEFAULT NULL,
  parent_contact VARCHAR(100) DEFAULT NULL,
  notes TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";

// donations
$queries[] = "CREATE TABLE IF NOT EXISTS donations (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  donor_name VARCHAR(150),
  amount DECIMAL(10,2) DEFAULT NULL,
  item VARCHAR(255) DEFAULT NULL,
  note TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";

// volunteers
$queries[] = "CREATE TABLE IF NOT EXISTS volunteers (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(150),
  email VARCHAR(150),
  phone VARCHAR(50),
  skills TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";

// feedback
$queries[] = "CREATE TABLE IF NOT EXISTS feedback (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  student_id INTEGER NULL,
  message TEXT,
  submitted_by VARCHAR(150),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL
);";

// assignments
$queries[] = "CREATE TABLE IF NOT EXISTS assignments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title VARCHAR(255),
  description TEXT,
  due_date DATE,
  file_path VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";

// books
$queries[] = "CREATE TABLE IF NOT EXISTS books (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title VARCHAR(255),
  author VARCHAR(255),
  notes TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";

foreach ($queries as $q) {
    $pdo->exec($q);
}

// create default admin if not exists
$adminEmail = 'admin@jagriti.local';
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$adminEmail]);
if (!$stmt->fetch()) {
    $adminPassword = 'admin123';
    $hash = password_hash($adminPassword, PASSWORD_DEFAULT);
    $ins = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?, 'admin')");
    $ins->execute(['Administrator', $adminEmail, $hash]);
    echo "<p>Admin created: email={$adminEmail} password={$adminPassword}</p>\n";
} else {
    echo "<p>Admin already exists.</p>\n";
}

echo "<p>Setup finished. Database initialized successfully with SQLite.</p>\n";
echo '<p><a href="public/login.php">Go to login</a></p>';
?>
