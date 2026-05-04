<?php
// IndicLex Common Functions

session_start();

// Redirect to login if not logged in
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /INDICLEX_FINAL/admin/login.php');
        exit;
    }
}

// Sanitize user input
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Get user preference (Cookie > DB > Default)
function get_preference($key, $default = '') {
    global $pdo;
    
    // Check Cookie
    if (isset($_COOKIE['pref_' . $key])) {
        return $_COOKIE['pref_' . $key];
    }
    
    // Check Database
    $stmt = $pdo->prepare("SELECT pref_value FROM preferences WHERE pref_key = ?");
    $stmt->execute([$key]);
    $pref = $stmt->fetch();
    
    return $pref ? $pref['pref_value'] : $default;
}

// Set preference in cookie
function set_preference($key, $value) {
    setcookie('pref_' . $key, $value, time() + (86400 * 30), "/INDICLEX_FINAL/"); // 30 days
}

// CSRF protection (basic)
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
