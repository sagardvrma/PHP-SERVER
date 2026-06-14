-- VANTHEX ENGINE License System Database
-- Owner: @SagarXModder

CREATE DATABASE IF NOT EXISTS vanthex_engine CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vanthex_engine;

-- Join Tracking Table (Session based - no login required)
CREATE TABLE join_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(64) NOT NULL,
    channel_number INT NOT NULL COMMENT '1, 2, or 3',
    channel_name VARCHAR(100) DEFAULT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified BOOLEAN DEFAULT TRUE,
    UNIQUE KEY unique_session_channel (session_id, channel_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Generated Keys Table
CREATE TABLE generated_keys (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- API Logs Table
CREATE TABLE api_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endpoint VARCHAR(100) NOT NULL,
    method VARCHAR(10) NOT NULL,
    request_data TEXT,
    response_data TEXT,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Settings Table
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT,
    description VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Default Settings
INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_name', 'VANTHEX ENGINE', 'Website name'),
('site_owner', '@SagarXModder', 'Site owner'),
('join_channels_required', '3', 'Number of channels to join'),
('key_prefix', 'VANTHEX', 'License key prefix');
