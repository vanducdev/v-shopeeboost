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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .radar-pulse {
            position: relative;
        }
        .radar-pulse::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: currentColor;
            border-radius: 50%;
            opacity: 0.4;
            animation: radar 1.5s infinite;
        }
        @keyframes radar {
            0% { transform: scale(1); opacity: 0.4; }
            100% { transform: scale(2.5); opacity: 0; }
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
                        Thiết Bị Liên Kết
                    </h1>
                </div>
                <p class="text-slate-500 text-xs mt-1">Kiểm soát và quản lý danh sách các máy tính PC liên kết với Key bản quyền của bạn.</p>
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

        <!-- Devices Grid -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Danh sách máy tính đã liên kết (<?= count($devices) ?>)
                </h2>
            </div>
            
            <?php if (empty($devices)): ?>
                <div class="glass-panel rounded-2xl p-12 text-center text-slate-400 shadow-sm flex flex-col items-center justify-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800">Chưa ghi nhận thiết bị kích hoạt</p>
                        <p class="text-[10px] text-slate-400 mt-1">Sử dụng Key bản quyền trên phần mềm để tự động kết nối PC của bạn.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($devices as $dev): ?>
                        <div class="glass-panel glass-panel-glow rounded-3xl p-6 shadow-sm hover:shadow-md hover:border-blue-500/30 transition-all duration-300 group flex flex-col justify-between h-[270px] relative overflow-hidden">
                            <!-- High tech card background glow -->
                            <div class="absolute -right-8 -top-8 w-24 h-24 bg-blue-500/5 rounded-full blur-xl group-hover:bg-blue-500/10 transition-all duration-300"></div>
                            
                            <!-- Card Header -->
                            <div class="flex justify-between items-start gap-4">
                                <div class="space-y-1.5">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-600 text-[9px] font-extrabold rounded-full border border-emerald-100 uppercase tracking-wider">
                                        <span class="w-1 h-1 bg-emerald-500 rounded-full radar-pulse"></span>
                                        Connected
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-sm truncate max-w-[180px] group-hover:text-blue-600 transition-colors" title="<?= htmlspecialchars($dev['device_name']) ?>">
                                        <?= htmlspecialchars($dev['device_name']) ?>
                                    </h3>
                                    <p class="text-[9px] text-slate-400 font-extrabold uppercase tracking-widest">
                                        <?= htmlspecialchars($dev['product_name'] ?? 'VMAX Software') ?>
                                    </p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-450 flex items-center justify-center shrink-0 shadow-sm group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:border-blue-100 transition-all duration-300">
                                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Card Specs Body -->
                            <div class="space-y-2 border-t border-slate-100 pt-4 my-4">
                                <div class="flex justify-between items-center text-[10px] text-slate-500">
                                    <span class="font-semibold">Mã phần cứng (HWID):</span>
                                    <div class="flex items-center gap-1.5">
                                        <code class="font-mono text-blue-600 bg-blue-50/50 border border-blue-100/55 px-1.5 py-0.5 rounded truncate max-w-[110px] font-bold" id="hwid-<?= $dev['id'] ?>" title="<?= htmlspecialchars($dev['device_id']) ?>"><?= htmlspecialchars($dev['device_id']) ?></code>
                                        <button onclick="copyHWID('<?= htmlspecialchars($dev['device_id']) ?>')" class="text-slate-400 hover:text-blue-600 transition-colors" title="Sao chép HWID">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center text-[10px] text-slate-500">
                                    <span class="font-semibold">Địa chỉ IP:</span>
                                    <span class="font-bold text-slate-850"><?= htmlspecialchars($dev['ip_address']) ?></span>
                                </div>
                                <div class="flex justify-between items-center text-[10px] text-slate-500">
                                    <span class="font-semibold">Ngày kích hoạt:</span>
                                    <span class="text-slate-500 font-bold"><?= date('d/m/Y H:i', $dev['activated_at']) ?></span>
                                </div>
                            </div>
                            
                            <!-- Card Actions -->
                            <div>
                                <form id="unlink-form-<?= $dev['id'] ?>" method="POST" action="/devices" class="hidden">
                                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                    <input type="hidden" name="action" value="remove_device">
                                    <input type="hidden" name="device_id" value="<?= $dev['id'] ?>">
                                </form>
                                <button type="button" onclick="confirmUnlink(<?= $dev['id'] ?>, '<?= htmlspecialchars($dev['device_name']) ?>')" class="w-full text-center py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl transition duration-200 flex items-center justify-center gap-1.5 border border-rose-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hủy liên kết thiết bị
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 pointer-events-none flex flex-col gap-3"></div>

    <script>
        // High-tech clipboard copy
        function copyHWID(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast("Sao chép mã phần cứng HWID thành công!", "success");
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

        // Modern SweetAlert2 Confirmation
        function confirmUnlink(id, deviceName) {
            Swal.fire({
                title: 'Hủy Liên Kết PC?',
                text: `Bạn có chắc chắn muốn hủy kích hoạt thiết bị "${deviceName}" ra khỏi hệ thống bản quyền không?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Đồng Ý Hủy',
                cancelButtonText: 'Bỏ Qua',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                background: '#ffffff',
                color: '#1e293b',
                customClass: {
                    popup: 'rounded-3xl border border-slate-100 shadow-2xl p-6',
                    title: 'text-base font-bold text-slate-800',
                    htmlContainer: 'text-xs text-slate-500 font-medium leading-relaxed mt-2',
                    confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-bold shadow-md shadow-rose-500/10 transition',
                    cancelButton: 'rounded-xl px-5 py-2.5 text-xs font-bold transition'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('unlink-form-' + id).submit();
                }
            });
        }
    </script>
</body>
</html>
