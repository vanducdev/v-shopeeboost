-- VMAX SaaS Software Platform MySQL Schema
-- Version: 1.0.0
-- Created: 2026-06-20

SET FOREIGN_KEY_CHECKS = 0;

-- 1. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) UNIQUE NOT NULL,
  `email` VARCHAR(150) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `google_id` VARCHAR(100) DEFAULT NULL,
  `facebook_id` VARCHAR(100) DEFAULT NULL,
  `telegram_id` VARCHAR(100) DEFAULT NULL,
  `role` ENUM('admin', 'user') DEFAULT 'user',
  `status` ENUM('active', 'banned') DEFAULT 'active',
  `otp_code` VARCHAR(10) DEFAULT NULL,
  `otp_expires_at` INT DEFAULT NULL,
  `google_2fa_secret` VARCHAR(100) DEFAULT NULL,
  `google_2fa_enabled` TINYINT(1) DEFAULT 0,
  `referrer_id` INT DEFAULT NULL,
  `created_at` INT NOT NULL,
  `updated_at` INT NOT NULL,
  FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. products
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) UNIQUE NOT NULL,
  `description` TEXT DEFAULT NULL,
  `short_description` TEXT DEFAULT NULL,
  `logo_url` VARCHAR(255) DEFAULT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `video_url` VARCHAR(255) DEFAULT NULL,
  `changelog` TEXT DEFAULT NULL,
  `download_url` VARCHAR(255) DEFAULT NULL,
  `price_monthly` DECIMAL(12,2) DEFAULT 0.00,
  `price_quarterly` DECIMAL(12,2) DEFAULT 0.00,
  `price_yearly` DECIMAL(12,2) DEFAULT 0.00,
  `price_lifetime` DECIMAL(12,2) DEFAULT 0.00,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `show_in_menu` TINYINT(1) DEFAULT 1,
  `is_combo` TINYINT(1) DEFAULT 0,
  `created_at` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. licenses
CREATE TABLE IF NOT EXISTS `licenses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `product_id` INT NOT NULL,
  `license_key` VARCHAR(100) UNIQUE NOT NULL,
  `type` ENUM('trial', 'monthly', 'quarterly', 'yearly', 'lifetime') DEFAULT 'trial',
  `status` ENUM('active', 'suspended') DEFAULT 'active',
  `device_limit` INT DEFAULT 1,
  `created_at` INT NOT NULL,
  `expires_at` INT NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. devices
CREATE TABLE IF NOT EXISTS `devices` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `license_id` INT NOT NULL,
  `device_id` VARCHAR(150) NOT NULL,
  `device_name` VARCHAR(150) DEFAULT NULL,
  `ip_address` VARCHAR(50) DEFAULT NULL,
  `status` ENUM('active', 'blocked') DEFAULT 'active',
  `activated_at` INT NOT NULL,
  `updated_at` INT NOT NULL,
  UNIQUE KEY `license_device` (`license_id`, `device_id`),
  FOREIGN KEY (`license_id`) REFERENCES `licenses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. downloads
CREATE TABLE IF NOT EXISTS `downloads` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `version` VARCHAR(50) NOT NULL,
  `size` VARCHAR(50) DEFAULT NULL,
  `download_url` VARCHAR(255) NOT NULL,
  `release_notes` TEXT DEFAULT NULL,
  `created_at` INT NOT NULL,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `status` ENUM('pending', 'completed', 'refunded', 'canceled') DEFAULT 'pending',
  `created_at` INT NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `payment_method` ENUM('VietQR', 'Momo', 'ZaloPay', 'Banking', 'Stripe', 'Paypal') NOT NULL,
  `transaction_id` VARCHAR(150) UNIQUE DEFAULT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `status` ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
  `created_at` INT NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. tickets
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `priority` ENUM('low', 'medium', 'high') DEFAULT 'medium',
  `status` ENUM('open', 'replied', 'closed') DEFAULT 'open',
  `created_at` INT NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. ticket_messages
