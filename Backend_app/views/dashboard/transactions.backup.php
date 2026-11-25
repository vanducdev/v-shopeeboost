<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Giao Dịch - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .tab-btn-active {
            background-color: #3b82f6;
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Client Sidebar -->
    <?php require BASE_PATH . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-6 md:p-10 max-w-7xl mx-auto w-full overflow-x-hidden">
        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8 pb-6 border-b border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Lịch sử giao dịch</h1>
                <p class="text-slate-500 text-xs mt-1 font-medium">Theo dõi các giao dịch nạp tiền, mua mới và gia hạn giấy phép khóa bản quyền của bạn.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-50 text-emerald-700 text-xs font-extrabold rounded-2xl border border-emerald-100/50 shadow-sm">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    Số dư: <?= number_format($user['balance'] ?? 0, 0, ',', '.') ?>đ
                </span>
            </div>
        </header>

        <!-- Navigation Tabs -->
        <div class="flex p-1 bg-slate-100 border border-slate-200/30 rounded-2xl gap-1 mb-8 max-w-md shadow-inner">
            <button onclick="switchTab('deposits')" id="tabBtn-deposits" class="flex-1 py-2.5 text-xs font-extrabold rounded-xl transition duration-200 tab-btn-active">
                Lịch sử nạp tiền
            </button>
            <button onclick="switchTab('orders')" id="tabBtn-orders" class="flex-1 py-2.5 text-xs font-extrabold rounded-xl transition duration-200 text-slate-500 hover:text-slate-700">
                Giao dịch mua & gia hạn
            </button>
        </div>

        <!-- Tab 1: Deposits list -->
        <div id="tab-deposits" class="space-y-6">
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider">
                                <th class="pb-3 font-bold">Mã giao dịch</th>
                                <th class="pb-3 font-bold">Số tiền nạp</th>
                                <th class="pb-3 font-bold">Trạng thái</th>
                                <th class="pb-3 font-bold">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 font-medium">
                            <?php if (empty($deposits)): ?>
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400">Bạn chưa thực hiện yêu cầu nạp tiền nào.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($deposits as $dep): ?>
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                        <td class="py-4 font-mono font-bold text-slate-900"><?= htmlspecialchars($dep['reference_code']) ?></td>
                                        <td class="font-extrabold text-slate-950 text-xs"><?= number_format($dep['amount'], 0, ',', '.') ?>đ</td>
                                        <td class="py-4">
                                            <?php if ($dep['status'] === 'approved'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-600">
                                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                                    Đã duyệt tiền
                                                </span>
                                            <?php elseif ($dep['status'] === 'rejected'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[9px] font-bold bg-slate-100 text-slate-500">
                                                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                                    Đã hủy
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[9px] font-bold bg-amber-50 text-amber-600">
                                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span>
                                                    Chờ xử lý
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 text-slate-400"><?= date('d/m/Y H:i', $dep['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Orders list -->
        <div id="tab-orders" class="space-y-6 hidden">
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider">
                                <th class="pb-3 font-bold">Mã hóa đơn</th>
                                <th class="pb-3 font-bold">Giá trị thanh toán</th>
                                <th class="pb-3 font-bold">Phương thức</th>
                                <th class="pb-3 font-bold">Mã GD ngân hàng</th>
                                <th class="pb-3 font-bold">Trạng thái</th>
                                <th class="pb-3 font-bold">Thời gian</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 font-medium">
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">Bạn chưa thực hiện giao dịch mua hay gia hạn nào.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $ord): ?>
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                        <td class="py-4 font-mono font-bold text-slate-900">#ORD-<?= htmlspecialchars($ord['id']) ?></td>
                                        <td class="font-extrabold text-blue-600 text-xs"><?= number_format($ord['total_amount'], 0, ',', '.') ?>đ</td>
                                        <td class="py-4 text-slate-500 font-bold uppercase"><?= htmlspecialchars($ord['payment_method'] ?? 'Banking') ?></td>
                                        <td class="py-4"><code class="font-mono text-slate-500 bg-slate-50 px-2 py-0.5 rounded text-[10px]"><?= htmlspecialchars($ord['transaction_id'] ?? 'N/A') ?></code></td>
                                        <td class="py-4">
                                            <?php if ($ord['status'] === 'completed'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-600">
                                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                                    Thành công
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[9px] font-bold bg-amber-50 text-amber-600">
                                                    <span class="w-1.5 h-1.5 bg-amber-550 rounded-full"></span>
                                                    <?= htmlspecialchars($ord['status']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 text-slate-400"><?= date('d/m/Y H:i', $ord['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <script>
        function switchTab(tabId) {
            const tabDeposits = document.getElementById('tab-deposits');
            const tabOrders = document.getElementById('tab-orders');
            const btnDeposits = document.getElementById('tabBtn-deposits');
            const btnOrders = document.getElementById('tabBtn-orders');

            if (tabId === 'deposits') {
                tabDeposits.classList.remove('hidden');
                tabOrders.classList.add('hidden');
                
                btnDeposits.className = "flex-1 py-2.5 text-xs font-extrabold rounded-xl transition duration-200 tab-btn-active";
                btnOrders.className = "flex-1 py-2.5 text-xs font-extrabold rounded-xl transition duration-200 text-slate-500 hover:text-slate-700";
            } else {
                tabOrders.classList.remove('hidden');
                tabDeposits.classList.add('hidden');
                
                btnOrders.className = "flex-1 py-2.5 text-xs font-extrabold rounded-xl transition duration-200 tab-btn-active";
                btnDeposits.className = "flex-1 py-2.5 text-xs font-extrabold rounded-xl transition duration-200 text-slate-500 hover:text-slate-700";
            }
        }

        // Auto switch tab based on URL query or hash
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab === 'orders' || window.location.hash === '#orders') {
                switchTab('orders');
            }
        });
    </script>

</body>
</html>
