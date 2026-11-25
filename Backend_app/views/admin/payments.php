<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn Hàng Thanh Toán - VMAX Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 min-h-screen overflow-y-auto">
        <header class="sticky top-0 z-30 px-8 py-4 flex items-center justify-between border-b border-slate-200 bg-white/95 backdrop-blur-md">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Đơn Hàng Thanh Toán</h1>
                <p class="text-slate-500 text-xs">Toàn bộ lịch sử giao dịch mua bản quyền phần mềm</p>
            </div>
            <span class="px-3 py-1.5 text-xs font-bold rounded-xl border bg-blue-50 text-blue-600 border-blue-200"><?= count($payments) ?> Đơn Hàng</span>
        </header>

        <div class="px-6 md:px-8 py-8">
            <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-bold text-slate-800">Toàn Bộ Lịch Sử Đơn Hàng</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-semibold bg-slate-50/20">
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Khách Hàng</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Số Tiền</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Mã Đơn Hàng</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Phương Thức</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Mã Giao Dịch</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Trạng Thái</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 font-medium">
                            <?php if (empty($payments)): ?>
                                <tr><td colspan="7" class="px-6 py-12 text-center text-slate-450 text-xs italic">Chưa có đơn hàng nào.</td></tr>
                            <?php else: ?>
                                <?php foreach ($payments as $pay): ?>
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-black text-slate-700 shrink-0">
                                                    <?= mb_strtoupper(mb_substr($pay['username'], 0, 1)) ?>
                                                </div>
                                                <span class="text-xs font-bold text-slate-800"><?= htmlspecialchars($pay['username']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-extrabold text-emerald-600"><?= number_format($pay['amount'], 0, ',', '.') ?>đ</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <code class="text-[10px] font-mono px-2 py-1 rounded-lg border bg-slate-50 text-slate-655 border-slate-150">#ORD-<?= $pay['order_id'] ?></code>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-[10px] font-bold rounded-lg border text-slate-600 bg-slate-50 border-slate-100"><?= htmlspecialchars($pay['payment_method']) ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <code class="text-[10px] font-mono text-slate-450"><?= htmlspecialchars($pay['transaction_id'] ?? '—') ?></code>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($pay['status'] === 'completed'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Hoàn thành</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-amber-50 text-amber-600 border border-amber-100 animate-pulse"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Chờ duyệt</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <?php if ($pay['status'] === 'pending'): ?>
                                                <form method="POST" action="/admin/payments">
                                                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                                    <input type="hidden" name="order_id" value="<?= $pay['order_id'] ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="px-3 py-1.5 text-[10px] font-bold rounded-lg transition text-white bg-emerald-600 hover:bg-emerald-700">Duyệt Đơn</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-[10px] text-slate-450 italic">Đã xử lý</span>
                                            <?php endif; ?>
                                        </td>
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
