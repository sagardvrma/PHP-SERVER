<?php
/**
 * VANTHEX ENGINE - Core Configuration
 * Owner: @SagarXModder
 * Docker Ready | Production Ready
 */

session_start();

// Error Reporting (Disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// =====================================================
// DATABASE CONFIGURATION (Docker Environment Support)
// =====================================================
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'vanthex_engine';

// For Docker: if DB_HOST is set but empty, use default
if (empty($dbHost)) $dbHost = 'localhost';
if (empty($dbUser)) $dbUser = 'root';
if (empty($dbName)) $dbName = 'vanthex_engine';

define('DB_HOST', $dbHost);
define('DB_USER', $dbUser);
define('DB_PASS', $dbPass);
define('DB_NAME', $dbName);

// =====================================================
// APPLICATION SETTINGS
// =====================================================
define('APP_NAME', 'VANTHEX ENGINE');
define('APP_VERSION', '2.0.0');
define('APP_OWNER', '@SagarXModder');

// Base URL auto-detect
$baseUrl = getenv('BASE_URL');
if (!$baseUrl) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $baseUrl = $protocol . '://' . $host . '/';
}
define('BASE_URL', $baseUrl);

// =====================================================
// SECURITY SETTINGS
// =====================================================
define('SECRET_KEY', 'VANTHEX_SECRET_' . md5(uniqid('', true)));
define('SESSION_LIFETIME', 86400);  // 24 hours
define('API_RATE_LIMIT', 100);       // per hour

// =====================================================
// LICENSE SETTINGS
// =====================================================
define('KEY_PREFIX', 'VANTHEX');
define('MAX_DEVICES', 1);
define('JOIN_CHANNELS_REQUIRED', 3);

// =====================================================
// DATABASE CONNECTION WITH RETRY (For Docker)
// =====================================================
$conn = null;
$maxRetries = 15;
$retryDelay = 2; // seconds

for ($i = 0; $i < $maxRetries; $i++) {
    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$conn->connect_error) {
        break; // Success!
    }
    
    // Check if database doesn't exist yet (first run)
    if ($conn->connect_errno == 1049) { // Unknown database
        // Try connecting without database
        $tempConn = @new mysqli(DB_HOST, DB_USER, DB_PASS);
        if (!$tempConn->connect_error) {
            // Create database
            $tempConn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $tempConn->close();
            // Retry connection
            continue;
        }
    }
    
    if ($i < $maxRetries - 1) {
        error_log("[VANTHEX] DB connection attempt " . ($i + 1) . "/{$maxRetries} failed. Retrying in {$retryDelay}s...");
        sleep($retryDelay);
    }
}

if ($conn === null || $conn->connect_error) {
    http_response_code(503);
    header('Content-Type: application/json');
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed. Please try again later.',
        'error' => $conn ? $conn->connect_error : 'Unable to connect',
        'host' => DB_HOST,
        'database' => DB_NAME
    ]));
}

$conn->set_charset("utf8mb4");

// =====================================================
// HELPER FUNCTIONS
// =====================================================

/**
 * Generate Random License Key
 */
function generateLicenseKey($type = '1day') {
    $prefix = KEY_PREFIX;
    $typeCode = strtoupper($type);
    $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8));
    $timestamp = substr(time(), -4);
    return "{$prefix}-{$typeCode}-{$random}{$timestamp}";
}

/**
 * Get Device Fingerprint (IP + User Agent + Headers)
 */
function getDeviceId() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    $encoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
    return hash('sha256', $ip . $ua . $accept . $lang . $encoding . 'VANTHEX_SALT_2026');
}

/**
 * Get Device Name from User Agent
 */
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

/**
 * Send JSON Response with CORS
 */
function sendJSON($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Max-Age: 86400');
    
    // Handle preflight
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
    
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Log API Request (for debugging)
 */
function logAPI($endpoint, $method, $request, $response) {
    global $conn;
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $sql = "INSERT INTO api_logs (endpoint, method, request_data, response_data, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssss", $endpoint, $method, $request, $response, $ip, $ua);
            $stmt->execute();
        }
    } catch (Exception $e) {
        // Silent fail - don't break API for logging errors
    }
}

/**
 * Get Setting from Database
 */
function getSetting($key, $default = '') {
    global $conn;
    try {
        $sql = "SELECT setting_value FROM settings WHERE setting_key = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return $default;
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['setting_value'];
        }
    } catch (Exception $e) {}
    return $default;
}

/**
 * Initialize Database Tables (if not exist)
 */
function initDatabase() {
    global $conn;
    
    // Create join_tracking table
    $sql = "CREATE TABLE IF NOT EXISTS join_tracking (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(64) NOT NULL,
        channel_number INT NOT NULL,
        channel_name VARCHAR(100) DEFAULT NULL,
        joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        verified BOOLEAN DEFAULT TRUE,
        UNIQUE KEY unique_session_channel (session_id, channel_number)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->query($sql);
    
    // Create generated_keys table
    $sql = "CREATE TABLE IF NOT EXISTS generated_keys (
        id INT AUTO_INCREMENT PRIMARY KEY,
        key_code VARCHAR(64) NOT NULL UNIQUE,
        session_id VARCHAR(64) DEFAULT NULL,
        duration_days INT DEFAULT 1,
        used BOOLEAN DEFAULT FALSE,
        device_id VARCHAR(255) DEFAULT NULL,
        activated_at TIMESTAMP NULL,
        expires_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_key_code (key_code),
        INDEX idx_session_id (session_id),
        INDEX idx_used (used)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->query($sql);
    
    // Create api_logs table
    $sql = "CREATE TABLE IF NOT EXISTS api_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        endpoint VARCHAR(100) NOT NULL,
        method VARCHAR(10) NOT NULL,
        request_data TEXT,
        response_data TEXT,
        ip_address VARCHAR(45) DEFAULT NULL,
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->query($sql);
    
    // Create settings table
    $sql = "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(50) NOT NULL UNIQUE,
        setting_value TEXT,
        description VARCHAR(255) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->query($sql);
    
    // Insert default settings
    $defaults = [
        ['site_name', 'VANTHEX ENGINE', 'Website name'],
        ['site_owner', '@SagarXModder', 'Site owner'],
        ['join_channels_required', '3', 'Number of channels to join'],
        ['key_prefix', 'VANTHEX', 'License key prefix'],
        ['max_devices_per_key', '1', 'Maximum devices per key'],
        ['session_lifetime', '86400', 'Session lifetime in seconds'],
        ['api_rate_limit', '100', 'API requests per hour']
    ];
    
    foreach ($defaults as $setting) {
        $sql = "INSERT IGNORE INTO settings (setting_key, setting_value, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $setting[0], $setting[1], $setting[2]);
            $stmt->execute();
        }
    }
}

// Auto-init database on first run
initDatabase();

// Success log
error_log("[VANTHEX] Connected to database: " . DB_NAME . " on " . DB_HOST);
?>