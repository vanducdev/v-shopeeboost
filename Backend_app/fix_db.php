<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

try {
    $db = DB::getInstance()->getConnection();
    
    // Add balance to users if not exists
    $columns = $db->query("SHOW COLUMNS FROM users LIKE 'balance'")->fetchAll();
    if (empty($columns)) {
        $db->exec("ALTER TABLE users ADD balance DECIMAL(12, 2) DEFAULT 0.00");
        echo "<p style='color: green;'>Đã thêm cột balance vào bảng users.</p>";
    } else {
        echo "<p>Cột balance đã tồn tại trong bảng users.</p>";
    }
    
    // Create deposits table
    $db->exec("CREATE TABLE IF NOT EXISTS `deposits` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `amount` DECIMAL(12, 2) NOT NULL,
        `reference_code` VARCHAR(50) UNIQUE NOT NULL,
        `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        `created_at` INT NOT NULL,
        `updated_at` INT NOT NULL,
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<p style='color: green;'>Đã tạo hoặc xác nhận bảng deposits thành công.</p>";
    
    echo "<h3 style='color: green;'>Sửa cấu trúc Database thành công!</h3>";
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Lỗi khi sửa Database: " . $e->getMessage() . "</h3>";
}
