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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .glass-panel-glow {
            box-shadow: 0 10px 30px -5px rgba(59, 130, 246, 0.04);
        }
    </style>
</head>
<body class="text-slate-850 min-h-screen flex flex-col md:flex-row">

    <!-- Client Sidebar -->
    <?php require BASE_PATH . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-4 md:p-8 max-w-7xl mx-auto w-full overflow-hidden">
        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8 pb-6 border-b border-slate-200/60">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></div>
                    <h1 class="text-2xl font-extrabold text-slate-950 tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700">
                        Quản Lý Bản Quyền
                    </h1>
                </div>
                <p class="text-slate-500 text-xs mt-1">Kích hoạt key bản quyền mới hoặc theo dõi tình trạng thời hạn của các giấy phép dịch vụ.</p>
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
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Key Activation Form -->
        <section class="glass-panel glass-panel-glow rounded-3xl p-6 md:p-8 mb-8 relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 w-24 h-24 bg-blue-500/5 rounded-full blur-xl group-hover:bg-blue-500/10 transition-all duration-300"></div>
            
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Kích Hoạt Key Mới</h2>
                    <p class="text-[11px] text-slate-500">Nhập mã bản quyền đã mua để cấp phép cho phần mềm hoạt động trên máy tính của bạn.</p>
                </div>
            </div>

            <form method="POST" action="/licenses" class="flex flex-col sm:flex-row gap-4 items-end">
                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                <input type="hidden" name="action" value="activate">
                
                <div class="flex-1 w-full space-y-1.5">
                    <label for="license_key" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Mã Key Bản Quyền</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m-5 0a2 2 0 012 2m-5 0a2 2 0 012 2m-5 0a2 2 0 012 2m-5 0a2 2 0 012 2m-5 0a2 2 0 012 2"/>
                            </svg>
                        </span>
                        <input type="text" id="license_key" name="license_key" placeholder="Ví dụ: VMAX-30D-XXXX-XXXX-XXXX" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none transition text-xs font-semibold placeholder-slate-400">
                    </div>
                </div>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl transition-all text-xs w-full sm:w-auto h-[46px] shadow-lg shadow-blue-500/10 flex items-center justify-center gap-1.5 shrink-0">
                    <span>Kích hoạt ngay</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>
        </section>

        <!-- Licenses Table List -->
        <section class="glass-panel rounded-2xl p-6 shadow-sm">
            <h2 class="text-xs font-bold text-slate-850 mb-6 border-b border-slate-100 pb-4 uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Danh sách key bản quyền đã mua
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider">
                            <th class="pb-3 font-bold">Phần mềm</th>
                            <th class="pb-3 font-bold">Mã key</th>
                            <th class="pb-3 font-bold">Giới hạn PC</th>
                            <th class="pb-3 font-bold">Thời hạn sử dụng</th>
                            <th class="pb-3 font-bold">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-medium">
                        <?php if (empty($licenses)): ?>
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">Bạn chưa kích hoạt mã bản quyền nào.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($licenses as $lic): 
                                $remaining = $lic['expires_at'] - time();
                                $totalDuration = $lic['expires_at'] - $lic['created_at'];
                                $percent = $totalDuration > 0 ? max(0, min(100, round((($lic['expires_at'] - time()) / $totalDuration) * 100))) : 0;
                                
                                $statusText = 'Đang hoạt động';
                                $statusClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                if ($remaining <= 0) {
                                    $statusText = 'Hết hạn';
                                    $statusClass = 'bg-rose-50 text-rose-600 border-rose-100';
                                    $percent = 0;
                                } else if ($remaining < 7 * 86400) {
                                    $statusText = 'Sắp hết hạn';
                                    $statusClass = 'bg-amber-50 text-amber-600 border-amber-100';
                                }
                                if ($lic['status'] === 'suspended') {
                                    $statusText = 'Bị khóa';
                                    $statusClass = 'bg-red-50 text-red-600 border-red-100';
                                }
                                
                                $deviceCount = (new DeviceModel())->countDevicesByLicense($lic['id']);
                                $devicePercent = $lic['device_limit'] > 0 ? max(0, min(100, round(($deviceCount / $lic['device_limit']) * 100))) : 0;
                                
                                // Color slot indicator
                                $slotColor = 'bg-blue-600';
                                if ($devicePercent >= 100) {
                                    $slotColor = 'bg-amber-500';
                                }
                            ?>
                                <tr class="border-b border-slate-100 hover:bg-slate-50/60 transition">
                                    <td class="py-4 pr-3">
                                        <div class="font-bold text-slate-900 text-xs"><?= htmlspecialchars($lic['product_name']) ?></div>
                                        <div class="text-[9px] text-slate-400 mt-1 uppercase font-extrabold tracking-wider"><?= htmlspecialchars($lic['type']) ?></div>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-1.5">
                                            <code class="text-[10px] bg-blue-50/30 border border-blue-100 px-2.5 py-1 rounded-lg text-blue-600 font-bold font-mono tracking-tight select-all">
                                                <?= htmlspecialchars($lic['license_key']) ?>
                                            </code>
                                            <button onclick="copyToClipboard('<?= htmlspecialchars($lic['license_key']) ?>')" class="p-1.5 hover:bg-slate-100 rounded-lg text-slate-400 hover:text-blue-600 transition-all" title="Sao chép Key">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex flex-col gap-1.5 max-w-[130px]">
                                            <div class="text-slate-800 font-bold text-[11px]"><?= $deviceCount ?> / <?= $lic['device_limit'] ?> PC</div>
                                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden border border-slate-200/50">
                                                <div class="h-full rounded-full <?= $slotColor ?>" style="width: <?= $devicePercent ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-slate-500">
                                        <div class="text-[10px] text-slate-500 font-bold"><?= date('d/m/Y H:i', $lic['expires_at']) ?></div>
                                        <div class="w-24 bg-slate-100 h-1 rounded-full mt-1.5 overflow-hidden border border-slate-200/50">
                                            <div class="h-full rounded-full <?= $remaining <= 0 ? 'bg-rose-500' : ($remaining < 7*86400 ? 'bg-amber-555' : 'bg-emerald-500') ?>" style="width: <?= $percent ?>%"></div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 text-[9px] font-extrabold rounded-full border <?= $statusClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </td>
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
                showToast("Sao chép thành công mã bảo mật!", "success");
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
