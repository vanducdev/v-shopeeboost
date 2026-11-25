<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản - VMAX Software</title>
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
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-300 text-xs font-bold border border-emerald-400/25 backdrop-blur-md mb-4 tracking-wide uppercase">
                        <i class="fa-solid fa-gift text-emerald-400 text-[11px]"></i> TẠO TÀI KHOẢN DÙNG THỬ MIỄN PHÍ
                    </span>
                    <h1 class="text-3xl xl:text-4xl font-extrabold text-white leading-tight tracking-tight mb-3">
                        Gia Nhập Cộng Đồng Phần Mềm VMAX
                    </h1>
                    <p class="text-slate-300 text-sm leading-relaxed font-normal">
                        Khởi tạo tài khoản chỉ trong 30 giây để bắt đầu trải nghiệm hệ thống Antidetect Browser, quản lý key bản quyền và nhận chiết khấu hoa hồng affiliate.
                    </p>
                </div>

                <!-- 3D Glass Benefits Card -->
                <div class="animate-float-card rounded-2xl bg-slate-900/80 border border-slate-700/60 p-5 shadow-2xl backdrop-blur-xl relative overflow-hidden mb-8">
                    <div class="absolute inset-0 bg-gradient-to-tr from-emerald-500/10 via-transparent to-blue-500/10 pointer-events-none"></div>
                    
                    <h4 class="font-bold text-white text-sm uppercase tracking-wider mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-crown text-amber-400"></i> Đặc quyền thành viên VMAX
                    </h4>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-800/60 border border-slate-700/50">
                            <div class="w-7 h-7 rounded-lg bg-blue-600/20 text-blue-400 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-shield-halved text-xs"></i>
                            </div>
                            <div>
                                <div class="font-bold text-slate-200">Trải nghiệm Antidetect miễn phí</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">Cấp profile dùng thử với công nghệ giả lập Canvas, WebGL sạch 100%.</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-800/60 border border-slate-700/50">
                            <div class="w-7 h-7 rounded-lg bg-emerald-600/20 text-emerald-400 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-rotate text-xs"></i>
                            </div>
                            <div>
                                <div class="font-bold text-slate-200">Đổi máy (HWID) 1-click tự động</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">Tự do đổi thiết bị linh hoạt ngay trong Dashboard cá nhân 24/7.</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-800/60 border border-slate-700/50">
                            <div class="w-7 h-7 rounded-lg bg-purple-600/20 text-purple-400 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-hand-holding-dollar text-xs"></i>
                            </div>
                            <div>
                                <div class="font-bold text-slate-200">Hoa hồng Affiliate tới 10% trọn đời</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">Giới thiệu bạn bè nhận hoa hồng rút về tài khoản ngân hàng tức thì.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3 Key Metric Badges -->
                <div class="grid grid-cols-3 gap-3">
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-center backdrop-blur-sm">
                        <div class="text-lg font-extrabold text-emerald-400">30 Giây</div>
                        <div class="text-[10.5px] text-slate-400 font-medium">Đăng Ký Nhanh</div>
                    </div>
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-center backdrop-blur-sm">
                        <div class="text-lg font-extrabold text-blue-400">100% Free</div>
                        <div class="text-[10.5px] text-slate-400 font-medium">Không Cần Thẻ Visa</div>
                    </div>
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-center backdrop-blur-sm">
                        <div class="text-lg font-extrabold text-purple-400">24/7</div>
                        <div class="text-[10.5px] text-slate-400 font-medium">Hỗ Trợ Kỹ Thuật 1:1</div>
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

        <!-- Right Side: Clean Modern Register Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-10 bg-[#f8fafc] relative overflow-y-auto">
            <div class="max-w-md w-full bg-white p-8 md:p-10 rounded-3xl shadow-[0_20px_50px_rgba(15,23,42,0.06)] border border-slate-200/80 my-4 animate-fade-in">
                
                <!-- Mobile Brand Header -->
                <div class="text-center mb-6 lg:hidden">
                    <a href="/" class="inline-flex items-center gap-2 mb-2">
                        <img src="/image/logo-app-vmax-64x.webp" alt="VMAX Logo" class="h-10 w-auto mx-auto object-contain">
                    </a>
                </div>
                
                <!-- Title Header -->
                <div class="mb-6 text-center lg:text-left">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Tạo tài khoản mới</h2>
                    <p class="text-slate-500 mt-1.5 text-sm font-medium">Bắt đầu trải nghiệm hệ thống bản quyền phần mềm VMAX</p>
                </div>

                <!-- Error Alert -->
                <?php if (!empty($error)): ?>
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-sm mb-5 flex items-center gap-3 animate-slide-down">
                        <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600">
                            <i class="fa-solid fa-circle-exclamation text-sm"></i>
                        </div>
                        <span class="font-medium text-[13px] leading-snug"><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/register" class="space-y-4" id="register-form">
                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                    <input type="hidden" name="referrer_code" value="<?= htmlspecialchars($_GET['ref'] ?? '') ?>">
                    
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tên Tài Khoản (Username)</label>
                        <div class="relative">
                            <input type="text" id="username" name="username" required placeholder="vmax_user" class="custom-input w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 font-medium rounded-xl text-sm outline-none">
                            <i class="fa-regular fa-user text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-sm"></i>
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Địa Chỉ Email</label>
                        <div class="relative">
                            <input type="email" id="email" name="email" required placeholder="name@domain.com" class="custom-input w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 font-medium rounded-xl text-sm outline-none">
                            <i class="fa-regular fa-envelope text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-sm"></i>
                        </div>
                        <p id="email-error" class="text-rose-500 text-[11px] mt-1 px-1 hidden animate-slide-down"><i class="fa-solid fa-circle-info mr-1"></i> Định dạng email chưa hợp lệ</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Mật Khẩu</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required placeholder="••••••••" class="custom-input w-full pl-11 pr-11 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 font-medium rounded-xl text-sm outline-none">
                            <i class="fa-solid fa-lock text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-sm"></i>
                            <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none p-1 transition" title="Ẩn/Hiện mật khẩu">
                                <i class="fa-regular fa-eye text-sm"></i>
                            </button>
                        </div>
                        
                        <!-- Dynamic Password Strength Meter -->
                        <div class="mt-2 space-y-1 px-0.5">
                            <div class="flex justify-between items-center text-[10px] font-bold text-slate-400">
                                <span>Độ mạnh mật khẩu:</span>
                                <span id="password-strength-text" class="text-slate-400 transition-colors duration-300">Chưa nhập</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div id="password-strength-bar" class="h-full w-0 bg-slate-300 rounded-full transition-all duration-300"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="confirm_password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Xác Nhận Mật Khẩu</label>
                        <div class="relative">
                            <input type="password" id="confirm_password" name="confirm_password" required placeholder="••••••••" class="custom-input w-full pl-11 pr-11 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 font-medium rounded-xl text-sm outline-none">
                            <i class="fa-solid fa-shield-halved text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-sm"></i>
                            <button type="button" onclick="togglePasswordVisibility('confirm_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none p-1 transition" title="Ẩn/Hiện mật khẩu">
                                <i class="fa-regular fa-eye text-sm"></i>
                            </button>
                        </div>
                        <p id="password-match-error" class="text-rose-500 text-[11px] mt-1 px-1 hidden animate-slide-down"><i class="fa-solid fa-circle-info mr-1"></i> Mật khẩu xác nhận chưa khớp</p>
                    </div>

                    <!-- Referral Code Badge if present -->
                    <?php if (!empty($_GET['ref'])): ?>
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-2.5 text-xs text-blue-700 flex items-center justify-between">
                            <span class="font-semibold flex items-center gap-1.5">
                                <i class="fa-solid fa-gift text-blue-500"></i> Mã giới thiệu:
                            </span>
                            <strong class="font-mono bg-blue-100 px-2 py-0.5 rounded text-blue-800"><?= htmlspecialchars($_GET['ref']) ?></strong>
                        </div>
                    <?php endif; ?>

                    <!-- Terms Agreement Checkbox -->
                    <div class="pt-1">
                        <label class="flex items-start cursor-pointer select-none group">
                            <div class="relative flex items-center mt-0.5">
                                <input type="checkbox" id="terms_agree" required class="sr-only peer" />
                                <div class="w-4 h-4 border border-slate-300 rounded bg-slate-50 flex items-center justify-center transition-all peer-checked:bg-blue-600 peer-checked:border-blue-600">
                                    <svg class="w-2.5 h-2.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <span class="ml-2.5 text-[11px] font-semibold text-slate-500 group-hover:text-slate-700 leading-normal transition-colors">
                                Tôi đồng ý với <a href="/terms" target="_blank" class="text-blue-600 hover:text-blue-700 hover:underline">Điều khoản</a> & <a href="/privacy" target="_blank" class="text-blue-600 hover:text-blue-700 hover:underline">Bảo mật</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="btn-submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 via-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35 hover:scale-[1.01] active:scale-[0.99] transition duration-200 text-sm tracking-wide flex items-center justify-center gap-2 cursor-pointer mt-2">
                        <span>Tạo Tài Khoản Dùng Thử</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative flex py-4 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-3 text-slate-400 text-[10px] font-extrabold uppercase tracking-widest">Hoặc đăng ký nhanh với</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- Google Sign-In Container -->
                <div class="flex justify-center w-full">
                    <div id="g_id_onload"
                         data-client_id="<?= GOOGLE_CLIENT_ID ?>"
                         data-context="signup"
                         data-ux_mode="popup"
                         data-login_uri="<?= SITE_URL ?>/auth/google"
                         data-auto_prompt="false">
                    </div>
                    <div class="g_id_signin w-full flex justify-center"
                         data-type="standard"
                         data-shape="pill"
                         data-theme="outline"
                         data-text="signup_with"
                         data-size="large"
                         data-logo_alignment="center"
                         data-width="360">
                    </div>
                </div>

                <!-- Footer Switch to Login -->
                <div class="text-center mt-6 pt-4 border-t border-slate-100 text-sm text-slate-500 font-medium">
                    Đã có tài khoản? <a href="/login" class="text-blue-600 font-extrabold hover:text-blue-700 hover:underline transition">Đăng nhập ngay →</a>
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

    // Auto-suggest Username based on Email
    let usernameManuallyEdited = false;
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');

    usernameInput.addEventListener('input', () => {
        usernameManuallyEdited = true;
    });

    emailInput.addEventListener('input', (e) => {
        if (!usernameManuallyEdited) {
            const emailValue = e.target.value;
            const atIndex = emailValue.indexOf('@');
            if (atIndex > 0) {
                const localPart = emailValue.substring(0, atIndex);
                const sanitized = localPart.toLowerCase().replace(/[^a-z0-9_]/g, '');
                usernameInput.value = sanitized;
            }
        }
    });

    // Inline validation for Email format
    const emailError = document.getElementById('email-error');
    emailInput.addEventListener('input', () => {
        const email = emailInput.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            emailError.textContent = 'Định dạng email chưa hợp lệ (Ví dụ: name@domain.com)';
            emailError.classList.remove('hidden');
        } else {
            emailError.classList.add('hidden');
        }
    });

    // Dynamic Password Strength Meter
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');

    passwordInput.addEventListener('input', () => {
        const val = passwordInput.value;
        let score = 0;
        
        if (!val) {
            strengthBar.style.width = '0%';
            strengthBar.className = 'h-full w-0 bg-slate-300 rounded-full transition-all duration-300';
            strengthText.textContent = 'Chưa nhập';
            strengthText.className = 'text-slate-400 transition-colors duration-300';
            return;
        }

        if (val.length >= 6) score += 1;
        if (val.length >= 10) score += 1;
        if (/[A-Z]/.test(val)) score += 1;
        if (/[0-9]/.test(val)) score += 1;
        if (/[^A-Za-z0-9]/.test(val)) score += 1;

        if (score <= 1) {
            strengthBar.style.width = '25%';
            strengthBar.className = 'h-full bg-rose-500 rounded-full transition-all duration-300';
            strengthText.textContent = 'Yếu';
            strengthText.className = 'text-rose-500 font-bold transition-colors duration-300';
        } else if (score <= 3) {
            strengthBar.style.width = '65%';
            strengthBar.className = 'h-full bg-amber-500 rounded-full transition-all duration-300';
            strengthText.textContent = 'Trung bình';
            strengthText.className = 'text-amber-500 font-bold transition-colors duration-300';
        } else {
            strengthBar.style.width = '100%';
            strengthBar.className = 'h-full bg-emerald-500 rounded-full transition-all duration-300';
            strengthText.textContent = 'Mạnh';
            strengthText.className = 'text-emerald-500 font-bold transition-colors duration-300';
        }
    });

    // Inline validation for Password match
    const confirmInput = document.getElementById('confirm_password');
    const matchError = document.getElementById('password-match-error');

    function checkPasswordMatch() {
        const pass = passwordInput.value;
        const confirmPass = confirmInput.value;
        if (confirmPass && pass !== confirmPass) {
            matchError.classList.remove('hidden');
        } else {
            matchError.classList.add('hidden');
        }
    }

    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmInput.addEventListener('input', checkPasswordMatch);

    function encodeData(str) {
        return btoa(unescape(encodeURIComponent(str)));
    }

    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById('register-form');
        form.addEventListener('submit', () => {
            const submitBtn = document.getElementById('btn-submit');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> <span>Đang khởi tạo tài khoản...</span>';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
            }
        });
    });
    </script>
</body>
</html>