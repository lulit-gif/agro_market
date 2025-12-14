DROP TABLE IF EXISTS password_resets;
CREATE TABLE IF NOT EXISTS password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token_hash VARCHAR(128) NOT NULL,
  expires_at DATETIME NOT NULL,
  used TINYINT(1) NOT NULL DEFAULT 0,
  used_at DATETIME DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user_id (user_id),
  INDEX idx_token_hash (token_hash),
  CONSTRAINT fk_password_resets_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO password_resets (user_id, token_hash, expires_at, used, created_at) VALUES 
(1, 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6', DATE_ADD(NOW(), INTERVAL 1 HOUR), 0, NOW());
INSERT INTO password_resets (user_id, token_hash, expires_at, used, created_at) VALUES 
(2, 'z9y8x7w6v5u4t3s2r1q0p9o8n7m6l5k4j3i2h1g0f9e8d7c6b5a4', DATE_SUB(NOW(), INTERVAL 1 HOUR), 0, DATE_SUB(NOW(), INTERVAL 2 HOUR));
INSERT INTO password_resets (user_id, token_hash, expires_at, used, used_at, created_at) VALUES 
(3, 'token123456789abcdefghijklmnopqrstuvwxyz0123456789abcde', DATE_ADD(NOW(), INTERVAL 1 HOUR), 1, NOW(), DATE_SUB(NOW(), INTERVAL 30 MINUTE));

