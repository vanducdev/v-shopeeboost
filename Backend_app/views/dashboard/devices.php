<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thiết Bị Liên Kết - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            box-shadow: 0 6px 20px -4px rgba(15, 23, 42, 0.06);
        }

        .dev-row {
            transition: background-color 0.15s ease;
        }
        .dev-row:hover {
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
                        Thiết Bị Liên Kết (HWID)
                    </h1>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200">
                        HWID SHIELD 2.0
                    </span>
                </div>
                <p class="text-slate-500 text-xs mt-0.5">
                    Kiểm soát và quản lý danh sách các máy tính PC được cấp quyền chạy phần mềm theo mã phần cứng.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="/licenses" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-key text-[11px]"></i>
                    <span>Xem Bản Quyền</span>
                </a>
                
                <button onclick="openResetModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-arrows-rotate text-amber-500 text-[11px]"></i>
                    <span>Reset Thiết Bị Máy Cũ</span>
                </button>

                <a href="/downloads" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-xl transition">
                    <i class="fa-solid fa-download text-emerald-600 text-[11px]"></i>
                    <span>Tải Bộ Cài</span>
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
        $totalDevices = count($devices ?? []);
        $totalLicensesWithDevices = count($licenses ?? []);
        $totalAllowedSlots = 0;
        if (!empty($licenses)) {
            foreach ($licenses as $l) {
                $totalAllowedSlots += (int)$l['device_limit'];
            }
        }
        ?>

        <!-- Compact 4 KPI Stats -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-5">
            <!-- Stat 1 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">MÁY ĐANG KẾT NỐI</span>
                    <span class="text-xl font-black text-indigo-600 tracking-tight mt-0.5 block flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <?= $totalDevices ?> <span class="text-xs font-semibold text-slate-400">thiết bị</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-laptop"></i>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">TỔNG GIỚI HẠN SLOTS</span>
                    <span class="text-xl font-black text-slate-900 tracking-tight mt-0.5 block">
                        <?= max($totalDevices, $totalAllowedSlots) ?> <span class="text-xs font-semibold text-slate-400">máy tối đa</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-server"></i>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">BẢN QUYỀN ĐÃ GÁN</span>
                    <span class="text-xl font-black text-emerald-600 tracking-tight mt-0.5 block">
                        <?= $totalLicensesWithDevices ?> <span class="text-xs font-semibold text-slate-400">giấy phép</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-key"></i>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">BẢO MẬT PHẦN CỨNG</span>
                    <span class="text-xs font-black text-slate-800 tracking-tight mt-1 block flex items-center gap-1">
                        <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                        <span>Khóa vân tay HWID</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-fingerprint"></i>
                </div>
            </div>
        </section>

        <!-- Compact Reset HWID Helper Bar -->
        <section class="card-surface rounded-2xl p-4 mb-5 bg-gradient-to-r from-amber-50/40 via-white to-blue-50/30 border-amber-100">
            <form method="POST" action="/devices" id="resetLicenseForm" class="flex flex-col sm:flex-row items-center gap-3">
                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                <input type="hidden" name="action" value="reset_license">

                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center text-xs shadow-sm">
                        <i class="fa-solid fa-rotate-left"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-800">Đổi Máy Tính / Cài Lại Windows?</span>
                        <span class="block text-[10px] text-slate-400">Chọn bản quyền để giải phóng toàn bộ máy cũ</span>
                    </div>
                </div>

                <div class="relative flex-1 w-full">
                    <select name="license_id" required class="w-full pl-3 pr-8 py-2 bg-white border border-slate-200 focus:border-blue-500 rounded-xl text-xs font-semibold text-slate-800 outline-none transition appearance-none cursor-pointer">
                        <option value="">-- Chọn License Key cần Reset thiết bị --</option>
                        <?php if (!empty($licenses)): ?>
                            <?php foreach ($licenses as $l): ?>
                                <option value="<?= $l['id'] ?>">
                                    <?= htmlspecialchars($l['product_name']) ?> (<?= htmlspecialchars($l['license_key']) ?> - <?= htmlspecialchars($l['type']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                </div>

                <button 
                    type="submit" 
                    onclick="return confirmReset(event)"
                    class="px-5 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center justify-center gap-1.5 shrink-0 w-full sm:w-auto h-[38px] cursor-pointer"
                >
                    <i class="fa-solid fa-arrows-rotate text-[11px]"></i>
                    <span>Reset Thiết Bị Key Này</span>
                </button>
            </form>
        </section>

        <!-- Main Devices List Panel -->
        <section class="card-surface rounded-2xl overflow-hidden mb-6">
            
            <!-- Toolbar -->
            <div class="p-4 border-b border-slate-200/80 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 bg-white">
                
                <!-- Status Filter Pills -->
                <div class="flex items-center gap-1 p-1 bg-slate-100/90 rounded-xl overflow-x-auto max-w-full">
                    <button onclick="filterDevices('all')" id="filter-all" class="filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition bg-white text-blue-600 shadow-sm">
                        Tất cả máy (<?= $totalDevices ?>)
                    </button>
                    <button onclick="filterDevices('active')" id="filter-active" class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition text-slate-600 hover:text-slate-900">
                        Đang kết nối (<?= $totalDevices ?>)
                    </button>
                </div>

                <!-- Right Search & Mode Toggle -->
                <div class="flex items-center gap-2.5 w-full md:w-auto">
                    <!-- Search Input -->
                    <div class="relative flex-1 md:w-60">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input 
                            type="text" 
                            id="searchDeviceInput" 
                            onkeyup="searchDevices()" 
                            placeholder="Lọc tên máy, HWID, IP..." 
                            class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl text-xs font-medium outline-none transition"
                        >
                    </div>

                    <!-- View Mode Toggle -->
                    <div class="flex items-center bg-slate-100 p-0.5 rounded-lg">
                        <button onclick="setViewMode('table')" id="view-table-btn" class="p-1.5 rounded-md text-xs font-bold text-blue-600 bg-white shadow-sm transition" title="Hiển thị dạng bảng (Khuyên dùng)">
                            <i class="fa-solid fa-list text-xs"></i>
                        </button>
                        <button onclick="setViewMode('grid')" id="view-grid-btn" class="p-1.5 rounded-md text-xs font-bold text-slate-500 hover:text-slate-900 transition" title="Hiển thị dạng thẻ">
                            <i class="fa-solid fa-grip text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <?php if (empty($devices)): ?>
                <!-- Empty State -->
                <div class="text-center py-12 px-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl mx-auto mb-3 border border-indigo-100 shadow-inner">
                        <i class="fa-solid fa-laptop"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Chưa có thiết bị nào liên kết</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto mt-0.5 mb-4">
                        Khi bạn mở phần mềm trên máy tính và nhập mã Key bản quyền, máy tính sẽ tự động xuất hiện tại đây.
                    </p>
                    <a href="/licenses" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                        <i class="fa-solid fa-key text-xs"></i>
                        <span>Lấy Mã Key Bản Quyền</span>
                    </a>
                </div>
            <?php else: ?>

                <!-- VIEW A: MODERN SAAS TABLE VIEW (DEFAULT & COMPACT) -->
                <div id="devicesTableView" class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider bg-slate-50/50">
                                <th class="py-3 px-4 font-extrabold">Tên Máy Tính PC</th>
                                <th class="py-3 px-4 font-extrabold">Phần Mềm & Bản Quyền</th>
                                <th class="py-3 px-4 font-extrabold">Mã Phần Cứng HWID</th>
                                <th class="py-3 px-4 font-extrabold">Địa Chỉ IP</th>
                                <th class="py-3 px-4 font-extrabold">Ngày Kích Hoạt</th>
                                <th class="py-3 px-4 font-extrabold">Trạng Thái</th>
                                <th class="py-3 px-4 font-extrabold text-right">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <?php foreach ($devices as $dev): 
                                $isWindows = stripos($dev['device_name'], 'win') !== false || stripos($dev['device_name'], 'desktop') !== false;
                                $osIcon = $isWindows ? 'fa-windows' : 'fa-apple';
                            ?>
                                <tr class="dev-row transition" 
                                    data-status="active" 
                                    data-search="<?= strtolower(htmlspecialchars($dev['device_name'] . ' ' . $dev['device_id'] . ' ' . $dev['ip_address'] . ' ' . ($dev['product_name'] ?? ''))) ?>">
                                    
                                    <!-- Device Name & OS Icon -->
                                    <td class="py-3 px-4 pr-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-xs shrink-0 shadow-sm">
                                                <i class="fa-solid fa-laptop"></i>
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900 text-xs flex items-center gap-1.5">
                                                    <span><?= htmlspecialchars($dev['device_name']) ?></span>
                                                </div>
                                                <div class="text-[10px] text-slate-400 font-medium flex items-center gap-1">
                                                    <i class="fa-brands <?= $osIcon ?> text-[10px] text-slate-500"></i>
                                                    <span>Windows 11 PC</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Software & Key -->
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-900 text-xs">
                                            <?= htmlspecialchars($dev['product_name'] ?? 'VMAX Software') ?>
                                        </div>
                                        <?php if (!empty($dev['license_key'])): ?>
                                            <code class="text-[10px] text-blue-600 font-mono font-bold tracking-tight">
                                                <?= htmlspecialchars($dev['license_key']) ?>
                                            </code>
                                        <?php endif; ?>
                                    </td>

                                    <!-- HWID Code with Copy -->
                                    <td class="py-3 px-4">
                                        <div class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-2 py-1 rounded-lg">
                                            <code class="text-[11px] text-slate-700 font-mono font-bold tracking-tight" title="<?= htmlspecialchars($dev['device_id']) ?>">
                                                <?= htmlspecialchars(strlen($dev['device_id']) > 22 ? substr($dev['device_id'], 0, 18) . '...' : $dev['device_id']) ?>
                                            </code>
                                            <button onclick="copyHWID('<?= htmlspecialchars($dev['device_id']) ?>', this)" class="text-slate-400 hover:text-blue-600 p-0.5 transition" title="Copy HWID">
                                                <i class="fa-regular fa-copy text-[11px]"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <!-- IP Address -->
                                    <td class="py-3 px-4">
                                        <div class="inline-flex items-center gap-1.5 text-xs text-slate-700 font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span><?= htmlspecialchars($dev['ip_address'] ?? '127.0.0.1') ?></span>
                                        </div>
                                        <div class="text-[10px] text-slate-400">IPv4 (Việt Nam)</div>
                                    </td>

                                    <!-- Activated Date -->
                                    <td class="py-3 px-4">
                                        <div class="text-xs font-bold text-slate-800"><?= date('d/m/Y H:i', $dev['activated_at']) ?></div>
                                        <div class="text-[10px] text-slate-400">Đã đồng bộ cloud</div>
                                    </td>

                                    <!-- Status Pill -->
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span>Đang kết nối</span>
                                        </span>
                                    </td>

                                    <!-- Unlink Action Button -->
                                    <td class="py-3 px-4 text-right">
                                        <form id="unlink-form-<?= $dev['id'] ?>" method="POST" action="/devices" class="hidden">
                                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                            <input type="hidden" name="action" value="remove_device">
                                            <input type="hidden" name="device_id" value="<?= $dev['id'] ?>">
                                        </form>

                                        <button 
                                            type="button" 
                                            onclick="confirmUnlink(<?= $dev['id'] ?>, '<?= htmlspecialchars($dev['device_name']) ?>')" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-[11px] font-bold transition cursor-pointer"
                                            title="Hủy liên kết máy này"
                                        >
                                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                                            <span>Hủy máy</span>
                                        </button>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- VIEW B: COMPACT GRID VIEW (ALTERNATIVE) -->
                <div id="devicesGridView" class="hidden p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($devices as $dev): ?>
                        <div class="card-surface rounded-xl p-4 flex flex-col justify-between" 
                             data-status="active" 
                             data-search="<?= strtolower(htmlspecialchars($dev['device_name'] . ' ' . $dev['device_id'] . ' ' . $dev['ip_address'] . ' ' . ($dev['product_name'] ?? ''))) ?>">
                            
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-xs shrink-0">
                                            <i class="fa-solid fa-laptop"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="font-bold text-slate-900 text-xs leading-snug truncate"><?= htmlspecialchars($dev['device_name']) ?></h3>
                                            <span class="text-[10px] text-slate-400 font-extrabold uppercase"><?= htmlspecialchars($dev['product_name'] ?? 'VMAX Software') ?></span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                                        Connected
                                    </span>
                                </div>

                                <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg mb-3">
                                    <div class="flex items-center justify-between text-[10px] text-slate-400 font-bold mb-1">
                                        <span>MÃ HWID:</span>
                                        <button onclick="copyHWID('<?= htmlspecialchars($dev['device_id']) ?>', this)" class="text-blue-600 hover:underline">Copy</button>
                                    </div>
                                    <code class="text-[11px] text-slate-800 font-mono font-bold block truncate">
                                        <?= htmlspecialchars($dev['device_id']) ?>
                                    </code>
                                </div>

                                <div class="flex items-center justify-between text-[11px] text-slate-500 mb-2">
                                    <span>IP: <b class="text-slate-800"><?= htmlspecialchars($dev['ip_address']) ?></b></span>
                                    <span>Ngày: <b class="text-slate-800"><?= date('d/m/Y', $dev['activated_at']) ?></b></span>
                                </div>
                            </div>

                            <div class="pt-2.5 border-t border-slate-100">
                                <button 
                                    type="button" 
                                    onclick="confirmUnlink(<?= $dev['id'] ?>, '<?= htmlspecialchars($dev['device_name']) ?>')" 
                                    class="w-full py-1.5 text-center text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition"
                                >
                                    Hủy Liên Kết Máy Này
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

        </section>

        <!-- Compact Bottom Quick Help -->
        <div class="flex flex-col sm:flex-row items-center justify-between p-4 bg-white border border-slate-200 rounded-2xl gap-3 text-xs text-slate-500 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </span>
                <div>
                    <span class="font-bold text-slate-800">Cơ chế quản lý phần cứng HWID là gì?</span>
                    <span class="block text-[11px] text-slate-400">Mỗi máy tính cài phần mềm sẽ được định danh bằng mã phần cứng duy nhất. Bạn có thể tự do hủy máy cũ để chuyển sang máy mới.</span>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="/support" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition text-xs">
                    Hỗ Trợ Kỹ Thuật
                </a>
                <a href="/licenses" class="px-3.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold transition text-xs">
                    Danh Sách Bản Quyền
                </a>
            </div>
        </div>

    </main>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 pointer-events-none flex flex-col gap-2"></div>

    <script>
        // Copy HWID to Clipboard
        function copyHWID(text, btnElement) {
            navigator.clipboard.writeText(text).then(() => {
                showToast("Đã sao chép mã HWID máy tính!", "success");
                if (btnElement) {
                    const icon = btnElement.querySelector('i');
                    if (icon) {
                        const oldClass = icon.className;
                        icon.className = 'fa-solid fa-check text-emerald-600 text-xs';
                        setTimeout(() => {
                            icon.className = oldClass;
                        }, 1800);
                    }
                }
            }).catch(() => {
                showToast("Không thể sao chép tự động.", "error");
            });
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
            
            setTimeout(() => {
                toast.classList.remove("translate-y-4", "opacity-0");
            }, 10);
            
            setTimeout(() => {
                toast.classList.add("translate-y-2", "opacity-0");
                setTimeout(() => {
                    toast.remove();
                }, 250);
            }, 2000);
        }

        // SweetAlert2 Confirmation for Unlink
        function confirmUnlink(id, deviceName) {
            Swal.fire({
                title: 'Hủy Liên Kết Máy Này?',
                text: `Bạn có chắc chắn muốn hủy kích hoạt máy "${deviceName}"? Mã bản quyền sẽ được giải phóng ngay lập tức.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng Ý Hủy',
                cancelButtonText: 'Bỏ Qua',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200 shadow-xl p-5',
                    title: 'text-sm font-bold text-slate-800',
                    htmlContainer: 'text-xs text-slate-500 leading-relaxed mt-1',
                    confirmButton: 'rounded-xl px-4 py-2 text-xs font-bold shadow transition',
                    cancelButton: 'rounded-xl px-4 py-2 text-xs font-bold transition'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('unlink-form-' + id).submit();
                }
            });
        }

        // Confirm Reset All Devices for a License
        function confirmReset(e) {
            const select = document.querySelector('select[name="license_id"]');
            if (!select || !select.value) {
                e.preventDefault();
                showToast("Vui lòng chọn 1 bản quyền cần reset thiết bị!", "error");
                return false;
            }
            return true;
        }

        function openResetModal() {
            const select = document.querySelector('select[name="license_id"]');
            if (select) {
                select.focus();
                select.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        // Search Devices by Keyword
        function searchDevices() {
            const query = (document.getElementById('searchDeviceInput').value || '').toLowerCase().trim();
            const elements = document.querySelectorAll('[data-search]');

            elements.forEach(el => {
                const searchData = el.getAttribute('data-search') || '';
                if (!query || searchData.includes(query)) {
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            });
        }

        // Toggle Grid / Table View Mode
        function setViewMode(mode) {
            const gridView = document.getElementById('devicesGridView');
            const tableView = document.getElementById('devicesTableView');
            const gridBtn = document.getElementById('view-grid-btn');
            const tableBtn = document.getElementById('view-table-btn');

            if (mode === 'table') {
                if (tableView) tableView.classList.remove('hidden');
                if (gridView) gridView.classList.add('hidden');
                if (tableBtn) tableBtn.className = 'p-1.5 rounded-md text-xs font-bold text-blue-600 bg-white shadow-sm transition';
                if (gridBtn) gridBtn.className = 'p-1.5 rounded-md text-xs font-bold text-slate-500 hover:text-slate-900 transition';
            } else {
                if (tableView) tableView.classList.add('hidden');
                if (gridView) gridView.classList.remove('hidden');
                if (gridBtn) gridBtn.className = 'p-1.5 rounded-md text-xs font-bold text-blue-600 bg-white shadow-sm transition';
                if (tableBtn) tableBtn.className = 'p-1.5 rounded-md text-xs font-bold text-slate-500 hover:text-slate-900 transition';
            }
        }
    </script>
</body>
</html>