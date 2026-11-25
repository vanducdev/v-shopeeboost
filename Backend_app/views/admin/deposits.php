<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyệt Tiền Nạp - VMAX Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 min-h-screen overflow-y-auto">
        <header class="sticky top-0 z-30 px-8 py-4 flex items-center justify-between border-b border-slate-200 bg-white/95 backdrop-blur-md">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Duyệt Tiền Nạp</h1>
                <p class="text-slate-500 text-xs">Xác minh và xử lý yêu cầu nạp tiền từ khách hàng</p>
            </div>
            <div class="flex items-center gap-3">
                <?php $pending = array_filter($deposits, fn($d) => $d['status'] === 'pending'); ?>
                <?php if (count($pending) > 0): ?>
                    <span class="px-3 py-1.5 text-xs font-bold rounded-xl border bg-amber-50 text-amber-700 border-amber-200 animate-pulse"><?= count($pending) ?> Chờ Duyệt</span>
                <?php endif; ?>
            </div>
        </header>

        <div class="px-6 md:px-8 py-8">
            <?php if (!empty($message)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250"><?= $message ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-red-50 text-red-700 border border-red-200"><?= $error ?></div>
            <?php endif; ?>

            <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800">Danh Sách Yêu Cầu Nạp Tiền</h2>
                    <span class="text-[10px] text-slate-450 font-bold uppercase"><?= count($deposits) ?> giao dịch</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-semibold bg-slate-50/20">
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Khách Hàng</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Mã Chuyển Khoản</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Số Tiền</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Trạng Thái</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Thời Gian</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 font-medium">
                            <?php if (empty($deposits)): ?>
                                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-450 text-xs italic">Chưa có yêu cầu nạp tiền nào.</td></tr>
                            <?php else: ?>
                                <?php foreach ($deposits as $dep): ?>
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition <?= $dep['status'] === 'pending' ? 'border-l-2 border-l-amber-500' : '' ?>">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-xs font-black text-slate-700 shrink-0">
                                                    <?= mb_strtoupper(mb_substr($dep['username'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="text-xs font-bold text-slate-800"><?= htmlspecialchars($dep['username']) ?></div>
                                                    <div class="text-[10px] text-slate-400"><?= htmlspecialchars($dep['email']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <code class="text-[10px] font-mono px-2.5 py-1.5 rounded-lg border bg-blue-50 text-blue-600 border-blue-100"><?= htmlspecialchars($dep['reference_code']) ?></code>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-extrabold text-emerald-600"><?= number_format($dep['amount'], 0, ',', '.') ?>đ</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($dep['status'] === 'approved'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Đã duyệt</span>
                                            <?php elseif ($dep['status'] === 'rejected'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-slate-100 text-slate-500 border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Từ chối</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-amber-50 text-amber-600 border border-amber-100 animate-pulse"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Chờ duyệt</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-[10px] text-slate-400 font-mono"><?= date('d/m/Y H:i', $dep['created_at']) ?></td>
                                        <td class="px-6 py-4">
                                            <?php if ($dep['status'] === 'pending'): ?>
                                                <div class="flex items-center justify-end gap-2">
                                                    <form method="POST" action="/admin/deposits" onsubmit="return confirm('Xác nhận duyệt <?= number_format($dep['amount'],0,',','.') ?>đ cho <?= htmlspecialchars($dep['username']) ?>?');">
                                                        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                                        <input type="hidden" name="deposit_id" value="<?= $dep['id'] ?>">
                                                        <input type="hidden" name="action" value="approve">
                                                        <button type="submit" class="px-3 py-1.5 text-[10px] font-bold rounded-lg transition text-white bg-emerald-600 hover:bg-emerald-700">✓ Duyệt</button>
                                                    </form>
                                                    <form method="POST" action="/admin/deposits" onsubmit="return confirm('Từ chối yêu cầu này?');">
                                                        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                                        <input type="hidden" name="deposit_id" value="<?= $dep['id'] ?>">
                                                        <input type="hidden" name="action" value="reject">
                                                        <button type="submit" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-red-50 text-red-600 border-red-200 hover:bg-red-100">✗ Hủy</button>
                                                    </form>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-right text-[10px] text-slate-450 italic">Đã xử lý</div>
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
