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
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-tr from-blue-700 via-indigo-900 to-purple-955 text-white p-16 flex-col justify-between relative overflow-hidden">
            <!-- Dynamic fluid blur shapes -->
            <div class="absolute top-[-10%] left-[-10%] w-[60%] h-[50%] bg-blue-500/25 rounded-full blur-[100px] animate-float-1"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[70%] h-[50%] bg-indigo-500/20 rounded-full blur-[120px] animate-float-2"></div>
            <div class="absolute top-[30%] right-[10%] w-[30%] h-[30%] bg-purple-500/20 rounded-full blur-[90px] animate-float-1"></div>
            
            <!-- Brand Logo -->
            <div class="z-10 animate-fade-in">
                <a href="/" class="transition-opacity hover:opacity-90 inline-block">
                    <img src="/image/logo-app-vmax45.webp" alt="VMAX Logo" class="h-16 w-auto object-contain">
                </a>
            </div>
            
            <!-- Center illustration & Copy -->
            <div class="my-auto z-10 flex flex-col items-center text-center max-w-xl mx-auto animate-fade-in" style="animation-delay: 0.1s;">
                <div class="mb-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 text-xs font-semibold text-blue-200 backdrop-blur-md border border-white/10 mb-5 tracking-wide uppercase">
                        <i class="fa-solid fa-users text-[10px] text-indigo-300"></i> CỘNG ĐỒNG BỀN VỮNG
                    </span>
                    <h2 class="text-4xl font-extrabold mb-5 leading-tight tracking-tight bg-gradient-to-r from-white via-slate-100 to-blue-200 bg-clip-text text-transparent">
                        Gia Nhập Cộng Đồng<br/>Phần Mềm VMAX
                    </h2>
                    <p class="text-blue-100/75 text-base leading-relaxed font-light">
                        Đăng ký tài khoản nhanh chóng để sử dụng bảng quản lý license thông minh, kích hoạt mã bản quyền và nhận ưu đãi affiliate lên tới 10%.
                    </p>
                </div>
                <div class="w-full max-w-sm hover:scale-[1.03] transition-all duration-500 ease-out glow-card rounded-[32px] overflow-hidden">
                    <img src="/image/isometric-b2b-illustration.webp" alt="VMAX B2B Platform" class="w-full h-auto rounded-[32px] border border-white/10 bg-white/5 backdrop-blur-md p-5">
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

        <!-- Right Side: Clean & Modern Register Form -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 md:p-8 bg-[#f8fafc] overflow-y-auto relative">
            <!-- Decorative circle for background depth -->
            <div class="lg:hidden absolute top-[-5%] right-[-5%] w-72 h-72 bg-blue-100/50 rounded-full blur-3xl -z-10"></div>
            
            <div class="max-w-xl w-full bg-white p-6 md:p-8 rounded-[32px] shadow-[0_25px_60px_rgba(0,0,0,0.025)] border border-slate-100/80 my-4 animate-fade-in" style="animation-delay: 0.15s;">
                <!-- Mobile logo representation -->
                <div class="text-center mb-6 lg:hidden">
                    <a href="/" class="inline-block">
                        <img src="/image/logo-app-vmax45.webp" alt="VMAX Logo" class="h-12 w-auto mx-auto object-contain">
                    </a>
                </div>
                
                <div class="mb-6 text-center lg:text-left">
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tạo tài khoản mới</h2>
                    <p class="text-slate-400 mt-1.5 text-xs font-medium">Bắt đầu trải nghiệm hệ thống bản quyền phần mềm VMAX</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="bg-rose-50/80 border border-rose-100/90 text-rose-700 px-4 py-3 rounded-2xl text-xs mb-5 flex items-center gap-3 animate-slide-down backdrop-blur-md">
                        <div class="flex-shrink-0 w-7 h-7 rounded-lg bg-rose-100 flex items-center justify-center text-rose-500">
                            <i class="fa-solid fa-circle-exclamation text-xs"></i>
                        </div>
                        <span class="font-semibold"><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/register" class="space-y-4" id="register-form">
                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                    <input type="hidden" name="referrer_code" value="<?= htmlspecialchars($_GET['ref'] ?? '') ?>">
                    
                    <!-- Row 1: Username & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="input-group">
                            <label for="username" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tên Tài Khoản (Username)</label>
                            <div class="relative">
                                <input type="text" id="username" name="username" required placeholder="vmax_user" class="custom-input w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 rounded-2xl outline-none text-sm text-slate-800 placeholder-slate-400 font-medium">
                                <i class="input-icon fa-regular fa-user text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 text-[14px] transition-colors duration-250"></i>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Địa Chỉ Email</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" required placeholder="name@domain.com" class="custom-input w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 rounded-2xl outline-none text-sm text-slate-800 placeholder-slate-400 font-medium">
                                <i class="input-icon fa-regular fa-envelope text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 text-[14px] transition-colors duration-250"></i>
                            </div>
                            <p id="email-error" class="text-rose-500 text-[11px] mt-1.5 px-1 hidden animate-slide-down"><i class="fa-solid fa-circle-info mr-1"></i> Định dạng email chưa hợp lệ</p>
                        </div>
                    </div>

                    <!-- Row 2: Password & Confirm Password -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="input-group">
                            <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mật Khẩu</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required placeholder="••••••••" class="custom-input w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200/80 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 rounded-2xl outline-none text-sm text-slate-800 placeholder-slate-400 font-medium">
                                <i class="input-icon fa-solid fa-lock text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 text-[14px] transition-colors duration-250"></i>
                                <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                                    <i class="fa-regular fa-eye text-[14px]"></i>
                                </button>
                            </div>
                            
                            <!-- Animated Password Strength Meter -->
                            <div class="mt-2 space-y-1 px-0.5">
                                <div class="flex justify-between items-center text-[10px] font-bold text-slate-400">
                                    <span>Độ mạnh:</span>
                                    <span id="password-strength-text" class="text-slate-400 transition-colors duration-300">Chưa nhập</span>
                                </div>
                                <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                    <div id="password-strength-bar" class="h-full w-0 bg-slate-300 rounded-full transition-all duration-300"></div>
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="confirm_password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Xác Nhận Mật Khẩu</label>
                            <div class="relative">
                                <input type="password" id="confirm_password" name="confirm_password" required placeholder="••••••••" class="custom-input w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200/80 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 rounded-2xl outline-none text-sm text-slate-800 placeholder-slate-400 font-medium">
                                <i class="input-icon fa-solid fa-shield-halved text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 text-[14px] transition-colors duration-250"></i>
                                <button type="button" onclick="togglePasswordVisibility('confirm_password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                                    <i class="fa-regular fa-eye text-[14px]"></i>
                                </button>
                            </div>
                            <p id="password-match-error" class="text-rose-500 text-[11px] mt-1.5 px-1 hidden animate-slide-down"><i class="fa-solid fa-circle-info mr-1"></i> Mật khẩu chưa khớp</p>
                        </div>
                    </div>

                    <?php if (!empty($_GET['ref'])): ?>
                        <div class="bg-blue-50/75 border border-blue-100/90 rounded-2xl p-3 text-xs text-blue-700 flex items-center justify-between animate-slide-down backdrop-blur-md">
                            <span class="font-semibold flex items-center gap-1.5">
                                <i class="fa-solid fa-gift text-blue-500"></i> Mã giới thiệu:
                            </span>
                            <strong class="font-mono bg-blue-100/90 px-2.5 py-0.5 rounded-lg text-blue-800 border border-blue-200/30"><?= htmlspecialchars($_GET['ref']) ?></strong>
                        </div>
                    <?php endif; ?>

                    <!-- Agreement & Remember Options -->
                    <div class="space-y-3 pt-2.5">
                        <!-- Terms Agreement (Required Checkbox) -->
                        <label class="flex items-start cursor-pointer select-none group py-0.5">
                            <div class="relative flex items-center mt-0.5">
                                <input type="checkbox" id="terms_agree" required class="sr-only peer" />
                                <div class="w-4.5 h-4.5 border border-slate-200 rounded-lg bg-slate-50 flex items-center justify-center transition-all peer-checked:bg-blue-600 peer-checked:border-blue-600 shadow-sm">
                                    <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <span class="ml-2.5 text-[11px] font-bold text-slate-500 group-hover:text-slate-700 leading-normal transition-colors">
                                Tôi đồng ý với <a href="/terms" target="_blank" class="text-blue-600 hover:text-blue-700 hover:underline transition">Điều khoản dịch vụ</a> & <a href="/privacy" target="_blank" class="text-blue-600 hover:text-blue-700 hover:underline transition">Chính sách bảo mật</a>
                            </span>
                        </label>

                        <!-- Remember Me Toggle (Optional switch) -->
                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer select-none">
                                <div class="relative">
                                    <input type="checkbox" id="remember_me" class="sr-only" />
                                    <div class="w-9 h-5 bg-slate-200 rounded-full transition-colors duration-200 ease-in-out toggle-bg"></div>
                                    <div class="dot absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full transition-transform duration-200 ease-in-out shadow-sm"></div>
                                </div>
                                <span class="ml-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 transition-colors">Lưu thông tin đăng nhập lần sau</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/15 hover:shadow-blue-500/25 hover:scale-[1.01] active:scale-[0.99] transition duration-300 text-sm tracking-wide cursor-pointer">
                            Tạo Tài Khoản <i class="fa-solid fa-user-plus ml-1.5 text-xs"></i>
                        </button>
                    </div>

                    <!-- Trust Signals -->
                    <div class="text-center text-[10px] text-slate-400 space-y-1 pt-2 border-t border-slate-100 mt-3">
                        <p class="font-bold text-slate-500 flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-shield-cat text-emerald-500 text-[12px]"></i> Bảo mật thông tin tuyệt đối
                        </p>
                    </div>
                </form>

                <!-- Google Sign-In Option -->
                <div class="relative flex py-4 items-center">
                    <div class="flex-grow border-t border-slate-200/60"></div>
                    <span class="flex-shrink mx-4 text-slate-400 text-[10px] font-extrabold uppercase tracking-widest">Hoặc đăng ký bằng</span>
                    <div class="flex-grow border-t border-slate-200/60"></div>
                </div>

                <div class="flex justify-center w-full hover:scale-[1.01] transition-transform duration-200">
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
                         data-width="400">
                    </div>
                </div>

                <div class="text-center mt-6 text-sm text-slate-500 font-medium">
                    Đã có tài khoản? <a href="/login" class="text-blue-600 font-bold hover:text-blue-700 hover:underline transition">Đăng nhập</a>
                </div>
            </div>

            <!-- Footer Centered Copyright -->
            <div class="text-xs text-slate-400 font-medium text-center mt-2">
                &copy; <?= date('Y') ?> VMAX Software. Bảo lưu mọi quyền.
            </div>
        </div>
        
    </div>

    <!-- Script Block for inline UX features -->
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
                // Sanitize to only alphanumeric and underscore
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

        // Strength criteria
        if (val.length >= 6) score += 1;
        if (val.length >= 10) score += 1;
        if (/[A-Z]/.test(val)) score += 1;
        if (/[0-9]/.test(val)) score += 1;
        if (/[^A-Za-z0-9]/.test(val)) score += 1;

        // Render strength meter states
        if (score <= 1) {
            strengthBar.style.width = '25%';
            strengthBar.className = 'h-full bg-rose-500 rounded-full transition-all duration-300';
            strengthText.textContent = 'Yếu';
            strengthText.className = 'text-rose-500 font-bold transition-colors duration-300';
        } else if (score <= 3) {
            strengthBar.style.width = '60%';
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

    // Encoding helpers for basic obfuscation in localStorage
    function encodeData(str) {
        return btoa(unescape(encodeURIComponent(str)));
    }

    // Save registration info if "Remember Me" toggle is enabled
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById('register-form');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const rememberCheckbox = document.getElementById('remember_me');

        form.addEventListener('submit', () => {
            if (rememberCheckbox && rememberCheckbox.checked) {
                localStorage.setItem('vmax_remember', 'true');
                localStorage.setItem('vmax_email', encodeData(emailInput.value));
                localStorage.setItem('vmax_pass', encodeData(passwordInput.value));
            }
        });
    });
    </script>
</body>
</html>
