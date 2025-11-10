<?php
// includes/auth.php - Centralized authentication and authorization functions

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function is_logged_in() {
    return !empty($_SESSION['user']);
}

// Get current user
function current_user() {
    return $_SESSION['user'] ?? null;
}

// Get current user role
function current_role() {
    $user = current_user();
    return $user['role'] ?? null;
}

// Check if current user is admin
function is_admin() {
    return current_role() === 'admin';
}

// Check if current user is volunteer
function is_volunteer() {
    return current_role() === 'volunteer';
}

// Check if current user is student
function is_student() {
    return current_role() === 'student';
}

// Require user to be logged in
function require_login() {
    if (!is_logged_in()) {
        $_SESSION['flash'] = 'Please login to access this page.';
        header('Location: ' . get_login_url());
        exit;
    }
}

// Require user to be admin
function require_admin() {
    require_login();
    if (!is_admin()) {
        $_SESSION['flash'] = 'Access denied. Admin privileges required.';
        header('Location: ' . get_index_url());
        exit;
    }
}

// Check if user can manage volunteers (admin only)
function can_manage_volunteers() {
    return is_admin();
}

// Check if user can manage students (admin or volunteer)
function can_manage_students() {
    return is_admin() || is_volunteer();
}

// Check if user can manage donations (admin or volunteer)
function can_manage_donations() {
    return is_admin() || is_volunteer();
}

// Check if user can manage assignments (admin or volunteer)
function can_manage_assignments() {
    return is_admin() || is_volunteer();
}

// Check if user can manage books (admin or volunteer)
function can_manage_books() {
    return is_admin() || is_volunteer();
}

// Check if user can manage feedback (admin or volunteer)
function can_manage_feedback() {
    return is_admin() || is_volunteer();
}

// Get login URL
function get_login_url() {
    // Determine if we're in public or api directory
    if (strpos($_SERVER['SCRIPT_NAME'], '/api/') !== false) {
        return '../public/login.php';
    }
    return 'login.php';
}

// Get index URL
function get_index_url() {
    // Determine if we're in public or api directory
    if (strpos($_SERVER['SCRIPT_NAME'], '/api/') !== false) {
        return '../public/index.php';
    }
    return 'index.php';
}

// Require user to be admin or volunteer (staff access)
function require_staff() {
    require_login();
    if (!is_admin() && !is_volunteer()) {
        $_SESSION['flash'] = 'Access denied. Staff privileges required.';
        header('Location: ' . get_index_url());
        exit;
    }
}

// Get student record for current user
function get_student_record() {
    if (!is_student()) {
        return null;
    }
    
    global $pdo;
    $user = current_user();
    $stmt = $pdo->prepare("SELECT * FROM students WHERE user_id = ? LIMIT 1");
    $stmt->execute([$user['id']]);
    return $stmt->fetch();
}

// Display user-friendly role name
function get_role_name($role) {
    $roles = [
        'admin' => 'Administrator',
        'volunteer' => 'Volunteer',
        'student' => 'Student',
    ];
    return $roles[$role] ?? ucfirst($role);
}
?>
