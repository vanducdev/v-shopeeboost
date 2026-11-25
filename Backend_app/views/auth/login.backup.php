<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(15px) rotate(-2deg); }
        }
        @keyframes glow-pulse {
            0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.1); }
            50% { box-shadow: 0 0 35px rgba(99, 102, 241, 0.25); }
        }
        @keyframes slide-down {
            from { opacity: 0; transform: translateY(-12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-float-1 {
            animation: float-slow 8s ease-in-out infinite;
        }
        .animate-float-2 {
            animation: float-delayed 10s ease-in-out infinite;
        }
        .glow-card {
            animation: glow-pulse 6s ease-in-out infinite;
        }
        .animate-slide-down {
            animation: slide-down 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-fade-in {
            animation: fade-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        .custom-input {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .custom-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
            background-color: #fff;
        }
        /* Custom Toggle Switch */
        input:checked ~ .toggle-bg {
            background-color: #2563eb;
        }
        input:checked ~ .dot {
            transform: translateX(100%);
        }
        /* Custom Checkbox checked states */
        input:checked + div {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        input:checked + div svg {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-[#f3f4f6] text-slate-900 min-h-screen flex items-center justify-center overflow-x-hidden antialiased">

    <!-- Split Layout Container -->
    <div class="flex w-full min-h-screen">
        
        <!-- Left Side: Beautiful Banner & Illustration (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-tr from-blue-700 via-indigo-900 to-purple-950 text-white p-16 flex-col justify-between relative overflow-hidden">
            <!-- Dynamic fluid blur shapes -->
            <div class="absolute top-[-10%] left-[-10%] w-[60%] h-[50%] bg-blue-500/25 rounded-full blur-[100px] animate-float-1"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[70%] h-[50%] bg-indigo-500/20 rounded-full blur-[120px] animate-float-2"></div>
            <div class="absolute top-[30%] right-[10%] w-[30%] h-[30%] bg-purple-500/20 rounded-full blur-[90px] animate-float-1"></div>
            
            <!-- Brand Logo -->
            <div class="z-10 animate-fade-in">
                <a href="/" class="transition-opacity hover:opacity-90 inline-block">
                    <img src="/image/logo-app-vmax45.png" alt="VMAX Logo" class="h-16 w-auto object-contain">
                </a>
            </div>
            
            <!-- Center illustration & Copy -->
            <div class="my-auto z-10 flex flex-col items-center text-center max-w-xl mx-auto animate-fade-in" style="animation-delay: 0.1s;">
                <div class="mb-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 text-xs font-semibold text-blue-200 backdrop-blur-md border border-white/10 mb-5 tracking-wide uppercase">
                        <i class="fa-solid fa-circle-check text-[10px] text-emerald-400"></i> GIẢI PHÁP TỐI ƯU MMO
                    </span>
                    <h2 class="text-4xl font-extrabold mb-5 leading-tight tracking-tight bg-gradient-to-r from-white via-slate-100 to-blue-200 bg-clip-text text-transparent">
                        Giải Pháp Quản Lý Bản Quyền<br/>Phần Mềm Chuyên Nghiệp
                    </h2>
                    <p class="text-blue-100/75 text-base leading-relaxed font-light">
                        Đồng hành cùng hàng ngàn khách hàng tối ưu hóa doanh nghiệp số, nuôi tài khoản và tự động hóa MMO an toàn, tối ưu chi phí.
                    </p>
                </div>
                <div class="w-full max-w-sm hover:scale-[1.03] transition-all duration-500 ease-out glow-card rounded-[32px] overflow-hidden">
                    <img src="/image/nhanvienvmax.png" alt="Nhân viên hỗ trợ VMAX" class="w-full h-auto rounded-[32px] border border-white/10 bg-white/5 backdrop-blur-md p-5">
                </div>
            </div>
            
            <!-- Footer text -->
            <div class="text-xs text-blue-200/50 z-10 font-light flex items-center justify-between border-t border-white/10 pt-4 w-full">
                <span>&copy; <?= date('Y') ?> VMAX Software. All rights reserved.</span>
                <span class="flex gap-4">
                    <a href="/terms" class="hover:text-blue-100 transition">Điều khoản</a>
                    <a href="/privacy" class="hover:text-blue-100 transition">Bảo mật</a>
                </span>
            </div>
        </div>

        <!-- Right Side: Clean & Modern Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-[#f8fafc] relative">
            <!-- Decorative circle for mobile layout background depth -->
            <div class="lg:hidden absolute top-[-5%] right-[-5%] w-72 h-72 bg-blue-100/50 rounded-full blur-3xl -z-10"></div>
            
            <div class="max-w-md w-full bg-white p-8 md:p-12 rounded-[32px] shadow-[0_25px_60px_rgba(0,0,0,0.025)] border border-slate-100/80 animate-fade-in" style="animation-delay: 0.15s;">
                <!-- Mobile logo representation -->
                <div class="text-center mb-8 lg:hidden">
                    <a href="/" class="inline-block">
                        <img src="/image/logo-app-vmax45.png" alt="VMAX Logo" class="h-14 w-auto mx-auto object-contain">
                    </a>
                </div>
                
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Chào mừng quay lại</h2>
                    <p class="text-slate-400 mt-2.5 text-sm font-medium">Đăng nhập tài khoản để tiếp tục truy cập hệ thống</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="bg-rose-50/80 border border-rose-100/90 text-rose-700 px-4 py-3.5 rounded-2xl text-sm mb-6 flex items-center gap-3 animate-slide-down backdrop-blur-md">
                        <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                        <span class="font-semibold text-[13px]"><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/login" class="space-y-4" id="login-form">
                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                    
                    <div class="input-group">
                        <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Địa Chỉ Email</label>
                        <div class="relative">
                            <input type="email" id="email" name="email" required placeholder="name@domain.com" class="custom-input w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200/80 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 rounded-2xl outline-none text-sm text-slate-800 placeholder-slate-400 font-medium">
                            <i class="input-icon fa-regular fa-envelope text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-[15px] transition-colors duration-250"></i>
                        </div>
                    </div>

                    <div class="input-group">
                        <div class="flex justify-between items-center mb-2.5">
                            <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Mật Khẩu</label>
                        </div>
                        <div class="relative">
                            <input type="password" id="password" name="password" required placeholder="••••••••" class="custom-input w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200/80 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 rounded-2xl outline-none text-sm text-slate-800 placeholder-slate-400 font-medium">
                            <i class="input-icon fa-solid fa-lock-open text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-[15px] transition-colors duration-250"></i>
                            <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                                <i class="fa-regular fa-eye text-[15px]"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Option -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox" id="remember_me" class="sr-only" />
                                <div class="w-9 h-5 bg-slate-200 rounded-full transition-colors duration-200 ease-in-out toggle-bg"></div>
                                <div class="dot absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full transition-transform duration-200 ease-in-out shadow-sm"></div>
                            </div>
                            <span class="ml-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 transition-colors">Nhớ tài khoản</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/15 hover:shadow-blue-500/25 hover:scale-[1.01] active:scale-[0.99] transition duration-300 text-sm tracking-wide">
                        Đăng Nhập <i class="fa-solid fa-arrow-right ml-1.5 text-xs transition-transform duration-200 hover:translate-x-0.5"></i>
                    </button>
                </form>

                <!-- Google Sign-In Option -->
                <div class="relative flex py-6 items-center">
                    <div class="flex-grow border-t border-slate-200/60"></div>
                    <span class="flex-shrink mx-4 text-slate-400 text-[10px] font-extrabold uppercase tracking-widest">Hoặc đăng nhập bằng</span>
                    <div class="flex-grow border-t border-slate-200/60"></div>
                </div>

                <div class="flex justify-center w-full hover:scale-[1.01] transition-transform duration-200">
                    <div id="g_id_onload"
                         data-client_id="<?= GOOGLE_CLIENT_ID ?>"
                         data-context="signin"
                         data-ux_mode="popup"
                         data-login_uri="<?= SITE_URL ?>/auth/google"
                         data-auto_prompt="false">
                    </div>
                    <div class="g_id_signin w-full flex justify-center"
                         data-type="standard"
                         data-shape="pill"
                         data-theme="outline"
                         data-text="signin_with"
                         data-size="large"
                         data-logo_alignment="center"
                         data-width="400">
                    </div>
                </div>

                <div class="text-center mt-8 text-sm text-slate-500 font-medium">
                    Chưa có tài khoản? <a href="/register" class="text-blue-600 font-bold hover:text-blue-700 hover:underline transition">Đăng ký ngay</a>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Script Block for UI features -->
    <script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Encoding helpers for basic obfuscation in localStorage
    function encodeData(str) {
        return btoa(unescape(encodeURIComponent(str)));
    }
    function decodeData(str) {
        try {
            return decodeURIComponent(escape(atob(str)));
        } catch(e) {
            return '';
        }
    }

    // Remember Me logic
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById('login-form');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const rememberCheckbox = document.getElementById('remember_me');

        // Auto-fill on load if enabled
        if (localStorage.getItem('vmax_remember') === 'true') {
            const savedEmail = decodeData(localStorage.getItem('vmax_email') || '');
            const savedPass = decodeData(localStorage.getItem('vmax_pass') || '');
            if (savedEmail) emailInput.value = savedEmail;
            if (savedPass) passwordInput.value = savedPass;
            if (rememberCheckbox) rememberCheckbox.checked = true;
        }

        form.addEventListener('submit', () => {
            if (rememberCheckbox && rememberCheckbox.checked) {
                localStorage.setItem('vmax_remember', 'true');
                localStorage.setItem('vmax_email', encodeData(emailInput.value));
                localStorage.setItem('vmax_pass', encodeData(passwordInput.value));
            } else {
                localStorage.removeItem('vmax_remember');
                localStorage.removeItem('vmax_email');
                localStorage.removeItem('vmax_pass');
            }
        });
    });
    </script>
</body>
</html>
