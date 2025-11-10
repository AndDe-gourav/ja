<?php
// migrate_roles.php - Run once to migrate existing database to new role system
require __DIR__ . '/config/db.php';

if ($pdo === null) {
    die('Database connection failed.');
}

echo "<h2>Role Migration Script</h2>";

// Check if users table exists
try {
    $pdo->query("SELECT role FROM users LIMIT 1");
} catch (Exception $e) {
    die("<p>Error: Users table not found or role column missing.</p>");
}

// Update 'staff' role to 'volunteer' (if any exist)
try {
    $stmt = $pdo->prepare("UPDATE users SET role = 'volunteer' WHERE role = 'staff'");
    $stmt->execute();
    $updated = $stmt->rowCount();
    echo "<p>✓ Updated {$updated} staff users to volunteer role.</p>";
} catch (Exception $e) {
    echo "<p>Error updating roles: " . $e->getMessage() . "</p>";
}

// Show current users
echo "<h3>Current Users:</h3>";
try {
    $users = $pdo->query("SELECT id, name, email, role FROM users ORDER BY role, name")->fetchAll();
    if (empty($users)) {
        echo "<p>No users found.</p>";
    } else {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>" . htmlspecialchars($user['name']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($user['role']) . "</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<p>Error fetching users: " . $e->getMessage() . "</p>";
}

echo "<p><strong>Migration complete!</strong></p>";
echo "<p><a href='public/login.php'>Go to Login</a> | <a href='public/index.php'>Go to Home</a></p>";
?>
