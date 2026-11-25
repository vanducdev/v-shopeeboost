<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản Phẩm & Giải Pháp Tự Động Hóa - VMAX Software</title>
    <meta name="description" content="Danh mục phần mềm Antidetect Browser, nuôi nick Facebook đa luồng và tự động hóa Shopee hàng đầu Việt Nam của VMAX Software.">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }
        .card-surface {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.85);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
        }
        .filter-pill {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .filter-pill.active {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            border-color: #2563eb;
        }
        .product-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.08), 0 8px 16px -6px rgba(15, 23, 42, 0.04);
            border-color: #93c5fd;
        }
        .hero-pattern {
            background-image: radial-gradient(rgba(37, 99, 235, 0.06) 1.5px, transparent 1.5px);
            background-size: 24px 24px;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-blue-600 selection:text-white flex flex-col min-h-screen">

    <!-- Global Top Navigation Bar -->
    <?php include BASE_PATH . '/views/layout/navbar.php'; ?>

    <!-- HERO SECTION -->
    <header class="relative pt-12 pb-14 px-4 sm:px-6 lg:px-8 border-b border-slate-200/70 hero-pattern bg-white/70 overflow-hidden">
        <!-- Soft Ambient Background Gradients -->
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[720px] h-[360px] bg-gradient-to-tr from-blue-500/10 via-indigo-500/10 to-teal-400/10 blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-6xl mx-auto text-center relative z-10">
            <!-- Breadcrumbs / Tag -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-blue-50/90 border border-blue-200/80 rounded-full text-blue-700 text-xs font-black tracking-wider uppercase mb-5 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                <span>Hệ Sinh Thái Tự Động Hóa VMAX 2026</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-4">
                Kho Giải Pháp Phần Mềm <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 bg-clip-text text-transparent">Antidetect & Marketing</span> Đỉnh Cao
            </h1>

            <!-- Subtitle -->
            <p class="text-slate-600 max-w-3xl mx-auto text-sm sm:text-base leading-relaxed font-normal mb-8">
                Tập hợp các bộ công cụ trình duyệt chống quét vân tay, tự động hóa nuôi nick đa luồng và tối ưu traffic sàn TMĐT. Chuẩn công nghệ quốc tế, vận hành ổn định và chống checkpoint 24/7.
            </p>

            <!-- 4 Trust Metric Badges -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 max-w-4xl mx-auto pt-6 border-t border-slate-200/60">
                <div class="p-3 bg-white/80 rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="text-xl sm:text-2xl font-black text-blue-600">6+</div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Phần Mềm Chuyên Sâu</div>
                </div>
                <div class="p-3 bg-white/80 rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="text-xl sm:text-2xl font-black text-indigo-600">15.000+</div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Doanh Nghiệp Tin Dùng</div>
                </div>
                <div class="p-3 bg-white/80 rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="text-xl sm:text-2xl font-black text-emerald-600">99.98%</div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Uptime Ổn Định 24/7</div>
                </div>
                <div class="p-3 bg-white/80 rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="text-xl sm:text-2xl font-black text-amber-600">10 Giây</div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Kích Hoạt VietQR Napas</div>
                </div>
            </div>
        </div>
    </header>

    <?php
    // Prepare standardized products list
    $productsList = [];
    $categoryCounts = [
        'all' => count($products),
        'browser' => 0,
        'facebook' => 0,
        'shopee' => 0,
        'combo' => 0,
    ];

    foreach ($products as $prod) {
        $slug = $prod['slug'];
        $category = 'single';
        $badge = 'Phần mềm lẻ';
        $tag = 'Mới Ra Mắt';
        $platform = 'Win 10/11 64-bit';
        $features = [];
        $imageUrl = $prod['image_url'] ?? '';

        if ($slug === 'vmaxlogin' || $slug === 'lushlogin') {
            $category = 'browser';
            $badge = 'Antidetect Browser';
            $tag = 'Hot Nhất 2026';
            $imageUrl = 'image/logologin.webp';
            $platform = 'Win 10/11 64-bit • macOS';
            $features = [
                'Tạo cấu hình vân tay trình duyệt cô lập riêng biệt 100%',
                'Fake Canvas, WebGL, AudioContext, WebRTC không thể phát hiện',
                'Hỗ trợ tích hợp mọi Proxy (IPv4, IPv6, SOCKS5, TMProxy)',
                'API & CLI điều khiển tự động hóa (Puppeteer, Selenium)',
                'Đồng bộ đám mây (Cloud Sync) profile và Cookie siêu tốc'
            ];
            $categoryCounts['browser']++;
        } elseif ($slug === 'v-autopost') {
            $category = 'facebook';
            $badge = 'Facebook Automation';
            $tag = 'Bán Chạy Nhất';
            $imageUrl = 'image/logofb.webp';
            $platform = 'Win 10/11 64-bit';
            $features = [
                'Nuôi hàng nghìn nick Facebook không lo checkpoint chéo',
                'Đăng bài tự động hàng loạt lên Fanpage, Group, trang cá nhân',
                'Tương tác Reels, lướt Newsfeed, like share bài tự nhiên',
                'Quản lý kịch bản tương tác đa luồng kéo-thả chuyên nghiệp',
                'Tự động gửi tin nhắn và chăm sóc khách hàng mục tiêu'
            ];
            $categoryCounts['facebook']++;
        } elseif ($slug === 'combo-marketing-facebook') {
            $category = 'combo';
            $badge = 'Combo Tiết Kiệm';
            $tag = 'Tiết Kiệm 45%';
            $imageUrl = 'image/sanphamfb.webp';
            $platform = 'Win 10/11 64-bit';
            $features = [
                'Trọn bộ VmaxLogin Browser + V-AutoPost Facebook Multi-Thread',
                'Xây dựng hệ thống bán hàng và nuôi nick tự động chuyên nghiệp',
                'Tặng kèm kho kịch bản nuôi nick 2026 độc quyền từ VMAX',
                'Hỗ trợ cài đặt và tối ưu kịch bản 1-1 qua UltraView',
                'Tiết kiệm 45% chi phí so với mua lẻ từng phần mềm'
            ];
            $categoryCounts['combo']++;
        } elseif ($slug === 'combo-phu-quy') {
            $category = 'combo';
            $badge = 'Combo Toàn Diện';
            $tag = 'Doanh Nghiệp VIP';
            $imageUrl = 'image/sanphamsp.webp';
            $platform = 'Win 10/11 64-bit';
            $features = [
                'Sở hữu trọn bộ 4 phần mềm mạnh mẽ nhất của VMAX Software',
                'Đầy đủ giải pháp marketing đa nền tảng tối tân (FB + Shopee)',
                'Cập nhật tự động trọn đời không phát sinh bất kỳ phụ phí nào',
                'Ưu tiên kết nối hỗ trợ kỹ thuật VIP 24/7 qua Hotline/Zalo',
                'Tiết kiệm hơn 60% chi phí đầu tư công cụ cho doanh nghiệp'
            ];
            $categoryCounts['combo']++;
        } elseif (strpos($slug, 'shopee') !== false) {
            $category = 'shopee';
            $badge = ($slug === 'v-shopeeboost-pro') ? 'Shopee Multi-Store' : 'Shopee Boost';
            $tag = ($slug === 'v-shopeeboost-pro') ? 'Dành Cho Pro Seller' : 'Tăng Trưởng Nhanh';
            $imageUrl = 'image/logosp.webp';
            $platform = 'Win 10/11 64-bit';
            $features = [
                'Tự động tăng lượt thích, theo dõi gian hàng Shopee chéo',
                'Tự động đẩy sản phẩm Shopee theo chu kỳ giờ vàng thông minh',
                'Tăng điểm hiển thị tự nhiên của gian hàng (SEO Shopee)',
                'Quản lý đa gian hàng Shopee tập trung không sợ bị phạt',
                'Tối ưu hóa tỷ lệ chuyển đổi, bứt phá doanh số bán lẻ'
            ];
            $categoryCounts['shopee']++;
        } else {
            $category = 'single';
            $badge = 'Công cụ tự động hóa';
            $tag = 'Tiện Ích 2026';
            $platform = 'Win 10/11 64-bit';
            $features = [
                'Tính năng tự động hóa thông minh và hiệu suất cao',
                'Hỗ trợ cập nhật các phiên bản mới miễn phí trọn đời',
                'Bảo mật thông tin dữ liệu người dùng tuyệt đối',
                'Hỗ trợ kỹ thuật chuyên nghiệp, tận tâm 24/7'
            ];
        }

        $productsList[] = array_merge($prod, [
            'category' => $category,
            'badge' => $badge,
            'tag' => $tag,
            'platform' => $platform,
            'features' => $features,
            'image_url' => $imageUrl
        ]);
    }
    ?>

    <!-- INTERACTIVE FILTER & SEARCH BAR -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">
        <div class="card-surface rounded-2xl p-3 sm:p-4 shadow-md bg-white">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 sm:gap-4">
                
                <!-- Category Tabs -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 lg:pb-0 scrollbar-none text-xs font-bold">
                    <button type="button" onclick="filterCategory('all')" id="btn-cat-all" class="filter-pill active px-3.5 py-2 rounded-xl border border-transparent whitespace-nowrap flex items-center gap-1.5">
                        <i class="fa-solid fa-shapes text-[11px]"></i>
                        <span>Tất Cả</span>
                        <span class="text-[10px] bg-white/20 text-white px-1.5 py-0.2 rounded-md"><?= $categoryCounts['all'] ?></span>
                    </button>

                    <button type="button" onclick="filterCategory('browser')" id="btn-cat-browser" class="filter-pill bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 rounded-xl border border-transparent whitespace-nowrap flex items-center gap-1.5">
                        <i class="fa-solid fa-window-maximize text-[11px] text-blue-600"></i>
                        <span>Antidetect Browser</span>
                        <span class="text-[10px] bg-slate-200 text-slate-600 px-1.5 py-0.2 rounded-md"><?= $categoryCounts['browser'] ?></span>
                    </button>

                    <button type="button" onclick="filterCategory('facebook')" id="btn-cat-facebook" class="filter-pill bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 rounded-xl border border-transparent whitespace-nowrap flex items-center gap-1.5">
                        <i class="fa-brands fa-facebook text-[11px] text-blue-600"></i>
                        <span>Facebook Marketing</span>
                        <span class="text-[10px] bg-slate-200 text-slate-600 px-1.5 py-0.2 rounded-md"><?= $categoryCounts['facebook'] ?></span>
                    </button>

                    <button type="button" onclick="filterCategory('shopee')" id="btn-cat-shopee" class="filter-pill bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 rounded-xl border border-transparent whitespace-nowrap flex items-center gap-1.5">
                        <i class="fa-solid fa-bag-shopping text-[11px] text-orange-600"></i>
                        <span>Shopee Automation</span>
                        <span class="text-[10px] bg-slate-200 text-slate-600 px-1.5 py-0.2 rounded-md"><?= $categoryCounts['shopee'] ?></span>
                    </button>

                    <button type="button" onclick="filterCategory('combo')" id="btn-cat-combo" class="filter-pill bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 rounded-xl border border-transparent whitespace-nowrap flex items-center gap-1.5">
                        <i class="fa-solid fa-box-open text-[11px] text-purple-600"></i>
                        <span>Combo Tiết Kiệm</span>
                        <span class="text-[10px] bg-slate-200 text-slate-600 px-1.5 py-0.2 rounded-md"><?= $categoryCounts['combo'] ?></span>
                    </button>
                </div>

                <!-- Live Search Box -->
                <div class="relative w-full lg:w-72 shrink-0">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" id="productSearchInput" oninput="searchProducts(this.value)" placeholder="Tìm kiếm phần mềm, tính năng..."
                        class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none text-xs font-semibold text-slate-800 transition shadow-inner">
                    <button type="button" onclick="clearSearch()" id="clearSearchBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 hidden text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

            </div>
        </div>
    </section>

    <!-- MAIN PRODUCT CATALOGUE GRID -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-1">
        
        <!-- Results Counter & Info Bar -->
        <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-200/60">
            <div class="text-xs font-bold text-slate-500">
                Hiển thị <span id="visible-count" class="text-blue-600 font-extrabold"><?= count($productsList) ?></span> / <?= count($productsList) ?> phần mềm
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                <span>Hỗ trợ kỹ thuật:</span>
                <span class="text-emerald-600 flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    UltraView 1-1 Sẵn Sàng
                </span>
            </div>
        </div>

        <!-- Cards Grid (3 columns) -->
        <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7 items-stretch">
            <?php foreach ($productsList as $p): ?>
                <?php
                $pJson = htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8');
                $priceMonth = number_format($p['price_monthly'], 0, ',', '.');
                $priceYear = number_format($p['price_yearly'], 0, ',', '.');
                $priceLife = number_format($p['price_lifetime'], 0, ',', '.');
                ?>
                <div class="product-card card-surface rounded-3xl p-6 flex flex-col justify-between relative group overflow-hidden" 
                     data-category="<?= $p['category'] ?>"
                     data-name="<?= htmlspecialchars(strtolower($p['name'])) ?>"
                     data-desc="<?= htmlspecialchars(strtolower($p['short_description'] . ' ' . $p['description'])) ?>">
                    
                    <div>
                        <!-- Top Header Badges -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-[10px] font-black rounded-full uppercase tracking-wider">
                                <?= $p['badge'] ?>
                            </span>
                            <span class="px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-700 text-[10px] font-black rounded-full">
                                <i class="fa-solid fa-star text-amber-500 text-[9px] mr-0.5"></i>
                                <?= $p['tag'] ?>
                            </span>
                        </div>

                        <!-- Product Visual Artwork -->
                        <div class="relative w-full h-44 rounded-2xl overflow-hidden bg-gradient-to-b from-slate-50 to-slate-100 border border-slate-200/80 mb-5 flex items-center justify-center p-4 group-hover:border-blue-300 transition-colors">
                            <!-- Background Aura Glow -->
                            <div class="absolute inset-0 bg-blue-500/5 group-hover:bg-blue-500/10 transition-colors pointer-events-none"></div>

                            <?php if (!empty($p['image_url'])): ?>
                                <img src="/<?= ltrim(htmlspecialchars($p['image_url']), '/') ?>" alt="<?= htmlspecialchars($p['name']) ?>"
                                     class="max-h-36 max-w-full object-contain filter drop-shadow-md group-hover:scale-105 transition-transform duration-300">
                            <?php else: ?>
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-2xl shadow-md uppercase">
                                    <?= substr($p['name'], 0, 2) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Platform Chip at bottom right of image -->
                            <div class="absolute bottom-2 right-2 px-2 py-0.5 bg-white/90 backdrop-blur-xs border border-slate-200/80 rounded-md text-[9px] font-bold text-slate-600 flex items-center gap-1 shadow-xs">
                                <i class="fa-brands fa-windows text-blue-600 text-[9px]"></i>
                                <span><?= $p['platform'] ?></span>
                            </div>
                        </div>

                        <!-- Title & Description -->
                        <h3 class="text-lg font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-tight mb-2">
                            <?= htmlspecialchars($p['name']) ?>
                        </h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed line-clamp-2 mb-4">
                            <?= htmlspecialchars($p['short_description'] ?: $p['description']) ?>
                        </p>

                        <!-- Key Capabilities Bullet List -->
                        <ul class="space-y-2 mb-6 border-t border-slate-100 pt-4">
                            <?php foreach (array_slice($p['features'], 0, 3) as $feat): ?>
                                <li class="flex items-start gap-2 text-xs text-slate-600 font-medium">
                                    <i class="fa-solid fa-circle-check text-emerald-500 text-[11px] mt-0.5 shrink-0"></i>
                                    <span class="leading-snug"><?= htmlspecialchars($feat) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Bottom Pricing & Action Section -->
                    <div class="border-t border-slate-100 pt-4 mt-auto">
                        <!-- Price Details -->
                        <div class="flex items-baseline justify-between mb-3.5">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Gói Khởi Điểm</span>
                                <div class="text-lg font-black text-slate-900 tracking-tight">
                                    <?= $priceMonth ?>đ <span class="text-xs font-semibold text-slate-400">/tháng</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                                    Trọn đời: <?= $priceLife ?>đ
                                </span>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="space-y-2">
                            <button type="button" onclick="openProductModal('<?= $pJson ?>')" 
                                    class="w-full py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl text-xs transition shadow-md shadow-blue-500/20 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-credit-card text-xs"></i>
                                <span>Xem Bảng Giá & Chi Tiết</span>
                            </button>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="/product/<?= htmlspecialchars($p['slug']) ?>" 
                                   class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-center text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-circle-info text-[11px] text-slate-500"></i>
                                    <span>Tính Năng</span>
                                </a>
                                <a href="/downloads" 
                                   class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-center text-xs transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-download text-[11px] text-blue-600"></i>
                                    <span>Tải Bộ Cài</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Fallback -->
        <div id="no-results" class="card-surface rounded-3xl p-12 text-center my-8 hidden">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h4 class="text-base font-bold text-slate-800 mb-1">Không tìm thấy phần mềm phù hợp</h4>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4">Vui lòng thử tìm kiếm bằng từ khóa khác hoặc bấm hiển thị tất cả giải pháp.</p>
            <button type="button" onclick="filterCategory('all'); clearSearch();" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-xl text-xs">
                Hiển Thị Tất Cả
            </button>
        </div>

    </main>

    <!-- TRUST & VALUE PROPOSITION BANNER -->
    <section class="bg-white border-t border-slate-200/70 py-12 px-4 sm:px-6 lg:px-8 mt-12">
        <div class="max-w-6xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-blue-600 text-xs font-black uppercase tracking-wider">Cam Kết Từ VMAX Software</span>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight mt-1">Vì Sao Hơn 15.000 Khách Hàng Chọn VMAX?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Col 1 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-base mb-3.5">
                        <i class="fa-solid fa-fingerprint"></i>
                    </div>
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-1.5">Vân Tay Độc Lập</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                        Tự động biến đổi Canvas, WebGL, AudioContext và WebRTC chuẩn gốc. Chống checkpoint tuyệt đối.
                    </p>
                </div>

                <!-- Col 2 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-base mb-3.5">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-1.5">Kích Hoạt Tức Thì</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                        Thanh toán quét mã VietQR Napas247, hệ thống nhận diện và cấp mã bản quyền tự động sau 10 giây.
                    </p>
                </div>

                <!-- Col 3 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-base mb-3.5">
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-1.5">Hỗ Trợ UltraView 24/7</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                        Đội ngũ kỹ thuật viên giàu kinh nghiệm đồng hành, cài đặt và khắc phục lỗi trực tiếp trên máy của bạn.
                    </p>
                </div>

                <!-- Col 4 -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-base mb-3.5">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </div>
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-1.5">Cập Nhật Trọn Đời</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                        Tự động update Chromium mới nhất và bám sát mọi biến động thuật toán từ Facebook, Shopee miễn phí.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION (Accordion) -->
    <section class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
        <div class="text-center mb-8">
            <span class="text-blue-600 text-xs font-black uppercase tracking-wider">Giải Đáp Thắc Mắc</span>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight mt-1">Câu Hỏi Thường Gặp Khi Mua Phần Mềm</h2>
        </div>

        <div class="space-y-3">
            <details class="group card-surface rounded-2xl p-4 cursor-pointer transition">
                <summary class="font-bold text-xs sm:text-sm text-slate-900 flex items-center justify-between list-none">
                    <span>1. Tôi có thể dùng thử phần mềm trước khi mua bản quyền không?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 group-open:rotate-180 transition-transform"></i>
                </summary>
                <p class="text-xs text-slate-600 mt-3 pt-3 border-t border-slate-100 leading-relaxed">
                    Có! Bạn có thể tải bản cài đặt dùng thử tại trang <a href="/downloads" class="text-blue-600 font-bold hover:underline">Tải Về</a> để trải nghiệm các tính năng cốt lõi trước khi nâng cấp key bản quyền chính thức.
                </p>
            </details>

            <details class="group card-surface rounded-2xl p-4 cursor-pointer transition">
                <summary class="font-bold text-xs sm:text-sm text-slate-900 flex items-center justify-between list-none">
                    <span>2. Khi tôi cài lại Windows hoặc đổi sang máy tính khác thì có mất bản quyền không?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 group-open:rotate-180 transition-transform"></i>
                </summary>
                <p class="text-xs text-slate-600 mt-3 pt-3 border-t border-slate-100 leading-relaxed">
                    Hoàn toàn không! VMAX hỗ trợ tính năng <strong>Reset Khóa Phần Cứng (HWID)</strong> trực tiếp trong trang quản trị cá nhân (<a href="/devices" class="text-blue-600 font-bold hover:underline">Thiết Bị Liên Kết</a>), giúp bạn chuyển bản quyền sang máy tính mới trong vòng 1 giây.
                </p>
            </details>

            <details class="group card-surface rounded-2xl p-4 cursor-pointer transition">
                <summary class="font-bold text-xs sm:text-sm text-slate-900 flex items-center justify-between list-none">
                    <span>3. VmaxLogin Antidetect Browser có gì vượt trội hơn phần mềm nước ngoài?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 group-open:rotate-180 transition-transform"></i>
                </summary>
                <p class="text-xs text-slate-600 mt-3 pt-3 border-t border-slate-100 leading-relaxed">
                    VmaxLogin được tối ưu hóa đặc biệt cho hạ tầng mạng và thói quen làm việc của người dùng Việt Nam. Hỗ trợ Proxy nội địa (TMProxy, Tinsoft...), giao diện 100% tiếng Việt, chi phí rẻ hơn 50% so với Gologin/Adspower và đội ngũ hỗ trợ kỹ thuật trực tiếp qua UltraView/Zalo.
                </p>
            </details>

            <details class="group card-surface rounded-2xl p-4 cursor-pointer transition">
                <summary class="font-bold text-xs sm:text-sm text-slate-900 flex items-center justify-between list-none">
                    <span>4. Hình thức thanh toán và thời gian nhận mã bản quyền như thế nào?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 group-open:rotate-180 transition-transform"></i>
                </summary>
                <p class="text-xs text-slate-600 mt-3 pt-3 border-t border-slate-100 leading-relaxed">
                    Bạn có thể nạp tiền tự động qua VietQR Napas247 từ bất kỳ ứng dụng ngân hàng nào. Khi giao dịch hoàn tất, tiền sẽ vào ví trong 10 giây và bạn có thể kích hoạt key ngay lập tức.
                </p>
            </details>
        </div>
    </section>

    <!-- INTERACTIVE PRICING & QUICK VIEW MODAL -->
    <div id="product-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300">
        <div class="card-surface rounded-3xl max-w-2xl w-full max-h-[92vh] overflow-y-auto p-6 sm:p-7 relative shadow-2xl transition-transform duration-300 scale-95" id="modal-content-wrapper">
            
            <!-- Close Button -->
            <button onclick="closeProductModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <!-- Modal Dynamic Content -->
            <div id="modal-body-content"></div>
        </div>
    </div>

    <!-- UNIVERSAL FOOTER -->
    <footer class="bg-slate-900 text-slate-400 text-xs py-10 px-4 sm:px-6 border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <div class="flex items-center gap-3 justify-center md:justify-start">
                <img src="/image/logo-app-vmax-64x.webp" alt="VMAX Logo" class="h-7 w-auto object-contain brightness-0 invert">
                <span class="font-extrabold text-white text-sm">VMAX Software Solutions</span>
            </div>
            <div>
                &copy; <?= date('Y') ?> VMAX Technology Platform. Bản quyền được bảo lưu toàn diện.
            </div>
            <div class="flex items-center gap-4 justify-center">
                <a href="/products" class="hover:text-white transition">Sản Phẩm</a>
                <a href="/pricing" class="hover:text-white transition">Bảng Giá</a>
                <a href="/downloads" class="hover:text-white transition">Tải Về</a>
                <a href="/support" class="hover:text-white transition">Hỗ Trợ 24/7</a>
            </div>
        </div>
    </footer>

    <!-- Contact Button Widget -->
    <?php require BASE_PATH . '/views/layout/contact_button.php'; ?>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        let currentFilter = 'all';

        // Filter products by category
        function filterCategory(category) {
            currentFilter = category;

            // Reset active pills
            document.querySelectorAll('.filter-pill').forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                btn.classList.add('bg-slate-100', 'text-slate-700');
            });

            // Activate button
            const activeBtn = document.getElementById('btn-cat-' + category);
            if (activeBtn) {
                activeBtn.classList.remove('bg-slate-100', 'text-slate-700');
                activeBtn.classList.add('active');
            }

            applyFilters();
        }

        // Live Search Filter
        function searchProducts(query) {
            const clearBtn = document.getElementById('clearSearchBtn');
            if (query.trim().length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
            applyFilters();
        }

        function clearSearch() {
            const input = document.getElementById('productSearchInput');
            input.value = '';
            document.getElementById('clearSearchBtn').classList.add('hidden');
            applyFilters();
        }

        // Combined filter execution
        function applyFilters() {
            const query = (document.getElementById('productSearchInput').value || '').trim().toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const cardCat = card.getAttribute('data-category');
                const cardName = card.getAttribute('data-name') || '';
                const cardDesc = card.getAttribute('data-desc') || '';

                const matchesCat = (currentFilter === 'all') || (cardCat === currentFilter);
                const matchesQuery = (query === '') || cardName.includes(query) || cardDesc.includes(query);

                if (matchesCat && matchesQuery) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            // Update visible counter
            document.getElementById('visible-count').textContent = visibleCount;

            // Show or hide No Results card
            const noResults = document.getElementById('no-results');
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        // Interactive Modal
        function openProductModal(prodJson) {
            const prod = typeof prodJson === 'string' ? JSON.parse(prodJson) : prodJson;
            const modal = document.getElementById('product-modal');
            const wrapper = document.getElementById('modal-content-wrapper');
            const body = document.getElementById('modal-body-content');

            const pM = new Intl.NumberFormat('vi-VN').format(prod.price_monthly) + 'đ';
            const pQ = new Intl.NumberFormat('vi-VN').format(prod.price_quarterly) + 'đ';
            const pY = new Intl.NumberFormat('vi-VN').format(prod.price_yearly) + 'đ';
            const pL = new Intl.NumberFormat('vi-VN').format(prod.price_lifetime) + 'đ';

            let featListHtml = '';
            prod.features.forEach(f => {
                featListHtml += `
                    <li class="flex items-start gap-2 text-xs text-slate-700">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-xs mt-0.5 shrink-0"></i>
                        <span>${f}</span>
                    </li>
                `;
            });

            let imgHtml = '';
            if (prod.image_url) {
                imgHtml = `<img src="/${prod.image_url.replace(/^\//, '')}" alt="${prod.name}" class="max-h-28 max-w-full object-contain filter drop-shadow">`;
            } else {
                imgHtml = `<div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-xl uppercase">${prod.name.substring(0, 2)}</div>`;
            }

            body.innerHTML = `
                <div class="flex items-center gap-4 pb-5 border-b border-slate-100">
                    <div class="w-24 h-24 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center p-2 shrink-0">
                        ${imgHtml}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-md uppercase">${prod.badge}</span>
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-md">${prod.tag}</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 leading-tight">${prod.name}</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">${prod.platform}</p>
                    </div>
                </div>

                <div class="py-4 space-y-4">
                    <div>
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2">Tính Năng Cốt Lõi</h4>
                        <ul class="space-y-1.5 bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                            ${featListHtml}
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2">Chọn Gói Thời Hạn Bản Quyền</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            
                            <label class="p-3 rounded-xl border-2 border-slate-200 bg-white hover:border-blue-500 cursor-pointer text-center transition flex flex-col justify-between duration-card active-plan" onclick="selectPlanCard(this, '${pM}')">
                                <input type="radio" name="plan_period" value="monthly" checked class="hidden">
                                <div class="text-[10px] font-bold text-slate-500 uppercase">1 Tháng</div>
                                <div class="text-xs font-black text-slate-900 my-1">${pM}</div>
                                <span class="text-[9px] text-slate-400">Gói dùng thử</span>
                            </label>

                            <label class="p-3 rounded-xl border-2 border-slate-200 bg-white hover:border-blue-500 cursor-pointer text-center transition flex flex-col justify-between duration-card" onclick="selectPlanCard(this, '${pQ}')">
                                <input type="radio" name="plan_period" value="quarterly" class="hidden">
                                <div class="text-[10px] font-bold text-slate-500 uppercase">3 Tháng</div>
                                <div class="text-xs font-black text-slate-900 my-1">${pQ}</div>
                                <span class="text-[9px] text-blue-600 font-bold">-15% Tiết kiệm</span>
                            </label>

                            <label class="p-3 rounded-xl border-2 border-slate-200 bg-white hover:border-blue-500 cursor-pointer text-center transition flex flex-col justify-between duration-card" onclick="selectPlanCard(this, '${pY}')">
                                <input type="radio" name="plan_period" value="yearly" class="hidden">
                                <div class="text-[10px] font-bold text-slate-500 uppercase">1 Năm</div>
                                <div class="text-xs font-black text-slate-900 my-1">${pY}</div>
                                <span class="text-[9px] text-emerald-600 font-bold">-35% Tiết kiệm</span>
                            </label>

                            <label class="p-3 rounded-xl border-2 border-slate-200 bg-white hover:border-blue-500 cursor-pointer text-center transition flex flex-col justify-between duration-card" onclick="selectPlanCard(this, '${pL}')">
                                <input type="radio" name="plan_period" value="lifetime" class="hidden">
                                <div class="text-[10px] font-bold text-purple-700 uppercase">Trọn Đời</div>
                                <div class="text-xs font-black text-purple-700 my-1">${pL}</div>
                                <span class="text-[9px] text-purple-600 font-bold">Hot Deal VIP</span>
                            </label>

                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                    <a href="/renew" class="w-full sm:flex-1 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl text-xs text-center shadow-lg shadow-blue-500/20 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-xs"></i>
                        <span>Kích Hoạt Bản Quyền Ngay</span>
                    </a>
                    <a href="/product/${prod.slug}" class="w-full sm:w-auto px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs text-center transition">
                        Chi Tiết Đầy Đủ
                    </a>
                </div>
            `;

            // Style default active card
            modal.classList.remove('hidden');
            setTimeout(() => {
                wrapper.classList.remove('scale-95');
                wrapper.classList.add('scale-100');
                const firstCard = body.querySelector('.duration-card');
                if (firstCard) selectPlanCard(firstCard);
            }, 10);
        }

        function selectPlanCard(cardEl) {
            document.querySelectorAll('.duration-card').forEach(c => {
                c.classList.remove('border-blue-600', 'bg-blue-50/40');
                c.classList.add('border-slate-200', 'bg-white');
            });
            cardEl.classList.remove('border-slate-200', 'bg-white');
            cardEl.classList.add('border-blue-600', 'bg-blue-50/40');
            const radio = cardEl.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        }

        function closeProductModal() {
            const modal = document.getElementById('product-modal');
            const wrapper = document.getElementById('modal-content-wrapper');
            wrapper.classList.remove('scale-100');
            wrapper.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // Close on click backdrop
        document.getElementById('product-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProductModal();
            }
        });
    </script>
</body>
</html>
