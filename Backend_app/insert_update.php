<?php
// VMAX Software - Script tự động cấu hình đăng ký phiên bản cập nhật mới trong database
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

try {
    $db = DB::getInstance();
    $conn = $db->getConnection();
    
    // Kiểm tra xem sản phẩm V-AutoPost (ID = 2) có tồn tại không
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = 2");
    $stmt->execute();
    $product = $stmt->fetch();
    
    if (!$product) {
        die("<h3 style='color: red;'>Lỗi: Không tìm thấy sản phẩm V-AutoPost (ID = 2) trong bảng products. Vui lòng nhập dữ liệu seed DB trước.</h3>");
    }
    
    $version = '4.3.4';
    $download_url = SITE_URL . '/assets/uploads/V-FBAutoPost-setup-4.3.4.exe';
    $release_notes = "1. [HỖ TRỢ ĐĂNG VIDEO] Bổ sung hỗ trợ đăng tải và upload video (MP4, MOV, AVI, MKV) bên cạnh hình ảnh ở cấu hình đăng bài Profile và tải tệp hàng loạt.\n2. [TRỢ LÝ SPINTAX] Tích hợp thanh công cụ chèn nhanh Spintax cùng tính năng đếm ký tự/nhóm theo thời gian thực.\n3. [TÁI CẤU TRÚC SIDEBAR DỌC] Chuyển đổi thanh menu ngang thành Cột Sidebar dọc 240px cố định bên góc trái, nâng cấp toàn bộ trải nghiệm điều hướng của ứng dụng.\n4. [HIỂN THỊ MENU TRẢI RỘNG] Loại bỏ menu con bay (flyout popup hover) thay bằng hiển thị trải rộng trực quan toàn bộ submenu thụt lề, tăng tốc độ truy cập danh mục kịch bản chỉ với 1 click.\n5. [MỸ THUẬT FLAT & WIDGET CAO CẤP] Loại bỏ toàn bộ viền hộp thô cứng, nâng cấp đồng hồ số tối giản và thẻ tài khoản dạng Widget lơ lửng (Floating Card) với hiệu ứng trỏ chuột nâng lên (scale/hover lift).\n6. [KHÔI PHỤC THEME ĐEN TRẮNG MẶC ĐỊNH] Đưa hệ thống màu sắc về giao diện mặc định đen trắng xám (Zinc Grayscale) sang trọng và đổi màu nhấn chủ đạo sang Xanh Dương Hoàng Gia (Royal Blue) truyền thống.";
    $size = '45 MB'; // Dung lượng ước lượng của bộ cài
    $created_at = time();
    
    // Thêm bản ghi download mới
    $insertStmt = $conn->prepare("
        INSERT INTO downloads (product_id, version, size, download_url, release_notes, created_at)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $insertStmt->execute([2, $version, $size, $download_url, $release_notes, $created_at]);
    
    echo "<h2 style='color: green;'>Thành công! Đã đăng ký phiên bản cập nhật 4.3.4 cho V-AutoPost trong database.</h2>";
    echo "<p><strong>Chi tiết cấu hình:</strong></p>";
    echo "<ul style='font-family: monospace; font-size: 13px;'>";
    echo "<li>Sản phẩm: V-AutoPost (ID = 2)</li>";
    echo "<li>Phiên bản đăng ký: <strong>$version</strong></li>";
    echo "<li>Đường dẫn tải gói cài đặt: <a href='$download_url' target='_blank'>$download_url</a></li>";
    echo "<li>Dung lượng hiển thị: $size</li>";
    echo "<li>Nội dung cập nhật: <pre style='background:#f4f4f4;padding:10px;border-radius:6px;white-space:pre-wrap;'>$release_notes</pre></li>";
    echo "</ul>";
    echo "<p style='color: red; font-weight: bold;'>⚠️ LƯU Ý BẢO MẬT: Vui lòng XÓA file <code>insert_update.php</code> này khỏi hosting/máy chủ của bạn ngay lập tức sau khi chạy thành công để tránh lỗ hổng bảo mật.</p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>Lỗi thực thi: " . $e->getMessage() . "</h2>";
}
