DROP TABLE IF EXISTS sessions;

CREATE TABLE IF NOT EXISTS sessions (
  id VARCHAR(128) PRIMARY KEY,
  user_id INT DEFAULT NULL,
  data LONGTEXT,
  last_activity DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user_id (user_id),
  INDEX idx_last_activity (last_activity),
  CONSTRAINT fk_sessions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE users ADD INDEX idx_created_at (created_at);
ALTER TABLE products ADD INDEX idx_created_at (created_at);
ALTER TABLE products ADD INDEX idx_price (price);
ALTER TABLE products ADD INDEX idx_stock (stock);
ALTER TABLE orders ADD INDEX idx_created_at (created_at);
ALTER TABLE order_items ADD INDEX idx_created_at (created_at);
ALTER TABLE reviews ADD INDEX idx_created_at (created_at);
ALTER TABLE reviews ADD INDEX idx_rating (rating);
ALTER TABLE password_resets ADD INDEX idx_created_at (created_at);
ALTER TABLE password_resets ADD INDEX idx_expires_at (expires_at);
ALTER TABLE password_resets ADD INDEX idx_used (used);

INSERT INTO sessions (id, user_id, data, last_activity) VALUES 
('sess_farmer_001_abcdef123456', 1, 'a:2:{s:7:"user_id";i:1;s:5:"role";s:8:"producer";}', NOW());

INSERT INTO sessions (id, user_id, data, last_activity) VALUES 
('sess_buyer_003_xyz789def456', 3, 'a:2:{s:7:"user_id";i:3;s:5:"role";s:8:"consumer";}', NOW());

INSERT INTO sessions (id, user_id, data, last_activity) VALUES 
('sess_admin_005_pqr321ghi789', 5, 'a:2:{s:7:"user_id";i:5;s:5:"role";s:5:"admin";}', NOW());

INSERT INTO sessions (id, user_id, data, last_activity) VALUES 
('sess_old_expired_789uvw012', 4, 'a:2:{s:7:"user_id";i:4;s:5:"role";s:8:"consumer";}', DATE_SUB(NOW(), INTERVAL 25 HOUR));
