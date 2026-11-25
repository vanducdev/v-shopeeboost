<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu Cầu Rút Tiền - VMAX Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-6 md:p-10 max-w-7xl mx-auto w-full">
        <header class="mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Quản Lý Affiliate & Hoa Hồng</h1>
            <p class="text-slate-500 text-sm mt-1">Quản lý các cộng tác viên, duyệt yêu cầu rút tiền và quản lý các khoản hoa hồng giới thiệu sản phẩm.</p>
        </header>

        <!-- Tabs -->
        <div class="flex gap-6 mb-6 border-b border-slate-200">
            <button onclick="switchTab('withdraw')" id="tab-withdraw-btn" class="pb-3 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 focus:outline-none transition">
                Yêu Cầu Rút Tiền (<?= count($requests) ?>)
            </button>
            <button onclick="switchTab('commissions')" id="tab-commissions-btn" class="pb-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 focus:outline-none transition">
                Lịch Sử Hoa Hồng (<?= count($commissions) ?>)
            </button>
        </div>

        <!-- Section 1: Withdraw Requests -->
        <section id="withdraw-section" class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100">
                            <th class="pb-3 font-semibold">Username</th>
                            <th class="pb-3 font-semibold">Thông Tin Ngân Hàng</th>
                            <th class="pb-3 font-semibold">Số tiền</th>
                            <th class="pb-3 font-semibold">Trạng thái</th>
                            <th class="pb-3 font-semibold">Ngày yêu cầu</th>
                            <th class="pb-3 font-semibold">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-medium">
                        <?php foreach ($requests as $req): ?>
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                <td class="py-4 font-bold"><?= htmlspecialchars($req['username']) ?></td>
                                <td>
                                    <div class="text-sm"><?= htmlspecialchars($req['bank_name']) ?></div>
                                    <div class="text-xs text-slate-400">STK: <?= htmlspecialchars($req['account_number']) ?> - Tên: <?= htmlspecialchars($req['account_name']) ?></div>
                                </td>
                                <td class="text-emerald-600 font-extrabold"><?= number_format($req['amount'], 0, ',', '.') ?>đ</td>
                                <td>
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full <?= $req['status'] === 'approved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' ?>">
                                        <?= htmlspecialchars($req['status']) ?>
                                    </span>
                                </td>
                                <td class="text-slate-400 text-xs"><?= date('d/m/Y H:i', $req['created_at']) ?></td>
                                <td>
                                    <?php if ($req['status'] === 'pending'): ?>
                                        <form method="POST" action="/admin/affiliate">
                                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                            <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                                            <input type="hidden" name="action" value="approve_withdraw">
                                            <button type="submit" class="px-3 py-1 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition">Đã Chuyển Tiền</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs italic">Đã xử lý</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($requests)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-6 text-slate-400 italic">Không có yêu cầu rút tiền nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Section 2: Commission Logs -->
        <section id="commissions-section" class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100">
                            <th class="pb-3 font-semibold">Cộng Tác Viên</th>
                            <th class="pb-3 font-semibold">Người Mua</th>
                            <th class="pb-3 font-semibold">Mã Đơn</th>
                            <th class="pb-3 font-semibold">Giá Trị Đơn</th>
                            <th class="pb-3 font-semibold">Tiền Hoa Hồng</th>
                            <th class="pb-3 font-semibold">Trạng Thái</th>
                            <th class="pb-3 font-semibold">Ngày Tạo</th>
                            <th class="pb-3 font-semibold">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-medium">
                        <?php foreach ($commissions as $comm): ?>
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                <td class="py-4 font-bold"><?= htmlspecialchars($comm['referrer_name']) ?></td>
                                <td><?= htmlspecialchars($comm['buyer_name']) ?></td>
                                <td>#ORD-<?= $comm['order_id'] ?></td>
                                <td><?= number_format($comm['total_amount'], 0, ',', '.') ?>đ</td>
                                <td class="text-emerald-600 font-extrabold"><?= number_format($comm['commission_amount'], 0, ',', '.') ?>đ</td>
                                <td>
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full <?= $comm['status'] === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($comm['status'] === 'rejected' ? 'bg-red-50 text-red-650' : 'bg-amber-50 text-amber-600') ?>">
                                        <?= htmlspecialchars($comm['status']) ?>
                                    </span>
                                </td>
                                <td class="text-slate-400 text-xs"><?= date('d/m/Y H:i', $comm['created_at']) ?></td>
                                <td>
                                    <?php if ($comm['status'] === 'pending'): ?>
                                        <div class="flex gap-2">
                                            <form method="POST" action="/admin/affiliate">
                                                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                                <input type="hidden" name="log_id" value="<?= $comm['id'] ?>">
                                                <input type="hidden" name="action" value="approve_commission">
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition">Duyệt</button>
                                            </form>
                                            <form method="POST" action="/admin/affiliate">
                                                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                                <input type="hidden" name="log_id" value="<?= $comm['id'] ?>">
                                                <input type="hidden" name="action" value="reject_commission">
                                                <button type="submit" class="px-2.5 py-1 bg-white border border-red-200 text-red-600 text-xs font-bold rounded-lg hover:bg-red-50 transition">Hủy</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-400 text-xs italic">Đã xử lý</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($commissions)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-6 text-slate-400 italic">Không có lịch sử hoa hồng nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
    function switchTab(tab) {
        if (tab === 'withdraw') {
            document.getElementById('withdraw-section').classList.remove('hidden');
            document.getElementById('commissions-section').classList.add('hidden');
            
            document.getElementById('tab-withdraw-btn').className = "pb-3 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 focus:outline-none transition";
            document.getElementById('tab-commissions-btn').className = "pb-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 focus:outline-none transition";
        } else {
            document.getElementById('withdraw-section').classList.add('hidden');
            document.getElementById('commissions-section').classList.remove('hidden');
            
            document.getElementById('tab-withdraw-btn').className = "pb-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 focus:outline-none transition";
            document.getElementById('tab-commissions-btn').className = "pb-3 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 focus:outline-none transition";
        }
    }
    </script>
</body>
</html>
