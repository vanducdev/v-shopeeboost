<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Bản Quyền - VMAX Software</title>
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
            box-shadow: 0 6px 20px -4px rgba(15, 23, 42, 0.06);
        }

        .lic-row {
            transition: background-color 0.15s ease;
        }
        .lic-row:hover {
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
                        Quản Lý Bản Quyền
                    </h1>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                        License Hub
                    </span>
                </div>
                <p class="text-slate-500 text-xs mt-0.5">
                    Theo dõi tình trạng thời hạn giấy phép, phân bổ thiết bị HWID và kích hoạt phần mềm.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="/products" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-cart-plus text-[11px]"></i>
                    <span>Mua Key Mới</span>
                </a>
                
                <a href="/devices" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-laptop-code text-indigo-600 text-[11px]"></i>
                    <span>Quản Lý HWID</span>
                </a>

                <a href="/deposit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-xl transition">
                    <i class="fa-solid fa-bolt text-emerald-600 text-[11px]"></i>
                    <span>Nạp Tiền</span>
                </a>
            </div>
        </div>

        <!-- Alerts -->
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
        // Process Licenses Data
        $totalLicenses = count($licenses ?? []);
        $activeLicenses = 0;
        $expiringLicenses = 0;
        $expiredLicenses = 0;
        $totalActiveDevices = 0;
        $totalDeviceLimit = 0;
        $deviceModel = new DeviceModel();

        $processedLicenses = [];
        if (!empty($licenses)) {
            foreach ($licenses as $lic) {
                $rem = $lic['expires_at'] - time();
                $devCount = $deviceModel->countDevicesByLicense($lic['id']);
                $totalActiveDevices += $devCount;
                $totalDeviceLimit += (int)$lic['device_limit'];

                $statusCategory = 'active';
                if ($lic['status'] === 'suspended') {
                    $statusCategory = 'suspended';
                } else if ($rem <= 0 && $lic['type'] !== 'lifetime') {
                    $statusCategory = 'expired';
                    $expiredLicenses++;
                } else if ($rem < 7 * 86400 && $lic['type'] !== 'lifetime') {
                    $statusCategory = 'expiring';
                    $expiringLicenses++;
                    $activeLicenses++;
                } else {
                    $statusCategory = 'active';
                    $activeLicenses++;
                }

                $lic['devCount'] = $devCount;
                $lic['statusCategory'] = $statusCategory;
                $lic['remainingSeconds'] = $rem;
                $processedLicenses[] = $lic;
            }
        }
        ?>

        <!-- Compact 4 KPI Stats -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-5">
            <!-- Stat 1 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">TỔNG BẢN QUYỀN</span>
                    <span class="text-xl font-black text-slate-900 tracking-tight mt-0.5 block"><?= $totalLicenses ?> <span class="text-xs font-semibold text-slate-400">gói</span></span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-key"></i>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">ĐANG HOẠT ĐỘNG</span>
                    <span class="text-xl font-black text-emerald-600 tracking-tight mt-0.5 block flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <?= $activeLicenses ?>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">CẦN GIA HẠN</span>
                    <span class="text-xl font-black <?= ($expiringLicenses > 0 || $expiredLicenses > 0) ? 'text-amber-600' : 'text-slate-900' ?> tracking-tight mt-0.5 block">
                        <?= $expiringLicenses + $expiredLicenses ?>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">THIẾT BỊ HWID</span>
                    <span class="text-xl font-black text-indigo-600 tracking-tight mt-0.5 block">
                        <?= $totalActiveDevices ?> <span class="text-xs font-semibold text-slate-400">/ <?= max($totalActiveDevices, $totalDeviceLimit) ?></span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
            </div>
        </section>

        <!-- Compact Key Activation Bar (Clean & Refined) -->
        <section class="card-surface rounded-2xl p-4 mb-5 bg-gradient-to-r from-blue-50/40 via-white to-indigo-50/30 border-blue-100">
            <form method="POST" action="/licenses" id="activateForm" class="flex flex-col sm:flex-row items-center gap-3">
                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                <input type="hidden" name="action" value="activate">

                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs shadow-sm">
                        <i class="fa-solid fa-unlock-keyhole"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-800">Kích Hoạt Key Bản Quyền</span>
                        <span class="block text-[10px] text-slate-400">Nhập mã key đã mua để cấp phép</span>
                    </div>
                </div>

                <div class="relative flex-1 w-full">
                    <i class="fa-solid fa-barcode absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input 
                        type="text" 
                        id="license_key" 
                        name="license_key" 
                        placeholder="Nhập mã key VMAX-XXXX-XXXX-XXXX..." 
                        required 
                        autocomplete="off"
                        class="w-full pl-9 pr-24 py-2 bg-white border border-slate-200 focus:border-blue-500 rounded-xl text-xs font-mono font-bold text-slate-900 outline-none uppercase placeholder-slate-400 transition tracking-wider shadow-inner"
                    >
                    <button 
                        type="button" 
                        onclick="pasteLicenseKey()" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] font-bold transition flex items-center gap-1"
                        title="Dán nhanh từ clipboard"
                    >
                        <i class="fa-regular fa-paste text-[10px]"></i>
                        <span>Dán</span>
                    </button>
                </div>

                <button 
                    type="submit" 
                    class="px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center justify-center gap-1.5 shrink-0 w-full sm:w-auto h-[38px] cursor-pointer"
                >
                    <i class="fa-solid fa-bolt text-[11px] text-yellow-300"></i>
                    <span>Kích Hoạt Ngay</span>
                </button>
            </form>
        </section>

        <!-- Main Licenses Panel -->
        <section class="card-surface rounded-2xl overflow-hidden mb-6">
            
            <!-- Toolbar -->
            <div class="p-4 border-b border-slate-200/80 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 bg-white">
                
                <!-- Status Filter Pills -->
                <div class="flex items-center gap-1 p-1 bg-slate-100/90 rounded-xl overflow-x-auto max-w-full">
                    <button onclick="filterLicenses('all')" id="filter-all" class="filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition bg-white text-blue-600 shadow-sm">
                        Tất cả (<?= $totalLicenses ?>)
                    </button>
                    <button onclick="filterLicenses('active')" id="filter-active" class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition text-slate-600 hover:text-slate-900">
                        Đang chạy (<?= $activeLicenses ?>)
                    </button>
                    <button onclick="filterLicenses('expiring')" id="filter-expiring" class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition text-slate-600 hover:text-slate-900">
                        Sắp hết hạn (<?= $expiringLicenses ?>)
                    </button>
                    <button onclick="filterLicenses('expired')" id="filter-expired" class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition text-slate-600 hover:text-slate-900">
                        Hết hạn (<?= $expiredLicenses ?>)
                    </button>
                </div>

                <!-- Right Search & Mode Toggle -->
                <div class="flex items-center gap-2.5 w-full md:w-auto">
                    <!-- Search Input -->
                    <div class="relative flex-1 md:w-56">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input 
                            type="text" 
                            id="searchLicenseInput" 
                            onkeyup="searchLicenses()" 
                            placeholder="Lọc phần mềm, key..." 
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
            <?php if (empty($processedLicenses)): ?>
                <!-- Empty State -->
                <div class="text-center py-12 px-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl mx-auto mb-3 border border-blue-100 shadow-inner">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Chưa có mã bản quyền nào</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto mt-0.5 mb-4">
                        Kích hoạt key ở ô bên trên hoặc ghé thăm cửa hàng sản phẩm VMAX để nhận bản quyền.
                    </p>
                    <a href="/products" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                        <i class="fa-solid fa-cart-shopping text-xs"></i>
                        <span>Xem Danh Mục Sản Phẩm</span>
                    </a>
                </div>
            <?php else: ?>

                <!-- VIEW A: MODERN SAAS TABLE VIEW (DEFAULT & COMPACT) -->
                <div id="licensesTableView" class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider bg-slate-50/50">
                                <th class="py-3 px-4 font-extrabold">Phần Mềm</th>
                                <th class="py-3 px-4 font-extrabold">Mã Key Bản Quyền</th>
                                <th class="py-3 px-4 font-extrabold">Gói Cước</th>
                                <th class="py-3 px-4 font-extrabold">Thiết Bị HWID</th>
                                <th class="py-3 px-4 font-extrabold">Thời Hạn</th>
                                <th class="py-3 px-4 font-extrabold">Trạng Thái</th>
                                <th class="py-3 px-4 font-extrabold text-right">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <?php foreach ($processedLicenses as $lic): 
                                $remaining = $lic['remainingSeconds'];
                                $isLifetime = ($lic['type'] === 'lifetime');
                                $isExpired = ($remaining <= 0 && !$isLifetime);
                                $isExpiring = ($remaining > 0 && $remaining < 7 * 86400 && !$isLifetime);
                                $daysLeft = max(0, ceil($remaining / 86400));

                                // App Icon and Color Theme
                                $isVmaxLogin = stripos($lic['product_name'], 'vmaxlogin') !== false || stripos($lic['product_name'], 'antidetect') !== false;
                                $iconBg = $isVmaxLogin ? 'bg-blue-600 text-white' : 'bg-emerald-600 text-white';
                                $iconFa = $isVmaxLogin ? 'fa-globe' : 'fa-robot';
                            ?>
                                <tr class="lic-row transition" 
                                    data-status="<?= $lic['statusCategory'] ?>" 
                                    data-search="<?= strtolower(htmlspecialchars($lic['product_name'] . ' ' . $lic['license_key'])) ?>">
                                    
                                    <!-- Product Name & Icon -->
                                    <td class="py-3 px-4 pr-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg <?= $iconBg ?> flex items-center justify-center text-xs shrink-0 shadow-sm">
                                                <i class="fa-solid <?= $iconFa ?>"></i>
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900 text-xs flex items-center gap-1.5">
                                                    <span><?= htmlspecialchars($lic['product_name']) ?></span>
                                                </div>
                                                <div class="text-[10px] text-slate-400 font-mono">ID: #<?= $lic['id'] ?></div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- License Key Box with Masking & Copy -->
                                    <td class="py-3 px-4">
                                        <div class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg">
                                            <code class="text-[11px] text-blue-700 font-mono font-bold tracking-tight">
                                                <span id="masked-key-<?= $lic['id'] ?>"><?= substr($lic['license_key'], 0, 8) ?>••••••••••••••••</span>
                                                <span id="full-key-<?= $lic['id'] ?>" class="hidden"><?= htmlspecialchars($lic['license_key']) ?></span>
                                            </code>
                                            <button onclick="toggleKeyMask('key-<?= $lic['id'] ?>')" class="text-slate-400 hover:text-blue-600 p-0.5 transition" title="Ẩn/Hiện mã key">
                                                <i class="fa-regular fa-eye text-[11px]" id="eye-icon-key-<?= $lic['id'] ?>"></i>
                                            </button>
                                            <button onclick="copyToClipboard('<?= htmlspecialchars($lic['license_key']) ?>', this)" class="text-slate-400 hover:text-blue-600 p-0.5 transition" title="Copy mã key">
                                                <i class="fa-regular fa-copy text-[11px]"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <!-- Plan Type -->
                                    <td class="py-3 px-4">
                                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">
                                            <?= htmlspecialchars($lic['type']) ?>
                                        </span>
                                    </td>

                                    <!-- HWID Devices -->
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-xs text-slate-800"><?= $lic['devCount'] ?> / <?= $lic['device_limit'] ?></span>
                                            <span class="text-[10px] text-slate-400">máy</span>
                                            <a href="/devices" class="text-blue-600 hover:text-blue-700 text-[10px] font-bold ml-1" title="Quản lý thiết bị">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                            </a>
                                        </div>
                                    </td>

                                    <!-- Remaining Duration -->
                                    <td class="py-3 px-4">
                                        <?php if ($isLifetime): ?>
                                            <div class="font-bold text-xs text-purple-700">Vĩnh viễn</div>
                                            <div class="text-[10px] text-slate-400">Trọn đời máy</div>
                                        <?php elseif ($isExpired): ?>
                                            <div class="font-bold text-xs text-rose-600">Đã hết hạn</div>
                                            <div class="text-[10px] text-slate-400"><?= date('d/m/Y', $lic['expires_at']) ?></div>
                                        <?php else: ?>
                                            <div class="font-bold text-xs <?= $isExpiring ? 'text-amber-600' : 'text-slate-800' ?>">
                                                Còn <?= $daysLeft ?> ngày
                                            </div>
                                            <div class="text-[10px] text-slate-400"><?= date('d/m/Y', $lic['expires_at']) ?></div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Status Pill -->
                                    <td class="py-3 px-4">
                                        <?php if ($isLifetime): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                                <span>Vĩnh viễn</span>
                                            </span>
                                        <?php elseif ($isExpired): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                <span>Hết hạn</span>
                                            </span>
                                        <?php elseif ($isExpiring): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                <span>Sắp hết hạn</span>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Đang chạy</span>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Quick Actions -->
                                    <td class="py-3 px-4 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="/renew?key=<?= urlencode($lic['license_key']) ?>" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-[11px] font-bold transition">
                                                Gia hạn
                                            </a>
                                            <a href="/devices" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[11px] font-bold transition" title="Thiết bị liên kết">
                                                HWID
                                            </a>
                                            <a href="/downloads" class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-[11px] font-bold transition" title="Tải bộ cài">
                                                <i class="fa-solid fa-download text-[10px]"></i>
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- VIEW B: COMPACT GRID VIEW (ALTERNATIVE) -->
                <div id="licensesGridView" class="hidden p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($processedLicenses as $lic): 
                        $remaining = $lic['remainingSeconds'];
                        $isLifetime = ($lic['type'] === 'lifetime');
                        $isExpired = ($remaining <= 0 && !$isLifetime);
                        $isExpiring = ($remaining > 0 && $remaining < 7 * 86400 && !$isLifetime);
                        $daysLeft = max(0, ceil($remaining / 86400));
                        
                        $isVmaxLogin = stripos($lic['product_name'], 'vmaxlogin') !== false || stripos($lic['product_name'], 'antidetect') !== false;
                        $iconBg = $isVmaxLogin ? 'bg-blue-600 text-white' : 'bg-emerald-600 text-white';
                        $iconFa = $isVmaxLogin ? 'fa-globe' : 'fa-robot';
                    ?>
                        <div class="card-surface rounded-xl p-4 flex flex-col justify-between" 
                             data-status="<?= $lic['statusCategory'] ?>" 
                             data-search="<?= strtolower(htmlspecialchars($lic['product_name'] . ' ' . $lic['license_key'])) ?>">
                            
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg <?= $iconBg ?> flex items-center justify-center text-xs shrink-0">
                                            <i class="fa-solid <?= $iconFa ?>"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-xs leading-snug"><?= htmlspecialchars($lic['product_name']) ?></h3>
                                            <span class="text-[10px] text-slate-400 font-extrabold uppercase">Gói: <?= htmlspecialchars($lic['type']) ?></span>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full <?= $isExpired ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' ?>">
                                        <?= $isExpired ? 'Hết hạn' : ($isExpiring ? 'Sắp hết' : 'Đang chạy') ?>
                                    </span>
                                </div>

                                <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg mb-3 flex items-center justify-between">
                                    <code class="text-[11px] text-blue-700 font-mono font-bold select-all">
                                        <?= substr($lic['license_key'], 0, 10) ?>••••••••
                                    </code>
                                    <button onclick="copyToClipboard('<?= htmlspecialchars($lic['license_key']) ?>', this)" class="text-slate-400 hover:text-blue-600 p-1" title="Copy">
                                        <i class="fa-regular fa-copy text-xs"></i>
                                    </button>
                                </div>

                                <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                                    <span>Thiết bị: <b class="text-slate-800"><?= $lic['devCount'] ?>/<?= $lic['device_limit'] ?> máy</b></span>
                                    <span>Thời hạn: <b class="text-slate-800"><?= $isLifetime ? 'Vĩnh viễn' : ($isExpired ? 'Hết hạn' : 'Còn ' . $daysLeft . ' ngày') ?></b></span>
                                </div>
                            </div>

                            <div class="pt-2.5 border-t border-slate-100 flex items-center gap-2">
                                <a href="/renew?key=<?= urlencode($lic['license_key']) ?>" class="flex-1 py-1.5 text-center text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                    Gia Hạn
                                </a>
                                <a href="/devices" class="flex-1 py-1.5 text-center text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                                    HWID
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

        </section>

        <!-- Compact Bottom Quick Help -->
        <div class="flex flex-col sm:flex-row items-center justify-between p-4 bg-white border border-slate-200 rounded-2xl gap-3 text-xs text-slate-500 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-headset text-sm"></i>
                </span>
                <div>
                    <span class="font-bold text-slate-800">Cần hỗ trợ đổi máy hoặc kích hoạt UltraView?</span>
                    <span class="block text-[11px] text-slate-400">Đội ngũ kỹ thuật viên VMAX Software trực máy 24/7 để hỗ trợ bạn.</span>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="/support" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition text-xs">
                    Gửi Yêu Cầu Hỗ Trợ
                </a>
                <a href="/downloads" class="px-3.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold transition text-xs">
                    Tải Bộ Cài Phần Mềm
                </a>
            </div>
        </div>

    </main>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 pointer-events-none flex flex-col gap-2"></div>

    <script>
        // Paste License Key from Clipboard
        async function pasteLicenseKey() {
            try {
                const text = await navigator.clipboard.readText();
                if (text) {
                    const input = document.getElementById('license_key');
                    input.value = text.trim();
                    showToast("Đã dán mã Key từ bộ nhớ tạm!", "success");
                }
            } catch (err) {
                showToast("Vui lòng cấp quyền clipboard hoặc nhập thủ công.", "error");
            }
        }

        // Toggle Key Masking (Privacy Mode)
        function toggleKeyMask(id) {
            const masked = document.getElementById('masked-' + id);
            const full = document.getElementById('full-' + id);
            const eyeIcon = document.getElementById('eye-icon-' + id);

            if (masked && full) {
                if (masked.classList.contains('hidden')) {
                    masked.classList.remove('hidden');
                    full.classList.add('hidden');
                    eyeIcon.className = 'fa-regular fa-eye text-[11px]';
                } else {
                    masked.classList.add('hidden');
                    full.classList.remove('hidden');
                    eyeIcon.className = 'fa-regular fa-eye-slash text-[11px]';
                }
            }
        }

        // Copy Key to Clipboard
        function copyToClipboard(text, btnElement) {
            navigator.clipboard.writeText(text).then(() => {
                showToast("Đã sao chép mã Key thành công!", "success");
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

        // Filter Licenses by Status
        let currentFilter = 'all';
        function filterLicenses(status) {
            currentFilter = status;
            
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.className = 'filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition text-slate-600 hover:text-slate-900';
            });
            const activeBtn = document.getElementById('filter-' + status);
            if (activeBtn) {
                activeBtn.className = 'filter-btn px-3 py-1.5 rounded-lg text-xs font-bold transition bg-white text-blue-600 shadow-sm';
            }

            applyFilters();
        }

        // Search Licenses by Keyword
        function searchLicenses() {
            applyFilters();
        }

        function applyFilters() {
            const query = (document.getElementById('searchLicenseInput').value || '').toLowerCase().trim();
            const elements = document.querySelectorAll('[data-status]');

            elements.forEach(el => {
                const status = el.getAttribute('data-status');
                const searchData = el.getAttribute('data-search') || '';

                const matchesStatus = (currentFilter === 'all') || (status === currentFilter);
                const matchesSearch = !query || searchData.includes(query);

                if (matchesStatus && matchesSearch) {
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            });
        }

        // Toggle Grid / Table View Mode
        function setViewMode(mode) {
            const gridView = document.getElementById('licensesGridView');
            const tableView = document.getElementById('licensesTableView');
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