CREATE TABLE IF NOT EXISTS `ticket_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ticket_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `file_attachment` VARCHAR(255) DEFAULT NULL,
  `created_at` INT NOT NULL,
  FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `type` ENUM('news', 'promotion', 'maintenance', 'update') DEFAULT 'news',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE NOT NULL,
  `content` TEXT NOT NULL,
  `summary` TEXT DEFAULT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` TEXT DEFAULT NULL,
  `status` ENUM('draft', 'published') DEFAULT 'draft',
  `created_at` INT NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. tags
CREATE TABLE IF NOT EXISTS `tags` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) UNIQUE NOT NULL,
  `value` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. affiliate_users
CREATE TABLE IF NOT EXISTS `affiliate_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNIQUE NOT NULL,
  `referral_code` VARCHAR(50) UNIQUE NOT NULL,
  `balance` DECIMAL(12,2) DEFAULT 0.00,
  `commission_rate` DECIMAL(5,2) DEFAULT 10.00,
  `created_at` INT NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. affiliate_logs
CREATE TABLE IF NOT EXISTS `affiliate_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `referrer_id` INT NOT NULL,
  `buyer_id` INT NOT NULL,
  `order_id` INT NOT NULL,
  `commission_amount` DECIMAL(12,2) NOT NULL,
  `status` ENUM('pending', 'approved') DEFAULT 'pending',
  `created_at` INT NOT NULL,
  FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. withdraw_requests
CREATE TABLE IF NOT EXISTS `withdraw_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `bank_name` VARCHAR(100) NOT NULL,
  `account_number` VARCHAR(50) NOT NULL,
  `account_name` VARCHAR(150) NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `created_at` INT NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. activity_logs
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `action` VARCHAR(255) NOT NULL,
  `ip_address` VARCHAR(50) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `created_at` INT NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. api_logs
CREATE TABLE IF NOT EXISTS `api_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `license_key` VARCHAR(100) DEFAULT NULL,
  `device_id` VARCHAR(150) DEFAULT NULL,
  `request_path` VARCHAR(255) NOT NULL,
  `response_code` INT NOT NULL,
  `ip_address` VARCHAR(50) DEFAULT NULL,
  `created_at` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- Seed default products
INSERT INTO `products` (`id`, `name`, `slug`, `short_description`, `description`, `price_monthly`, `price_quarterly`, `price_yearly`, `price_lifetime`, `status`, `show_in_menu`, `is_combo`, `image_url`, `created_at`) VALUES
(1, 'LushLogin', 'lushlogin', 'Trình duyệt chống dò quét vân tay antidetect bảo mật cao.', 'Quản lý hàng ngàn tài khoản Facebook, Google, Tiktok với vân tay trình duyệt độc lập.', 200000.00, 500000.00, 1800000.00, 3900000.00, 'active', 1, 0, NULL, 1781918300),
(2, 'V-AutoPost', 'v-autopost', 'Phần mềm tự động hóa marketing, đăng bài đa luồng.', 'Tự động tương tác fanpage, group, và lướt bảng tin thông minh.', 300000.00, 650000.00, 3500000.00, 6000000.00, 'active', 1, 0, NULL, 1781918300),
(3, 'Combo Marketing Facebook', 'combo-marketing-facebook', 'Bộ 3 phần mềm bán hàng Facebook tự động đỉnh cao.', 'Giải pháp hoàn hảo hỗ trợ tiếp cận hàng triệu khách hàng tiềm năng trên Facebook.', 500000.00, 1200000.00, 3500000.00, 7500000.00, 'active', 1, 1, 'image/sanphamfb.png', 1781918300),
(4, 'Combo Phú Quý', 'combo-phu-quy', 'Bộ 4 công cụ marketing tự động tối ưu doanh thu.', 'Tự động hóa hoàn toàn quy trình xây dựng phễu bán hàng đa nền tảng.', 600000.00, 1500000.00, 4500000.00, 9500000.00, 'active', 1, 1, 'image/sanphamsp.png', 1781918300);

-- Seed default admin settings
INSERT INTO `settings` (`key`, `value`) VALUES
('site_name', 'VMAX Software'),
('bank_transfer_info', 'Vietcombank - STK: 1043236471 - NGUYEN VAN DUC'),
('stripe_key', 'pk_test_placeholder'),
('telegram_bot_token', 'bot_token_placeholder');
