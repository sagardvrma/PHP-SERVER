
-- =====================================================
-- VANTHEX ENGINE - Database Schema
-- Owner: @SagarXModder
-- Version: 2.0.0
-- =====================================================

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS vanthex_engine 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE vanthex_engine;

-- =====================================================
-- TABLE: join_tracking
-- Purpose: Track channel joins per session
-- =====================================================
CREATE TABLE IF NOT EXISTS join_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(64) NOT NULL,
    channel_number INT NOT NULL COMMENT '1=Telegram, 2=YouTube, 3=Instagram',
    channel_name VARCHAR(100) DEFAULT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified BOOLEAN DEFAULT TRUE,
    
    -- Prevent duplicate joins
    UNIQUE KEY unique_session_channel (session_id, channel_number),
    INDEX idx_session_id (session_id),
    INDEX idx_channel_number (channel_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    COMMENT='Tracks which channels a user has joined';

-- =====================================================
-- TABLE: generated_keys
-- Purpose: Store all generated license keys
-- =====================================================
CREATE TABLE IF NOT EXISTS generated_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_code VARCHAR(64) NOT NULL,
    session_id VARCHAR(64) DEFAULT NULL COMMENT 'Who generated this key',
    duration_days INT NOT NULL DEFAULT 1 COMMENT '1, 2, 7, or 30 days',
    used BOOLEAN DEFAULT FALSE COMMENT 'Has this key been activated?',
    device_id VARCHAR(255) DEFAULT NULL COMMENT 'Bound device (one device policy)',
    activated_at TIMESTAMP NULL COMMENT 'When key was first used',
    expires_at TIMESTAMP NULL COMMENT 'When key expires',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_key_code (key_code),
    INDEX idx_session_id (session_id),
    INDEX idx_device_id (device_id),
    INDEX idx_used (used),
    INDEX idx_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    COMMENT='All generated license keys';

-- =====================================================
-- TABLE: api_logs
-- Purpose: Log all API requests for debugging
-- =====================================================
CREATE TABLE IF NOT EXISTS api_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endpoint VARCHAR(100) NOT NULL COMMENT 'Which API endpoint',
    method VARCHAR(10) NOT NULL COMMENT 'GET/POST/etc',
    request_data TEXT COMMENT 'Request parameters',
    response_data TEXT COMMENT 'Response sent',
    ip_address VARCHAR(45) DEFAULT NULL COMMENT 'Client IP',
    user_agent TEXT COMMENT 'Client user agent',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_endpoint (endpoint),
    INDEX idx_created_at (created_at),
    INDEX idx_ip_address (ip_address)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    COMMENT='API request logs';

-- =====================================================
-- TABLE: settings
-- Purpose: System configuration
-- =====================================================
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL,
    setting_value TEXT,
    description VARCHAR(255) DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    COMMENT='System configuration settings';

-- =====================================================
-- INSERT DEFAULT SETTINGS
-- =====================================================
INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_name', 'VANTHEX ENGINE', 'Website/Application name')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_owner', '@SagarXModder', 'Owner/creator name')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_version', '2.0.0', 'Current version')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('join_channels_required', '3', 'Number of channels user must join')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('key_prefix', 'VANTHEX', 'License key prefix')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('max_devices_per_key', '1', 'Maximum devices per license key')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('session_lifetime', '86400', 'Session lifetime in seconds (24 hours)')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('api_rate_limit', '100', 'Max API requests per hour per IP')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('telegram_link', 'https://t.me/sagarxmodder', 'Telegram channel URL')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('youtube_link', 'https://youtube.com/@VANTHEXCORE', 'YouTube channel URL')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('instagram_link', 'https://instagram.com/sagar_db_20', 'Instagram URL')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- =====================================================
-- DONE!
-- ===================================================