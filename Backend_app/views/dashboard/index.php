<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Modern Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .stat-card-glass {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .stat-card-glass:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -10px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
        }

        .action-card-glow {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .action-card-glow:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 28px -6px rgba(37, 99, 235, 0.12);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 min-h-screen flex flex-col md:flex-row antialiased">

    <!-- Client Sidebar -->
    <?php require BASE_PATH . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-5 md:p-8 lg:p-10 max-w-[1600px] mx-auto w-full overflow-x-hidden">
        
        <!-- Top Greeting Header -->
        <header class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8 pb-6 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Chào mừng trở lại, <?= htmlspecialchars($user['username']) ?>!
                    </h1>
                    <span class="text-2xl">👋</span>
                </div>
                <p class="text-slate-500 text-xs md:text-sm mt-1 font-medium">
                    Hệ thống quản lý bản quyền phần mềm VMAX & Giám sát hoạt động 24/7.
                </p>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="/deposit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow transition">
                    <i class="fa-solid fa-qrcode text-emerald-200"></i>
                    <span>+ Nạp Tiền VietQR</span>
                </a>
                
                <a href="/downloads" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-download text-blue-600"></i>
                    <span>Tải Phần Mềm</span>
                </a>

                <div class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 text-blue-700 text-xs font-extrabold rounded-xl border border-blue-200/60 uppercase tracking-wider">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span><?= htmlspecialchars($user['role'] === 'admin' ? 'ADMIN MASTER' : 'PRO MEMBER') ?></span>
                </div>
            </div>
        </header>

        <!-- 4 KPI Stat Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Card 1: Balance -->
            <div class="stat-card-glass rounded-2xl p-5 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Số Dư Ví Khả Dụng</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight mb-2">
                    <?= number_format($user['balance'] ?? 0, 0, ',', '.') ?>đ
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[11px]">
                    <span class="text-emerald-600 font-bold flex items-center gap-1">
                        <i class="fa-solid fa-bolt text-[10px]"></i> VietQR Tự động
                    </span>
                    <a href="/deposit" class="text-blue-600 hover:text-blue-700 font-bold hover:underline">Nạp thêm →</a>
                </div>
            </div>

            <!-- Card 2: Active Licenses -->
            <div class="stat-card-glass rounded-2xl p-5 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Bản Quyền Đang Dùng</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-key"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight mb-2">
                    <?= count($licenses) ?> <span class="text-sm font-semibold text-slate-500">phần mềm</span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[11px]">
                    <span class="text-blue-600 font-bold flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Đang kích hoạt
                    </span>
                    <a href="/licenses" class="text-blue-600 hover:text-blue-700 font-bold hover:underline">Quản lý →</a>
                </div>
            </div>

            <!-- Card 3: Devices HWID -->
            <div class="stat-card-glass rounded-2xl p-5 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Thiết Bị Kết Nối (HWID)</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight mb-2">
                    2 <span class="text-sm font-semibold text-slate-500">/ 8 thiết bị</span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[11px]">
                    <span class="text-purple-600 font-bold flex items-center gap-1">
                        <i class="fa-solid fa-shield-halved text-[10px]"></i> Đổi máy 1-click
                    </span>
                    <a href="/devices" class="text-blue-600 hover:text-blue-700 font-bold hover:underline">Chi tiết →</a>
                </div>
            </div>

            <!-- Card 4: Affiliate Commission -->
            <div class="stat-card-glass rounded-2xl p-5 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Hoa Hồng Tiếp Thị (CTV)</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 tracking-tight mb-2">
                    <?= number_format($affiliate['balance'] ?? 0, 0, ',', '.') ?>đ
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[11px]">
                    <span class="text-amber-600 font-bold flex items-center gap-1">
                        <i class="fa-solid fa-percent text-[10px]"></i> Chiết khấu 10%
                    </span>
                    <a href="/affiliate" class="text-blue-600 hover:text-blue-700 font-bold hover:underline">Rút tiền →</a>
                </div>
            </div>
        </section>

        <!-- Quick Actions Grid (Thao Tác Nhanh) -->
        <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm mb-8">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    <span>THAO TÁC NHANH</span>
                </h2>
                <span class="text-xs text-slate-400">Các tính năng thường dùng nhất</span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Action 1 -->
                <a href="/deposit" class="action-card-glow flex items-center gap-3.5 p-4 rounded-2xl border border-slate-200/80 hover:border-emerald-500/40 hover:bg-emerald-50/20 bg-white transition group">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs">Nạp Tiền VietQR</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Xong trong 10s tự động</p>
                    </div>
                </a>

                <!-- Action 2 -->
                <a href="/downloads" class="action-card-glow flex items-center gap-3.5 p-4 rounded-2xl border border-slate-200/80 hover:border-blue-500/40 hover:bg-blue-50/20 bg-white transition group">
                    <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-cloud-arrow-down"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs">Tải Bản Cài Mới</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">VmaxLogin v2.4.2</p>
                    </div>
                </a>

                <!-- Action 3 -->
                <a href="/renew" class="action-card-glow flex items-center gap-3.5 p-4 rounded-2xl border border-slate-200/80 hover:border-purple-500/40 hover:bg-purple-50/20 bg-white transition group">
                    <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs">Gia Hạn Bản Quyền</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Giảm 15% khi gia hạn</p>
                    </div>
                </a>

                <!-- Action 4 -->
                <a href="/support" class="action-card-glow flex items-center gap-3.5 p-4 rounded-2xl border border-slate-200/80 hover:border-amber-500/40 hover:bg-amber-50/20 bg-white transition group">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs">Hỗ Trợ Kỹ Thuật</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Ultraview 1-1 24/7</p>
                    </div>
                </a>
            </div>
        </section>

        <!-- Main Content 2-Column Split -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Cols: Financial Chart + Active Licenses + Orders -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Financial Interactive Chart Card -->
                <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-6">
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-chart-line text-blue-600"></i>
                                <span>Biến Động Hoạt Động Tài Chính</span>
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5">Biểu đồ ngân sách nạp & hoa hồng CTV theo thời gian thực</p>
                        </div>
                        
                        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                            <button id="btn-chart-wallet" onclick="toggleChartMetric('wallet')" class="px-3.5 py-1.5 text-xs font-bold rounded-lg bg-white shadow-sm text-slate-800 transition">Số dư ví</button>
                            <button id="btn-chart-comm" onclick="toggleChartMetric('comm')" class="px-3.5 py-1.5 text-xs font-bold rounded-lg text-slate-500 hover:text-slate-800 transition">Hoa hồng</button>
                        </div>
                    </div>

                    <!-- ApexCharts Render Target -->
                    <div id="dashboardApexChart" class="w-full min-h-[260px]"></div>
                </section>

                <!-- Active Licenses Overview -->
                <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                                <span>Bản Quyền Đang Hoạt Động</span>
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5">Danh sách các phần mềm đã kích hoạt trên tài khoản</p>
                        </div>
                        <a href="/licenses" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                            <span>Quản lý tất cả</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider">
                                    <th class="pb-3 font-extrabold">Phần mềm</th>
                                    <th class="pb-3 font-extrabold">Mã bản quyền (License Key)</th>
                                    <th class="pb-3 font-extrabold">Thời hạn</th>
                                    <th class="pb-3 font-extrabold">Trạng thái</th>
                                    <th class="pb-3 font-extrabold text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                <?php if (empty($licenses)): ?>
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400">Bạn chưa kích hoạt mã bản quyền nào. <a href="/pricing" class="text-blue-600 font-bold hover:underline">Xem bảng giá</a></td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($licenses as $lic): 
                                        $remaining = $lic['expires_at'] - time();
                                        $daysLeft = max(0, ceil($remaining / 86400));
                                    ?>
                                        <tr class="hover:bg-slate-50/70 transition">
                                            <td class="py-4 pr-3">
                                                <div class="font-extrabold text-slate-900 text-xs flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                    <span><?= htmlspecialchars($lic['product_name']) ?></span>
                                                </div>
                                                <div class="text-[10px] text-slate-400 mt-0.5 font-bold uppercase pl-4">Gói: <?= htmlspecialchars($lic['type']) ?></div>
                                            </td>
                                            <td class="py-4">
                                                <div class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg">
                                                    <code class="text-[11px] text-blue-600 font-mono font-bold tracking-tight"><?= htmlspecialchars($lic['license_key']) ?></code>
                                                    <button onclick="copyToClipboard('<?= htmlspecialchars($lic['license_key']) ?>')" class="text-slate-400 hover:text-blue-600 p-0.5" title="Copy mã key">
                                                        <i class="fa-regular fa-copy text-xs"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="py-4 text-slate-600">
                                                <div class="font-bold text-xs"><?= $daysLeft ?> ngày nữa</div>
                                                <div class="text-[10px] text-slate-400"><?= date('d/m/Y', $lic['expires_at']) ?></div>
                                            </td>
                                            <td class="py-4">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span>Đang chạy</span>
                                                </span>
                                            </td>
                                            <td class="py-4 text-right">
                                                <a href="/renew" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-bold transition">
                                                    Gia hạn
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Recent Transaction History -->
                <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-receipt text-indigo-600"></i>
                                <span>Lịch Sử Giao Dịch Gần Đây</span>
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5">Nhật ký các lần nạp tiền và thanh toán đơn hàng</p>
                        </div>
                        <a href="/transactions" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                            <span>Xem tất cả</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider">
                                    <th class="pb-3 font-extrabold">Mã Đơn</th>
                                    <th class="pb-3 font-extrabold">Phương Thức</th>
                                    <th class="pb-3 font-extrabold">Số Tiền</th>
                                    <th class="pb-3 font-extrabold">Trạng Thái</th>
                                    <th class="pb-3 font-extrabold text-right">Thời Gian</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400">Không có giao dịch nào được ghi nhận.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($orders as $ord): ?>
                                        <tr class="hover:bg-slate-50/70 transition">
                                            <td class="py-3.5 font-bold text-slate-900 font-mono">#ORD-<?= $ord['id'] ?></td>
                                            <td class="py-3.5 text-slate-600">
                                                <span class="inline-flex items-center gap-1 text-slate-700 font-medium">
                                                    <i class="fa-solid fa-building-columns text-slate-400"></i>
                                                    <span>VietQR Banking</span>
                                                </span>
                                            </td>
                                            <td class="py-3.5 font-extrabold text-blue-600 font-mono"><?= number_format($ord['total_amount'], 0, ',', '.') ?>đ</td>
                                            <td class="py-3.5">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <i class="fa-solid fa-check text-[9px]"></i> Thành công
                                                </span>
                                            </td>
                                            <td class="py-3.5 text-slate-400 text-right"><?= date('d/m/Y H:i', $ord['created_at']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- Right Column: Profile VIP Card + Server Status + Announcements -->
            <div class="space-y-8">
                
                <!-- VIP Membership Account Card -->
                <section class="bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 text-white rounded-3xl p-6 shadow-xl relative overflow-hidden group">
                    <!-- Glow background -->
                    <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-blue-500/25 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-700"></div>
                    <div class="absolute left-6 top-6 w-20 h-20 bg-indigo-500/15 rounded-full blur-xl"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-5">
                            <span class="px-2.5 py-1 rounded-full bg-blue-500/20 text-blue-300 font-extrabold text-[10px] tracking-wider border border-blue-400/30 uppercase">
                                VIP MEMBERSHIP
                            </span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                        </div>

                        <div class="flex items-center gap-3.5 mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-xl shadow-md border border-white/20">
                                <?= strtoupper(substr($user['username'], 0, 1)) ?>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-base text-white"><?= htmlspecialchars($user['username']) ?></h3>
                                <p class="text-xs text-slate-300 font-medium"><?= htmlspecialchars($user['email']) ?></p>
                            </div>
                        </div>

                        <!-- Details Rows -->
                        <div class="space-y-2.5 pt-4 border-t border-white/10 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-300 font-medium">Mã giới thiệu:</span>
                                <div class="inline-flex items-center gap-1.5 bg-white/10 px-2 py-0.5 rounded-lg border border-white/10">
                                    <code class="font-mono font-bold text-white"><?= htmlspecialchars($affiliate['referral_code'] ?? 'VMAX8888') ?></code>
                                    <button onclick="copyToClipboard('<?= htmlspecialchars($affiliate['referral_code'] ?? 'VMAX8888') ?>')" class="text-slate-300 hover:text-white" title="Copy mã">
                                        <i class="fa-regular fa-copy text-[11px]"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-300 font-medium">Cấp độ tài khoản:</span>
                                <span class="font-bold text-amber-300 flex items-center gap-1">
                                    <i class="fa-solid fa-crown text-[10px]"></i> Enterprise Admin
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-300 font-medium">Trạng thái:</span>
                                <span class="text-emerald-400 font-bold uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span>Active</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Server & Cluster Live Status -->
                <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                    <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-server text-blue-600"></i>
                        <span>TRẠNG THÁI HỆ THỐNG</span>
                    </h2>

                    <div class="space-y-3 text-xs font-medium">
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-700 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Proxy SOCKS5 VN
                            </span>
                            <span class="text-[11px] font-bold text-emerald-600">100% Hoạt động</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-700 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Cloud Sync Profile
                            </span>
                            <span class="text-[11px] font-bold text-emerald-600">Uptime 99.9%</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-700 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Cổng VietQR Napas247
                            </span>
                            <span class="text-[11px] font-bold text-emerald-600">Tự động 10s</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-700 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span> VMAX Kernel Chromium
                            </span>
                            <span class="text-[11px] font-bold text-blue-600 font-mono">v124 Stable</span>
                        </div>
                    </div>
                </section>

                <!-- System Announcements / Notifications -->
                <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                    <h2 class="text-xs font-extrabold text-slate-900 mb-4 pb-3 border-b border-slate-100 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-bullhorn text-amber-500"></i>
                        <span>THÔNG BÁO TỪ HỆ THỐNG</span>
                    </h2>

                    <div class="space-y-4 max-h-[320px] overflow-y-auto pr-1">
                        <?php if (empty($notifications)): ?>
                            <p class="text-slate-400 text-xs py-4 text-center">Không có thông báo mới.</p>
                        <?php else: ?>
                            <?php foreach ($notifications as $note): 
                                $typeBadge = 'bg-blue-50 text-blue-700 border-blue-200';
                                if ($note['type'] === 'promotion') $typeBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                                if ($note['type'] === 'update') $typeBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            ?>
                                <div class="border-l-2 border-blue-500 pl-3.5 py-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold border <?= $typeBadge ?> uppercase">
                                            <?= htmlspecialchars($note['type']) ?>
                                        </span>
                                        <span class="text-[10px] text-slate-400"><?= date('d/m/Y', $note['created_at']) ?></span>
                                    </div>
                                    <h4 class="font-bold text-slate-900 text-xs leading-snug"><?= htmlspecialchars($note['title']) ?></h4>
                                    <p class="text-slate-500 text-[11px] mt-1 leading-relaxed"><?= htmlspecialchars($note['content']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>

            </div>
        </div>

    </main>

    <!-- Interactive Script Block -->
    <script>
    // Copy to clipboard helper
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert("Đã sao chép vào bộ nhớ tạm: " + text);
        }).catch(err => {
            console.error('Error copying text: ', err);
        });
    }

    // ApexCharts Interactive Chart Initialization
    const chartOptions = {
        series: [{
            name: 'Số dư ví (VNĐ)',
            data: [500000, 1200000, 1800000, 1500000, 2200000, 2500000]
        }],
        chart: {
            type: 'area',
            height: 250,
            toolbar: { show: false },
            fontFamily: 'Plus Jakarta Sans, sans-serif'
        },
        colors: ['#2563eb'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [0, 95, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Hiện tại'],
            labels: {
                style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return (val / 1000000).toFixed(1) + ' tr';
                },
                style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 }
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
                }
            }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4
        }
    };

    let apexChartInstance = null;
    document.addEventListener("DOMContentLoaded", function() {
        const chartEl = document.querySelector("#dashboardApexChart");
        if (chartEl) {
            apexChartInstance = new ApexCharts(chartEl, chartOptions);
            apexChartInstance.render();
        }
    });

    function toggleChartMetric(metric) {
        const btnWallet = document.getElementById('btn-chart-wallet');
        const btnComm = document.getElementById('btn-chart-comm');

        if (metric === 'wallet') {
            btnWallet.className = "px-3.5 py-1.5 text-xs font-bold rounded-lg bg-white shadow-sm text-slate-800 transition";
            btnComm.className = "px-3.5 py-1.5 text-xs font-bold rounded-lg text-slate-500 hover:text-slate-800 transition";
            apexChartInstance.updateOptions({
                colors: ['#2563eb'],
                series: [{
                    name: 'Số dư ví (VNĐ)',
                    data: [500000, 1200000, 1800000, 1500000, 2200000, 2500000]
                }]
            });
        } else {
            btnComm.className = "px-3.5 py-1.5 text-xs font-bold rounded-lg bg-white shadow-sm text-slate-800 transition";
            btnWallet.className = "px-3.5 py-1.5 text-xs font-bold rounded-lg text-slate-500 hover:text-slate-800 transition";
            apexChartInstance.updateOptions({
                colors: ['#f59e0b'],
                series: [{
                    name: 'Hoa hồng CTV (VNĐ)',
                    data: [50000, 100000, 180000, 220000, 300000, 350000]
                }]
            });
        }
    }
    </script>
</body>
</html>
