<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

try {
    $db = DB::getInstance()->getConnection();
    
    // Get all tables
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Drop all tables
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");
    foreach ($tables as $table) {
        $db->exec("DROP TABLE `$table`");
        echo "Đã xóa bảng cũ: <strong>$table</strong><br>";
    }
    $db->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Read and execute database.sql
    $sqlFile = __DIR__ . '/database.sql';
    if (!file_exists($sqlFile)) {
        die("Không tìm thấy tệp database.sql tại: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Execute SQL content
    $db->exec($sql);
    echo "<h3 style='color: green; margin-top: 20px;'>Nạp lại cơ sở dữ liệu VMAX mới thành công!</h3>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Lỗi khi reset cơ sở dữ liệu: " . $e->getMessage() . "</h3>";
}
