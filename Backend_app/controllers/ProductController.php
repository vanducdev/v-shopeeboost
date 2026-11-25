<?php
// VMAX Software - Product Public Page Controller

class ProductController {
    
    public function index() {
        $productModel = new ProductModel();
        $products = $productModel->getAllActiveProducts();
        
        view('products/index', [
            'products' => $products
        ]);
    }

    public function detail($slug) {
        $productModel = new ProductModel();
        $product = $productModel->findBySlug($slug);
        
        if (!$product) {
            http_response_code(404);
            echo "<h1>404 Product Not Found</h1><p>Sorry, the product you are looking for ('" . htmlspecialchars($slug) . "') could not be found.</p>";
            exit;
        }
        
        // Define default features based on product slug if empty in DB
        $features = [];
        if ($slug === 'lushlogin' || $slug === 'vmaxlogin') {
            $features = [
                'Tạo cấu hình vân tay trình duyệt riêng biệt hoàn toàn',
                'Hỗ trợ API, CLI điều khiển từ xa tự động',
                'Đồng bộ đám mây (Cloud sync) nhanh chóng, bảo mật',
                'Tránh checkpoint chéo tài khoản quảng cáo, tài khoản shop'
            ];
        } elseif ($slug === 'v-autopost') {
            $features = [
                'Đăng bài tự động hàng loạt lên Fanpage, Group, trang cá nhân',
                'Tự động tương tác, seeding bài viết tự nhiên như người dùng thật',
                'Quản lý kịch bản tương tác đa luồng chuyên nghiệp',
                'Tự động gửi tin nhắn, chăm sóc khách hàng mục tiêu'
            ];
        } elseif ($slug === 'combo-marketing-facebook') {
            $features = [
                'Trọn bộ VmaxLogin Browser + V-AutoPost + Seeding nâng cao',
                'Xây dựng hệ thống bán hàng tự động chuyên nghiệp',
                'Tiết kiệm 45% chi phí so với mua lẻ'
            ];
        } elseif ($slug === 'combo-phu-quy') {
            $features = [
                'Sở hữu toàn bộ danh mục phần mềm của VMAX Software',
                'Đầy đủ tính năng, giải pháp marketing đa nền tảng tối tân',
                'Tiết kiệm chi phí tối đa cho doanh nghiệp'
            ];
        } elseif (strpos($slug, 'shopee') !== false) {
            $features = [
                'Tự động tăng lượt thích, theo dõi sản phẩm Shopee',
                'Tự động đẩy sản phẩm Shopee theo chu kỳ thông minh',
                'Tăng điểm hiển thị tự nhiên của gian hàng, thu hút khách hàng',
                'Tối ưu hóa chuyển đổi, bứt phá doanh số bán hàng'
            ];
        } else {
            // General default features if not specified
            $features = [
                'Tính năng tự động hóa thông minh và hiệu suất cao',
                'Hỗ trợ cập nhật các phiên bản mới miễn phí trọn đời',
                'Bảo mật thông tin dữ liệu người dùng tuyệt đối',
                'Hỗ trợ kỹ thuật chuyên nghiệp, tận tâm 24/7'
            ];
        }
        
        view('products/detail', [
            'product' => $product,
            'features' => $features
        ]);
    }
}
