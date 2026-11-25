<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thiết Bị - VMAX Admin</title>
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
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Quản Lý Thiết Bị Kích Hoạt</h1>
                <p class="text-slate-500 text-xs">Giám sát và quản lý các PC đang được liên kết với bản quyền</p>
            </div>
            <span class="px-3 py-1.5 text-xs font-bold rounded-xl border bg-slate-100 text-slate-700 border-slate-200"><?= count($devices) ?> thiết bị</span>
        </header>

        <div class="px-6 md:px-8 py-8">
            <?php if (!empty($error)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-red-50 text-red-755 border border-red-200"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($message)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-bold text-slate-800">Danh Sách Thiết Bị Đang Kết Nối</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-semibold bg-slate-50/20">
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Khách Hàng</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Phần Mềm & License</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Tên Thiết Bị</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Địa Chỉ IP</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Trạng Thái</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Kích Hoạt</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 font-medium">
                            <?php if (empty($devices)): ?>
                                <tr><td colspan="7" class="px-6 py-12 text-center text-slate-450 text-xs italic">Không có thiết bị nào đang kích hoạt.</td></tr>
                            <?php else: ?>
                                <?php foreach ($devices as $d): ?>
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-black text-slate-700 shrink-0">
                                                    <?= mb_strtoupper(mb_substr($d['owner_name'] ?? 'U', 0, 1)) ?>
                                                </div>
                                                <span class="text-xs font-bold text-slate-800"><?= htmlspecialchars($d['owner_name'] ?? 'Khách vãng lai') ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs font-bold text-slate-800"><?= htmlspecialchars($d['product_name']) ?></div>
                                            <code class="text-[9px] font-mono text-blue-600 opacity-80"><?= htmlspecialchars(substr($d['license_key'], 0, 20)) ?>...</code>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                <div>
                                                    <div class="text-xs font-bold text-slate-800"><?= htmlspecialchars($d['device_name'] ?? 'Unknown PC') ?></div>
                                                    <div class="text-[9px] font-mono text-slate-450">HWID: <?= htmlspecialchars(substr($d['device_id'], 0, 16)) ?>...</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <code class="text-[10px] font-mono px-2 py-1 rounded-lg border bg-slate-50 text-slate-600 border-slate-100"><?= htmlspecialchars($d['ip_address'] ?? '—') ?></code>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($d['status'] === 'active'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Kích hoạt</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-slate-100 text-slate-500 border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Không hoạt động</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-[10px] text-slate-450 font-mono"><?= date('d/m/Y H:i', $d['activated_at']) ?></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <button onclick="renameDevice(<?= $d['id'] ?>, '<?= htmlspecialchars($d['device_name'] ?? '', ENT_QUOTES) ?>')" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100">Đổi Tên</button>
                                                <button onclick="confirmRemoveDevice(<?= $d['id'] ?>)" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-red-50 text-red-600 border-red-200 hover:bg-red-100">Reset</button>
                                            </div>
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

    <form id="action-form" method="POST" action="/admin/devices" class="hidden">
        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
        <input type="hidden" name="device_id" id="form-device-id">
        <input type="hidden" name="action" id="form-action">
        <input type="hidden" name="device_name" id="form-device-name">
    </form>

    <script>
    function renameDevice(id, currentName) {
        Swal.fire({
            title: 'Đổi Tên Thiết Bị',
            input: 'text',
            inputValue: currentName,
            inputPlaceholder: 'Nhập tên thiết bị mới...',
            showCancelButton: true,
            confirmButtonText: 'Lưu Thay Đổi',
            cancelButtonText: 'Hủy',
            inputValidator: (v) => { if (!v.trim()) return 'Tên thiết bị không được để trống!'; }
        }).then((r) => {
            if (r.isConfirmed) {
                document.getElementById('form-device-id').value = id;
                document.getElementById('form-action').value = 'rename_device';
                document.getElementById('form-device-name').value = r.value;
                document.getElementById('action-form').submit();
            }
        });
    }
    function confirmRemoveDevice(id) {
        Swal.fire({
            title: 'Xác Nhận Reset Thiết Bị?',
            html: '<p style="color:#64748b;font-size:13px;">Thiết bị này sẽ bị ngắt kết nối khỏi License Key ngay lập tức. Khách hàng có thể kết nối thiết bị mới.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xác Nhận Reset',
            cancelButtonText: 'Hủy'
        }).then((r) => {
            if (r.isConfirmed) {
                document.getElementById('form-device-id').value = id;
                document.getElementById('form-action').value = 'remove_device';
                document.getElementById('action-form').submit();
            }
        });
    }
    </script>
</body>
</html>
