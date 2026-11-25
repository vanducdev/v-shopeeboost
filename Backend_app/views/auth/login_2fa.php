<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Thực Hai Yếu Tố - VMAX Software</title>
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
            box-shadow: 0 15px 35px -5px rgba(59, 130, 246, 0.08);
        }
    </style>
</head>
<body class="text-slate-850 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="/image/logo-app-vmax-64x.png" alt="VMAX Logo" class="w-16 h-16 mx-auto mb-3 hover:scale-105 transition-transform duration-300">
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Xác thực 2FA</h1>
            <p class="text-slate-400 text-xs mt-1 font-medium">Bảo vệ tài khoản của bạn bằng mã Google Authenticator.</p>
        </div>

        <!-- 2FA Box -->
        <div class="glass-panel glass-panel-glow rounded-3xl p-6 md:p-8 shadow-sm">
            
            <?php if (!empty($error)): ?>
                <div class="bg-rose-50 border border-rose-200/60 text-rose-700 px-4 py-3 rounded-xl text-xs mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login-2fa" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                
                <div class="space-y-2">
                    <label for="2fa_code" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center mb-1">Mã xác minh 2FA gồm 6 chữ số</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="text" id="2fa_code" name="2fa_code" maxlength="6" pattern="[0-9]{6}" required placeholder="000000" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-2xl outline-none transition text-center text-lg font-extrabold tracking-[0.5em] text-slate-800 placeholder-slate-300">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-500/10 text-xs flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Xác nhận đăng nhập
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="/login" class="text-xs text-blue-600 hover:text-blue-700 font-bold transition">Quay lại đăng nhập</a>
            </div>
        </div>
    </div>

</body>
</html>
