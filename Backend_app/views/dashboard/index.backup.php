<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển Khách Hàng - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .text-glow-green {
            text-shadow: 0 0 12px rgba(16, 185, 129, 0.2);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Client Sidebar -->
    <?php require BASE_PATH . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-6 md:p-10 max-w-7xl mx-auto w-full overflow-x-hidden">
        <!-- Dashboard Header -->
        <header class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8 pb-6 border-b border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    Chào mừng trở lại, <?= htmlspecialchars($user['username']) ?> 👋
                </h1>
                <p class="text-slate-500 text-xs mt-1 font-medium">Hệ thống quản lý bản quyền phần mềm và tài chính thông minh.</p>
            </div>
            <div class="flex items-center gap-3.5 flex-wrap">
                <!-- Language Switcher -->
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200/50">
                    <button class="flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold rounded-lg bg-white shadow-sm border border-slate-200/30 text-slate-800 transition">
                        <img src="<?= ASSET_PATH ?>/flag/vi.png" alt="VI" class="w-4 h-3 object-cover rounded-sm">
                        Tiếng Việt
                    </button>
                    <button class="flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold rounded-lg text-slate-500 hover:text-slate-800 transition">
                        <img src="<?= ASSET_PATH ?>/flag/en.png" alt="EN" class="w-4 h-3 object-cover rounded-sm">
                        English
                    </button>
                </div>
                
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-extrabold rounded-xl uppercase tracking-wider border border-blue-100/50">
                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-ping"></span>
                    Vai trò: <?= htmlspecialchars($user['role']) ?>
                </span>
            </div>
        </header>

        <!-- Stats Cards Grid -->
        <section class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Card 1: Licenses -->
            <div class="bg-gradient-to-br from-indigo-500/10 via-purple-500/5 to-transparent border border-indigo-500/10 p-6 rounded-2xl shadow-[0_4px_20px_rgba(99,102,241,0.02)] transition-all duration-300 hover:shadow-md hover:border-indigo-500/20 relative group overflow-hidden">
                <div class="absolute right-4 top-4 w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                </div>
                <span class="text-slate-400 text-[10px] font-extrabold uppercase tracking-widest block mb-2">Bản Quyền Đang Hoạt Động</span>
                <span class="text-2xl font-extrabold text-slate-900 tracking-tight block"><?= count($licenses) ?> <span class="text-sm font-medium text-slate-500">giấy phép</span></span>
                <span class="text-[10px] text-indigo-600 font-semibold mt-3 inline-flex items-center gap-1 hover:underline">
                    <a href="/licenses">Quản lý bản quyền &rarr;</a>
                </span>
            </div>

            <!-- Card 2: Wallet Balance -->
            <div class="bg-gradient-to-br from-emerald-500/10 via-teal-500/5 to-transparent border border-emerald-500/10 p-6 rounded-2xl shadow-[0_4px_20px_rgba(16,185,129,0.02)] transition-all duration-300 hover:shadow-md hover:border-emerald-500/20 relative group overflow-hidden">
                <div class="absolute right-4 top-4 w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <span class="text-slate-400 text-[10px] font-extrabold uppercase tracking-widest block mb-2">Số Dư Ví Khả Dụng</span>
                <span class="text-2xl font-extrabold text-emerald-600 tracking-tight block text-glow-green"><?= number_format($user['balance'] ?? 0, 0, ',', '.') ?>đ</span>
                <span class="text-[10px] text-emerald-600 font-semibold mt-3 inline-flex items-center gap-1 hover:underline">
                    <a href="/deposit">Nạp tiền hệ thống ngay &rarr;</a>
                </span>
            </div>

            <!-- Card 3: Commission Balance -->
            <div class="bg-gradient-to-br from-amber-500/10 via-orange-500/5 to-transparent border border-amber-500/10 p-6 rounded-2xl shadow-[0_4px_20px_rgba(245,158,11L,0.02)] transition-all duration-300 hover:shadow-md hover:border-amber-500/20 relative group overflow-hidden">
                <div class="absolute right-4 top-4 w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-slate-400 text-[10px] font-extrabold uppercase tracking-widest block mb-2">Số Dư Hoa Hồng CTV</span>
                <span class="text-2xl font-extrabold text-amber-600 tracking-tight block"><?= number_format($affiliate['balance'] ?? 0, 0, ',', '.') ?>đ</span>
                <span class="text-[10px] text-amber-600 font-semibold mt-3 inline-flex items-center gap-1 hover:underline">
                    <a href="/affiliate">Xem tiếp thị liên kết &rarr;</a>
                </span>
            </div>
        </section>

        <!-- Quick Actions Panel -->
        <section class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm mb-8">
            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Thao tác nhanh
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="/deposit" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-blue-500/30 hover:bg-blue-50/20 transition group">
                    <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </span>
                    <span class="text-[11px] font-bold text-slate-700">Nạp tiền ví</span>
                </a>
                <a href="/renew" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-emerald-500/30 hover:bg-emerald-50/20 transition group">
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                    <span class="text-[11px] font-bold text-slate-700">Gia hạn Key</span>
                </a>
                <a href="/licenses" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-purple-500/30 hover:bg-purple-50/20 transition group">
                    <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </span>
                    <span class="text-[11px] font-bold text-slate-700">Xem bản quyền</span>
                </a>
                <a href="/support" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-amber-500/30 hover:bg-amber-50/20 transition group">
                    <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-2.5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </span>
                    <span class="text-[11px] font-bold text-slate-700">Hỗ trợ kỹ thuật</span>
                </a>
            </div>
        </section>

        <!-- Dynamic Content Rows -->
        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Cols: Active Licenses, Charts & Transactions -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Interactive Charts Panel -->
                <section class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-widest">Thống kê hoạt động tài chính</h2>
                            <p class="text-[10px] text-slate-400 mt-0.5">Biểu đồ biến động ngân sách & hoa hồng</p>
                        </div>
                        <div class="flex items-center gap-1 bg-slate-50 p-1 rounded-xl border border-slate-200/40">
                            <button onclick="switchChartTab('wallet')" id="tab-wallet" class="px-3 py-1 text-[10px] font-bold rounded-lg bg-white shadow-sm border border-slate-200/50 text-slate-800 transition">Số dư ví</button>
                            <button onclick="switchChartTab('commission')" id="tab-commission" class="px-3 py-1 text-[10px] font-bold rounded-lg text-slate-500 hover:text-slate-800 transition">Hoa hồng</button>
                        </div>
                    </div>
                    
                    <!-- SVG Chart Container -->
                    <div class="relative h-56 w-full flex items-end">
                        <!-- Chart Gridlines -->
                        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none text-[9px] text-slate-300 font-mono">
                            <div class="border-b border-slate-100 w-full pb-1 flex justify-between"><span>Max</span><span id="chart-val-max">3.000.000đ</span></div>
                            <div class="border-b border-slate-100 w-full pb-1 flex justify-between"><span>Mid</span><span id="chart-val-mid">1.500.000đ</span></div>
                            <div class="w-full pt-1 flex justify-between"><span>0</span><span>0đ</span></div>
                        </div>
                        
                        <!-- Beautiful SVG Line Path -->
                        <svg class="w-full h-40 overflow-visible z-10" viewBox="0 0 500 100" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="chart-gradient-wallet" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.00"/>
                                </linearGradient>
                                <linearGradient id="chart-gradient-commission" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#f59e0b" stop-opacity="0.00"/>
                                </linearGradient>
                            </defs>
                            
                            <!-- Wallet Path and Area -->
                            <path id="chart-area-wallet" d="M 0 100 Q 100 80, 200 40 T 400 30 L 500 20 L 500 100 Z" fill="url(#chart-gradient-wallet)" class="transition-all duration-500"></path>
                            <path id="chart-line-wallet" d="M 0 100 Q 100 80, 200 40 T 400 30 L 500 20" fill="none" stroke="#3b82f6" stroke-width="3" class="transition-all duration-500"></path>
                            
                            <!-- Commission Path and Area (Hidden initially) -->
                            <path id="chart-area-commission" d="M 0 100 Q 100 95, 200 90 T 400 85 L 500 85 L 500 100 Z" fill="url(#chart-gradient-commission)" class="transition-all duration-500" style="display:none;"></path>
                            <path id="chart-line-commission" d="M 0 100 Q 100 95, 200 90 T 400 85 L 500 85" fill="none" stroke="#f59e0b" stroke-width="3" class="transition-all duration-500" style="display:none;"></path>
                        </svg>
                    </div>
                    <!-- Chart X axis labels -->
                    <div class="flex justify-between items-center mt-3 pt-2 border-t border-slate-50 text-[10px] text-slate-400 font-semibold px-1">
                        <span>T1</span>
                        <span>T2</span>
                        <span>T3</span>
                        <span>T4</span>
                        <span>T5</span>
                        <span>T6 (Hiện tại)</span>
                    </div>
                </section>
                
                <!-- Active Licenses Overview -->
                <section class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-6 pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-widest">Bản quyền của bạn</h2>
                            <p class="text-[10px] text-slate-400 mt-0.5">Danh sách các phần mềm đã kích hoạt gần đây</p>
                        </div>
                        <a href="/licenses" class="text-[10px] text-blue-600 font-bold hover:underline flex items-center gap-1">
                            Quản lý bản quyền 
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider">
                                    <th class="pb-3 font-bold">Phần mềm</th>
                                    <th class="pb-3 font-bold">Mã key bản quyền</th>
                                    <th class="pb-3 font-bold">Thời hạn</th>
                                    <th class="pb-3 font-bold">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700 font-medium">
                                <?php if (empty($licenses)): ?>
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400">Bạn chưa kích hoạt mã bản quyền nào.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach (array_slice($licenses, 0, 3) as $lic): 
                                        $remaining = $lic['expires_at'] - time();
                                        $totalDuration = $lic['expires_at'] - $lic['created_at'];
                                        $percent = $totalDuration > 0 ? clamp(round((($lic['expires_at'] - time()) / $totalDuration) * 100), 0, 100) : 0;
                                        
                                        // Status calculation
                                        $statusText = 'Đang hoạt động';
                                        $statusClass = 'bg-emerald-50 text-emerald-600';
                                        if ($remaining <= 0) {
                                            $statusText = 'Hết hạn';
                                            $statusClass = 'bg-rose-50 text-rose-600';
                                            $percent = 0;
                                        } else if ($remaining < 7 * 86400) {
                                            $statusText = 'Sắp hết hạn';
                                            $statusClass = 'bg-amber-50 text-amber-600';
                                        }
                                        
                                        function clamp($val, $min, $max) {
                                            return max($min, min($max, $val));
                                        }
                                    ?>
                                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                            <td class="py-4 pr-3">
                                                <div class="font-bold text-slate-900 text-xs"><?= htmlspecialchars($lic['product_name']) ?></div>
                                                <div class="text-[9px] text-slate-400 mt-1 uppercase font-bold tracking-wider"><?= htmlspecialchars($lic['type']) ?></div>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center gap-1.5">
                                                    <code id="key-<?= $lic['id'] ?>" class="text-[10px] bg-slate-50 px-2.5 py-1 rounded-lg text-blue-600 border border-slate-100 font-mono tracking-tight select-all">
                                                        <?= htmlspecialchars($lic['license_key']) ?>
                                                    </code>
                                                    <button onclick="copyToClipboard('<?= htmlspecialchars($lic['license_key']) ?>')" class="p-1 hover:bg-slate-100 rounded text-slate-400 hover:text-slate-600 transition" title="Sao chép Key">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="py-4 text-slate-500">
                                                <div class="text-[10px] text-slate-400"><?= date('d/m/Y H:i', $lic['expires_at']) ?></div>
                                                <div class="w-24 bg-slate-100 h-1 rounded-full mt-1.5 overflow-hidden">
                                                    <div class="h-full rounded-full <?= $remaining <= 0 ? 'bg-rose-500' : ($remaining < 7*86400 ? 'bg-amber-500' : 'bg-emerald-500') ?>" style="width: <?= $percent ?>%"></div>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <span class="inline-flex items-center px-2.5 py-1 text-[9px] font-bold rounded-full <?= $statusClass ?>">
                                                    <?= $statusText ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Transactions -->
                <section class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-6 pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-widest">Lịch sử giao dịch gần đây</h2>
                            <p class="text-[10px] text-slate-400 mt-0.5">Nhật ký nạp tiền và thanh toán đơn hàng</p>
                        </div>
                        <a href="/transactions" class="text-[10px] text-blue-600 font-bold hover:underline flex items-center gap-1">
                            Xem tất cả 
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider">
                                    <th class="pb-3 font-bold">Mã đơn</th>
                                    <th class="pb-3 font-bold">Hình thức</th>
                                    <th class="pb-3 font-bold">Mã GD</th>
                                    <th class="pb-3 font-bold">Số tiền</th>
                                    <th class="pb-3 font-bold">Trạng thái</th>
                                    <th class="pb-3 font-bold">Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700 font-medium">
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-slate-400">Không có giao dịch nào được ghi nhận.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach (array_slice($orders, 0, 5) as $ord): ?>
                                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                            <td class="py-4 font-bold text-slate-900">#ORD-<?= $ord['id'] ?></td>
                                            <td class="py-4 text-slate-500"><?= htmlspecialchars($ord['payment_method'] ?? 'Banking') ?></td>
                                            <td class="py-4"><code class="bg-slate-50 px-2 py-0.5 rounded text-slate-500 font-mono text-[10px]"><?= htmlspecialchars($ord['transaction_id'] ?? '-') ?></code></td>
                                            <td class="py-4 text-blue-600 font-bold"><?= number_format($ord['total_amount'], 0, ',', '.') ?>đ</td>
                                            <td class="py-4">
                                                <span class="inline-flex px-2.5 py-1 text-[9px] font-bold rounded-full <?= $ord['status'] === 'completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' ?>">
                                                    <?= $ord['status'] === 'completed' ? 'Đã duyệt' : 'Chờ duyệt' ?>
                                                </span>
                                            </td>
                                            <td class="py-4 text-slate-400"><?= date('d/m/Y H:i', $ord['created_at']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- Right 1 Col: Profile & Announcements -->
            <div class="space-y-8">
                
                <!-- Account details card -->
                <section class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-3xl p-6 shadow-md relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="absolute left-10 top-10 w-24 h-24 bg-indigo-500/20 rounded-full blur-xl"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-3.5 mb-6">
                            <?php if (!empty($avatarUrl)): ?>
                                <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="Avatar" class="w-12 h-12 rounded-2xl object-cover border-2 border-white/40 shadow-md">
                            <?php else: ?>
                                <div class="w-12 h-12 rounded-2xl bg-white/20 text-white flex items-center justify-center font-extrabold text-lg uppercase shadow-md border border-white/20">
                                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <span class="text-[9px] bg-white/25 text-white font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-widest">Premium Member</span>
                                <h3 class="font-extrabold text-sm truncate mt-1.5"><?= htmlspecialchars($user['username']) ?></h3>
                            </div>
                        </div>

                        <div class="space-y-3 text-[11px] border-t border-white/10 pt-4">
                            <div class="flex justify-between">
                                <span class="text-indigo-100">Email tài khoản:</span>
                                <span class="font-bold text-white"><?= htmlspecialchars($user['email']) ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-indigo-100">Mã giới thiệu:</span>
                                <div class="flex items-center gap-1">
                                    <span class="font-mono font-bold text-white bg-white/10 px-2 py-0.5 rounded"><?= htmlspecialchars($affiliate['referral_code'] ?? 'Chưa tạo') ?></span>
                                    <?php if (!empty($affiliate['referral_code'])): ?>
                                        <button onclick="copyToClipboard('<?= htmlspecialchars($affiliate['referral_code']) ?>')" class="p-1 hover:bg-white/10 rounded transition text-indigo-100 hover:text-white" title="Copy mã giới thiệu">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-indigo-100">Trạng thái:</span>
                                <span class="font-bold uppercase text-emerald-300 tracking-wider text-[10px] flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-emerald-300 rounded-full"></span>
                                    <?= htmlspecialchars($user['status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Announcements -->
                <section class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-xs font-bold text-slate-800 mb-5 pb-3 border-b border-slate-100 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        Thông báo hệ thống
                    </h2>
                    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                        <?php if (empty($notifications)): ?>
                            <p class="text-slate-400 text-xs py-4 text-center">Không có thông báo mới.</p>
                        <?php else: ?>
                            <?php foreach ($notifications as $note): 
                                // Notification category configuration
                                $typeLabel = 'Tin tức';
                                $typeClass = 'bg-blue-50 text-blue-600';
                                if ($note['type'] === 'promotion') {
                                    $typeLabel = 'Khuyến mãi';
                                    $typeClass = 'bg-amber-50 text-amber-600';
                                } else if ($note['type'] === 'maintenance') {
                                    $typeLabel = 'Bảo trì';
                                    $typeClass = 'bg-rose-50 text-rose-600';
                                } else if ($note['type'] === 'update') {
                                    $typeLabel = 'Cập nhật';
                                    $typeClass = 'bg-purple-50 text-purple-600';
                                }
                            ?>
                                <div class="border-l-2 border-slate-200 pl-4 py-1 transition hover:border-blue-500">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider <?= $typeClass ?>">
                                            <?= $typeLabel ?>
                                        </span>
                                        <span class="text-[9px] text-slate-400"><?= date('d/m/Y H:i', $note['created_at']) ?></span>
                                    </div>
                                    <strong class="text-slate-800 text-[11px] block mt-0.5 leading-snug"><?= htmlspecialchars($note['title']) ?></strong>
                                    <p class="text-slate-500 text-[10px] mt-1 leading-relaxed"><?= htmlspecialchars($note['content']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
            
        </div>
    </main>

    <!-- Beautiful Animated Toast Notifications -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 pointer-events-none flex flex-col gap-3"></div>

    <!-- JS Logic for Interactivity -->
    <script>
        // Copy to clipboard with premium toast animation
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast("Sao chép thành công mã bảo mật!", "success");
            }).catch(err => {
                showToast("Không thể sao chép tự động.", "error");
            });
        }

        function showToast(message, type = "success") {
            const container = document.getElementById("toast-container");
            const toast = document.createElement("div");
            toast.className = "pointer-events-auto flex items-center gap-2.5 px-4.5 py-3 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-slate-100 bg-white transition-all duration-300 transform translate-y-6 opacity-0 text-xs font-semibold text-slate-850";
            
            const icon = type === "success" 
                ? '<svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                : '<svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            
            toast.innerHTML = `${icon}<span>${message}</span>`;
            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.remove("translate-y-6", "opacity-0");
            }, 10);
            
            // Auto destroy
            setTimeout(() => {
                toast.classList.add("translate-y-2", "opacity-0");
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 2500);
        }

        // Beautiful SVG Chart Data Switching
        function switchChartTab(type) {
            const tabWallet = document.getElementById("tab-wallet");
            const tabCommission = document.getElementById("tab-commission");
            
            const lineWallet = document.getElementById("chart-line-wallet");
            const areaWallet = document.getElementById("chart-area-wallet");
            
            const lineCommission = document.getElementById("chart-line-commission");
            const areaCommission = document.getElementById("chart-area-commission");
            
            const maxVal = document.getElementById("chart-val-max");
            const midVal = document.getElementById("chart-val-mid");
            
            if (type === "wallet") {
                tabWallet.className = "px-3 py-1 text-[10px] font-bold rounded-lg bg-white shadow-sm border border-slate-200/50 text-slate-800 transition";
                tabCommission.className = "px-3 py-1 text-[10px] font-bold rounded-lg text-slate-500 hover:text-slate-800 transition";
                
                lineWallet.style.display = "block";
                areaWallet.style.display = "block";
                lineCommission.style.display = "none";
                areaCommission.style.display = "none";
                
                maxVal.textContent = "3.000.000đ";
                midVal.textContent = "1.500.000đ";
            } else {
                tabCommission.className = "px-3 py-1 text-[10px] font-bold rounded-lg bg-white shadow-sm border border-slate-200/50 text-slate-800 transition";
                tabWallet.className = "px-3 py-1 text-[10px] font-bold rounded-lg text-slate-500 hover:text-slate-800 transition";
                
                lineWallet.style.display = "none";
                areaWallet.style.display = "none";
                lineCommission.style.display = "block";
                areaCommission.style.display = "block";
                
                maxVal.textContent = "200.000đ";
                midVal.textContent = "100.000đ";
            }
        }
    </script>
</body>
</html>
