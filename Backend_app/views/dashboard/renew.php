<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mua & Gia Hạn Key Bản Quyền - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .card-surface {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-surface:hover {
            border-color: #cbd5e1;
        }

        .pkg-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
        }
        .pkg-card:hover {
            border-color: #93c5fd;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(37, 99, 235, 0.08);
        }
        .pkg-card-selected {
            border-color: #2563eb !important;
            background: #eff6ff !important;
            box-shadow: 0 0 0 1px #2563eb, 0 8px 20px -4px rgba(37, 99, 235, 0.12) !important;
        }

        /* Subtle scrollbars */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 min-h-screen flex flex-col md:flex-row antialiased">

    <!-- Client Sidebar -->
    <?php require BASE_PATH . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-4 md:p-7 lg:p-8 max-w-[1500px] mx-auto w-full overflow-x-hidden">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-6 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">
                        Mua & Gia Hạn Bản Quyền
                    </h1>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Instant Checkout
                    </span>
                </div>
                <p class="text-slate-500 text-xs mt-0.5">
                    Khởi tạo key bản quyền mới hoặc gia hạn thêm thời gian sử dụng từ số dư ví tài khoản.
                </p>
            </div>

            <!-- Wallet Balance Badge & Deposit Button -->
            <div class="flex items-center gap-2 flex-wrap">
                <div class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200 rounded-xl shadow-sm text-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-slate-500 font-semibold">Số dư ví:</span>
                    <span class="font-extrabold text-slate-900 tracking-tight text-sm">
                        <span id="userBalanceText"><?= number_format($user['balance'] ?? 0, 0, ',', '.') ?></span>đ
                    </span>
                </div>

                <a href="/deposit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-bolt text-emerald-200 text-[11px]"></i>
                    <span>+ Nạp Tiền VietQR</span>
                </a>
            </div>
        </div>

        <?php
        $db = DB::getInstance();
        $stmt = $db->query("SELECT * FROM products WHERE status = 'active' ORDER BY id ASC");
        $products = $stmt->fetchAll();

        // Features mapping
        $featuresMap = [
            'vmaxlogin' => ['Trình duyệt Antidetect Browser bảo mật chống phát hiện vân tay 100%', 'Tạo vô số Profile độc lập, cookie và IP proxy riêng biệt', 'Mô phỏng Canvas, WebGL, Audio, WebRTC, Fonts mượt mà', 'Đồng bộ hóa đám mây (Cloud Sync), chia sẻ profile nhóm'],
            'v-autopost' => ['Tự động hóa đăng bài viết lên Group, Fanpage, Profile đa luồng', 'Tương tác thông minh: Lướt Newsfeed, Like, Thả tim, Bình luận tự động', 'Gửi tin nhắn Messenger tự động cho danh sách khách hàng tiềm năng', 'Lên lịch biểu đăng bài tự động 24/7 theo kịch bản tùy biến'],
            'v-shopeeboost-pro' => ['Tự động đẩy sản phẩm Shopee lên top tìm kiếm mỗi 4 giờ', 'Tăng lượt yêu thích và follow gian hàng tự động, kéo traffic thật', 'Quét phân tích từ khóa hot và tối ưu hóa tiêu đề sản phẩm', 'Quản lý tập trung đồng thời hàng chục shop trên 1 máy tính'],
            'v-shopeeboost' => ['Tự động đẩy sản phẩm Shopee định kỳ', 'Tăng tương tác gian hàng Shopee tự động', 'Tối ưu danh mục và từ khóa cơ bản'],
            'combo-marketing-facebook' => ['Trọn bộ 3 công cụ Marketing đỉnh cao cho Facebook', 'Bao gồm VmaxLogin + V-AutoPost + Bộ quét Data khách hàng', 'Tiết kiệm 45% chi phí so với mua lẻ từng phần mềm'],
            'combo-phu-quy' => ['Hệ sinh thái trọn gói 4 công cụ tự động hóa MMO & E-Commerce', 'Bao gồm VmaxLogin Pro, V-AutoPost, V-ShopeeBoost Pro và Data Tool', 'Tặng kèm 50 luồng chạy đồng thời và hỗ trợ UltraView 1-1']
        ];

        // Pre-selected key from GET
        $selectedKey = sanitize($_GET['key'] ?? '');
        $initialMode = !empty($selectedKey) ? 'renew' : 'buy';
        $initialProductSlug = 'vmaxlogin';

        // If key passed, identify product
        if (!empty($selectedKey) && !empty($licenses)) {
            foreach ($licenses as $l) {
                if ($l['license_key'] === $selectedKey) {
                    foreach ($products as $p) {
                        if ($p['id'] == $l['product_id']) {
                            $initialProductSlug = $p['slug'];
                            break 2;
                        }
                    }
                }
            }
        }
        ?>

        <!-- Top Mode Switcher (Mua Key Mới vs Gia Hạn Key) -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 p-2 bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="flex items-center gap-1.5 w-full sm:w-auto p-1 bg-slate-100/90 rounded-xl">
                <button 
                    type="button" 
                    onclick="switchMode('buy')" 
                    id="mode-btn-buy" 
                    class="flex-1 sm:flex-none px-5 py-2 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 <?= $initialMode === 'buy' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>"
                >
                    <i class="fa-solid fa-cart-plus text-xs"></i>
                    <span>Mua Key Bản Quyền Mới</span>
                </button>
                <button 
                    type="button" 
                    onclick="switchMode('renew')" 
                    id="mode-btn-renew" 
                    class="flex-1 sm:flex-none px-5 py-2 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 <?= $initialMode === 'renew' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>"
                >
                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                    <span>Gia Hạn Key Đang Dùng</span>
                </button>
            </div>

            <div class="text-slate-400 text-xs hidden sm:flex items-center gap-1.5 pr-2">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                <span>Kích hoạt tự động ngay sau khi thanh toán</span>
            </div>
        </div>

        <!-- Software Selection Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6 max-w-full">
            <?php foreach ($products as $p): 
                $isCombo = (int)($p['is_combo'] ?? 0);
            ?>
                <button 
                    type="button" 
                    onclick="selectProduct('<?= $p['slug'] ?>', <?= $p['id'] ?>)" 
                    id="prod-tab-<?= $p['slug'] ?>" 
                    class="prod-tab-btn flex items-center gap-2.5 px-4 py-2.5 rounded-xl border text-xs font-bold transition shrink-0 <?= $p['slug'] === $initialProductSlug ? 'bg-white border-blue-500 text-blue-700 shadow-sm ring-2 ring-blue-500/10' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-900' ?>"
                >
                    <span class="w-6 h-6 rounded-lg <?= $isCombo ? 'bg-purple-100 text-purple-600' : 'bg-blue-50 text-blue-600' ?> flex items-center justify-center text-xs">
                        <i class="fa-solid <?= $isCombo ? 'fa-boxes-stacked' : 'fa-cube' ?>"></i>
                    </span>
                    <span><?= htmlspecialchars($p['name']) ?></span>
                    <?php if ($isCombo): ?>
                        <span class="text-[9px] bg-rose-50 text-rose-600 border border-rose-200 px-1.5 py-0.2 rounded font-extrabold uppercase">Tiết kiệm 45%</span>
                    <?php endif; ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Main Product Workspace Container -->
        <?php foreach ($products as $p): 
            $prodSlug = $p['slug'];
            $prodId = $p['id'];
            $prodLicenses = array_filter($licenses ?? [], function($lic) use ($prodId) {
                return $lic['product_id'] == $prodId;
            });
            $features = $featuresMap[$prodSlug] ?? [
                'Bản quyền phần mềm chính hãng VMAX Software',
                'Kích hoạt nhanh chóng trong 3 giây',
                'Hỗ trợ kỹ thuật 24/7'
            ];
        ?>
            <div id="product-workspace-<?= $prodSlug ?>" class="product-workspace <?= $prodSlug === $initialProductSlug ? '' : 'hidden' ?> grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left 2 Cols: Package Selection & Form -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <form onsubmit="handleTransaction(event, '<?= $prodSlug ?>', <?= $prodId ?>)" class="space-y-6">
                        <input type="hidden" name="package" id="selected-package-<?= $prodSlug ?>" required>
                        <input type="hidden" name="current_mode" id="current-mode-<?= $prodSlug ?>" value="<?= $initialMode ?>">

                        <!-- Section: Choose License Key (Only in Renew Mode) -->
                        <div id="renew-license-select-box-<?= $prodSlug ?>" class="<?= $initialMode === 'renew' ? '' : 'hidden' ?> card-surface rounded-2xl p-4 bg-emerald-50/30 border-emerald-200/70">
                            <label class="block text-xs font-bold text-slate-800 mb-1.5 flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-key text-emerald-600"></i>
                                    <span>Chọn Mã Key Cần Gia Hạn:</span>
                                </span>
                                <span class="text-[10px] text-emerald-700 font-extrabold">Cộng dồn vào hạn cũ</span>
                            </label>

                            <?php if (empty($prodLicenses)): ?>
                                <div class="p-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-xs flex items-center gap-2">
                                    <i class="fa-solid fa-circle-exclamation text-amber-600"></i>
                                    <span>Bạn chưa có mã key nào của phần mềm này để gia hạn. Vui lòng chọn tab <strong>"Mua Key Bản Quyền Mới"</strong>.</span>
                                </div>
                            <?php else: ?>
                                <div class="relative">
                                    <select name="license_id" class="w-full pl-3.5 pr-8 py-2.5 bg-white border border-slate-200 focus:border-emerald-500 rounded-xl text-xs font-bold text-slate-800 outline-none transition appearance-none cursor-pointer">
                                        <?php foreach ($prodLicenses as $lic): 
                                            $isSelected = ($lic['license_key'] === $selectedKey);
                                        ?>
                                            <option value="<?= $lic['id'] ?>" <?= $isSelected ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($lic['license_key']) ?> — (Hạn cũ: <?= date('d/m/Y', $lic['expires_at']) ?> - Gói: <?= htmlspecialchars($lic['type']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Section: 4 Duration Packages -->
                        <div class="card-surface rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fa-solid fa-layer-group text-blue-600"></i>
                                    <span>Chọn Gói Thời Hạn Bản Quyền</span>
                                </h2>
                                <span class="text-[11px] text-slate-400">Chọn 1 trong 4 gói</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <!-- Package 1: Monthly -->
                                <div 
                                    onclick="selectPackage('<?= $prodSlug ?>', 'monthly', <?= $p['price_monthly'] ?>, 'Gói 1 Tháng (+30 ngày)')"
                                    id="pkg-btn-<?= $prodSlug ?>-monthly" 
                                    class="pkg-card rounded-2xl p-4 flex flex-col justify-between h-[105px]"
                                >
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="text-xs font-bold text-slate-900 block">Gói 1 Tháng</span>
                                            <span class="text-[10px] text-slate-400 block mt-0.5">30 ngày sử dụng</span>
                                        </div>
                                        <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                            Tiêu chuẩn
                                        </span>
                                    </div>
                                    <div class="text-base font-black text-slate-900 tracking-tight">
                                        <?= number_format($p['price_monthly'], 0, ',', '.') ?>đ
                                    </div>
                                </div>

                                <!-- Package 2: Quarterly -->
                                <div 
                                    onclick="selectPackage('<?= $prodSlug ?>', 'quarterly', <?= $p['price_quarterly'] ?>, 'Gói 3 Tháng (+90 ngày)')"
                                    id="pkg-btn-<?= $prodSlug ?>-quarterly" 
                                    class="pkg-card rounded-2xl p-4 flex flex-col justify-between h-[105px]"
                                >
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="text-xs font-bold text-slate-900 block">Gói 3 Tháng</span>
                                            <span class="text-[10px] text-slate-400 block mt-0.5">90 ngày sử dụng</span>
                                        </div>
                                        <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Tiết kiệm
                                        </span>
                                    </div>
                                    <div class="text-base font-black text-slate-900 tracking-tight">
                                        <?= number_format($p['price_quarterly'], 0, ',', '.') ?>đ
                                    </div>
                                </div>

                                <!-- Package 3: Yearly -->
                                <div 
                                    onclick="selectPackage('<?= $prodSlug ?>', 'yearly', <?= $p['price_yearly'] ?>, 'Gói 1 Năm (+365 ngày)')"
                                    id="pkg-btn-<?= $prodSlug ?>-yearly" 
                                    class="pkg-card rounded-2xl p-4 flex flex-col justify-between h-[105px] border-blue-200 bg-blue-50/10"
                                >
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="text-xs font-bold text-blue-900 block">Gói 1 Năm</span>
                                            <span class="text-[10px] text-blue-600 font-semibold block mt-0.5">365 ngày sử dụng</span>
                                        </div>
                                        <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-300">
                                            Phổ biến nhất
                                        </span>
                                    </div>
                                    <div class="text-base font-black text-blue-700 tracking-tight">
                                        <?= number_format($p['price_yearly'], 0, ',', '.') ?>đ
                                    </div>
                                </div>

                                <!-- Package 4: Lifetime -->
                                <div 
                                    onclick="selectPackage('<?= $prodSlug ?>', 'lifetime', <?= $p['price_lifetime'] ?>, 'Gói Vĩnh Viễn (Trọn Đời)')"
                                    id="pkg-btn-<?= $prodSlug ?>-lifetime" 
                                    class="pkg-card rounded-2xl p-4 flex flex-col justify-between h-[105px]"
                                >
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="text-xs font-bold text-purple-900 block">Gói Trọn Đời</span>
                                            <span class="text-[10px] text-purple-600 font-semibold block mt-0.5">Vĩnh viễn không giới hạn</span>
                                        </div>
                                        <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200">
                                            VIP Lifetime
                                        </span>
                                    </div>
                                    <div class="text-base font-black text-purple-700 tracking-tight">
                                        <?= number_format($p['price_lifetime'], 0, ',', '.') ?>đ
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Live Order Summary & Submit Card -->
                        <div class="card-surface rounded-2xl p-5 space-y-4">
                            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-receipt text-indigo-600"></i>
                                <span>Tóm Tắt Đơn Hàng</span>
                            </h3>

                            <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-xl space-y-2.5 text-xs text-slate-600">
                                <div class="flex justify-between items-center">
                                    <span>Sản phẩm:</span>
                                    <span class="font-bold text-slate-900"><?= htmlspecialchars($p['name']) ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span>Gói cước đã chọn:</span>
                                    <span class="font-bold text-blue-700" id="summary-pkg-text-<?= $prodSlug ?>">Chưa chọn gói</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                                    <span class="font-semibold">Thành tiền:</span>
                                    <span class="text-base font-black text-slate-900" id="summary-price-text-<?= $prodSlug ?>">0đ</span>
                                </div>
                                <div class="flex justify-between items-center pt-1 text-[11px]">
                                    <span>Số dư ví sau khi thanh toán:</span>
                                    <span class="font-bold text-emerald-600" id="summary-remaining-text-<?= $prodSlug ?>">
                                        <?= number_format($user['balance'] ?? 0, 0, ',', '.') ?>đ
                                    </span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                id="btn-submit-order-<?= $prodSlug ?>" 
                                class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold rounded-xl text-xs md:text-sm shadow-md shadow-blue-500/10 transition flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i class="fa-solid fa-circle-check"></i>
                                <span id="btn-submit-label-<?= $prodSlug ?>">Xác Nhận Mua Key Bản Quyền</span>
                            </button>
                        </div>

                    </form>

                </div>

                <!-- Right 1 Col: Product Perks, Features & Guarantee -->
                <div class="space-y-5">
                    
                    <!-- Features Card -->
                    <div class="card-surface rounded-2xl p-5">
                        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider">Tính Năng Nổi Bật</h3>
                                <p class="text-[10px] text-slate-400"><?= htmlspecialchars($p['name']) ?></p>
                            </div>
                        </div>

                        <ul class="space-y-3 text-xs text-slate-600">
                            <?php foreach ($features as $f): ?>
                                <li class="flex items-start gap-2.5">
                                    <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                    <span class="leading-relaxed"><?= htmlspecialchars($f) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Guarantee Card -->
                    <div class="card-surface rounded-2xl p-5 bg-gradient-to-br from-slate-50 via-white to-blue-50/20 border-slate-200">
                        <h4 class="text-xs font-bold text-slate-900 mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                            <span>Chính Sách Cam Kết VMAX</span>
                        </h4>

                        <div class="space-y-2.5 text-[11px] text-slate-500">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-bolt text-amber-500 text-xs"></i>
                                <span>Cấp mã key kích hoạt tức thì sau 3 giây.</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-laptop text-blue-500 text-xs"></i>
                                <span>Hỗ trợ reset thiết bị HWID tự do khi đổi máy.</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-headset text-indigo-500 text-xs"></i>
                                <span>Kỹ thuật viên hỗ trợ UltraView cài đặt 24/7.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Support Callout -->
                    <div class="p-4 bg-white border border-slate-200 rounded-2xl text-xs text-slate-500 flex items-center justify-between">
                        <span class="text-[11px]">Cần tư vấn gói sỉ / đại lý?</span>
                        <a href="/support" class="text-blue-600 hover:underline font-bold text-[11px]">Nhắn hỗ trợ →</a>
                    </div>

                </div>

            </div>
        <?php endforeach; ?>

    </main>

    <script>
        let currentBalance = <?= (float)($user['balance'] ?? 0) ?>;
        let activeMode = '<?= $initialMode ?>';
        let activeSlug = '<?= $initialProductSlug ?>';
        let selectedPrices = {};

        // Switch between Buy New Key vs Renew Existing Key
        function switchMode(mode) {
            activeMode = mode;
            
            const btnBuy = document.getElementById('mode-btn-buy');
            const btnRenew = document.getElementById('mode-btn-renew');

            if (mode === 'buy') {
                btnBuy.className = "flex-1 sm:flex-none px-5 py-2 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 bg-blue-600 text-white shadow-sm";
                btnRenew.className = "flex-1 sm:flex-none px-5 py-2 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900";
            } else {
                btnBuy.className = "flex-1 sm:flex-none px-5 py-2 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 text-slate-600 hover:text-slate-900";
                btnRenew.className = "flex-1 sm:flex-none px-5 py-2 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 bg-emerald-600 text-white shadow-sm";
            }

            // Update renew selector boxes across products
            document.querySelectorAll('[id^="renew-license-select-box-"]').forEach(box => {
                if (mode === 'renew') {
                    box.classList.remove('hidden');
                } else {
                    box.classList.add('hidden');
                }
            });

            // Update mode inputs
            document.querySelectorAll('[id^="current-mode-"]').forEach(input => {
                input.value = mode;
            });

            // Update submit button text
            document.querySelectorAll('[id^="btn-submit-label-"]').forEach(lbl => {
                lbl.innerText = (mode === 'buy') ? "Xác Nhận Mua Key Bản Quyền" : "Xác Nhận Gia Hạn Key Đang Dùng";
            });
            document.querySelectorAll('[id^="btn-submit-order-"]').forEach(btn => {
                if (mode === 'buy') {
                    btn.className = "w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold rounded-xl text-xs md:text-sm shadow-md shadow-blue-500/10 transition flex items-center justify-center gap-2 cursor-pointer";
                } else {
                    btn.className = "w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold rounded-xl text-xs md:text-sm shadow-md shadow-emerald-500/10 transition flex items-center justify-center gap-2 cursor-pointer";
                }
            });
        }

        // Switch Active Product Tab
        function selectProduct(slug, id) {
            activeSlug = slug;

            // Hide all workspaces
            document.querySelectorAll('.product-workspace').forEach(el => el.classList.add('hidden'));
            const targetWorkspace = document.getElementById(`product-workspace-${slug}`);
            if (targetWorkspace) targetWorkspace.classList.remove('hidden');

            // Update tab styles
            document.querySelectorAll('.prod-tab-btn').forEach(btn => {
                btn.className = "prod-tab-btn flex items-center gap-2.5 px-4 py-2.5 rounded-xl border text-xs font-bold transition shrink-0 bg-white border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-900";
            });
            const activeTabBtn = document.getElementById(`prod-tab-${slug}`);
            if (activeTabBtn) {
                activeTabBtn.className = "prod-tab-btn flex items-center gap-2.5 px-4 py-2.5 rounded-xl border text-xs font-bold transition shrink-0 bg-white border-blue-500 text-blue-700 shadow-sm ring-2 ring-blue-500/10";
            }
        }

        // Select a Duration Package
        function selectPackage(slug, pkgType, price, pkgTitle) {
            selectedPrices[slug] = price;
            
            // Set hidden form input
            const input = document.getElementById(`selected-package-${slug}`);
            if (input) input.value = pkgType;

            // Update card border styles
            const pkgTypes = ['monthly', 'quarterly', 'yearly', 'lifetime'];
            pkgTypes.forEach(t => {
                const card = document.getElementById(`pkg-btn-${slug}-${t}`);
                if (card) {
                    if (t === pkgType) {
                        card.classList.add('pkg-card-selected');
                    } else {
                        card.classList.remove('pkg-card-selected');
                    }
                }
            });

            // Update Summary Text
            const pkgTextEl = document.getElementById(`summary-pkg-text-${slug}`);
            const priceTextEl = document.getElementById(`summary-price-text-${slug}`);
            const remainingTextEl = document.getElementById(`summary-remaining-text-${slug}`);
            const submitBtn = document.getElementById(`btn-submit-order-${slug}`);

            if (pkgTextEl) pkgTextEl.innerText = pkgTitle;
            if (priceTextEl) priceTextEl.innerText = price.toLocaleString('vi-VN') + 'đ';

            const remaining = currentBalance - price;
            if (remainingTextEl) {
                remainingTextEl.innerText = remaining.toLocaleString('vi-VN') + 'đ';
                if (remaining < 0) {
                    remainingTextEl.className = "font-black text-rose-600 text-xs";
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerText = "Số Dư Ví Không Đủ — Vui Lòng Nạp Thêm";
                        submitBtn.className = "w-full py-3 bg-slate-200 text-slate-400 font-bold rounded-xl text-xs cursor-not-allowed";
                    }
                } else {
                    remainingTextEl.className = "font-black text-emerald-600 text-xs";
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        const label = (activeMode === 'buy') ? "Xác Nhận Mua Key Bản Quyền" : "Xác Nhận Gia Hạn Key Đang Dùng";
                        submitBtn.innerHTML = `<i class="fa-solid fa-circle-check"></i><span>${label}</span>`;
                        if (activeMode === 'buy') {
                            submitBtn.className = "w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold rounded-xl text-xs md:text-sm shadow-md shadow-blue-500/10 transition flex items-center justify-center gap-2 cursor-pointer";
                        } else {
                            submitBtn.className = "w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold rounded-xl text-xs md:text-sm shadow-md shadow-emerald-500/10 transition flex items-center justify-center gap-2 cursor-pointer";
                        }
                    }
                }
            }
        }

        // Handle AJAX Transaction Submission
        function handleTransaction(event, slug, productId) {
            event.preventDefault();
            const form = event.target;
            const pkgInput = document.getElementById(`selected-package-${slug}`);
            
            if (!pkgInput || !pkgInput.value) {
                Swal.fire({
                    title: 'Chưa Chọn Gói',
                    text: 'Vui lòng chọn 1 gói thời hạn bản quyền trước khi thanh toán.',
                    icon: 'info',
                    confirmButtonText: 'Đã Hiểu',
                    confirmButtonColor: '#2563eb',
                    customClass: { popup: 'rounded-2xl' }
                });
                return;
            }

            const formData = new FormData(form);
            formData.append('csrf_token', '<?= Auth::getCsrfToken() ?>');
            formData.append('action', activeMode);
            if (activeMode === 'buy') {
                formData.append('product_id', productId);
            }

            Swal.fire({ 
                title: 'Đang xử lý giao dịch...', 
                text: 'Hệ thống đang trích xuất số dư và khởi tạo mã key bản quyền',
                allowOutsideClick: false, 
                didOpen: () => Swal.showLoading(),
                customClass: { popup: 'rounded-2xl' }
            });

            fetch('/renew?ajax=1', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    currentBalance = data.balance;
                    document.getElementById('userBalanceText').innerText = currentBalance.toLocaleString('vi-VN');
                    
                    Swal.fire({
                        title: 'Thanh Toán Thành Công!',
                        html: `<div class="text-left space-y-3 text-xs text-slate-700 bg-slate-50 p-4 border border-slate-200 rounded-xl mt-2">
                                    <div class="text-center font-bold text-sm text-emerald-600 pb-2 border-b border-slate-200">${data.message}</div>
                                    <div class="pt-1"><strong>Mã Key Bản Quyền Của Bạn:</strong></div>
                                    <div class="flex items-center justify-between bg-blue-50 border border-blue-200 px-3 py-2.5 rounded-xl">
                                        <code class="font-mono font-black text-blue-700 text-xs" id="successKey">${data.key}</code>
                                        <button onclick="navigator.clipboard.writeText('${data.key}'); Swal.showToast?.('Đã copy!');" class="text-[11px] font-bold text-blue-600 hover:underline">Sao chép</button>
                                    </div>
                                    <div class="text-[11px] text-slate-400 text-center">Bản quyền đã được tự động lưu vào trang Quản Lý Bản Quyền.</div>
                               </div>`,
                        icon: 'success',
                        confirmButtonText: 'Xem Danh Sách Bản Quyền',
                        cancelButtonText: 'Ở Lại Trang Này',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#64748b',
                        customClass: { popup: 'rounded-2xl' }
                    }).then((result) => { 
                        if (result.isConfirmed) {
                            location.href = '/licenses'; 
                        }
                    });
                } else {
                    Swal.fire({ 
                        title: 'Giao Dịch Thất Bại', 
                        text: data.message, 
                        icon: 'error', 
                        confirmButtonText: 'Đóng', 
                        confirmButtonColor: '#dc2626',
                        customClass: { popup: 'rounded-2xl' }
                    });
                }
            })
            .catch(() => { 
                Swal.fire({ 
                    title: 'Lỗi Kết Nối', 
                    text: 'Không thể kết nối tới máy chủ. Vui lòng thử lại.', 
                    icon: 'error', 
                    confirmButtonText: 'Đóng', 
                    confirmButtonColor: '#dc2626',
                    customClass: { popup: 'rounded-2xl' }
                }); 
            });
        }

        // Auto-select first package on load
        window.addEventListener('DOMContentLoaded', () => {
            selectPackage('<?= $initialProductSlug ?>', 'yearly', <?= $products[0]['price_yearly'] ?? 0 ?>, 'Gói 1 Năm (+365 ngày)');
        });
    </script>
</body>
</html>