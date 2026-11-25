<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cấu Hình Hệ Thống - VMAX Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 min-h-screen overflow-y-auto">
        <header class="sticky top-0 z-30 px-8 py-4 flex items-center justify-between border-b border-slate-200 bg-white/95 backdrop-blur-md">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Cấu Hình Hệ Thống</h1>
                <p class="text-slate-500 text-xs">Quản lý thông số, API key thanh toán và cấu hình SMTP</p>
            </div>
        </header>

        <div class="px-6 md:px-8 py-8 max-w-4xl">
            <?php if (!empty($message)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-emerald-55 text-emerald-700 border border-emerald-200"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST" action="/admin/settings" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">

                <!-- General Settings -->
                <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-sm font-bold text-slate-800 mb-5 pb-4 border-b border-slate-100 flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        Cài Đặt Chung
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Tên Thương Hiệu Hệ Thống</label>
                            <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? 'VMAX Software') ?>" class="w-full px-4 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Thông Tin Tài Khoản Ngân Hàng (VietQR)</label>
                            <textarea name="bank_transfer_info" rows="3" class="w-full px-4 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition resize-none"><?= htmlspecialchars($settings['bank_transfer_info'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- API Keys -->
                <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-sm font-bold text-slate-800 mb-5 pb-4 border-b border-slate-100 flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        </div>
                        API Keys & Tích Hợp
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Stripe API Key (Secret Key)</label>
                            <input type="text" name="stripe_key" value="<?= htmlspecialchars($settings['stripe_key'] ?? '') ?>" placeholder="sk_live_..." class="w-full px-4 py-2.5 rounded-xl text-xs font-mono border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Telegram Bot Token (2FA / Thông báo)</label>
                            <input type="text" name="telegram_bot_token" value="<?= htmlspecialchars($settings['telegram_bot_token'] ?? '') ?>" placeholder="123456789:AAXXXXXXXXX" class="w-full px-4 py-2.5 rounded-xl text-xs font-mono border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                        </div>
                    </div>
                </div>

                <!-- SMTP Settings -->
                <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-sm font-bold text-slate-800 mb-5 pb-4 border-b border-slate-100 flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        Cấu Hình Mail Server (SMTP)
                        <span class="ml-auto text-[10px] font-bold text-blue-500">Dùng để gửi OTP & thông báo</span>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">SMTP Host</label>
                            <input type="text" name="smtp_host" value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>" placeholder="smtp.gmail.com" class="w-full px-4 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">SMTP Port</label>
                            <input type="text" name="smtp_port" value="<?= htmlspecialchars($settings['smtp_port'] ?? '') ?>" placeholder="465 hoặc 587" class="w-full px-4 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">SMTP Username</label>
                            <input type="text" name="smtp_user" value="<?= htmlspecialchars($settings['smtp_user'] ?? '') ?>" placeholder="email@gmail.com" class="w-full px-4 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">SMTP Password</label>
                            <input type="password" name="smtp_pass" value="<?= htmlspecialchars($settings['smtp_pass'] ?? '') ?>" placeholder="App Password" class="w-full px-4 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">SMTP From Email</label>
                        <input type="text" name="smtp_from" value="<?= htmlspecialchars($settings['smtp_from'] ?? '') ?>" placeholder="noreply@vmaxtech.vn" class="w-full px-4 py-2.5 rounded-xl text-xs border border-slate-200 bg-slate-50 text-slate-800 outline-none focus:border-blue-500 focus:bg-white transition">
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-3 text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-500/10">
                        Lưu Tất Cả Cấu Hình
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
