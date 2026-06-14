<?php
/**
 * VANTHEX ENGINE - Main API
 * Owner: @SagarXModder
 * Endpoints: check_joins, join, generate_key, verify, admin_create_keys
 */

require_once 'config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$endpoint = 'api.php?action=' . $action;
$requestData = json_encode($_POST);

// Log the request
logAPI($endpoint, $_SERVER['REQUEST_METHOD'], $requestData, '');

// Helper: Get session ID from IP + User Agent
function getSessionId() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    return md5($ip . $ua . 'VANTHEX_SESSION_SALT');
}

// Helper: Check if table exists
function tableExists($tableName) {
    global $conn;
    $result = $conn->query("SHOW TABLES LIKE '{$tableName}'");
    return $result && $result->num_rows > 0;
}

// Auto-create tables if missing
if (!tableExists('join_tracking')) {
    initDatabase();
}

switch ($action) {
    
    // =====================================================
    // CHECK JOINS STATUS
    // =====================================================
    case 'check_joins':
        $sessionId = getSessionId();
        
        try {
            $sql = "SELECT COUNT(*) as join_count FROM join_tracking WHERE session_id = ? AND verified = 1";
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) {
                sendJSON(['status' => 'error', 'message' => 'Database error: ' . $conn->error], 500);
            }
            
            $stmt->bind_param("s", $sessionId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $joinCount = intval($row['join_count'] ?? 0);
            
            sendJSON([
                'status' => 'success',
                'join_count' => $joinCount,
                'required' => 3,
                'can_generate' => $joinCount >= 3,
                'session_id' => substr($sessionId, 0, 8) . '...'
            ]);
            
        } catch (Exception $e) {
            sendJSON(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
        break;

    // =====================================================
    // JOIN CHANNEL
    // =====================================================
    case 'join':
        $channel = intval($_POST['channel'] ?? 0);
        $sessionId = getSessionId();
        
        if ($channel < 1 || $channel > 3) {
            sendJSON(['status' => 'error', 'message' => 'Invalid channel number. Use 1, 2, or 3.'], 400);
        }
        
        try {
            // Check if already joined
            $sql = "SELECT id FROM join_tracking WHERE session_id = ? AND channel_number = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $sessionId, $channel);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows > 0) {
                sendJSON(['status' => 'error', 'message' => 'Already joined this channel'], 400);
            }
            
            // Record join
            $channelNames = ['', 'Telegram', 'YouTube', 'Instagram'];
            $channelName = $channelNames[$channel] ?? 'Unknown';
            
            $sql = "INSERT INTO join_tracking (session_id, channel_number, channel_name, verified) VALUES (?, ?, ?, 1)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sis", $sessionId, $channel, $channelName);
            
            if (!$stmt->execute()) {
                sendJSON(['status' => 'error', 'message' => 'Failed to record join: ' . $stmt->error], 500);
            }
            
            // Get updated count
            $sql = "SELECT COUNT(*) as count FROM join_tracking WHERE session_id = ? AND verified = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $sessionId);
            $stmt->execute();
            $count = intval($stmt->get_result()->fetch_assoc()['count']);
            
            sendJSON([
                'status' => 'success',
                'message' => "Channel {$channel} ({$channelName}) joined successfully!",
                'join_count' => $count,
                'required' => 3,
                'can_generate' => $count >= 3
            ]);
            
        } catch (Exception $e) {
            sendJSON(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
        break;

    // =====================================================
    // GENERATE LICENSE KEY
    // =====================================================
    case 'generate_key':
        $duration = intval($_POST['duration'] ?? 1);
        $sessionId = getSessionId();
        
        // Validate duration
        if (!in_array($duration, [1, 2, 7, 30])) {
            sendJSON(['status' => 'error', 'message' => 'Invalid duration. Choose 1, 2, 7, or 30 days.'], 400);
        }
        
        try {
            // Check joins
            $sql = "SELECT COUNT(*) as join_count FROM join_tracking WHERE session_id = ? AND verified = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $sessionId);
            $stmt->execute();
            $joinCount = intval($stmt->get_result()->fetch_assoc()['join_count']);
            
            if ($joinCount < 3) {
                sendJSON([
                    'status' => 'error',
                    'message' => 'Complete all 3 channel joins first!',
                    'joins_completed' => $joinCount,
                    'joins_required' => 3,
                    'joins_needed' => 3 - $joinCount
                ], 403);
            }
            
            // Check if already generated key for this session
            $sql = "SELECT key_code FROM generated_keys WHERE session_id = ? AND used = 0 LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $sessionId);
            $stmt->execute();
            $existing = $stmt->get_result();
            
            if ($existing->num_rows > 0) {
                $existingKey = $existing->fetch_assoc();
                sendJSON([
                    'status' => 'success',
                    'message' => 'You already have a generated key!',
                    'key' => $existingKey['key_code'],
                    'note' => 'Use this key in your mod menu'
                ]);
            }
            
            // Generate key
            $typeMap = [1 => '1DAY', 2 => '2DAY', 7 => '7DAY', 30 => '30DAY'];
            $type = $typeMap[$duration] ?? '1DAY';
            $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8));
            $timestamp = substr(time(), -4);
            $key = "VANTHEX-{$type}-{$random}{$timestamp}";
            
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$duration} days"));
            
            // Save key
            $sql = "INSERT INTO generated_keys (key_code, session_id, duration_days, expires_at) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssis", $key, $sessionId, $duration, $expiresAt);
            
            if (!$stmt->execute()) {
                // Retry with new key if duplicate
                if ($stmt->errno == 1062) {
                    $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8));
                    $key = "VANTHEX-{$type}-{$random}" . substr(time(), -4);
                    $stmt->bind_param("ssis", $key, $sessionId, $duration, $expiresAt);
                    if (!$stmt->execute()) {
                        sendJSON(['status' => 'error', 'message' => 'Failed to generate unique key'], 500);
                    }
                } else {
                    sendJSON(['status' => 'error', 'message' => 'Failed to save key: ' . $stmt->error], 500);
                }
            }
            
            sendJSON([
                'status' => 'success',
                'message' => 'License key generated successfully!',
                'key' => $key,
                'type' => $type,
                'duration_days' => $duration,
                'expires_at' => $expiresAt,
                'note' => 'Copy this key and use it in your IMGUI mod menu'
            ]);
            
        } catch (Exception $e) {
            sendJSON(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
        break;

    // =====================================================
    // VERIFY KEY (For IMGUI Mod Menu via cURL)
    // =====================================================
    case 'verify':
        $licenseKey = strtoupper(trim($_POST['key'] ?? $_GET['key'] ?? ''));
        $deviceId = trim($_POST['device_id'] ?? $_GET['device_id'] ?? '');
        
        if (empty($licenseKey)) {
            sendJSON([
                'status' => 'error',
                'message' => 'License key is required',
                'valid' => false
            ], 400);
        }
        
        try {
            // Check key in database
            $sql = "SELECT * FROM generated_keys WHERE key_code = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $licenseKey);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                sendJSON([
                    'status' => 'error',
                    'message' => 'Invalid license key',
                    'valid' => false,
                    'key' => $licenseKey
                ], 404);
            }
            
            $keyData = $result->fetch_assoc();
            
            // Check if key is used
            if ($keyData['used'] == 1) {
                // Key already used - check device binding
                if (!empty($keyData['device_id']) && $keyData['device_id'] !== $deviceId) {
                    sendJSON([
                        'status' => 'error',
                        'message' => 'This key is already bound to another device',
                        'valid' => false,
                        'device_bound' => true,
                        'reason' => 'device_mismatch'
                    ], 403);
                }
                
                // Check expiry
                if ($keyData['expires_at'] && strtotime($keyData['expires_at']) < time()) {
                    sendJSON([
                        'status' => 'error',
                        'message' => 'License key has expired',
                        'valid' => false,
                        'reason' => 'expired',
                        'expired_at' => $keyData['expires_at']
                    ], 403);
                }
                
                // Key valid and bound to this device
                $daysLeft = ceil((strtotime($keyData['expires_at']) - time()) / 86400);
                
                sendJSON([
                    'status' => 'success',
                    'message' => 'License key is valid',
                    'valid' => true,
                    'key' => $licenseKey,
                    'expires_at' => $keyData['expires_at'],
                    'days_left' => max(0, $daysLeft),
                    'device_verified' => true
                ]);
            }
            
            // Key is unused - bind to device
            if (empty($deviceId)) {
                sendJSON([
                    'status' => 'error',
                    'message' => 'Device ID is required for first activation',
                    'valid' => false
                ], 400);
            }
            
            // Activate key
            $sql = "UPDATE generated_keys SET used = 1, device_id = ?, activated_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $deviceId, $keyData['id']);
            
            if (!$stmt->execute()) {
                sendJSON(['status' => 'error', 'message' => 'Failed to activate key'], 500);
            }
            
            $daysLeft = $keyData['duration_days'];
            
            sendJSON([
                'status' => 'success',
                'message' => 'License key activated successfully!',
                'valid' => true,
                'key' => $licenseKey,
                'expires_at' => $keyData['expires_at'],
                'days_left' => $daysLeft,
                'device_bound' => true,
                'first_activation' => true
            ]);
            
        } catch (Exception $e) {
            sendJSON(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
        break;

    // =====================================================
    // CHECK KEY STATUS (Without modifying)
    // =====================================================
    case 'check_key':
        $licenseKey = strtoupper(trim($_POST['key'] ?? $_GET['key'] ?? ''));
        $deviceId = trim($_POST['device_id'] ?? $_GET['device_id'] ?? '');
        
        if (empty($licenseKey)) {
            sendJSON(['status' => 'error', 'message' => 'License key required'], 400);
        }
        
        try {
            $sql = "SELECT * FROM generated_keys WHERE key_code = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $licenseKey);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                sendJSON(['status' => 'error', 'message' => 'Key not found', 'valid' => false], 404);
            }
            
            $keyData = $result->fetch_assoc();
            $isExpired = $keyData['expires_at'] && strtotime($keyData['expires_at']) < time();
            $daysLeft = $isExpired ? 0 : ceil((strtotime($keyData['expires_at']) - time()) / 86400);
            
            sendJSON([
                'status' => 'success',
                'valid' => !$isExpired && $keyData['used'] == 1,
                'key' => $licenseKey,
                'used' => $keyData['used'] == 1,
                'expired' => $isExpired,
                'expires_at' => $keyData['expires_at'],
                'days_left' => max(0, $daysLeft),
                'device_bound' => !empty($keyData['device_id']),
                'device_match' => $keyData['device_id'] === $deviceId
            ]);
            
        } catch (Exception $e) {
            sendJSON(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
        break;

    // =====================================================
    // ADMIN: CREATE KEYS IN BULK
    // =====================================================
    case 'admin_create_keys':
        $adminKey = $_POST['admin_key'] ?? $_GET['admin_key'] ?? '';
        $count = min(intval($_POST['count'] ?? 10), 100); // Max 100 at once
        $duration = intval($_POST['duration'] ?? 1);
        
        // Verify admin secret (change this in production!)
        $adminSecret = getenv('ADMIN_SECRET') ?: 'VANTHEX_ADMIN_SECRET_2026';
        
        if ($adminKey !== $adminSecret) {
            sendJSON(['status' => 'error', 'message' => 'Unauthorized - Invalid admin key'], 401);
        }
        
        if (!in_array($duration, [1, 2, 7, 30])) {
            sendJSON(['status' => 'error', 'message' => 'Invalid duration'], 400);
        }
        
        try {
            $typeMap = [1 => '1DAY', 2 => '2DAY', 7 => '7DAY', 30 => '30DAY'];
            $type = $typeMap[$duration] ?? '1DAY';
            $keys = [];
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$duration} days"));
            
            for ($i = 0; $i < $count; $i++) {
                $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8));
                $timestamp = substr(time() + $i, -4);
                $key = "VANTHEX-{$type}-{$random}{$timestamp}";
                
                $sql = "INSERT INTO generated_keys (key_code, duration_days, expires_at) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sis", $key, $duration, $expiresAt);
                
                if ($stmt->execute()) {
                    $keys[] = $key;
                }
            }
            
            sendJSON([
                'status' => 'success',
                'message' => count($keys) . ' keys generated successfully',
                'keys' => $keys,
                'duration' => $duration,
                'type' => $type,
                'expires_at' => $expiresAt
            ]);
            
        } catch (Exception $e) {
            sendJSON(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
        break;

    // =====================================================
    // ADMIN: LIST ALL KEYS
    // =====================================================
    case 'admin_list_keys':
        $adminKey = $_POST['admin_key'] ?? $_GET['admin_key'] ?? '';
        $adminSecret = getenv('ADMIN_SECRET') ?: 'VANTHEX_ADMIN_SECRET_2026';
        
        if ($adminKey !== $adminSecret) {
            sendJSON(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        
        try {
            $sql = "SELECT key_code, duration_days, used, device_id, activated_at, expires_at, created_at 
                    FROM generated_keys ORDER BY created_at DESC LIMIT 100";
            $result = $conn->query($sql);
            
            $keys = [];
            while ($row = $result->fetch_assoc()) {
                $keys[] = [
                    'key' => $row['key_code'],
                    'duration' => $row['duration_days'],
                    'used' => $row['used'] == 1,
                    'device_id' => $row['device_id'] ? substr($row['device_id'], 0, 16) . '...' : null,
                    'activated_at' => $row['activated_at'],
                    'expires_at' => $row['expires_at'],
                    'created_at' => $row['created_at']
                ];
            }
            
            sendJSON([
                'status' => 'success',
                'total' => count($keys),
                'keys' => $keys
            ]);
            
        } catch (Exception $e) {
            sendJSON(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
        break;

    // =====================================================
    // HEALTH CHECK
    // =====================================================
    case 'health':
        sendJSON([
            'status' => 'success',
            'message' => 'VANTHEX ENGINE is running',
            'version' => '2.0.0',
            'owner' => '@SagarXModder',
            'timestamp' => date('Y-m-d H:i:s'),
            'database' => $conn->ping() ? 'connected' : 'disconnected'
        ]);
        break;

    // =====================================================
    // DEFAULT: INVALID ACTION
    // =====================================================
    default:
        sendJSON([
            'status' => 'error',
            'message' => 'Invalid action',
            'available_actions' => [
                'check_joins' => 'Check channel join progress',
                'join' => 'Record a channel join (POST: channel=1|2|3)',
                'generate_key' => 'Generate license key (POST: duration=1|2|7|30)',
                'verify' => 'Verify key for IMGUI (POST/GET: key=XXX&device_id=XXX)',
                'check_key' => 'Check key status without activation',
                'admin_create_keys' => 'Bulk generate keys (admin_key required)',
                'admin_list_keys' => 'List all keys (admin_key required)',
                'health' => 'Health check'
            ]
        ], 400);
}
?>