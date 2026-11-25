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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .glass-panel-glow {
            box-shadow: 0 10px 30px -5px rgba(16, 185, 129, 0.08);
        }
        .scrollbar-custom::-webkit-scrollbar {
            width: 5px;
        }
        .scrollbar-custom::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.01);
        }
        .scrollbar-custom::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 99px;
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen flex flex-col md:flex-row">

    <!-- Client Sidebar -->
    <?php require BASE_PATH . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-4 md:p-8 max-w-7xl mx-auto w-full overflow-hidden">
        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8 pb-6 border-b border-slate-200/60">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <h1 class="text-2xl font-extrabold text-slate-950 tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700">
                        Tiếp Thị Liên Kết
                    </h1>
                </div>
                <p class="text-slate-500 text-xs mt-1">Giới thiệu người dùng khác mua sản phẩm của VMAX và nhận hoa hồng hấp dẫn tức thì.</p>
            </div>
        </header>

        <!-- Status Alerts -->
        <?php if (!empty($error)): ?>
            <div class="bg-rose-50 border border-rose-200/60 text-rose-700 px-4 py-3.5 rounded-xl text-xs mb-6 flex items-center gap-2.5">
                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <div class="bg-emerald-50 border border-emerald-200/60 text-emerald-700 px-4 py-3.5 rounded-xl text-xs mb-6 flex items-center gap-2.5">
                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Affiliate Link Info & Balance -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="glass-panel rounded-2xl p-6 lg:col-span-2 flex flex-col justify-between gap-4 shadow-sm">
                <div>
                    <span class="text-blue-600 text-[10px] font-extrabold uppercase tracking-widest block mb-1">Đường dẫn giới thiệu của bạn</span>
                    <p class="text-slate-500 text-[11px]">Nhận hoa hồng trọn đời khi người dùng đăng ký tài khoản qua liên kết này và mua bản quyền.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <input type="text" readonly value="<?= SITE_URL ?>/register?ref=<?= htmlspecialchars($profile['referral_code'] ?? '') ?>" id="ref_link" class="flex-1 bg-slate-50 border border-slate-200/70 rounded-xl px-4 py-3 text-xs outline-none text-blue-600 font-bold font-mono tracking-wide select-all">
                    <button onclick="copyToClipboard(document.getElementById('ref_link').value)" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-xs shadow-md shadow-blue-500/10 shrink-0 flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        Sao chép liên kết
                    </button>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-6 rounded-2xl shadow-lg shadow-emerald-500/10 flex flex-col justify-between text-white relative overflow-hidden group">
                <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:bg-white/20 transition-all"></div>
                <div>
                    <span class="text-emerald-100 text-[10px] font-extrabold uppercase tracking-widest block mb-1">Số dư hoa hồng khả dụng</span>
                    <span class="text-3xl font-extrabold tracking-tight block mt-2"><?= number_format($profile['balance'] ?? 0, 0, ',', '.') ?>đ</span>
                </div>
                <div class="text-[10px] text-emerald-100 mt-4 font-medium leading-relaxed">
                    Bạn có thể gửi yêu cầu rút tiền về tài khoản ngân hàng bất cứ lúc nào (tối thiểu 50.000đ).
                </div>
            </div>
        </section>

        <!-- Withdraw Requests Form & Leaderboard -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Withdrawal Request Form -->
            <div class="glass-panel rounded-2xl p-6 shadow-sm">
                <h2 class="text-xs font-bold text-slate-800 mb-5 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Yêu cầu rút tiền hoa hồng
                </h2>
                <form method="POST" action="/affiliate" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                    <input type="hidden" name="action" value="withdraw">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="bank_name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tên ngân hàng</label>
                            <input type="text" id="bank_name" name="bank_name" required placeholder="Ví dụ: Vietcombank" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none text-xs text-slate-850 font-semibold">
                        </div>
                        <div class="space-y-1.5">
                            <label for="account_number" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Số tài khoản</label>
                            <input type="text" id="account_number" name="account_number" required placeholder="0123456789" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none text-xs text-slate-850 font-semibold">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label for="account_name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tên chủ tài khoản</label>
                        <input type="text" id="account_name" name="account_name" required placeholder="NGUYEN VAN A" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none text-xs text-slate-850 font-semibold uppercase">
                    </div>
                    <div class="space-y-1.5">
                        <label for="amount" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Số tiền rút (VNĐ)</label>
                        <input type="number" id="amount" name="amount" required min="50000" placeholder="Tối thiểu 50.000 VNĐ" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none text-xs text-slate-850 font-semibold">
                    </div>
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-xl transition-all text-xs shadow-md shadow-emerald-500/10 mt-2 flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Gửi yêu cầu rút tiền
                    </button>
                </form>
            </div>

            <!-- Leaderboard list -->
            <div class="glass-panel rounded-2xl p-6 shadow-sm">
                <h2 class="text-xs font-bold text-slate-800 mb-5 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Bảng xếp hạng tiếp thị liên kết
                </h2>
                <div class="space-y-3.5 max-h-[260px] overflow-y-auto scrollbar-custom pr-1">
                    <?php if (empty($leaderboard)): ?>
                        <div class="text-center py-8 text-slate-400 text-xs">Chưa có bảng xếp hạng.</div>
                    <?php else: ?>
                        <?php foreach ($leaderboard as $idx => $lead): 
                            $badgeBg = 'bg-slate-100 text-slate-500 border border-slate-200';
                            if ($idx === 0) $badgeBg = 'bg-amber-500/10 text-amber-600 border border-amber-500/20';
                            else if ($idx === 1) $badgeBg = 'bg-slate-300/20 text-slate-600 border border-slate-300/30';
                            else if ($idx === 2) $badgeBg = 'bg-orange-500/10 text-orange-600 border border-orange-500/20';
                        ?>
                            <div class="flex justify-between items-center text-xs font-semibold border-b border-slate-100 pb-2.5 last:border-0 last:pb-0">
                                <div class="flex items-center gap-3">
                                    <span class="w-5 h-5 flex items-center justify-center rounded-full text-[9px] font-extrabold <?= $badgeBg ?>">
                                        <?= $idx + 1 ?>
                                    </span>
                                    <span class="text-slate-800 font-bold"><?= htmlspecialchars($lead['username']) ?></span>
                                </div>
                                <span class="text-slate-900 font-bold font-mono"><?= number_format($lead['balance'] ?? 0, 0, ',', '.') ?>đ</span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Withdraw Requests Table -->
        <section class="glass-panel rounded-2xl p-6 shadow-sm">
            <h2 class="text-xs font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4 uppercase tracking-widest">Lịch sử rút tiền</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider">
                            <th class="pb-3 font-bold">Mã lệnh</th>
                            <th class="pb-3 font-bold">Ngân hàng</th>
                            <th class="pb-3 font-bold">Số tiền</th>
                            <th class="pb-3 font-bold">Trạng thái</th>
                            <th class="pb-3 font-bold">Ngày yêu cầu</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-medium">
                        <?php if (empty($withdrawals)): ?>
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">Không có yêu cầu rút tiền nào.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($withdrawals as $w): 
                                $statusColor = 'bg-slate-100 text-slate-500 border-slate-200';
                                if ($w['status'] === 'approved') {
                                    $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                } elseif ($w['status'] === 'pending') {
                                    $statusColor = 'bg-amber-50 text-amber-600 border-amber-100';
                                } elseif ($w['status'] === 'rejected') {
                                    $statusColor = 'bg-rose-50 text-rose-600 border-rose-100';
                                }
                            ?>
                                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                                    <td class="py-4 font-bold text-slate-900">#W-<?= $w['id'] ?></td>
                                    <td class="py-4 text-slate-500"><?= htmlspecialchars($w['bank_name']) ?></td>
                                    <td class="py-4 text-slate-900 font-bold font-mono"><?= number_format($w['amount'], 0, ',', '.') ?>đ</td>
                                    <td class="py-4">
                                        <span class="inline-flex px-2.5 py-1 text-[9px] font-bold rounded-full border <?= $statusColor ?>">
                                            <?= $w['status'] === 'approved' ? 'Đã duyệt' : ($w['status'] === 'pending' ? 'Đang chờ' : 'Bị từ chối') ?>
                                        </span>
                                    </td>
                                    <td class="py-4 text-slate-500"><?= date('d/m/Y H:i', $w['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Beautiful Animated Toast Notifications -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 pointer-events-none flex flex-col gap-3"></div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast("Sao chép liên kết tiếp thị thành công!", "success");
            }).catch(err => {
                showToast("Không thể sao chép tự động.", "error");
            });
        }

        function showToast(message, type = "success") {
            const container = document.getElementById("toast-container");
            const toast = document.createElement("div");
            toast.className = "pointer-events-auto flex items-center gap-2.5 px-4.5 py-3.5 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] border border-slate-200/80 bg-white transition-all duration-300 transform translate-y-6 opacity-0 text-xs font-semibold text-slate-800";
            
            const icon = type === "success" 
                ? '<svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                : '<svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            
            toast.innerHTML = `${icon}<span>${message}</span>`;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove("translate-y-6", "opacity-0");
            }, 10);
            
            setTimeout(() => {
                toast.classList.add("translate-y-2", "opacity-0");
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 2500);
        }
    </script>
</body>
</html>
