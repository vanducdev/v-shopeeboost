<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiếp Thị Liên Kết - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

        .aff-row {
            transition: background-color 0.15s ease;
        }
        .aff-row:hover {
            background-color: #f8fafc;
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
                        Tiếp Thị Liên Kết (Affiliate)
                    </h1>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                        10% Hoa Hồng Trọn Đời
                    </span>
                </div>
                <p class="text-slate-500 text-xs mt-0.5">
                    Giới thiệu bạn bè và đối tác sử dụng phần mềm VMAX để nhận hoa hồng thu nhập thụ động không giới hạn.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-2 flex-wrap">
                <button onclick="scrollToWithdraw()" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-money-bill-wave text-[11px]"></i>
                    <span>Rút Tiền Hoa Hồng</span>
                </button>
                
                <a href="/renew" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-cart-shopping text-blue-600 text-[11px]"></i>
                    <span>Bảng Giá Sản Phẩm</span>
                </a>
            </div>
        </div>

        <!-- Status Alerts -->
        <?php if (!empty($error)): ?>
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-bold mb-5 flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-bold mb-5 flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php
        $refCode = $profile['referral_code'] ?? 'VMAX' . rand(1000, 9999);
        $refLink = SITE_URL . '/register?ref=' . urlencode($refCode);
        $affBalance = (float)($profile['balance'] ?? 0);
        $commissionRate = (float)($profile['commission_rate'] ?? 10);
        $totalLogsCount = count($logs ?? []);
        $totalWithdrawCount = count($withdrawals ?? []);
        ?>

        <!-- Compact 4 KPI Stats Overview -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
            <!-- Stat 1 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">HOA HỒNG KHẢ DỤNG</span>
                    <span class="text-xl font-black text-emerald-600 tracking-tight mt-0.5 block">
                        <?= number_format($affBalance, 0, ',', '.') ?>đ
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">TỶ LỆ HOA HỒNG</span>
                    <span class="text-xl font-black text-blue-600 tracking-tight mt-0.5 block">
                        <?= $commissionRate ?>% <span class="text-xs font-semibold text-slate-400">/ đơn</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-percent"></i>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">MÃ GIỚI THIỆU</span>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-base font-mono font-black text-slate-900 tracking-tight"><?= htmlspecialchars($refCode) ?></span>
                        <button onclick="copyText('<?= htmlspecialchars($refCode) ?>', this)" class="text-slate-400 hover:text-blue-600" title="Sao chép mã">
                            <i class="fa-regular fa-copy text-xs"></i>
                        </button>
                    </div>
                </div>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">CHÍNH SÁCH HOA HỒNG</span>
                    <span class="text-xs font-black text-slate-800 tracking-tight mt-1 block flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Trọn đời mỗi đơn</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-handshake"></i>
                </div>
            </div>
        </section>

        <!-- Referral Link Share Banner -->
        <section class="card-surface rounded-2xl p-4 md:p-5 mb-6 bg-gradient-to-r from-blue-50/50 via-white to-indigo-50/40 border-blue-100">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-base shadow-sm shrink-0">
                        <i class="fa-solid fa-link"></i>
                    </div>
                    <div>
                        <span class="text-xs font-black text-slate-900 block">Đường Dẫn Tiếp Thị Liên Kết Của Bạn</span>
                        <p class="text-[11px] text-slate-500 mt-0.5">Chia sẻ liên kết này lên Facebook, Zalo, YouTube hoặc Website để tự động ghi nhận hoa hồng khi có người mua.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-1 max-w-xl">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            readonly 
                            value="<?= htmlspecialchars($refLink) ?>" 
                            id="ref_link_input" 
                            class="w-full pl-3.5 pr-20 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-blue-700 outline-none select-all shadow-inner"
                        >
                        <button 
                            type="button" 
                            onclick="copyText(document.getElementById('ref_link_input').value, this)" 
                            class="absolute right-1.5 top-1/2 -translate-y-1/2 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg transition flex items-center gap-1 shadow-sm"
                        >
                            <i class="fa-regular fa-copy text-[10px]"></i>
                            <span>Copy Link</span>
                        </button>
                    </div>

                    <a 
                        href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($refLink) ?>" 
                        target="_blank" 
                        class="px-3 py-2.5 bg-[#1877f2] hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1 shrink-0 shadow-sm"
                        title="Chia sẻ lên Facebook"
                    >
                        <i class="fa-brands fa-facebook text-xs"></i>
                        <span class="hidden sm:inline">Share FB</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- 2 Columns: Withdraw Form & Leaderboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            
            <!-- Left 2 Cols: Withdraw Form -->
            <div id="withdraw-section" class="lg:col-span-2 card-surface rounded-2xl p-5 md:p-6">
                <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                        </span>
                        <div>
                            <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Yêu Cầu Rút Tiền Về Ngân Hàng</h2>
                            <p class="text-[10px] text-slate-400">Rút tiền hoa hồng về tài khoản ngân hàng ATM / VietQR (Tối thiểu 50.000đ)</p>
                        </div>
                    </div>

                    <span class="text-xs font-extrabold text-emerald-600">
                        Ví CTV: <?= number_format($affBalance, 0, ',', '.') ?>đ
                    </span>
                </div>

                <form method="POST" action="/affiliate" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                    <input type="hidden" name="action" value="withdraw">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Ngân hàng thụ hưởng</label>
                            <input 
                                type="text" 
                                name="bank_name" 
                                required 
                                placeholder="Ví dụ: MB Bank, Vietcombank, Techcombank..." 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-xl text-xs font-bold text-slate-800 outline-none transition"
                            >
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Số tài khoản nhận tiền</label>
                            <input 
                                type="text" 
                                name="account_number" 
                                required 
                                placeholder="Ví dụ: 0359261551" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-xl text-xs font-mono font-bold text-slate-800 outline-none transition"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Tên chủ tài khoản (Viết hoa không dấu)</label>
                            <input 
                                type="text" 
                                name="account_name" 
                                required 
                                placeholder="Ví dụ: NGUYEN VAN A" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-xl text-xs font-bold uppercase text-slate-800 outline-none transition"
                            >
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Số tiền rút (VNĐ)</label>
                                <button type="button" onclick="setAllBalance(<?= $affBalance ?>)" class="text-[10px] font-bold text-emerald-600 hover:underline">
                                    Rút tất cả (<?= number_format($affBalance, 0, ',', '.') ?>đ)
                                </button>
                            </div>
                            <input 
                                type="number" 
                                id="withdrawAmountField" 
                                name="amount" 
                                required 
                                min="50000" 
                                max="<?= max(50000, $affBalance) ?>"
                                placeholder="Tối thiểu 50.000 VNĐ" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:bg-white rounded-xl text-xs font-black text-slate-900 outline-none transition"
                            >
                        </div>
                    </div>

                    <!-- Preset Quick Amount Buttons -->
                    <div class="flex items-center gap-2 pt-1">
                        <span class="text-[10px] text-slate-400 font-bold uppercase">Nhanh:</span>
                        <button type="button" onclick="setWithdrawAmount(50000)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold rounded-lg transition">50.000đ</button>
                        <button type="button" onclick="setWithdrawAmount(100000)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold rounded-lg transition">100.000đ</button>
                        <button type="button" onclick="setWithdrawAmount(200000)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold rounded-lg transition">200.000đ</button>
                        <button type="button" onclick="setWithdrawAmount(500000)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold rounded-lg transition">500.000đ</button>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold rounded-xl text-xs shadow-md shadow-emerald-500/10 transition flex items-center justify-center gap-2 cursor-pointer mt-2"
                    >
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Gửi Yêu Cầu Rút Tiền Về Ngân Hàng</span>
                    </button>
                </form>
            </div>

            <!-- Right 1 Col: Top Affiliates Leaderboard -->
            <div class="card-surface rounded-2xl p-5 md:p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                        <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-trophy"></i>
                        </span>
                        <div>
                            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Top Đối Tác Doanh Thu</h3>
                            <p class="text-[10px] text-slate-400">Bảng vinh danh CTV tiếp thị hàng đầu</p>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <?php if (empty($leaderboard)): ?>
                            <div class="text-center py-8 text-slate-400 text-xs">Chưa có dữ liệu bảng xếp hạng.</div>
                        <?php else: ?>
                            <?php foreach ($leaderboard as $idx => $lead): 
                                $isTop1 = ($idx === 0);
                                $isTop2 = ($idx === 1);
                                $isTop3 = ($idx === 2);

                                if ($isTop1) {
                                    $rankBadge = 'bg-amber-100 text-amber-700 border border-amber-300';
                                    $rankIcon = '🥇';
                                } else if ($isTop2) {
                                    $rankBadge = 'bg-slate-200 text-slate-700 border border-slate-300';
                                    $rankIcon = '🥈';
                                } else if ($isTop3) {
                                    $rankBadge = 'bg-orange-100 text-orange-700 border border-orange-300';
                                    $rankIcon = '🥉';
                                } else {
                                    $rankBadge = 'bg-slate-50 text-slate-500 border border-slate-200';
                                    $rankIcon = ($idx + 1);
                                }
                            ?>
                                <div class="flex items-center justify-between p-2.5 rounded-xl <?= $isTop1 ? 'bg-amber-50/40 border border-amber-200/60' : 'bg-slate-50/70 border border-slate-100' ?> text-xs">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <span class="w-6 h-6 rounded-lg <?= $rankBadge ?> flex items-center justify-center font-bold text-[11px] shrink-0">
                                            <?= $rankIcon ?>
                                        </span>
                                        <span class="font-bold text-slate-800 truncate"><?= htmlspecialchars($lead['username']) ?></span>
                                    </div>
                                    <span class="font-black text-emerald-600 font-mono shrink-0">
                                        <?= number_format($lead['balance'] ?? 0, 0, ',', '.') ?>đ
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4 text-[11px] text-slate-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-crown text-amber-500"></i>
                    <span>Thưởng thêm 5% doanh số cho TOP 1 hàng tháng.</span>
                </div>
            </div>

        </div>

        <!-- Withdrawal & Commission History Panel -->
        <section class="card-surface rounded-2xl overflow-hidden mb-6">
            
            <!-- Toolbar -->
            <div class="p-4 border-b border-slate-200/80 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 bg-white">
                <div class="flex items-center gap-1 p-1 bg-slate-100/90 rounded-xl">
                    <button 
                        type="button" 
                        onclick="switchHistoryTab('withdrawals')" 
                        id="tab-btn-withdrawals" 
                        class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-white text-emerald-600 shadow-sm"
                    >
                        <i class="fa-solid fa-money-bill-transfer text-xs"></i>
                        <span>Lịch Sử Rút Tiền (<?= $totalWithdrawCount ?>)</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="switchHistoryTab('commissions')" 
                        id="tab-btn-commissions" 
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 text-slate-600 hover:text-slate-900"
                    >
                        <i class="fa-solid fa-receipt text-xs"></i>
                        <span>Hoa Hồng Nhận Được (<?= $totalLogsCount ?>)</span>
                    </button>
                </div>

                <div class="text-xs text-slate-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span>Tự động cập nhật số dư sau mỗi đơn hàng thành công</span>
                </div>
            </div>

            <!-- TAB A: WITHDRAWAL REQUESTS TABLE -->
            <div id="history-withdrawals" class="overflow-x-auto">
                <?php if (empty($withdrawals)): ?>
                    <div class="text-center py-10 text-slate-400 text-xs">
                        <i class="fa-solid fa-file-invoice text-2xl mb-2 text-slate-300 block"></i>
                        Bạn chưa gửi yêu cầu rút tiền nào.
                    </div>
                <?php else: ?>
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider bg-slate-50/50">
                                <th class="py-3 px-4 font-extrabold">Mã Lệnh</th>
                                <th class="py-3 px-4 font-extrabold">Ngân Hàng Thụ Hưởng</th>
                                <th class="py-3 px-4 font-extrabold text-right">Số Tiền Rút</th>
                                <th class="py-3 px-4 font-extrabold">Thời Gian Tạo</th>
                                <th class="py-3 px-4 font-extrabold text-center">Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <?php foreach ($withdrawals as $w): ?>
                                <tr class="aff-row transition">
                                    <td class="py-3 px-4 font-mono font-bold text-slate-900">#W-<?= $w['id'] ?></td>
                                    <td class="py-3 px-4 text-slate-700">
                                        <div class="font-bold text-xs"><?= htmlspecialchars($w['bank_name']) ?></div>
                                        <div class="text-[10px] text-slate-400 font-mono"><?= htmlspecialchars($w['account_number'] ?? '') ?> - <?= htmlspecialchars($w['account_name'] ?? '') ?></div>
                                    </td>
                                    <td class="py-3 px-4 text-right font-black text-slate-900">
                                        <?= number_format($w['amount'], 0, ',', '.') ?>đ
                                    </td>
                                    <td class="py-3 px-4 text-slate-500 text-[11px]">
                                        <?= date('H:i d/m/Y', $w['created_at']) ?>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <?php if ($w['status'] === 'approved'): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-full border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Đã chuyển khoản
                                            </span>
                                        <?php elseif ($w['status'] === 'rejected'): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-extrabold rounded-full border border-rose-200">
                                                Bị từ chối
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-extrabold rounded-full border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Đang chờ duyệt
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- TAB B: COMMISSIONS LOGS TABLE -->
            <div id="history-commissions" class="overflow-x-auto hidden">
                <?php if (empty($logs)): ?>
                    <div class="text-center py-10 text-slate-400 text-xs">
                        <i class="fa-solid fa-sack-xmark text-2xl mb-2 text-slate-300 block"></i>
                        Chưa có lịch sử nhận hoa hồng nào. Hãy chia sẻ link giới thiệu để bắt đầu kiếm tiền!
                    </div>
                <?php else: ?>
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider bg-slate-50/50">
                                <th class="py-3 px-4 font-extrabold">Khách Hàng Mua</th>
                                <th class="py-3 px-4 font-extrabold">Đơn Hàng Gốc</th>
                                <th class="py-3 px-4 font-extrabold text-right">Hoa Hồng Nhận (+10%)</th>
                                <th class="py-3 px-4 font-extrabold">Thời Gian</th>
                                <th class="py-3 px-4 font-extrabold text-center">Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <?php foreach ($logs as $log): ?>
                                <tr class="aff-row transition">
                                    <td class="py-3 px-4 font-bold text-slate-900"><?= htmlspecialchars($log['buyer_name'] ?? 'Khách hàng') ?></td>
                                    <td class="py-3 px-4 font-mono text-slate-500 text-xs"><?= number_format($log['order_amount'] ?? 0, 0, ',', '.') ?>đ</td>
                                    <td class="py-3 px-4 text-right font-black text-emerald-600">+<?= number_format($log['commission_amount'] ?? 0, 0, ',', '.') ?>đ</td>
                                    <td class="py-3 px-4 text-slate-500 text-[11px]"><?= date('H:i d/m/Y', $log['created_at']) ?></td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-full border border-emerald-200">
                                            Đã cộng ví
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

        </section>

    </main>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 pointer-events-none flex flex-col gap-2"></div>

    <script>
        // Copy Helper
        function copyText(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                showToast("Sao chép thành công!", "success");
                if (btn) {
                    const icon = btn.querySelector('i');
                    if (icon) {
                        const oldClass = icon.className;
                        icon.className = 'fa-solid fa-check text-xs';
                        setTimeout(() => { icon.className = oldClass; }, 1800);
                    }
                }
            }).catch(() => {
                showToast("Không thể sao chép tự động.", "error");
            });
        }

        // Quick Amount Presets for Withdrawal
        function setWithdrawAmount(val) {
            const input = document.getElementById('withdrawAmountField');
            if (input) input.value = val;
        }

        function setAllBalance(balance) {
            const input = document.getElementById('withdrawAmountField');
            if (input) input.value = Math.floor(balance);
        }

        function scrollToWithdraw() {
            const sec = document.getElementById('withdraw-section');
            if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Switch History Tabs
        function switchHistoryTab(tab) {
            const tabWithdrawals = document.getElementById('history-withdrawals');
            const tabCommissions = document.getElementById('history-commissions');
            const btnWithdrawals = document.getElementById('tab-btn-withdrawals');
            const btnCommissions = document.getElementById('tab-btn-commissions');

            if (tab === 'withdrawals') {
                tabWithdrawals.classList.remove('hidden');
                tabCommissions.classList.add('hidden');
                btnWithdrawals.className = "px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-white text-emerald-600 shadow-sm";
                btnCommissions.className = "px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 text-slate-600 hover:text-slate-900";
            } else {
                tabCommissions.classList.remove('hidden');
                tabWithdrawals.classList.add('hidden');
                btnCommissions.className = "px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-white text-emerald-600 shadow-sm";
                btnWithdrawals.className = "px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 text-slate-600 hover:text-slate-900";
            }
        }

        // Toast Helper
        function showToast(message, type = "success") {
            const container = document.getElementById("toast-container");
            const toast = document.createElement("div");
            toast.className = "pointer-events-auto flex items-center gap-2 px-3.5 py-2.5 rounded-xl shadow-lg border border-slate-200 bg-white transition-all duration-200 transform translate-y-4 opacity-0 text-xs font-bold text-slate-800";
            
            const icon = type === "success" 
                ? '<i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>'
                : '<i class="fa-solid fa-circle-exclamation text-rose-500 text-sm"></i>';
            
            toast.innerHTML = `${icon}<span>${message}</span>`;
            container.appendChild(toast);
            
            setTimeout(() => { toast.classList.remove("translate-y-4", "opacity-0"); }, 10);
            setTimeout(() => {
                toast.classList.add("translate-y-2", "opacity-0");
                setTimeout(() => { toast.remove(); }, 250);
            }, 2000);
        }
    </script>
</body>
</html>