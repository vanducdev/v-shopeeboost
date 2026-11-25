<?php
// VMAX Software - PDO Database Wrapper

class DB {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            ];
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            // Auto migrate new columns to avoid manual script execution issues
            try {
                @$this->pdo->exec("ALTER TABLE `products` ADD COLUMN `show_in_menu` TINYINT(1) DEFAULT 1");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("ALTER TABLE `products` ADD COLUMN `is_combo` TINYINT(1) DEFAULT 0");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("ALTER TABLE `users` ADD COLUMN `avatar_url` VARCHAR(255) DEFAULT NULL");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("ALTER TABLE `users` ADD COLUMN `balance` DECIMAL(12, 2) DEFAULT 0.00");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("CREATE TABLE IF NOT EXISTS `deposits` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `user_id` INT NOT NULL,
                    `amount` DECIMAL(12, 2) NOT NULL,
                    `reference_code` VARCHAR(50) UNIQUE NOT NULL,
                    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                    `created_at` INT NOT NULL,
                    `updated_at` INT NOT NULL,
                    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("CREATE TABLE IF NOT EXISTS `shopee_interactions` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `license_key` VARCHAR(100) DEFAULT NULL,
                    `device_id` VARCHAR(150) DEFAULT NULL,
                    `main_shop_link` TEXT NOT NULL,
                    `sub_accounts` TEXT NOT NULL,
                    `created_at` INT NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("ALTER TABLE `shopee_interactions` ADD COLUMN `shop_name` VARCHAR(255) DEFAULT NULL");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("ALTER TABLE `shopee_interactions` ADD COLUMN `shop_avatar` TEXT DEFAULT NULL");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("ALTER TABLE `shopee_interactions` ADD COLUMN `follower_count` INT DEFAULT 0");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("ALTER TABLE `shopee_interactions` ADD COLUMN `item_count` INT DEFAULT 0");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("ALTER TABLE `shopee_interactions` ADD COLUMN `rating_star` DECIMAL(3,1) DEFAULT 5.0");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("CREATE TABLE IF NOT EXISTS `shopee_announcements` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `title` VARCHAR(255) NOT NULL,
                    `content` TEXT NOT NULL,
                    `image_url` TEXT DEFAULT NULL,
                    `action_link` TEXT DEFAULT NULL,
                    `created_at` INT NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            } catch (PDOException $e) {}
            try {
                @$this->pdo->exec("CREATE TABLE IF NOT EXISTS `shopee_versions` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `version` VARCHAR(50) NOT NULL,
                    `download_url` TEXT NOT NULL,
                    `changelog` TEXT NOT NULL,
                    `is_mandatory` TINYINT DEFAULT 0,
                    `created_at` INT NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            } catch (PDOException $e) {}
            try {
                // Update product 1 (previously LushLogin/lushlogin) to VmaxLogin
                $this->pdo->exec("UPDATE `products` SET 
                    `name` = 'VmaxLogin',
                    `slug` = 'vmaxlogin',
                    `price_monthly` = 300000.00,
                    `price_quarterly` = 800000.00,
                    `price_yearly` = 3500000.00,
                    `price_lifetime` = 7000000.00
                    WHERE `id` = 1 OR `slug` = 'lushlogin' OR `slug` = 'vmaxlogin'");
                
                // Update product 2 (V-AutoPost)
                $this->pdo->exec("UPDATE `products` SET 
                    `name` = 'V-AutoPost',
                    `slug` = 'v-autopost',
                    `price_monthly` = 300000.00,
                    `price_quarterly` = 800000.00,
                    `price_yearly` = 3500000.00,
                    `price_lifetime` = 7000000.00
                    WHERE `slug` = 'v-autopost'");
            } catch (PDOException $e) {}
            try {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM `products` WHERE `name` = ?");
                $stmt->execute(['V-ShopeeBoost Pro']);
                if ($stmt->fetchColumn() == 0) {
                    $this->pdo->prepare("INSERT INTO `products` (`name`, `slug`, `short_description`, `description`, `price_monthly`, `price_quarterly`, `price_yearly`, `price_lifetime`, `status`, `show_in_menu`, `is_combo`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
                        ->execute([
                            'V-ShopeeBoost Pro',
                            'v-shopeeboost-pro',
                            'Phần mềm tăng tương tác Shopee tự động chuyên nghiệp.',
                            'Tự động tăng tym, theo dõi, đẩy sản phẩm Shopee thông minh.',
                            200000.00,
                            500000.00,
                            1800000.00,
                            3900000.00,
                            'active',
                            1,
                            0,
                            time()
                        ]);
                }
            } catch (PDOException $e) {}
            try {
                $this->pdo->exec("UPDATE `products` SET 
                    `price_monthly` = 300000.00,
                    `price_quarterly` = 800000.00,
                    `price_yearly` = 3500000.00,
                    `price_lifetime` = 7000000.00
                    WHERE `slug` = 'v-shopeeboost-pro'");
            } catch (PDOException $e) {}
            try {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM `products` WHERE `name` = ?");
                $stmt->execute(['V-ShopeeBoost']);
                if ($stmt->fetchColumn() == 0) {
                    $this->pdo->prepare("INSERT INTO `products` (`name`, `slug`, `short_description`, `description`, `price_monthly`, `price_quarterly`, `price_yearly`, `price_lifetime`, `status`, `show_in_menu`, `is_combo`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
                        ->execute([
                            'V-ShopeeBoost',
                            'v-shopeeboost',
                            'Phần mềm Shopee Boost tự động.',
                            'Hỗ trợ boost sản phẩm Shopee tự động.',
                            200000.00,
                            500000.00,
                            1800000.00,
                            3900000.00,
                            'active',
                            1,
                            0,
                            time()
                        ]);
                }
            } catch (PDOException $e) {}
        } catch (PDOException $e) {
            die("Database Connection Failure: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    // Helper method to execute queries quickly
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
