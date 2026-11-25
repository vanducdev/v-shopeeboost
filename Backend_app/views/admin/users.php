<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng - VMAX Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 min-h-screen overflow-y-auto">
        <header class="sticky top-0 z-30 px-8 py-4 flex items-center justify-between border-b border-slate-200 bg-white/95 backdrop-blur-md">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Quản Lý Người Dùng</h1>
                <p class="text-slate-500 text-xs">Xem, khóa và quản lý tài khoản khách hàng</p>
            </div>
            <span class="px-3 py-1.5 text-xs font-bold rounded-xl border bg-slate-100 text-slate-700 border-slate-200"><?= count($users) ?> Tài Khoản</span>
        </header>

        <div class="px-6 md:px-8 py-8">
            <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm font-bold text-slate-800">Danh Sách Tài Khoản Hệ Thống</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-semibold bg-slate-50/20">
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Người Dùng</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Email</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Vai Trò</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Trạng Thái</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Ngày Tạo</th>
                                <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 font-medium">
                            <?php foreach ($users as $usr): ?>
                                <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-xs font-black text-slate-700 shrink-0">
                                                <?= mb_strtoupper(mb_substr($usr['username'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="text-xs font-bold text-slate-800"><?= htmlspecialchars($usr['username']) ?></div>
                                                <div class="text-[10px] text-slate-400">ID #<?= $usr['id'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-550 font-mono"><?= htmlspecialchars($usr['email']) ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($usr['role'] === 'admin'): ?>
                                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-indigo-50 text-indigo-650 border border-indigo-100">ADMIN</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-slate-50 text-slate-500 border border-slate-100">USER</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($usr['status'] === 'active'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Hoạt động
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-full bg-red-50 text-red-650 border border-red-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Bị khóa
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-[10px] text-slate-400 font-mono"><?= date('d/m/Y H:i', $usr['created_at']) ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($usr['role'] !== 'admin'): ?>
                                            <div class="flex items-center justify-end gap-2">
                                                <form method="POST" action="/admin/users">
                                                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                                    <input type="hidden" name="user_id" value="<?= $usr['id'] ?>">
                                                    <?php if ($usr['status'] === 'active'): ?>
                                                        <input type="hidden" name="action" value="ban">
                                                        <button type="submit" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100">Khóa TK</button>
                                                    <?php else: ?>
                                                        <input type="hidden" name="action" value="unban">
                                                        <button type="submit" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-emerald-50 text-emerald-700 border-emerald-250 hover:bg-emerald-100">Mở Khóa</button>
                                                    <?php endif; ?>
                                                </form>
                                                <form method="POST" action="/admin/users" onsubmit="return confirm('Bạn có chắc chắn muốn xóa user này?');">
                                                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                                    <input type="hidden" name="user_id" value="<?= $usr['id'] ?>">
                                                    <input type="hidden" name="action" value="delete">
                                                    <button type="submit" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-slate-50 text-slate-650 border-slate-200 hover:bg-slate-100">Xóa</button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-right"><span class="text-[10px] text-slate-400 italic">Không thay đổi</span></div>
                                        <?php endif; ?>
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
