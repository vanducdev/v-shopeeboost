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

        .tx-row {
            transition: background-color 0.15s ease;
        }
        .tx-row:hover {
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
                        Lịch Sử Giao Dịch
                    </h1>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                        Audit Log & Invoices
                    </span>
                </div>
                <p class="text-slate-500 text-xs mt-0.5">
                    Theo dõi sao kê nạp tiền VietQR, hóa đơn mua mới và gia hạn giấy phép bản quyền của tài khoản.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="/deposit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-plus text-[11px]"></i>
                    <span>+ Nạp Tiền VietQR</span>
                </a>
                
                <a href="/renew" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-cart-shopping text-blue-600 text-[11px]"></i>
                    <span>Mua Bản Quyền Mới</span>
                </a>
            </div>
        </div>

        <?php
        // Calculate Totals
        $totalDeposited = 0;
        $totalSpent = 0;
        $totalDepositCount = count($deposits ?? []);
        $totalOrderCount = count($orders ?? []);

        if (!empty($deposits)) {
            foreach ($deposits as $d) {
                if ($d['status'] === 'approved') {
                    $totalDeposited += (float)$d['amount'];
                }
            }
        }

        if (!empty($orders)) {
            foreach ($orders as $o) {
                if ($o['status'] === 'completed') {
                    $totalSpent += (float)$o['total_amount'];
                }
            }
        }
        ?>

        <!-- Compact 4 KPI Stats Overview -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
            <!-- Stat 1 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">SỐ DƯ VÍ HIỆN TẠI</span>
                    <span class="text-xl font-black text-slate-900 tracking-tight mt-0.5 block">
                        <?= number_format($user['balance'] ?? 0, 0, ',', '.') ?>đ
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">TỔNG TIỀN ĐÃ NẠP</span>
                    <span class="text-xl font-black text-emerald-600 tracking-tight mt-0.5 block">
                        +<?= number_format($totalDeposited, 0, ',', '.') ?>đ
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-arrow-down-long"></i>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">TỔNG CHI TIÊU BẢN QUYỀN</span>
                    <span class="text-xl font-black text-indigo-600 tracking-tight mt-0.5 block">
                        -<?= number_format($totalSpent, 0, ',', '.') ?>đ
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-arrow-up-long"></i>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">TỔNG GIAO DỊCH</span>
                    <span class="text-xl font-black text-slate-800 tracking-tight mt-0.5 block">
                        <?= $totalDepositCount + $totalOrderCount ?> <span class="text-xs font-semibold text-slate-400">lượt</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
        </section>

        <!-- Main Transactions Workspace -->
        <section class="card-surface rounded-2xl overflow-hidden mb-6">
            
            <!-- Toolbar: Tab Switcher & Search Bar -->
            <div class="p-4 border-b border-slate-200/80 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 bg-white">
                
                <!-- Category Tabs -->
                <div class="flex items-center gap-1 p-1 bg-slate-100/90 rounded-xl overflow-x-auto max-w-full">
                    <button 
                        type="button" 
                        onclick="switchTab('deposits')" 
                        id="tabBtn-deposits" 
                        class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-white text-blue-600 shadow-sm"
                    >
                        <i class="fa-solid fa-money-bill-transfer text-xs"></i>
                        <span>Lịch Sử Nạp Tiền (<?= $totalDepositCount ?>)</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="switchTab('orders')" 
                        id="tabBtn-orders" 
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 text-slate-600 hover:text-slate-900"
                    >
                        <i class="fa-solid fa-file-invoice text-xs"></i>
                        <span>Hóa Đơn Mua Key (<?= $totalOrderCount ?>)</span>
                    </button>
                </div>

                <!-- Live Search Box -->
                <div class="relative w-full md:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input 
                        type="text" 
                        id="searchTxInput" 
                        onkeyup="searchTransactions()" 
                        placeholder="Tìm mã GD, số tiền..." 
                        class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl text-xs font-medium outline-none transition"
                    >
                </div>
            </div>

            <!-- TAB 1: DEPOSITS TABLE -->
            <div id="tab-deposits" class="overflow-x-auto">
                <?php if (empty($deposits)): ?>
                    <div class="text-center py-12 px-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl mx-auto mb-3 border border-emerald-100 shadow-inner">
                            <i class="fa-solid fa-money-bill-trend-up"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Chưa có giao dịch nạp tiền nào</h3>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto mt-0.5 mb-4">
                            Nạp tiền vào tài khoản qua VietQR để kích hoạt các gói phần mềm tự động trong 10 giây.
                        </p>
                        <a href="/deposit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                            <i class="fa-solid fa-qrcode text-xs"></i>
                            <span>Tạo Lệnh Nạp Tiền VietQR</span>
                        </a>
                    </div>
                <?php else: ?>
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider bg-slate-50/50">
                                <th class="py-3 px-4 font-extrabold">Mã Giao Dịch</th>
                                <th class="py-3 px-4 font-extrabold">Phương Thức</th>
                                <th class="py-3 px-4 font-extrabold text-right">Số Tiền Nạp</th>
                                <th class="py-3 px-4 font-extrabold">Thời Gian</th>
                                <th class="py-3 px-4 font-extrabold text-center">Trạng Thái</th>
                                <th class="py-3 px-4 font-extrabold text-right">Chi Tiết</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <?php foreach ($deposits as $dep): ?>
                                <tr class="tx-row transition" 
                                    data-search="<?= strtolower(htmlspecialchars($dep['reference_code'] . ' ' . $dep['amount'] . ' ' . $dep['status'])) ?>">
                                    
                                    <!-- Reference Code -->
                                    <td class="py-3.5 px-4">
                                        <div class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg">
                                            <code class="font-mono font-bold text-blue-700 text-[11px] select-all"><?= htmlspecialchars($dep['reference_code']) ?></code>
                                            <button onclick="copyCode('<?= htmlspecialchars($dep['reference_code']) ?>', this)" class="text-slate-400 hover:text-blue-600 p-0.5 transition" title="Copy mã">
                                                <i class="fa-regular fa-copy text-[11px]"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <!-- Method -->
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-bold">
                                                <i class="fa-solid fa-qrcode"></i>
                                            </span>
                                            <div>
                                                <span class="font-bold text-slate-800 text-xs block">VietQR Bank</span>
                                                <span class="text-[10px] text-slate-400 block">MB Bank (Quân Đội)</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Amount -->
                                    <td class="py-3.5 px-4 text-right">
                                        <span class="text-sm font-black text-emerald-600 tracking-tight">
                                            +<?= number_format($dep['amount'], 0, ',', '.') ?>đ
                                        </span>
                                    </td>

                                    <!-- Time -->
                                    <td class="py-3.5 px-4 text-slate-600">
                                        <div class="text-xs font-semibold"><?= date('H:i:s', $dep['created_at']) ?></div>
                                        <div class="text-[10px] text-slate-400"><?= date('d/m/Y', $dep['created_at']) ?></div>
                                    </td>

                                    <!-- Status -->
                                    <td class="py-3.5 px-4 text-center">
                                        <?php if ($dep['status'] === 'approved'): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-full border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Thành công
                                            </span>
                                        <?php elseif ($dep['status'] === 'rejected'): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-extrabold rounded-full border border-rose-200">
                                                Đã hủy
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-extrabold rounded-full border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Chờ duyệt
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Action -->
                                    <td class="py-3.5 px-4 text-right">
                                        <button 
                                            onclick="showReceiptModal('Nạp Tiền VietQR', '<?= htmlspecialchars($dep['reference_code']) ?>', '<?= number_format($dep['amount'], 0, ',', '.') ?>đ', 'MB Bank (Quân Đội)', '<?= date('H:i d/m/Y', $dep['created_at']) ?>', '<?= $dep['status'] === 'approved' ? 'Thành Công' : ($dep['status'] === 'rejected' ? 'Đã Hủy' : 'Chờ Xử Lý') ?>')"
                                            class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[11px] font-bold transition"
                                        >
                                            Chi tiết
                                        </button>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- TAB 2: ORDERS TABLE -->
            <div id="tab-orders" class="overflow-x-auto hidden">
                <?php if (empty($orders)): ?>
                    <div class="text-center py-12 px-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl mx-auto mb-3 border border-blue-100 shadow-inner">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Chưa có hóa đơn mua key nào</h3>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto mt-0.5 mb-4">
                            Bạn chưa phát sinh đơn hàng mua mới hoặc gia hạn phần mềm nào từ số dư ví.
                        </p>
                        <a href="/renew" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                            <i class="fa-solid fa-cart-shopping text-xs"></i>
                            <span>Xem Bảng Giá & Mua Key</span>
                        </a>
                    </div>
                <?php else: ?>
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider bg-slate-50/50">
                                <th class="py-3 px-4 font-extrabold">Mã Hóa Đơn</th>
                                <th class="py-3 px-4 font-extrabold">Dịch Vụ Thanh Toán</th>
                                <th class="py-3 px-4 font-extrabold text-right">Giá Trị Đơn Hàng</th>
                                <th class="py-3 px-4 font-extrabold">Mã GD / Key</th>
                                <th class="py-3 px-4 font-extrabold">Thời Gian</th>
                                <th class="py-3 px-4 font-extrabold text-center">Trạng Thái</th>
                                <th class="py-3 px-4 font-extrabold text-right">Chi Tiết</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <?php foreach ($orders as $ord): ?>
                                <tr class="tx-row transition" 
                                    data-search="<?= strtolower(htmlspecialchars('ord-' . $ord['id'] . ' ' . $ord['total_amount'] . ' ' . ($ord['transaction_id'] ?? ''))) ?>">
                                    
                                    <!-- Order ID -->
                                    <td class="py-3.5 px-4">
                                        <span class="font-mono font-bold text-slate-900 text-xs">#ORD-<?= htmlspecialchars($ord['id']) ?></span>
                                    </td>

                                    <!-- Service -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-900 text-xs">Mua / Gia Hạn Key Bản Quyền</div>
                                        <div class="text-[10px] text-slate-400">Trừ trực tiếp số dư ví</div>
                                    </td>

                                    <!-- Amount -->
                                    <td class="py-3.5 px-4 text-right">
                                        <span class="text-sm font-black text-blue-700 tracking-tight">
                                            -<?= number_format($ord['total_amount'], 0, ',', '.') ?>đ
                                        </span>
                                    </td>

                                    <!-- Transaction Memo / Key -->
                                    <td class="py-3.5 px-4">
                                        <code class="font-mono text-slate-600 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded text-[10px] block truncate max-w-[160px]" title="<?= htmlspecialchars($ord['transaction_id'] ?? 'N/A') ?>">
                                            <?= htmlspecialchars($ord['transaction_id'] ?? 'N/A') ?>
                                        </code>
                                    </td>

                                    <!-- Time -->
                                    <td class="py-3.5 px-4 text-slate-600">
                                        <div class="text-xs font-semibold"><?= date('H:i:s', $ord['created_at']) ?></div>
                                        <div class="text-[10px] text-slate-400"><?= date('d/m/Y', $ord['created_at']) ?></div>
                                    </td>

                                    <!-- Status -->
                                    <td class="py-3.5 px-4 text-center">
                                        <?php if ($ord['status'] === 'completed'): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-full border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Hoàn tất
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-extrabold rounded-full border border-amber-200">
                                                <?= htmlspecialchars($ord['status']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Action -->
                                    <td class="py-3.5 px-4 text-right">
                                        <button 
                                            onclick="showReceiptModal('Hóa Đơn Bản Quyền', '#ORD-<?= htmlspecialchars($ord['id']) ?>', '<?= number_format($ord['total_amount'], 0, ',', '.') ?>đ', 'Ví Tài Khoản VMAX', '<?= date('H:i d/m/Y', $ord['created_at']) ?>', 'Hoàn Tất (Đã Kích Hoạt)')"
                                            class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[11px] font-bold transition"
                                        >
                                            Hóa đơn
                                        </button>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

        </section>

        <!-- Compact Bottom Quick Help -->
        <div class="flex flex-col sm:flex-row items-center justify-between p-4 bg-white border border-slate-200 rounded-2xl gap-3 text-xs text-slate-500 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-headset text-sm"></i>
                </span>
                <div>
                    <span class="font-bold text-slate-800">Cần tra soát giao dịch chuyển khoản hoặc lấy hóa đơn VAT?</span>
                    <span class="block text-[11px] text-slate-400">Đội ngũ kế toán VMAX Software hỗ trợ xuất hóa đơn điện tử cho doanh nghiệp 24/7.</span>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="/support" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition text-xs">
                    Gửi Yêu Cầu Hỗ Trợ
                </a>
                <a href="/deposit" class="px-3.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold transition text-xs">
                    + Nạp Tiền Ngay
                </a>
            </div>
        </div>

    </main>

    <!-- Electronic Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 relative animate-pop-in">
            <!-- Close Button -->
            <button onclick="closeReceiptModal()" class="absolute right-4 top-4 w-7 h-7 rounded-full bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Brand Header -->
            <div class="text-center pb-4 mb-4 border-b border-slate-100">
                <img src="/image/logo-app-vmax-64x.webp" alt="VMAX Logo" class="h-8 mx-auto mb-2">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900" id="receiptModalTitle">HÓA ĐƠN ĐIỆN TỬ</h3>
                <p class="text-[10px] text-slate-400 font-mono" id="receiptModalCode">#REF-0000</p>
            </div>

            <!-- Receipt Info Fields -->
            <div class="space-y-2.5 text-xs text-slate-600 mb-5">
                <div class="flex justify-between">
                    <span class="text-slate-400">Số tiền:</span>
                    <span class="font-black text-slate-900 text-sm" id="receiptModalAmount">0đ</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Phương thức:</span>
                    <span class="font-bold text-slate-800" id="receiptModalMethod">VietQR Bank</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Thời gian:</span>
                    <span class="font-medium text-slate-700" id="receiptModalTime">--/--/----</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Trạng thái:</span>
                    <span class="font-extrabold text-emerald-600" id="receiptModalStatus">Thành Công</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-100">
                    <span class="text-slate-400">Đơn vị cung cấp:</span>
                    <span class="font-bold text-slate-800 text-[11px]">VMAX Software Solutions</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    <i class="fa-solid fa-print mr-1"></i> In / Lưu PDF
                </button>
                <button onclick="closeReceiptModal()" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 pointer-events-none flex flex-col gap-2"></div>

    <script>
        // Switch Between Deposits vs Orders
        function switchTab(tabId) {
            const tabDeposits = document.getElementById('tab-deposits');
            const tabOrders = document.getElementById('tab-orders');
            const btnDeposits = document.getElementById('tabBtn-deposits');
            const btnOrders = document.getElementById('tabBtn-orders');

            if (tabId === 'deposits') {
                tabDeposits.classList.remove('hidden');
                tabOrders.classList.add('hidden');
                
                btnDeposits.className = "px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-white text-blue-600 shadow-sm";
                btnOrders.className = "px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 text-slate-600 hover:text-slate-900";
            } else {
                tabOrders.classList.remove('hidden');
                tabDeposits.classList.add('hidden');
                
                btnOrders.className = "px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 bg-white text-blue-600 shadow-sm";
                btnDeposits.className = "px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 text-slate-600 hover:text-slate-900";
            }
        }

        // Live Search Transactions Filter
        function searchTransactions() {
            const query = (document.getElementById('searchTxInput').value || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.tx-row');

            rows.forEach(r => {
                const searchData = r.getAttribute('data-search') || '';
                if (!query || searchData.includes(query)) {
                    r.style.display = '';
                } else {
                    r.style.display = 'none';
                }
            });
        }

        // Copy Code to Clipboard
        function copyCode(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                showToast("Đã sao chép mã giao dịch!", "success");
                if (btn) {
                    const icon = btn.querySelector('i');
                    if (icon) {
                        const oldClass = icon.className;
                        icon.className = 'fa-solid fa-check text-emerald-600 text-xs';
                        setTimeout(() => { icon.className = oldClass; }, 1800);
                    }
                }
            });
        }

        // Electronic Receipt Modal Helpers
        function showReceiptModal(title, code, amount, method, time, status) {
            document.getElementById('receiptModalTitle').innerText = title;
            document.getElementById('receiptModalCode').innerText = code;
            document.getElementById('receiptModalAmount').innerText = amount;
            document.getElementById('receiptModalMethod').innerText = method;
            document.getElementById('receiptModalTime').innerText = time;
            document.getElementById('receiptModalStatus').innerText = status;

            document.getElementById('receiptModal').classList.remove('hidden');
        }

        function closeReceiptModal() {
            document.getElementById('receiptModal').classList.add('hidden');
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

        // Auto switch tab if URL has ?tab=orders or #orders
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('tab') === 'orders' || window.location.hash === '#orders') {
                switchTab('orders');
            }
        });
    </script>
</body>
</html>