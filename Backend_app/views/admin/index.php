<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Trị VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -10px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 min-h-screen overflow-y-auto">
        <!-- Top Bar -->
        <header class="sticky top-0 z-30 px-8 py-4 flex items-center justify-between border-b border-slate-200 bg-white/95 backdrop-blur-md">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Bảng Điều Khiển Quản Trị</h1>
                <p class="text-slate-500 text-xs">Theo dõi các chỉ số kinh doanh theo thời gian thực</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-500 font-mono"><?= date('d/m/Y H:i') ?></span>
                <div class="w-px h-5 bg-slate-200"></div>
                <span class="px-3 py-1.5 text-xs font-bold rounded-xl border bg-emerald-50 text-emerald-600 border-emerald-250">● Hệ thống hoạt động</span>
            </div>
        </header>

        <div class="px-6 md:px-8 py-8">
            <!-- Stats Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <!-- Revenue -->
                <div class="stat-card bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm relative overflow-hidden">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-emerald-50 border border-emerald-100">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-lg">Doanh Thu</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900"><?= number_format($revenue, 0, ',', '.') ?>đ</div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">Tổng doanh thu tích lũy</div>
                </div>

                <!-- Users -->
                <div class="stat-card bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm relative overflow-hidden">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-50 border border-blue-100">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-lg">Người Dùng</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900"><?= number_format($users_count) ?><span class="text-base font-semibold text-slate-400 ml-1">users</span></div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">Tổng tài khoản đã đăng ký</div>
                </div>

                <!-- Keys -->
                <div class="stat-card bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm relative overflow-hidden">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-indigo-50 border border-indigo-100">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-lg">License</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900"><?= number_format($licenses_count) ?><span class="text-base font-semibold text-slate-400 ml-1">keys</span></div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">Tổng mã key đã cấp phát</div>
                </div>

                <!-- Orders -->
                <div class="stat-card bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm relative overflow-hidden">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-amber-50 border border-amber-100">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded-lg">Đơn Hàng</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900"><?= number_format($orders_count) ?><span class="text-base font-semibold text-slate-400 ml-1">orders</span></div>
                    <div class="text-xs text-slate-500 mt-1 font-medium">Đơn hàng đã hoàn thành</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <a href="/admin/deposits" class="bg-white border border-slate-200/60 rounded-xl p-4 flex items-center gap-3 hover:border-slate-350 hover:bg-slate-50 transition group">
                    <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-indigo-650" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div><div class="text-xs font-bold text-slate-800">Duyệt Nạp Tiền</div><div class="text-[10px] text-slate-450 mt-0.5">Xem yêu cầu chờ duyệt</div></div>
                </a>
                <a href="/admin/tickets" class="bg-white border border-slate-200/60 rounded-xl p-4 flex items-center gap-3 hover:border-slate-350 hover:bg-slate-50 transition group">
                    <div class="w-9 h-9 rounded-lg bg-violet-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-violet-650" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <div><div class="text-xs font-bold text-slate-800">Hỗ Trợ Ticket</div><div class="text-[10px] text-slate-455 mt-0.5">Phản hồi khách hàng</div></div>
                </a>
                <a href="/admin/licenses" class="bg-white border border-slate-200/60 rounded-xl p-4 flex items-center gap-3 hover:border-slate-350 hover:bg-slate-50 transition group">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-blue-650" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    </div>
                    <div><div class="text-xs font-bold text-slate-800">Cấp Key Mới</div><div class="text-[10px] text-slate-450 mt-0.5">Tạo license key</div></div>
                </a>
                <a href="/admin/users" class="bg-white border border-slate-200/60 rounded-xl p-4 flex items-center gap-3 hover:border-slate-350 hover:bg-slate-50 transition group">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-emerald-650" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div><div class="text-xs font-bold text-slate-800">Quản Lý Users</div><div class="text-[10px] text-slate-450 mt-0.5">Xem tài khoản người dùng</div></div>
                </a>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Giao Dịch Thanh Toán Gần Đây</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Các đơn hàng được xử lý mới nhất</p>
                    </div>
                    <a href="/admin/payments" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                        Xem tất cả
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-semibold bg-slate-50/50">
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Khách Hàng</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Số Tiền</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Phương Thức</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Trạng Thái</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Thời Gian</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 font-medium">
                            <?php if (empty($recent_payments)): ?>
                                <tr><td colspan="5" class="px-6 py-10 text-center text-slate-450 text-xs italic">Không có giao dịch nào gần đây.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recent_payments as $pay): ?>
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-black text-slate-700 shrink-0">
                                                    <?= mb_strtoupper(mb_substr($pay['username'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="text-xs font-bold text-slate-800"><?= htmlspecialchars($pay['username']) ?></div>
                                                    <div class="text-[10px] text-slate-400">#ORD-<?= $pay['order_id'] ?? '—' ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-extrabold text-emerald-600"><?= number_format($pay['amount'], 0, ',', '.') ?>đ</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-[10px] font-bold rounded-lg border text-slate-600 bg-slate-50 border-slate-100"><?= htmlspecialchars($pay['payment_method']) ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($pay['status'] === 'completed'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>Thành công
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-amber-50 text-amber-600 border border-amber-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block animate-pulse"></span>Chờ duyệt
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-[10px] text-slate-400 font-mono"><?= date('d/m/Y H:i', $pay['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
