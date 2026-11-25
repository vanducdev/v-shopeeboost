<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cấu Hình Tài Khoản & Bảo Mật - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .card-surface {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.85);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
        }
        .tab-btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .tab-btn.active {
            background: #ffffff;
            color: #2563eb;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04);
            border-color: #dbeafe;
        }
        .radar-pulse {
            position: relative;
        }
        .radar-pulse::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 9999px;
            background: rgba(16, 185, 129, 0.35);
            animation: radar 2s cubic-bezier(0, 0, 0.2, 1) infinite;
            z-index: 0;
        }
        @keyframes radar {
            0% { transform: scale(0.95); opacity: 0.8; }
            100% { transform: scale(1.6); opacity: 0; }
        }
        .avatar-box:hover .avatar-hover-overlay {
            opacity: 1;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row antialiased">

    <!-- Client Sidebar Component -->
    <?php require BASE_PATH . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Workspace Content -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full transition-all">

        <!-- Top Header Breadcrumb & Actions -->
        <header class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6 pb-5 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                        <i class="fa-solid fa-user-gear text-blue-600 text-lg"></i>
                        Cấu Hình Tài Khoản
                    </h1>
                </div>
                <p class="text-xs text-slate-500 mt-1 font-medium">
                    Quản lý thông tin hồ sơ số, cập nhật mật khẩu đăng nhập và kích hoạt bảo mật Google Authenticator 2FA.
                </p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-xl border border-blue-200">
                    <i class="fa-solid fa-shield-halved text-[11px] text-blue-600"></i>
                    Tài khoản <?= htmlspecialchars(strtoupper($user['role'] ?? 'MEMBER')) ?>
                </span>
                <?php if ($user['google_2fa_enabled']): ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        2FA Đang Bật
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 text-xs font-bold rounded-xl border border-amber-200">
                        <i class="fa-solid fa-triangle-exclamation text-[11px]"></i>
                        Chưa Bật 2FA
                    </span>
                <?php endif; ?>
                <a href="/transactions" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-slate-700 text-xs font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left text-slate-500 text-xs"></i>
                    Nhật Ký Ví
                </a>
            </div>
        </header>

        <!-- 4 Compact Security & Account KPIs -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4 mb-6">
            <!-- KPI 1: Phân Quyền -->
            <div class="card-surface rounded-2xl p-4 transition-all hover:border-blue-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Cấp Tài Khoản</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                </div>
                <div class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                    <?= htmlspecialchars(strtoupper($user['role'] ?? 'MEMBER')) ?>
                </div>
                <div class="text-[11px] text-slate-500 font-medium mt-1 flex items-center gap-1">
                    <span class="font-mono text-slate-700 font-bold">#<?= $user['id'] ?></span>
                    <span>• Gia nhập <?= date('d/m/Y', $user['created_at'] ?? time()) ?></span>
                </div>
            </div>

            <!-- KPI 2: Bảo Mật 2FA -->
            <div class="card-surface rounded-2xl p-4 transition-all hover:border-emerald-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Bảo Mật 2FA</span>
                    <div class="w-8 h-8 rounded-xl <?= $user['google_2fa_enabled'] ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' ?> flex items-center justify-center text-xs">
                        <i class="fa-solid <?= $user['google_2fa_enabled'] ? 'fa-shield-check' : 'fa-lock' ?>"></i>
                    </div>
                </div>
                <div class="text-base sm:text-lg font-black <?= $user['google_2fa_enabled'] ? 'text-emerald-600' : 'text-amber-600' ?> tracking-tight">
                    <?= $user['google_2fa_enabled'] ? 'Đang Kích Hoạt' : 'Chưa Kích Hoạt' ?>
                </div>
                <div class="text-[11px] text-slate-500 font-medium mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-qrcode text-[10px] text-slate-400"></i>
                    <span>Google Authenticator OTP</span>
                </div>
            </div>

            <!-- KPI 3: Mã Hóa & Mật Khẩu -->
            <div class="card-surface rounded-2xl p-4 transition-all hover:border-indigo-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Mã Hóa Dữ Liệu</span>
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-fingerprint"></i>
                    </div>
                </div>
                <div class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                    BCrypt 256-bit
                </div>
                <div class="text-[11px] text-slate-500 font-medium mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-[10px]"></i>
                    <span>Chuẩn mật mã an toàn cao</span>
                </div>
            </div>

            <!-- KPI 4: Số Dư Khả Dụng -->
            <div class="card-surface rounded-2xl p-4 transition-all hover:border-violet-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Số Dư Hiện Tại</span>
                    <div class="w-8 h-8 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>
                <div class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                    <?= number_format($user['balance'] ?? 0, 0, ',', '.') ?>đ
                </div>
                <div class="text-[11px] text-blue-600 font-bold mt-1">
                    <a href="/deposit" class="hover:underline flex items-center gap-1">
                        <span>Nạp tiền nhanh</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Grid Layout (2 cols left, 1 col right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Left Main Column: Form Tabs (8 cols) -->
            <div class="lg:col-span-8 space-y-5">

                <!-- Navigation Tabs Bar -->
                <div class="bg-slate-100/90 p-1 rounded-2xl flex items-center gap-1 border border-slate-200/70 shadow-inner">
                    <button type="button" onclick="switchTab('profile')" id="tab-btn-profile" class="tab-btn active flex-1 py-2.5 px-3 rounded-xl text-xs font-bold text-slate-600 border border-transparent flex items-center justify-center gap-2">
                        <i class="fa-regular fa-id-badge text-sm"></i>
                        <span>Thông Tin Cá Nhân</span>
                    </button>
                    <button type="button" onclick="switchTab('security')" id="tab-btn-security" class="tab-btn flex-1 py-2.5 px-3 rounded-xl text-xs font-bold text-slate-600 border border-transparent flex items-center justify-center gap-2">
                        <i class="fa-solid fa-key text-xs"></i>
                        <span>Đổi Mật Khẩu</span>
                    </button>
                    <button type="button" onclick="switchTab('twofa')" id="tab-btn-twofa" class="tab-btn flex-1 py-2.5 px-3 rounded-xl text-xs font-bold text-slate-600 border border-transparent flex items-center justify-center gap-2">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                        <span>Bảo Mật 2FA</span>
                        <?php if ($user['google_2fa_enabled']): ?>
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <?php else: ?>
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <?php endif; ?>
                    </button>
                </div>

                <!-- Settings Card Container -->
                <div class="card-surface rounded-2xl p-5 sm:p-7 transition-all">

                    <!-- ============================================== -->
                    <!-- TAB 1: THÔNG TIN CÁ NHÂN & ẢNH ĐẠI DIỆN -->
                    <!-- ============================================== -->
                    <div id="section-profile" class="space-y-6">
                        
                        <form id="profileForm" method="POST" action="/settings" enctype="multipart/form-data" class="space-y-6">
                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                            <input type="hidden" name="action" value="update_profile">

                            <!-- Avatar Upload Row -->
                            <div class="flex flex-col sm:flex-row items-center gap-5 pb-6 border-b border-slate-100">
                                <div class="relative avatar-box shrink-0 w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden border-2 border-slate-200 bg-slate-50 cursor-pointer shadow-sm group" onclick="triggerFileInput()">
                                    <?php if (!empty($user['avatar_url'])): ?>
                                        <img id="avatarPreview" src="<?= htmlspecialchars($user['avatar_url']) ?>" alt="Avatar" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div id="avatarFallback" class="w-full h-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-2xl uppercase shadow-inner">
                                            <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Camera Overlay -->
                                    <div class="avatar-hover-overlay absolute inset-0 bg-slate-900/60 opacity-0 transition-opacity duration-200 flex flex-col items-center justify-center text-white">
                                        <i class="fa-solid fa-camera text-lg"></i>
                                        <span class="text-[9px] font-bold uppercase tracking-wider mt-1">Đổi Ảnh</span>
                                    </div>
                                </div>

                                <div class="flex-1 text-center sm:text-left space-y-1.5">
                                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Ảnh Đại Diện Hồ Sơ</h4>
                                    <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                                        Định dạng hỗ trợ: JPG, PNG, WEBP (tối đa 2MB). Ảnh sẽ tự động đồng bộ trên thanh điều hướng và menu hệ thống.
                                    </p>
                                    <div class="flex items-center justify-center sm:justify-start gap-2 pt-1">
                                        <button type="button" onclick="triggerFileInput()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                                            <i class="fa-solid fa-arrow-up-from-bracket text-xs text-slate-500"></i>
                                            Chọn Tệp Ảnh Mới
                                        </button>
                                        <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" onchange="previewImage(this)">
                                    </div>
                                </div>
                            </div>

                            <!-- Input Fields -->
                            <div class="space-y-4">
                                <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fa-solid fa-address-card text-blue-600"></i>
                                    Thông Tin Đăng Ký Tài Khoản
                                </h4>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Username -->
                                    <div class="space-y-1.5">
                                        <label for="username" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                                            Tên Đăng Nhập <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required
                                                class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none text-xs font-semibold text-slate-800 transition shadow-inner">
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="space-y-1.5">
                                        <label for="email" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                                            Địa Chỉ Email <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required
                                                class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none text-xs font-semibold text-slate-800 transition shadow-inner">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                                    <!-- User ID (Readonly) -->
                                    <div class="space-y-1.5">
                                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Mã Định Danh (User ID)</label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                                <i class="fa-solid fa-hashtag"></i>
                                            </span>
                                            <input type="text" value="<?= $user['id'] ?>" disabled
                                                class="w-full pl-9 pr-4 py-2.5 bg-slate-100/80 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 cursor-not-allowed">
                                        </div>
                                    </div>

                                    <!-- Role (Readonly) -->
                                    <div class="space-y-1.5">
                                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nhóm Quyền Hạn</label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                                <i class="fa-solid fa-user-shield"></i>
                                            </span>
                                            <input type="text" value="<?= strtoupper($user['role'] ?? 'MEMBER') ?>" disabled
                                                class="w-full pl-9 pr-4 py-2.5 bg-slate-100/80 border border-slate-200 rounded-xl text-xs font-black text-blue-600 uppercase cursor-not-allowed">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl text-xs transition shadow-md shadow-blue-500/20 flex items-center gap-2">
                                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                                    <span>Lưu Thông Tin Hồ Sơ</span>
                                </button>
                            </div>
                        </form>

                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 2: ĐỔI MẬT KHẨU TÀI KHOẢN -->
                    <!-- ============================================== -->
                    <div id="section-security" class="space-y-6 hidden">
                        
                        <div class="p-4 bg-blue-50/70 border border-blue-100 rounded-xl flex items-start gap-3">
                            <i class="fa-solid fa-shield-halved text-blue-600 text-sm mt-0.5 shrink-0"></i>
                            <div class="text-[11px] text-blue-900 leading-relaxed font-medium">
                                <strong class="font-bold">Khuyến cáo bảo mật:</strong> Hãy sử dụng mật khẩu mạnh từ 8 ký tự trở lên, kết hợp chữ hoa, chữ thường, chữ số và ký tự đặc biệt để đảm bảo an toàn cao nhất cho ví tiền và key bản quyền phần mềm của bạn.
                            </div>
                        </div>

                        <form id="passwordForm" method="POST" action="/settings" class="space-y-5">
                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                            <input type="hidden" name="action" value="update_profile">
                            <!-- Retain username & email so update does not wipe them -->
                            <input type="hidden" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>">
                            <input type="hidden" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">

                            <div class="space-y-4">
                                <!-- New Password Field -->
                                <div class="space-y-1.5">
                                    <label for="new_password" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                                        Mật Khẩu Mới <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                            <i class="fa-solid fa-lock"></i>
                                        </span>
                                        <input type="password" id="new_password" name="new_password" required minlength="6" placeholder="Nhập mật khẩu mới ít nhất 6 ký tự..."
                                            oninput="checkPasswordStrength(this.value)"
                                            class="w-full pl-9 pr-10 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none text-xs font-semibold text-slate-800 transition shadow-inner">
                                        <button type="button" onclick="togglePasswordVisibility('new_password', 'toggle-new-pwd-icon')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                            <i id="toggle-new-pwd-icon" class="fa-solid fa-eye text-xs"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Password Strength Indicator -->
                                    <div class="pt-1.5 space-y-1">
                                        <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                            <span>Độ mạnh mật khẩu:</span>
                                            <span id="strength-text" class="text-slate-400">Chưa nhập</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden flex">
                                            <div id="strength-bar" class="h-full w-0 transition-all duration-300 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Confirm Password Field -->
                                <div class="space-y-1.5">
                                    <label for="confirm_password" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                                        Xác Nhận Mật Khẩu Mới <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                            <i class="fa-solid fa-check-double"></i>
                                        </span>
                                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Nhập lại mật khẩu mới..."
                                            oninput="checkPasswordMatch()"
                                            class="w-full pl-9 pr-10 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none text-xs font-semibold text-slate-800 transition shadow-inner">
                                        <button type="button" onclick="togglePasswordVisibility('confirm_password', 'toggle-confirm-pwd-icon')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                            <i id="toggle-confirm-pwd-icon" class="fa-solid fa-eye text-xs"></i>
                                        </button>
                                    </div>
                                    <p id="match-text" class="text-[10px] font-bold text-slate-400 hidden"></p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl text-xs transition shadow-md shadow-blue-500/20 flex items-center gap-2">
                                    <i class="fa-solid fa-key text-xs"></i>
                                    <span>Cập Nhật Mật Khẩu Mới</span>
                                </button>
                            </div>
                        </form>

                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 3: BẢO MẬT 2 LỚP GOOGLE AUTHENTICATOR -->
                    <!-- ============================================== -->
                    <div id="section-twofa" class="space-y-6 hidden">

                        <?php if (!$user['google_2fa_enabled']): ?>
                            <!-- 2FA NOT ENABLED YET: SETUP FLOW -->
                            <div class="space-y-5">
                                
                                <div class="flex items-center gap-2 text-blue-600">
                                    <i class="fa-solid fa-shield-halved text-base"></i>
                                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900">
                                        Kích Hoạt Xác Thực 2 Lớp (Google 2FA)
                                    </h3>
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed font-medium">
                                    Xác thực 2 yếu tố (2FA) bổ sung thêm một lớp bảo vệ vững chắc cho tài khoản VMAX Software của bạn. Khi kích hoạt, mỗi lần đăng nhập bạn sẽ cung cấp thêm mã 6 số thay đổi mỗi 30 giây từ ứng dụng di động.
                                </p>

                                <!-- 3 Step Workflow -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-black flex items-center justify-center">1</span>
                                            <h5 class="text-xs font-bold text-slate-800">Cài ứng dụng</h5>
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                                            Tải <strong class="text-slate-700">Google Authenticator</strong> hoặc <strong class="text-slate-700">Authy</strong> từ App Store hoặc Google Play.
                                        </p>
                                    </div>

                                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-black flex items-center justify-center">2</span>
                                            <h5 class="text-xs font-bold text-slate-800">Quét mã QR</h5>
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                                            Mở ứng dụng trên điện thoại, chọn quét mã QR hoặc nhập khóa bí mật thủ công bên dưới.
                                        </p>
                                    </div>

                                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-black flex items-center justify-center">3</span>
                                            <h5 class="text-xs font-bold text-slate-800">Nhập mã 6 số</h5>
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                                            Nhập mã 6 chữ số đang hiển thị trên ứng dụng để xác thực và hoàn tất kích hoạt.
                                        </p>
                                    </div>
                                </div>

                                <!-- QR Code & Setup Form Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center pt-2">
                                    <!-- QR Box (5 cols) -->
                                    <div class="md:col-span-5 flex flex-col items-center text-center gap-2.5">
                                        <div class="p-3 bg-white border-2 border-slate-200 rounded-2xl shadow-sm hover:border-blue-300 transition-all">
                                            <img src="<?= $qrUrl ?>" alt="2FA QR Code" class="w-36 h-36 mx-auto object-contain">
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                            Quét mã bằng ứng dụng 2FA
                                        </span>
                                    </div>

                                    <!-- Secret Key & Verification (7 cols) -->
                                    <div class="md:col-span-7 space-y-4">
                                        <div class="space-y-1.5">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">
                                                Hoặc nhập Secret Key thủ công:
                                            </span>
                                            <div class="flex items-center gap-1.5">
                                                <code id="manualSecretKey" class="flex-1 py-2 px-3 bg-blue-50/60 border border-blue-200 rounded-xl text-blue-700 font-bold font-mono text-xs tracking-wider select-all text-center">
                                                    <?= htmlspecialchars($tempSecret) ?>
                                                </code>
                                                <button type="button" onclick="copySecretKey()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition shrink-0" title="Sao chép Secret Key">
                                                    <i class="fa-regular fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Form to verify & enable 2FA -->
                                        <form id="enable2FAForm" method="POST" action="/settings" class="space-y-3.5">
                                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                            <input type="hidden" name="action" value="enable_2fa">

                                            <div class="space-y-1.5">
                                                <label for="2fa_code_enable" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                                                    Nhập mã OTP 6 số từ điện thoại:
                                                </label>
                                                <input type="text" id="2fa_code_enable" name="2fa_code" maxlength="6" pattern="[0-9]{6}" placeholder="••••••" required
                                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none text-center text-base font-black tracking-[0.4em] text-slate-800 placeholder-slate-300 transition shadow-inner">
                                            </div>

                                            <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl transition text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2">
                                                <i class="fa-solid fa-shield-check text-xs"></i>
                                                <span>Xác Nhận & Kích Hoạt 2FA</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        <?php else: ?>
                            <!-- 2FA ALREADY ENABLED: STATUS & DEACTIVATION -->
                            <div class="space-y-6 text-center py-4">
                                <div class="relative w-16 h-16 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 mx-auto radar-pulse shadow-sm">
                                    <i class="fa-solid fa-shield-check text-2xl relative z-10"></i>
                                </div>

                                <div class="space-y-1">
                                    <h3 class="text-base font-black text-slate-900 tracking-tight">Tài Khoản Đang Được Bảo Vệ Bằng 2FA!</h3>
                                    <p class="text-xs text-slate-500 font-medium max-w-md mx-auto">
                                        Mọi yêu cầu đăng nhập từ thiết bị mới hoặc thay đổi thông tin nhạy cảm đều được yêu cầu mã xác thực 6 số Google Authenticator.
                                    </p>
                                </div>

                                <!-- Box to disable 2FA -->
                                <div class="max-w-md mx-auto border border-slate-200 bg-slate-50/60 rounded-2xl p-5 text-left space-y-3.5">
                                    <div class="flex items-center gap-2 text-rose-600">
                                        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                        <h5 class="text-xs font-black uppercase tracking-wider text-rose-700">Hủy Kích Hoạt 2FA (Nếu cần)</h5>
                                    </div>
                                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                                        Nhập mã OTP 6 số hiện tại từ ứng dụng Google Authenticator để xác minh danh tính trước khi tắt bảo mật 2 lớp.
                                    </p>

                                    <form id="disable2FAForm" method="POST" action="/settings" class="space-y-3">
                                        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                        <input type="hidden" name="action" value="disable_2fa">

                                        <div class="space-y-1">
                                            <input type="text" id="2fa_code_disable" name="2fa_code" maxlength="6" pattern="[0-9]{6}" placeholder="000000" required
                                                class="w-full px-4 py-2 bg-white border border-slate-200 focus:border-rose-500 rounded-xl outline-none text-center text-sm font-black tracking-[0.4em] text-slate-800 placeholder-slate-300">
                                        </div>

                                        <button type="submit" class="w-full py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl border border-rose-200 text-xs transition flex items-center justify-center gap-1.5">
                                            <i class="fa-solid fa-lock-open text-xs"></i>
                                            <span>Hủy Kích Hoạt Bảo Mật 2FA</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

            </div>

            <!-- Right Sidebar Column: Profile Summary & Security Status (4 cols) -->
            <div class="lg:col-span-4 space-y-5">

                <!-- Card 1: User Profile Summary -->
                <div class="card-surface rounded-2xl p-5 text-center">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden mx-auto border-2 border-slate-200 bg-slate-50 mb-3 shadow-sm">
                        <?php if (!empty($user['avatar_url'])): ?>
                            <img src="<?= htmlspecialchars($user['avatar_url']) ?>" alt="Avatar" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-2xl uppercase">
                                <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h3 class="text-sm font-black text-slate-900 tracking-tight">
                        <?= htmlspecialchars($user['username'] ?? '') ?>
                    </h3>
                    <p class="text-[11px] text-slate-500 font-medium">
                        <?= htmlspecialchars($user['email'] ?? '') ?>
                    </p>

                    <div class="mt-3 flex items-center justify-center gap-1.5">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-700 uppercase">
                            <i class="fa-solid fa-shield-halved text-[9px]"></i>
                            <?= htmlspecialchars($user['role'] ?? 'MEMBER') ?>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Hoạt Động
                        </span>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-left text-xs">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Mã Khách Hàng</span>
                            <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded text-[11px]">#<?= $user['id'] ?></span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Số Dư Tài Khoản</span>
                            <span class="font-bold text-blue-600"><?= number_format($user['balance'] ?? 0, 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Ngày Tham Gia</span>
                            <span class="font-semibold text-slate-700"><?= date('d/m/Y', $user['created_at'] ?? time()) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Security Health Check -->
                <div class="card-surface rounded-2xl p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-shield-virus text-blue-600"></i>
                            Kiểm Tra An Toàn
                        </h4>
                        <span class="text-[10px] font-bold text-slate-400">4 tiêu chí</span>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <!-- Check 1: Email Verified -->
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                                <div>
                                    <div class="font-bold text-slate-800 text-[11px]">Xác Thực Email</div>
                                    <div class="text-[10px] text-slate-500"><?= htmlspecialchars($user['email'] ?? '') ?></div>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">Hợp Lệ</span>
                        </div>

                        <!-- Check 2: Strong Password -->
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                                <div>
                                    <div class="font-bold text-slate-800 text-[11px]">Mật Khẩu BCrypt</div>
                                    <div class="text-[10px] text-slate-500">Mã hóa Hash 1 chiều</div>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">An Toàn</span>
                        </div>

                        <!-- Check 3: 2FA Status -->
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <?php if ($user['google_2fa_enabled']): ?>
                                    <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm"></i>
                                <?php endif; ?>
                                <div>
                                    <div class="font-bold text-slate-800 text-[11px]">Google Authenticator</div>
                                    <div class="text-[10px] text-slate-500"><?= $user['google_2fa_enabled'] ? 'Đang bảo vệ OTP' : 'Khuyến nghị kích hoạt' ?></div>
                                </div>
                            </div>
                            <?php if ($user['google_2fa_enabled']): ?>
                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">Đã Bật</span>
                            <?php else: ?>
                                <button type="button" onclick="switchTab('twofa')" class="text-[10px] font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-2 py-0.5 rounded-lg border border-blue-200 transition">Bật Ngay</button>
                            <?php endif; ?>
                        </div>

                        <!-- Check 4: HWID Device Lock -->
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                                <div>
                                    <div class="font-bold text-slate-800 text-[11px]">Khóa Thiết Bị HWID</div>
                                    <div class="text-[10px] text-slate-500">Chống sao chép key bản quyền</div>
                                </div>
                            </div>
                            <a href="/devices" class="text-[10px] font-bold text-blue-600 hover:underline">Chi Tiết &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Quick Links & Helpdesk -->
                <div class="card-surface rounded-2xl p-5 space-y-3">
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-headset text-blue-600"></i>
                        Trung Tâm Trợ Giúp
                    </h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                        Bạn gặp khó khăn trong việc cập nhật thông tin hoặc mất mã khôi phục 2FA? Hãy gửi yêu cầu hỗ trợ đến đội ngũ VMAX.
                    </p>
                    <div class="pt-1 flex flex-col gap-2">
                        <a href="/support" class="w-full py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-ticket text-blue-600 text-xs"></i>
                                <span>Gửi Ticket Hỗ Trợ 24/7</span>
                            </span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        </a>
                        <a href="/devices" class="w-full py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-desktop text-blue-600 text-xs"></i>
                                <span>Quản Lý Thiết Bị Liên Kết</span>
                            </span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- JavaScript Handlers -->
    <script>
        // Tab switching logic
        function switchTab(tabId) {
            // Hide all sections
            document.getElementById('section-profile').classList.add('hidden');
            document.getElementById('section-security').classList.add('hidden');
            document.getElementById('section-twofa').classList.add('hidden');

            // Reset tab button states
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Activate chosen tab
            const targetSection = document.getElementById('section-' + tabId);
            const targetBtn = document.getElementById('tab-btn-' + tabId);
            if (targetSection) targetSection.classList.remove('hidden');
            if (targetBtn) targetBtn.classList.add('active');
        }

        // Avatar file trigger & preview
        function triggerFileInput() {
            document.getElementById('avatarInput').click();
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        const fallback = document.getElementById('avatarFallback');
                        if (fallback) {
                            const img = document.createElement('img');
                            img.id = 'avatarPreview';
                            img.className = 'w-full h-full object-cover';
                            img.src = e.target.result;
                            fallback.parentNode.replaceChild(img, fallback);
                        }
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Toggle Password Show/Hide
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

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

        // Password Strength Evaluator
        function checkPasswordStrength(password) {
            const bar = document.getElementById('strength-bar');
            const text = document.getElementById('strength-text');
            if (!bar || !text) return;

            if (!password || password.length === 0) {
                bar.style.width = '0%';
                bar.className = 'h-full transition-all duration-300 rounded-full';
                text.textContent = 'Chưa nhập';
                text.className = 'text-slate-400';
                return;
            }

            let score = 0;
            if (password.length >= 6) score += 1;
            if (password.length >= 8) score += 1;
            if (/[A-Z]/.test(password)) score += 1;
            if (/[0-9]/.test(password)) score += 1;
            if (/[^A-Za-z0-9]/.test(password)) score += 1;

            if (score <= 1) {
                bar.style.width = '25%';
                bar.className = 'h-full bg-rose-500 transition-all duration-300 rounded-full';
                text.textContent = 'Rất Yếu';
                text.className = 'text-rose-500 font-bold';
            } else if (score === 2) {
                bar.style.width = '50%';
                bar.className = 'h-full bg-amber-500 transition-all duration-300 rounded-full';
                text.textContent = 'Trung Bình';
                text.className = 'text-amber-500 font-bold';
            } else if (score === 3 || score === 4) {
                bar.style.width = '75%';
                bar.className = 'h-full bg-blue-500 transition-all duration-300 rounded-full';
                text.textContent = 'Khá Mạnh';
                text.className = 'text-blue-500 font-bold';
            } else {
                bar.style.width = '100%';
                bar.className = 'h-full bg-emerald-500 transition-all duration-300 rounded-full';
                text.textContent = 'Cực Kỳ Mạnh';
                text.className = 'text-emerald-500 font-bold';
            }

            checkPasswordMatch();
        }

        // Password Match Validator
        function checkPasswordMatch() {
            const pwd = document.getElementById('new_password').value;
            const confirm = document.getElementById('confirm_password').value;
            const matchText = document.getElementById('match-text');
            if (!matchText) return;

            if (!confirm) {
                matchText.classList.add('hidden');
                return;
            }

            matchText.classList.remove('hidden');
            if (pwd === confirm) {
                matchText.textContent = '✓ Mật khẩu xác nhận trùng khớp';
                matchText.className = 'text-[10px] font-bold text-emerald-600';
            } else {
                matchText.textContent = '✕ Mật khẩu xác nhận chưa khớp';
                matchText.className = 'text-[10px] font-bold text-rose-500';
            }
        }

        // Copy Secret Key
        function copySecretKey() {
            const keyEl = document.getElementById('manualSecretKey');
            if (!keyEl) return;
            const text = keyEl.textContent.trim();
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Đã sao chép Secret Key!',
                    text: text,
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        }

        // AJAX Form Submission Helper
        function handleAjaxForm(formId, successCallback) {
            const form = document.getElementById(formId);
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // If password form, check match
                if (formId === 'passwordForm') {
                    const pwd = document.getElementById('new_password').value;
                    const confirm = document.getElementById('confirm_password').value;
                    if (pwd !== confirm) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Mật khẩu chưa khớp',
                            text: 'Vui lòng kiểm tra lại mật khẩu mới và xác nhận mật khẩu.',
                            confirmButtonColor: '#2563eb'
                        });
                        return;
                    }
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalHtml = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <i class="fa-solid fa-circle-notch fa-spin text-xs"></i>
                    <span>Đang xử lý...</span>
                `;

                const formData = new FormData(this);

                fetch('/settings?ajax=1', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(async response => {
                    const isJson = response.headers.get('content-type')?.includes('application/json');
                    const data = isJson ? await response.json() : null;
                    return { status: response.status, success: response.ok, data };
                })
                .then(res => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;

                    if (res.success && res.data && res.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành Công!',
                            text: res.data.message || 'Cập nhật thành công!',
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-2xl border border-slate-100 shadow-xl'
                            }
                        }).then(() => {
                            if (successCallback) {
                                successCallback(res.data);
                            } else {
                                window.location.reload();
                            }
                        });
                    } else {
                        const errMsg = (res.data && res.data.message) ? res.data.message : 'Có lỗi xảy ra, vui lòng thử lại.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất Bại',
                            text: errMsg,
                            confirmButtonText: 'Đã hiểu',
                            confirmButtonColor: '#2563eb',
                            customClass: {
                                popup: 'rounded-2xl border border-slate-100 shadow-xl'
                            }
                        });
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi Kết Nối',
                        text: 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.',
                        confirmButtonColor: '#2563eb'
                    });
                });
            });
        }

        // Register AJAX Listeners
        handleAjaxForm('profileForm', function(data) {
            if (data.user && data.user.avatar_url) {
                const avatarImgs = document.querySelectorAll('img[alt="Avatar"]');
                avatarImgs.forEach(img => {
                    img.src = data.user.avatar_url + '?t=' + new Date().getTime();
                });
            }
        });

        handleAjaxForm('passwordForm', function() {
            document.getElementById('new_password').value = '';
            document.getElementById('confirm_password').value = '';
            checkPasswordStrength('');
        });

        handleAjaxForm('enable2FAForm', function() {
            window.location.reload();
        });

        handleAjaxForm('disable2FAForm', function() {
            window.location.reload();
        });
    </script>
</body>
</html>
