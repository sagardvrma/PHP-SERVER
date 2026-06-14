<?php
/**
 * VANTHEX ENGINE - Core Configuration
 * Owner: @SagarXModder
 */

session_start();

// Error Reporting (Disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Configuration
define('DB_HOST', getenv('MYSQLHOST') ?: 'zephyr.proxy.rlwy.net');
define('DB_USER', getenv('MYSQLUSER') ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: 'WUBfTjOXkkODxGqOMZgzpEbwmZDmgclX');
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'railway');
define('DB_PORT', getenv('MYSQLPORT') ?: 3306);

// Create Connection
$conn = new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASS,
    DB_NAME,
    DB_PORT
);

// Application Settings
define('APP_NAME', 'VANTHEX ENGINE');
define('APP_VERSION', '2.0.0');
define('APP_OWNER', '@SagarXModder');
define('BASE_URL', 'https://yourdomain.com/');

// Security Settings
define('SECRET_KEY', 'VANTHEX_SECRET_' . uniqid());
define('SESSION_LIFETIME', 86400);
define('API_RATE_LIMIT', 100);

// License Settings
define('KEY_PREFIX', 'VANTHEX');
define('MAX_DEVICES', 1);
define('JOIN_CHANNELS_REQUIRED', 3);

// Create Connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed',
        'error' => $conn->connect_error
    ]));
}

$conn->set_charset("utf8mb4");

function generateLicenseKey($type = '1day') {
    $prefix = KEY_PREFIX;
    $typeCode = strtoupper($type);
    $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8));
    $timestamp = substr(time(), -4);
    return "{$prefix}-{$typeCode}-{$random}{$timestamp}";
}

function getDeviceId() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    return hash('sha256', $ip . $ua . $accept . $lang . 'VANTHEX_SALT');
}

function getDeviceName() {
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    if (strpos($ua, 'Windows') !== false) return 'Windows PC';
    if (strpos($ua, 'Mac') !== false) return 'Mac Device';
    if (strpos($ua, 'Linux') !== false) return 'Linux Device';
    if (strpos($ua, 'Android') !== false) return 'Android Device';
    if (strpos($ua, 'iPhone') !== false) return 'iPhone';
    if (strpos($ua, 'iPad') !== false) return 'iPad';
    return 'Unknown Device';
}

function sendJSON($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

function logAPI($endpoint, $method, $request, $response) {
    global $conn;
    $userId = $_SESSION['user_id'] ?? null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $sql = "INSERT INTO api_logs (endpoint, method, request_data, response_data, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $endpoint, $method, $request, $response, $ip, $ua);
    $stmt->execute();
}

function checkAuth() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['session_token'])) {
        sendJSON(['status' => 'error', 'message' => 'Unauthorized. Please login with your license key.'], 401);
    }
    global $conn;
    $sessionToken = $_SESSION['session_token'];
    $deviceId = getDeviceId();
    $sql = "SELECT * FROM login_sessions WHERE session_token = ? AND device_id = ? AND is_active = 1 AND (expires_at IS NULL OR expires_at > NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $sessionToken, $deviceId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        session_destroy();
        sendJSON(['status' => 'error', 'message' => 'Session expired or invalid device.'], 401);
    }
    $sql = "UPDATE login_sessions SET last_activity = NOW() WHERE session_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $sessionToken);
    $stmt->execute();
}

function getSetting($key, $default = '') {
    global $conn;
    $sql = "SELECT setting_value FROM settings WHERE setting_key = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $key);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['setting_value'];
    }
    return $default;
}
?>
