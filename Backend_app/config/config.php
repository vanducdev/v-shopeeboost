<?php
// VMAX Software Global Configuration File

// Ensure session is started globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global Path Constants
define('BASE_PATH', dirname(__DIR__));

// Language initialization
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] === 'en' ? 'en' : 'vi';
    $_SESSION['lang'] = $lang;
    setcookie('lang', $lang, time() + (3600 * 24 * 30), '/'); // 30 days
} elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
} elseif (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'vi';
}
define('APP_LANG', $lang);

// Load translation mapping
$translations = [];
$langFile = BASE_PATH . '/config/lang/' . APP_LANG . '.php';
if (file_exists($langFile)) {
    $translations = require $langFile;
}

function __($key) {
    global $translations;
    return $translations[$key] ?? $key;
}

define('SECRET_KEY', 'VMAX-SOFTWARE-PLATFORM-SECRET-KEY-2026');

// Domain Configuration
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$currentHost = $_SERVER['HTTP_HOST'] ?? 'vmaxsoftware.vn';
define('SITE_URL', $protocol . $currentHost);
define('API_URL', $protocol . 'api.' . str_replace('app.', '', $currentHost));

// Asset Path for Subdirectory Support
$baseFolder = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'] ?? '');
define('ASSET_PATH', $baseFolder);

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'sql_app_vmaxtech_vn');
define('DB_PASS', '8e8f274f6252d');
define('DB_NAME', 'sql_app_vmaxtech_vn');

// Google OAuth client ID
define('GOOGLE_CLIENT_ID', '326337579332-7cqnbohkjjjgrd2n6nnq0bsk4cn4q7k9.apps.googleusercontent.com');

// Global helper functions
function redirect($url) {
    header("Location: $url");
    exit;
}

function view($viewName, $data = []) {
    extract($data);
    $viewFile = BASE_PATH . '/views/' . $viewName . '.php';
    if (file_exists($viewFile)) {
        require $viewFile;
    } else {
        die("View template <strong>$viewName</strong> not found.");
    }
}

function jsonResponse($data, $statusCode = 200) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
