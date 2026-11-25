<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - VMAX Software</title>
    <meta name="description" content="<?= htmlspecialchars($product['short_description'] ?? '') ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
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
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">

    <!-- Global Navigation Bar -->
    <?php include BASE_PATH . '/views/layout/navbar.php'; ?>

    <?php
    $isLoggedIn = Auth::check();
    $buyUrl = $isLoggedIn ? '/renew' : '/login';
    ?>

    <!-- Main Detail Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 flex-1">
        
        <!-- Breadcrumbs Navigation -->
        <nav class="flex text-xs font-bold text-slate-400 gap-2 mb-6 items-center">
            <a href="/" class="hover:text-blue-600 transition flex items-center gap-1.5">
                <i class="fa-solid fa-house text-xs"></i>
                <span>Trang Chủ</span>
            </a>
            <span>/</span>
            <a href="/products" class="hover:text-blue-600 transition">Sản Phẩm & Giải Pháp</a>
            <span>/</span>
            <span class="text-slate-800 font-black"><?= htmlspecialchars($product['name']) ?></span>
        </nav>

        <div class="grid lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Product Showcase & Specs (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="card-surface rounded-3xl p-6 sm:p-7 text-center">
                    <!-- Artwork Box -->
                    <div class="relative w-full h-56 rounded-2xl overflow-hidden bg-gradient-to-b from-slate-50 to-slate-100 border border-slate-200/80 mb-6 flex items-center justify-center p-4">
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="/<?= ltrim(htmlspecialchars($product['image_url']), '/') ?>" alt="<?= htmlspecialchars($product['name']) ?>" 
                                 class="max-h-48 max-w-full object-contain filter drop-shadow-lg">
                        <?php else: ?>
                            <div class="w-24 h-24 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-3xl shadow-lg uppercase">
                                <?= substr($product['name'], 0, 2) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Title & Category Badges -->
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <span class="px-2.5 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-[10px] font-black rounded-full uppercase">
                            <?= $product['is_combo'] ? 'Combo Ưu Đãi' : 'Phần Mềm Độc Lập' ?>
                        </span>
                        <span class="px-2.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-black rounded-full">
                            <i class="fa-solid fa-shield-check text-[9px] mr-0.5"></i>
                            Bản Quyền Chính Hãng
                        </span>
                    </div>

                    <h1 class="text-2xl font-black text-slate-900 mb-1"><?= htmlspecialchars($product['name']) ?></h1>
                    <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto mb-6">
                        <?= htmlspecialchars($product['short_description'] ?? '') ?>
                    </p>

                    <!-- Software Technical Specs -->
                    <div class="border-t border-slate-100 pt-5 space-y-2.5 text-left text-xs">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Nền tảng hỗ trợ</span>
                            <span class="font-bold text-slate-800">Windows 10 / 11 (64-bit)</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Bảo vệ thiết bị</span>
                            <span class="font-bold text-slate-800">Khóa HWID (Đổi máy 1s)</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Cập nhật phiên bản</span>
                            <span class="font-bold text-emerald-600">Miễn phí trọn đời</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Hỗ trợ kỹ thuật</span>
                            <span class="font-bold text-blue-600">UltraView / Zalo 24/7</span>
                        </div>
                    </div>

                    <!-- Quick Trial Action Button -->
                    <div class="pt-5 border-t border-slate-100 flex gap-2">
                        <a href="/downloads" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-download text-xs text-blue-600"></i>
                            <span>Tải Bộ Cài Dùng Thử</span>
                        </a>
                        <a href="https://zalo.me" target="_blank" class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-xl text-xs transition flex items-center gap-1.5">
                            <i class="fa-solid fa-headset text-xs"></i>
                            <span>Tư Vấn Zalo</span>
                        </a>
                    </div>
                </div>

                <!-- Feature Checklist Card -->
                <div class="card-surface rounded-3xl p-6 sm:p-7 space-y-4">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-blue-600"></i>
                        <span>Tính Năng & Năng Lực Cốt Lõi</span>
                    </h3>

                    <ul class="space-y-3">
                        <?php foreach ($features as $feat): ?>
                            <li class="flex items-start gap-2.5 text-xs text-slate-700 font-medium leading-relaxed">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                                <span><?= htmlspecialchars($feat) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>

            <!-- Right Column: Overview & Pricing Matrix (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Detailed Introduction Card -->
                <div class="card-surface rounded-3xl p-6 sm:p-8">
                    <h2 class="text-lg font-black text-slate-900 tracking-tight pb-3 border-b border-slate-100 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-600 text-base"></i>
                        <span>Giới Thiệu Tổng Quan Sản Phẩm</span>
                    </h2>

                    <p class="text-slate-600 leading-relaxed text-sm mb-4">
                        <?= nl2br(htmlspecialchars($product['short_description'] ?? '')) ?>
                    </p>

                    <?php if (!empty($product['description'])): ?>
                        <div class="prose max-w-none text-slate-700 text-xs sm:text-sm leading-relaxed border-t border-slate-100 pt-4">
                            <?= $product['description'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pricing Packages Selection Card -->
                <div class="card-surface rounded-3xl p-6 sm:p-8">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-6">
                        <div>
                            <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                                <i class="fa-solid fa-tags text-blue-600 text-base"></i>
                                <span>Bảng Giá Bản Quyền Chính Thức</span>
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Chọn gói phù hợp và kích hoạt tự động tức thì qua VietQR Napas247.</p>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        
                        <!-- 1 Month -->
                        <div class="p-5 rounded-2xl border border-slate-200 hover:border-blue-300 bg-slate-50/50 hover:bg-white transition flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Thời hạn 30 Ngày</span>
                                <h3 class="text-base font-black text-slate-800 mt-1">Gói Tiêu Chuẩn</h3>
                                <div class="my-3">
                                    <span class="text-2xl font-black text-slate-900"><?= number_format($product['price_monthly'], 0, ',', '.') ?>đ</span>
                                    <span class="text-xs text-slate-400 font-semibold">/ tháng</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mb-4">Dành cho cá nhân trải nghiệm hoặc dự án ngắn hạn.</p>
                            </div>
                            <a href="<?= $buyUrl ?>" class="w-full py-2.5 bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold rounded-xl text-center transition flex items-center justify-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-cart-shopping text-xs"></i>
                                <span>Chọn Mua Gói Này</span>
                            </a>
                        </div>

                        <!-- 3 Months -->
                        <div class="p-5 rounded-2xl border border-slate-200 hover:border-blue-300 bg-slate-50/50 hover:bg-white transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black uppercase text-blue-600 tracking-wider">Thời hạn 90 Ngày</span>
                                    <span class="text-[9px] font-black text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">-15% Tiết kiệm</span>
                                </div>
                                <h3 class="text-base font-black text-slate-800 mt-1">Gói Tiết Kiệm</h3>
                                <div class="my-3">
                                    <span class="text-2xl font-black text-slate-900"><?= number_format($product['price_quarterly'], 0, ',', '.') ?>đ</span>
                                    <span class="text-xs text-slate-400 font-semibold">/ 3 tháng</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mb-4">Lựa chọn phổ biến cho các shop bán hàng và đội nhóm nhỏ.</p>
                            </div>
                            <a href="<?= $buyUrl ?>" class="w-full py-2.5 bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold rounded-xl text-center transition flex items-center justify-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-cart-shopping text-xs"></i>
                                <span>Chọn Mua Gói Này</span>
                            </a>
                        </div>

                        <!-- 1 Year (Recommended) -->
                        <div class="p-5 rounded-2xl border-2 border-blue-500 bg-blue-50/20 hover:bg-white transition flex flex-col justify-between relative shadow-md shadow-blue-500/10">
                            <span class="absolute -top-3 right-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[9px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-sm">
                                Lựa Chọn Nhiều Nhất
                            </span>
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black uppercase text-blue-600 tracking-wider">Thời hạn 365 Ngày</span>
                                    <span class="text-[9px] font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">-35% Siêu Tiết Kiệm</span>
                                </div>
                                <h3 class="text-base font-black text-slate-900 mt-1">Gói Doanh Nghiệp</h3>
                                <div class="my-3">
                                    <span class="text-2xl font-black text-blue-600"><?= number_format($product['price_yearly'], 0, ',', '.') ?>đ</span>
                                    <span class="text-xs text-slate-400 font-semibold">/ 1 năm</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mb-4">Tối ưu chi phí tối đa, vận hành không gián đoạn quanh năm.</p>
                            </div>
                            <a href="<?= $buyUrl ?>" class="w-full py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl text-center transition flex items-center justify-center gap-1.5 shadow-md shadow-blue-500/20">
                                <i class="fa-solid fa-bolt text-xs"></i>
                                <span>Kích Hoạt Gói 1 Năm</span>
                            </a>
                        </div>

                        <!-- Lifetime -->
                        <div class="p-5 rounded-2xl border border-purple-200 hover:border-purple-400 bg-purple-50/20 hover:bg-white transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black uppercase text-purple-700 tracking-wider">Trọn Đời Vĩnh Viễn</span>
                                    <span class="text-[9px] font-black text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full">VIP All-Time</span>
                                </div>
                                <h3 class="text-base font-black text-slate-800 mt-1">Gói Vĩnh Viễn (VIP)</h3>
                                <div class="my-3">
                                    <span class="text-2xl font-black text-purple-700"><?= number_format($product['price_lifetime'], 0, ',', '.') ?>đ</span>
                                    <span class="text-xs text-slate-400 font-semibold">/ trọn đời</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mb-4">Sở hữu vĩnh viễn, miễn phí toàn bộ các bản update tương lai.</p>
                            </div>
                            <a href="<?= $buyUrl ?>" class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl text-center transition flex items-center justify-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-crown text-xs"></i>
                                <span>Sở Hữu Trọn Đời</span>
                            </a>
                        </div>

                    </div>

                    <!-- Privilege Support Note -->
                    <div class="mt-6 p-4 bg-slate-50 rounded-2xl border border-slate-200/80 flex items-start gap-3">
                        <i class="fa-solid fa-shield-halved text-blue-600 text-base mt-0.5 shrink-0"></i>
                        <div class="text-[11px] text-slate-600 leading-relaxed font-medium">
                            <strong class="text-slate-800">Đặc quyền hỗ trợ VIP:</strong> Được kỹ thuật viên hỗ trợ cài đặt, cấu hình kịch bản và khắc phục sự cố trực tiếp qua UltraView / Zalo hoàn toàn miễn phí trong suốt thời hạn bản quyền.
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Universal Footer -->
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

</body>
</html>
