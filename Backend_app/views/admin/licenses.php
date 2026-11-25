<!DOCTYPE html>
<html lang="vi">
<head>
    <link class="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cấp Phát Bản Quyền - VMAX Admin</title>
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
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Cấp Phát License Keys</h1>
                <p class="text-slate-500 text-xs">Tạo và quản lý mã bản quyền phần mềm VMAX</p>
            </div>
        </header>

        <div class="px-6 md:px-8 py-8">
            <?php if (!empty($message)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250"><?= $message ?></div>
            <?php endif; ?>

            <!-- Create Key Form -->
            <div class="bg-white border border-slate-200/60 rounded-2xl p-6 mb-6 shadow-sm">
                <h2 class="text-sm font-bold text-slate-800 mb-5 pb-4 border-b border-slate-100">Tạo License Key Mới</h2>
                <form method="POST" action="/admin/licenses" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                    <input type="hidden" name="action" value="create_key">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Phần Mềm</label>
                        <select name="product_id" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Thời Hạn</label>
                        <select name="duration" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                            <option value="30D">30 Ngày</option>
                            <option value="90D">90 Ngày</option>
                            <option value="180D">180 Ngày</option>
                            <option value="365D">365 Ngày</option>
                            <option value="LIFE">Trọn Đời</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Giới Hạn PC</label>
                        <input type="number" name="device_limit" min="1" max="100" value="1" required class="w-full px-3 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                    </div>
                    <button type="submit" class="h-[42px] px-6 text-xs font-bold rounded-xl transition text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-500/10">+ Tạo Key Ngay</button>
                </form>
            </div>

            <!-- Licenses Table -->
            <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800">Danh Sách License Keys</h2>
                    <span class="text-[10px] text-slate-400 font-bold uppercase"><?= count($licenses) ?> keys</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-semibold bg-slate-50/20">
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Phần Mềm</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Mã Key</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Khách Hàng</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Trạng Thái</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Hạn Dùng</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 font-medium">
                            <?php foreach ($licenses as $lic): ?>
                                <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-bold text-slate-800"><?= htmlspecialchars($lic['product_name']) ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <code class="text-[10px] font-mono px-2.5 py-1.5 rounded-lg border bg-blue-50 text-blue-600 border-blue-100 tracking-wider"><?= htmlspecialchars($lic['license_key']) ?></code>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($lic['user_id'] == 0): ?>
                                            <span class="text-xs text-slate-450 italic">Chưa kích hoạt</span>
                                        <?php else: ?>
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center text-[9px] font-black text-slate-700">
                                                    <?= mb_strtoupper(mb_substr($lic['username'], 0, 1)) ?>
                                                </div>
                                                <span class="text-xs font-bold text-slate-850"><?= htmlspecialchars($lic['username']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($lic['status'] === 'active'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Active
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-red-50 text-red-650 border border-red-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Suspended
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-[10px] text-slate-450 font-mono"><?= date('d/m/Y', $lic['expires_at']) ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="/admin/licenses">
                                                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                                <input type="hidden" name="license_id" value="<?= $lic['id'] ?>">
                                                <?php if ($lic['status'] === 'active'): ?>
                                                    <input type="hidden" name="action" value="suspend">
                                                    <button type="submit" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100">Khóa Key</button>
                                                <?php else: ?>
                                                    <input type="hidden" name="action" value="activate">
                                                    <button type="submit" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-emerald-50 text-emerald-700 border-emerald-250 hover:bg-emerald-100">Mở Khóa</button>
                                                <?php endif; ?>
                                            </form>
                                            <form method="POST" action="/admin/licenses" onsubmit="return confirm('Xóa vĩnh viễn key này?');">
                                                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                                <input type="hidden" name="license_id" value="<?= $lic['id'] ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-slate-50 text-slate-650 border-slate-200 hover:bg-slate-100">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
