<?php
require 'config/config.php';
require 'config/database.php';
require 'models/LicenseModel.php';

// Tự động tìm product ID của V-ShopeeBoost
$db = DB::getInstance();
$stmt = $db->query("SELECT id FROM products WHERE name LIKE '%ShopeeBoost%' LIMIT 1");
$product = $stmt->fetch();
$productId = $product ? $product['id'] : 1;

// ID của admin (giả sử là 1)
$userId = 1;

$licenseModel = new LicenseModel();

// Tạo 4 key mặc định
$keys = [
    ['key' => 'VMAX-30D-' . strtoupper(uniqid()), 'type' => 'subscription', 'duration' => 30],
    ['key' => 'VMAX-90D-' . strtoupper(uniqid()), 'type' => 'subscription', 'duration' => 90],
    ['key' => 'VMAX-365D-' . strtoupper(uniqid()), 'type' => 'subscription', 'duration' => 365],
    ['key' => 'VMAX-LIFE-' . strtoupper(uniqid()), 'type' => 'lifetime', 'duration' => 0]
];

echo "Đã tạo thành công 4 mã Key kích hoạt mặc định:\n\n";

foreach ($keys as $k) {
    // createLicense($userId, $productId, $key, $type, $deviceLimit, $durationDays)
    $licenseModel->createLicense($userId, $productId, $k['key'], $k['type'], 5, $k['duration']);
    echo "- Gói " . ($k['duration'] ? $k['duration'] . " ngày" : "vĩnh viễn") . ": " . $k['key'] . "\n";
}
?>
