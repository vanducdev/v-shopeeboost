<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center p-4">

    <!-- Card Container -->
    <div class="w-full max-w-md bg-white border border-slate-200/60 rounded-3xl p-8 shadow-xl relative overflow-hidden">
        <!-- Blur effect -->
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl"></div>

        <div class="text-center mb-8">
            <a href="/" class="inline-block mb-6">
                <img src="/image/logo-app-vmax-64x.png" alt="VMAX Logo" class="h-12 w-auto object-contain mx-auto">
            </a>
            <h1 class="text-xl font-extrabold text-slate-900">Thiết lập mật khẩu mới</h1>
            <?php if (!empty($email)): ?>
                <p class="text-slate-500 text-xs mt-2">Đang khôi phục cho tài khoản: <strong><?= htmlspecialchars($email) ?></strong></p>
            <?php else: ?>
                <p class="text-slate-500 text-xs mt-2">Vui lòng kiểm tra mã OTP gửi về Email của bạn.</p>
            <?php endif; ?>
        </div>

        <!-- Alerts -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl text-xs mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2.5 rounded-xl text-xs mb-4">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/reset-password" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">

            <div>
                <label for="otp" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mã Xác Thực OTP (6 số)</label>
                <input type="text" id="otp" name="otp" required maxlength="6" placeholder="Nhập 6 số..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 focus:bg-white text-center text-sm font-bold tracking-widest transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật Khẩu Mới</label>
                <input type="password" id="password" name="password" required placeholder="Tối thiểu 6 ký tự..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 focus:bg-white text-xs font-semibold transition">
            </div>

            <div>
                <label for="confirm_password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Xác Nhận Mật Khẩu Mới</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Nhập lại mật khẩu mới..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 focus:bg-white text-xs font-semibold transition">
            </div>

            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition shadow-lg shadow-blue-500/20">Lưu Mật Khẩu Mới</button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-center flex justify-between text-xs">
            <a href="/forgot-password" class="text-slate-450 hover:text-slate-600 transition">Gửi lại mã OTP</a>
            <a href="/login" class="text-blue-600 hover:text-blue-800 font-bold transition">Đăng nhập</a>
        </div>
    </div>

</body>
</html>
