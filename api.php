<?php
/**
 * VANTHEX ENGINE - Main API
 * Owner: @SagarXModder
 */

require_once 'config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$endpoint = 'api.php?action=' . $action;
$requestData = json_encode($_POST);

switch ($action) {
    
    // ========== CHECK JOINS ==========
    case 'check_joins':
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $sessionId = md5($ip . $ua);
        
        $sql = "SELECT COUNT(*) as join_count FROM join_tracking WHERE session_id = ? AND verified = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $sessionId);
        $stmt->execute();
        $joinCount = $stmt->get_result()->fetch_assoc()['join_count'];
        
        sendJSON([
            'status' => 'success',
            'join_count' => intval($joinCount),
            'required' => 3,
            'can_generate' => $joinCount >= 3
        ]);
        break;

    // ========== JOIN CHANNEL ==========
    case 'join':
        $channel = intval($_POST['channel'] ?? 0);
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $sessionId = md5($ip . $ua);
        
        if ($channel < 1 || $channel > 3) {
            sendJSON(['status' => 'error', 'message' => 'Invalid channel'], 400);
        }
        
        // Check if already joined
        $sql = "SELECT * FROM join_tracking WHERE session_id = ? AND channel_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $sessionId, $channel);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            sendJSON(['status' => 'error', 'message' => 'Already joined this channel'], 400);
        }
        
        // Record join
        $channelNames = ['', 'Telegram', 'YouTube', 'Instagram'];
        $sql = "INSERT INTO join_tracking (session_id, channel_number, channel_name, verified) VALUES (?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $sessionId, $channel, $channelNames[$channel]);
        
        if ($stmt->execute()) {
            // Get updated count
            $sql = "SELECT COUNT(*) as count FROM join_tracking WHERE session_id = ? AND verified = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $sessionId);
            $stmt->execute();
            $count = $stmt->get_result()->fetch_assoc()['count'];
            
            sendJSON([
                'status' => 'success',
                'message' => "Channel {$channel} joined!",
                'join_count' => intval($count),
                'required' => 3,
                'can_generate' => $count >= 3
            ]);
        } else {
            sendJSON(['status' => 'error', 'message' => 'Failed to record join'], 500);
        }
        break;

    // ========== GENERATE KEY ==========
    case 'generate_key':
        $duration = intval($_POST['duration'] ?? 1);
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $sessionId = md5($ip . $ua);
        
        // Check joins
        $sql = "SELECT COUNT(*) as join_count FROM join_tracking WHERE session_id = ? AND verified = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $sessionId);
        $stmt->execute();
        $joinCount = $stmt->get_result()->fetch_assoc()['join_count'];
        
        if ($joinCount < 3) {
            sendJSON([
                'status' => 'error',
                'message' => 'Complete all 3 joins first!',
                'joins_needed' => 3 - $joinCount
            ], 403);
        }
        
        // Generate key
        $typeMap = [1 => '1DAY', 2 => '2DAY'];
        $type = $typeMap[$duration] ?? '1DAY';
        $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8));
        $timestamp = substr(time(), -4);
        $key = "VANTHEX-{$type}-{$random}{$timestamp}";
        
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$duration} days"));
        
        // Save key
        $sql = "INSERT INTO generated_keys (key_code, session_id, duration_days, expires_at) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $key, $sessionId, $duration, $expiresAt);
        
        if ($stmt->execute()) {
            sendJSON([
                'status' => 'success',
                'message' => 'Key generated!',
                'key' => $key,
                'duration_days' => $duration,
                'expires_at' => $expiresAt
            ]);
        } else {
            sendJSON(['status' => 'error', 'message' => 'Failed to generate key'], 500);
        }
        break;

    // ========== VERIFY KEY (For IMGUI Mod Menu) ==========
    case 'verify':
        $licenseKey = strtoupper(trim($_POST['key'] ?? $_GET['key'] ?? ''));
        $deviceId = $_POST['device_id'] ?? $_GET['device_id'] ?? '';
        
        if (empty($licenseKey)) {
            sendJSON(['status' => 'error', 'message' => 'License key required', 'valid' => false], 400);
        }
        
        // Check in generated_keys
        $sql = "SELECT * FROM generated_keys WHERE key_code = ? AND used = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $licenseKey);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            // Check if already used
            $sql = "SELECT * FROM generated_keys WHERE key_code = ? AND used = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $licenseKey);
            $stmt->execute();
            $usedResult = $stmt->get_result();
            
            if ($usedResult->num_rows > 0) {
                $usedKey = $usedResult->fetch_assoc();
                
                // Check device binding
                if (!empty($usedKey['device_id']) && $usedKey['device_id'] !== $deviceId) {
                    sendJSON([
                        'status' => 'error',
                        'message' => 'Key bound to another device',
                        'valid' => false,
                        'device_bound' => true
                    ], 403);
                }
                
                // Check expiry
                if ($usedKey['expires_at'] && strtotime($usedKey['expires_at']) < time()) {
                    sendJSON([
                        'status' => 'error',
                        'message' => 'Key expired',
                        'valid' => false,
                        'reason' => 'expired'
                    ], 403);
                }
                
                sendJSON([
                    'status' => 'success',
                    'message' => 'Key valid',
                    'valid' => true,
                    'key' => $licenseKey,
                    'expires_at' => $usedKey['expires_at'],
                    'days_left' => ceil((strtotime($usedKey['expires_at']) - time()) / 86400)
                ]);
            }
            
            sendJSON(['status' => 'error', 'message' => 'Invalid key', 'valid' => false], 404);
        }
        
        $keyData = $result->fetch_assoc();
        
        // Bind device and mark used
        $sql = "UPDATE generated_keys SET used = 1, device_id = ?, activated_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $deviceId, $keyData['id']);
        $stmt->execute();
        
        sendJSON([
            'status' => 'success',
            'message' => 'Key activated!',
            'valid' => true,
            'key' => $licenseKey,
            'expires_at' => $keyData['expires_at'],
            'days_left' => $keyData['duration_days']
        ]);
        break;

    // ========== ADMIN: CREATE KEYS ==========
    case 'admin_create_keys':
        $adminKey = $_POST['admin_key'] ?? '';
        $count = intval($_POST['count'] ?? 10);
        $duration = intval($_POST['duration'] ?? 1);
        
        if ($adminKey !== 'VANTHEX_ADMIN_SECRET') {
            sendJSON(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        
        $typeMap = [1 => '1DAY', 2 => '2DAY'];
        $type = $typeMap[$duration] ?? '1DAY';
        $keys = [];
        
        for ($i = 0; $i < $count; $i++) {
            $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8));
            $timestamp = substr(time(), -4);
            $key = "VANTHEX-{$type}-{$random}{$timestamp}";
            
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$duration} days"));
            
            $sql = "INSERT INTO generated_keys (key_code, duration_days, expires_at) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sis", $key, $duration, $expiresAt);
            if ($stmt->execute()) {
                $keys[] = $key;
            }
        }
        
        sendJSON([
            'status' => 'success',
            'message' => count($keys) . ' keys generated',
            'keys' => $keys,
            'duration' => $duration
        ]);
        break;

    default:
        sendJSON(['status' => 'error', 'message' => 'Invalid action'], 400);
}
?>
