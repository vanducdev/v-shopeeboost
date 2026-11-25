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
            50% { transform: translateY(-10px) rotate(1deg); }
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.35; transform: scale(1); }
            50% { opacity: 0.65; transform: scale(1.08); }
        }
        @keyframes slide-down {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-float-card {
            animation: float-slow 7s ease-in-out infinite;
        }
        .animate-glow-mesh {
            animation: pulse-glow 9s ease-in-out infinite;
        }
        .animate-slide-down {
            animation: slide-down 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-fade-in {
            animation: fade-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        .custom-input {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .custom-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
            background-color: #ffffff;
        }

        /* Modern Toggle Switch */
        input:checked ~ .toggle-bg {
            background-color: #2563eb;
        }
        input:checked ~ .dot {
            transform: translateX(100%);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 min-h-screen flex items-center justify-center overflow-x-hidden antialiased">

    <!-- Split Layout Container -->
    <div class="flex w-full min-h-screen">
        
        <!-- Left Side: High-Tech 2026 SaaS Showcase (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#090d1a] text-white p-12 xl:p-16 flex-col justify-between relative overflow-hidden" style="background: radial-gradient(circle at 15% 15%, #1e1b4b 0%, #0f172a 60%, #030712 100%);">
            <!-- Dynamic Glowing Mesh Orbs -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-600/25 rounded-full blur-[120px] animate-glow-mesh pointer-events-none"></div>
            <div class="absolute top-1/2 -right-24 w-80 h-80 bg-indigo-600/20 rounded-full blur-[100px] animate-glow-mesh pointer-events-none" style="animation-delay: 3s;"></div>
            <div class="absolute -bottom-24 left-1/3 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px] animate-glow-mesh pointer-events-none" style="animation-delay: 5s;"></div>
            
            <!-- Top Header: Logo + Back to Home -->
            <div class="z-10 flex items-center justify-between w-full">
                <a href="/" class="transition-opacity hover:opacity-90 inline-flex items-center gap-2">
                    <img src="/image/logo-app-vmax-64x.webp" alt="VMAX Logo" class="h-10 w-auto object-contain">
                    <span class="px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-300 font-mono text-[10px] font-extrabold border border-blue-400/30">PRO 2026</span>
                </a>
                <a href="/" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/15 text-slate-300 hover:text-white text-xs font-semibold backdrop-blur-md border border-white/10 transition-all">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Trang chủ</span>
                </a>
            </div>
            
            <!-- Center Showcase -->
            <div class="my-auto z-10 max-w-lg mx-auto w-full py-6">
                <!-- Headline & Intro -->
                <div class="mb-8">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/15 text-blue-300 text-xs font-bold border border-blue-400/25 backdrop-blur-md mb-4 tracking-wide uppercase">
                        <i class="fa-solid fa-shield-halved text-emerald-400 text-[11px]"></i> HỆ SINH THÁI ANTIDETECT BẢO MẬT
                    </span>
                    <h1 class="text-3xl xl:text-4xl font-extrabold text-white leading-tight tracking-tight mb-3">
                        Bảo Mật Profile Tuyệt Đối & Tự Động Hóa Đa Kênh
                    </h1>
                    <p class="text-slate-300 text-sm leading-relaxed font-normal">
                        Nuôi hàng ngàn tài khoản Facebook, Shopee, Google Ads với vân tay trình duyệt Canvas, WebGL độc lập 100% không lo checkpoint.
                    </p>
                </div>

                <!-- 3D Glass Antidetect Device Card -->
                <div class="animate-float-card rounded-2xl bg-slate-900/80 border border-slate-700/60 p-4 shadow-2xl backdrop-blur-xl relative overflow-hidden mb-8">
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-500/10 via-transparent to-purple-500/10 pointer-events-none"></div>
                    
                    <!-- Window Top Bar -->
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-800 text-[11px] font-semibold text-slate-400">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span class="ml-2 font-mono text-slate-300 text-[10px]">VmaxLogin Workstation v2.4</span>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-950/80 text-emerald-400 text-[10px] font-mono border border-emerald-800/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> SOCKS5 Connected
                        </span>
                    </div>

                    <!-- Mini Profile Rows -->
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-800/60 border border-slate-700/50 hover:bg-slate-800 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-blue-600/20 text-blue-400 flex items-center justify-center shrink-0">
                                    <i class="fa-brands fa-facebook-f text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-200 truncate">FB_Agency_BM2500</div>
                                    <div class="text-[10px] text-slate-400">Chrome 124 • Canvas Noise • VN Proxy</div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-500/15 text-emerald-400 text-[10px] font-bold border border-emerald-500/30 shrink-0">Đang chạy</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-800/60 border border-slate-700/50 hover:bg-slate-800 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-orange-600/20 text-orange-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-cart-shopping text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-200 truncate">Shopee_Mall_Store</div>
                                    <div class="text-[10px] text-slate-400">Chrome 124 • WebGL Clean • Đẩy Top Auto</div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-500/15 text-emerald-400 text-[10px] font-bold border border-emerald-500/30 shrink-0">Đang chạy</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-800/60 border border-slate-700/50 hover:bg-slate-800 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-purple-600/20 text-purple-400 flex items-center justify-center shrink-0">
                                    <i class="fa-brands fa-tiktok text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-200 truncate">TikTok_Live_Seeding</div>
                                    <div class="text-[10px] text-slate-400">macOS • WebRTC Shield • Multi-Thread</div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-md bg-blue-500/15 text-blue-400 text-[10px] font-bold border border-blue-500/30 shrink-0">Sẵn sàng</span>
                        </div>
                    </div>

                    <!-- Mini Footer inside Card -->
                    <div class="flex items-center justify-between pt-3 mt-3 border-t border-slate-800/80 text-[10px] text-slate-400 font-mono">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-microchip text-blue-400"></i> RAM: 1.4 GB</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-gauge-high text-emerald-400"></i> CPU: 12%</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-cloud-arrow-up text-indigo-400"></i> Cloud Sync 100%</span>
                    </div>
                </div>

                <!-- 3 Key Metric Badges -->
                <div class="grid grid-cols-3 gap-3">
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-center backdrop-blur-sm">
                        <div class="text-lg font-extrabold text-blue-400">10.000+</div>
                        <div class="text-[10.5px] text-slate-400 font-medium">Khách Hàng Tin Dùng</div>
                    </div>
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-center backdrop-blur-sm">
                        <div class="text-lg font-extrabold text-emerald-400">0 Checkpoint</div>
                        <div class="text-[10.5px] text-slate-400 font-medium">Vân Tay Độc Lập</div>
                    </div>
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-center backdrop-blur-sm">
                        <div class="text-lg font-extrabold text-warning text-amber-400">VietQR 10s</div>
                        <div class="text-[10.5px] text-slate-400 font-medium">Kích Hoạt Tự Động</div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Left Footer -->
            <div class="text-xs text-slate-400 z-10 flex items-center justify-between border-t border-white/10 pt-4 w-full">
                <span>&copy; <?= date('Y') ?> VMAX Software. All rights reserved.</span>
                <span class="flex gap-4">
                    <a href="/terms" class="hover:text-white transition">Điều khoản</a>
                    <span>•</span>
                    <a href="/privacy" class="hover:text-white transition">Bảo mật</a>
                </span>
            </div>
        </div>

        <!-- Right Side: Ultra-Modern SaaS Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-[#f8fafc] relative">
            <div class="max-w-md w-full bg-white p-8 md:p-10 rounded-3xl shadow-[0_20px_50px_rgba(15,23,42,0.06)] border border-slate-200/80 animate-fade-in">
                
                <!-- Mobile Brand Header -->
                <div class="text-center mb-6 lg:hidden">
                    <a href="/" class="inline-flex items-center gap-2 mb-2">
                        <img src="/image/logo-app-vmax-64x.webp" alt="VMAX Logo" class="h-10 w-auto mx-auto object-contain">
                    </a>
                </div>
                
                <!-- Title Header -->
                <div class="mb-7 text-center lg:text-left">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Chào mừng quay lại!</h2>
                    <p class="text-slate-500 mt-2 text-sm font-medium">Đăng nhập để truy cập tài khoản và quản lý bản quyền</p>
                </div>

                <!-- Error Alert -->
                <?php if (!empty($error)): ?>
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-sm mb-6 flex items-center gap-3 animate-slide-down">
                        <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600">
                            <i class="fa-solid fa-circle-exclamation text-sm"></i>
                        </div>
                        <span class="font-medium text-[13px] leading-snug"><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/login" class="space-y-4" id="login-form">
                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                    
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Email hoặc Tên đăng nhập</label>
                        <div class="relative">
                            <input type="text" id="email" name="email" required placeholder="admin hoặc admin@vmaxsoftware.vn" class="custom-input w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 font-medium rounded-xl text-sm outline-none">
                            <i class="fa-regular fa-envelope text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-sm"></i>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Mật Khẩu</label>
                            <a href="/forgot-password" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline transition">Quên mật khẩu?</a>
                        </div>
                        <div class="relative">
                            <input type="password" id="password" name="password" required placeholder="••••••••" class="custom-input w-full pl-11 pr-11 py-3 bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 font-medium rounded-xl text-sm outline-none">
                            <i class="fa-solid fa-lock text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-sm"></i>
                            <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none p-1 transition" title="Ẩn/Hiện mật khẩu">
                                <i class="fa-regular fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Option -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox" id="remember_me" class="sr-only" />
                                <div class="w-8 h-4.5 bg-slate-200 rounded-full transition-colors duration-200 ease-in-out toggle-bg"></div>
                                <div class="dot absolute left-0.5 top-0.5 bg-white w-3.5 h-3.5 rounded-full transition-transform duration-200 ease-in-out shadow-sm"></div>
                            </div>
                            <span class="ml-2.5 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors">Ghi nhớ đăng nhập</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="btn-submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 via-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35 hover:scale-[1.01] active:scale-[0.99] transition duration-200 text-sm tracking-wide flex items-center justify-center gap-2 cursor-pointer">
                        <span>Đăng Nhập</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative flex py-5 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-3 text-slate-400 text-[10px] font-extrabold uppercase tracking-widest">Hoặc tiếp tục với</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- Google Sign-In Container -->
                <div class="flex justify-center w-full">
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
                         data-width="360">
                    </div>
                </div>

                <!-- Footer Switch to Register -->
                <div class="text-center mt-7 pt-5 border-t border-slate-100 text-sm text-slate-500 font-medium">
                    Chưa có tài khoản? <a href="/register" class="text-blue-600 font-extrabold hover:text-blue-700 hover:underline transition">Đăng ký dùng thử ngay →</a>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Script Block -->
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
            const submitBtn = document.getElementById('btn-submit');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> <span>Đang đăng nhập...</span>';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
            }

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