<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài Đặt Tài Khoản - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            box-shadow: 0 10px 30px -5px rgba(59, 130, 246, 0.04);
        }
        .avatar-overlay:hover .upload-icon {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
        .tab-btn.active {
            color: #2563eb;
            border-color: rgba(37, 99, 235, 0.2);
            background: rgba(37, 99, 235, 0.06);
        }
        .radar-pulse {
            position: relative;
        }
        .radar-pulse::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: currentColor;
            border-radius: 50%;
            opacity: 0.4;
            animation: radar 1.5s infinite;
        }
        @keyframes radar {
            0% { transform: scale(1); opacity: 0.4; }
            100% { transform: scale(2.5); opacity: 0; }
        }
    </style>
</head>
<body class="text-slate-850 min-h-screen flex flex-col md:flex-row">

    <!-- Client Sidebar -->
    <?php require BASE_PATH . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-4 md:p-8 max-w-6xl mx-auto w-full">
        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8 pb-6 border-b border-slate-200/60">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                    <h1 class="text-2xl font-extrabold text-slate-950 tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700">
                        Cấu Hình Tài Khoản
                    </h1>
                </div>
                <p class="text-slate-500 text-xs mt-1">Cập nhật thông tin cá nhân, cập nhật ảnh đại diện số và quản lý bảo mật nâng cao.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-50 text-blue-600 text-[10px] font-extrabold rounded-xl border border-blue-100 uppercase tracking-widest">
                    <svg class="w-3 h-3 text-blue-550" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Tài khoản <?= htmlspecialchars($user['role']) ?>
                </span>
            </div>
        </header>

        <!-- Main Workspace -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Left 2 Cols: Tabs & Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Navigation Tabs -->
                <div class="glass-panel rounded-xl p-1.5 flex gap-2 shadow-sm">
                    <button type="button" onclick="switchTab('profile')" id="tab-profile" class="tab-btn active flex-1 py-2 text-xs font-bold rounded-lg text-slate-500 border border-transparent hover:text-blue-650 transition-all">
                        Thông Tin Cá Nhân
                    </button>
                    <button type="button" onclick="switchTab('security')" id="tab-security" class="tab-btn flex-1 py-2 text-xs font-bold rounded-lg text-slate-500 border border-transparent hover:text-blue-650 transition-all">
                        Bảo Mật & Mật Khẩu
                    </button>
                    <button type="button" onclick="switchTab('twofa')" id="tab-twofa" class="tab-btn flex-1 py-2 text-xs font-bold rounded-lg text-slate-500 border border-transparent hover:text-blue-650 transition-all flex items-center justify-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Bảo Mật 2FA
                    </button>
                </div>

                <!-- Settings Form Container -->
                <div class="glass-panel glass-panel-glow rounded-3xl p-6 md:p-8 shadow-sm">
                    
                    <!-- PROFILE TAB SECTION -->
                    <form id="settingsForm" method="POST" action="/settings" enctype="multipart/form-data" class="space-y-6">
                        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                        <input type="hidden" name="action" value="update_profile">

                        <div id="section-profile" class="space-y-6">
                            <!-- Avatar Upload Section -->
                            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-100">
                                <div class="relative shrink-0 w-24 h-24 rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 cursor-pointer avatar-overlay group" onclick="triggerFileInput()">
                                    <?php if (!empty($user['avatar_url'])): ?>
                                        <img id="avatarPreview" src="<?= htmlspecialchars($user['avatar_url']) ?>" alt="Avatar" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div id="avatarFallback" class="w-full h-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-3xl uppercase">
                                            <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <svg class="w-6 h-6 text-white transform scale-75 group-hover:scale-100 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 text-center sm:text-left space-y-2">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ảnh đại diện kỹ thuật số</label>
                                    <button type="button" onclick="triggerFileInput()" class="px-4 py-2 bg-slate-50 border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold rounded-xl text-xs transition-all flex items-center gap-1.5 mx-auto sm:mx-0">
                                        Chọn ảnh mới
                                    </button>
                                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" onchange="previewImage(this)">
                                    <p class="text-[9px] text-slate-500">Định dạng JPG, PNG, WEBP. Dung lượng tối đa 2MB.</p>
                                </div>
                            </div>

                            <!-- Form Fields -->
                            <div class="space-y-4">
                                <h3 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-2">Thông tin liên lạc</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label for="username" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tên đăng nhập</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </span>
                                            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none transition text-xs font-semibold text-slate-800">
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="email" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Địa chỉ Email</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </span>
                                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none transition text-xs font-semibold text-slate-800">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECURITY TAB SECTION -->
                        <div id="section-security" class="space-y-6 hidden">
                            <div class="space-y-4">
                                <h3 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-2">Đổi mật khẩu tài khoản</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label for="new_password" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Mật khẩu mới</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            </span>
                                            <input type="password" id="new_password" name="new_password" placeholder="Bỏ trống nếu giữ nguyên..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none transition text-xs text-slate-800 font-semibold">
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="confirm_password" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Xác nhận mật khẩu</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            </span>
                                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu mới..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none transition text-xs text-slate-800 font-semibold">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Submit Button for Profile/Security -->
                        <div class="pt-4 flex justify-end" id="profile-action-btn">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/10 text-xs flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Lưu Cấu Hình
                            </button>
                        </div>
                    </form>

                    <!-- TWO-FACTOR AUTH (2FA) TAB SECTION -->
                    <div id="section-twofa" class="space-y-6 hidden">
                        <?php if (!$user['google_2fa_enabled']): ?>
                            <!-- 2FA Activation Process -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-2.5 text-blue-600">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <h3 class="text-xs font-bold uppercase tracking-widest">Kích hoạt bảo mật 2 lớp (Google 2FA)</h3>
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed font-medium">
                                    Bảo vệ tài khoản của bạn khỏi việc truy cập trái phép. Khi được kích hoạt, bạn sẽ cần cung cấp thêm mã số 6 chữ số từ ứng dụng Google Authenticator mỗi khi đăng nhập.
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center border-t border-slate-100 pt-6">
                                    <!-- QR Code (5 cols) -->
                                    <div class="md:col-span-5 flex flex-col items-center text-center gap-3">
                                        <div class="p-3 bg-white border border-slate-200 rounded-2xl shadow-sm">
                                            <img src="<?= $qrUrl ?>" alt="2FA QR Code" class="w-36 h-36">
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-bold">Quét mã QR bằng ứng dụng Authenticator</span>
                                    </div>
                                    
                                    <!-- Step Info (7 cols) -->
                                    <div class="md:col-span-7 space-y-4">
                                        <div class="space-y-1">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Hoặc nhập mã khóa thủ công</span>
                                            <code class="block w-full text-center py-2 bg-blue-50/50 border border-blue-100 rounded-xl text-blue-600 font-bold font-mono text-sm tracking-widest select-all">
                                                <?= htmlspecialchars($tempSecret) ?>
                                            </code>
                                        </div>
                                        
                                        <!-- Form to verify and enable -->
                                        <form id="enable2FAForm" method="POST" action="/settings" class="space-y-3">
                                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                            <input type="hidden" name="action" value="enable_2fa">
                                            
                                            <div class="space-y-1.5">
                                                <label for="2fa_code_enable" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nhập mã xác thực 6 số trên điện thoại</label>
                                                <input type="text" id="2fa_code_enable" name="2fa_code" maxlength="6" pattern="[0-9]{6}" placeholder="000000" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none text-center text-sm font-bold tracking-[0.5em] text-slate-800 placeholder-slate-300">
                                            </div>
                                            
                                            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-xs shadow-md shadow-blue-500/10 flex items-center justify-center gap-1.5">
                                                Xác nhận & Kích hoạt 2FA
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- 2FA Active Status and Deactivation -->
                            <div class="space-y-5 text-center py-4">
                                <div class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 mx-auto radar-pulse">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                
                                <div class="space-y-1">
                                    <h3 class="text-sm font-bold text-slate-800">Tài khoản của bạn đã được bảo vệ!</h3>
                                    <p class="text-[11px] text-slate-500 font-medium">Bảo mật hai lớp Google Authenticator đang hoạt động.</p>
                                </div>
                                
                                <div class="max-w-xs mx-auto border-t border-slate-100 pt-5 mt-5">
                                    <!-- Form to disable 2FA -->
                                    <form id="disable2FAForm" method="POST" action="/settings" class="space-y-3">
                                        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                        <input type="hidden" name="action" value="disable_2fa">
                                        
                                        <div class="space-y-1.5">
                                            <label for="2fa_code_disable" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nhập mã xác thực 2FA để tắt</label>
                                            <input type="text" id="2fa_code_disable" name="2fa_code" maxlength="6" pattern="[0-9]{6}" placeholder="000000" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 focus:border-red-500 focus:bg-white rounded-xl outline-none text-center text-xs font-bold tracking-[0.5em] text-slate-800 placeholder-slate-300">
                                        </div>
                                        
                                        <button type="submit" class="w-full py-2 bg-rose-50 hover:bg-rose-100 text-rose-655 font-bold rounded-xl border border-rose-200 text-xs transition duration-200 flex items-center justify-center gap-1">
                                            Hủy kích hoạt 2FA
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <!-- Right side: Info and safety cards -->
            <div class="space-y-6">
                <!-- Card 1: Account info details -->
                <div class="glass-panel rounded-3xl p-6 shadow-sm">
                    <h3 class="font-bold text-slate-900 text-xs uppercase tracking-widest mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Thống Số Hồ Sơ
                    </h3>
                    
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">ID tài khoản</span>
                            <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">#<?= $user['id'] ?></span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Quyền hạn</span>
                            <span class="font-bold text-blue-600 uppercase"><?= htmlspecialchars($user['role']) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Tình trạng ví</span>
                            <span class="flex items-center gap-1 font-bold text-emerald-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Hoạt động
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-1.5">
                            <span class="text-slate-500">Ngày gia nhập</span>
                            <span class="font-bold text-slate-700"><?= date('d/m/Y', $user['created_at'] ?? time()) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Security Advice -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 p-6 rounded-3xl shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-blue-500/5 rounded-full blur-xl group-hover:bg-blue-500/10 transition-all duration-500"></div>
                    
                    <h3 class="font-extrabold text-xs uppercase tracking-widest mb-3 text-blue-600 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Khuyến Nghị Bảo Mật
                    </h3>
                    <p class="text-[11px] text-slate-600 leading-relaxed font-medium">
                        Nên thiết lập mật khẩu mạnh và **kích hoạt bảo mật 2FA** để đảm bảo an toàn tuyệt đối cho số dư ví và quyền sở hữu các Key bản quyền phần mềm của bạn.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Tab switching logic
        function switchTab(tabId) {
            // Hide all sections
            document.getElementById('section-profile').classList.add('hidden');
            document.getElementById('section-security').classList.add('hidden');
            document.getElementById('section-twofa').classList.add('hidden');
            
            // Remove active classes
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show active section & button
            document.getElementById('section-' + tabId).classList.remove('hidden');
            document.getElementById('tab-' + tabId).classList.add('active');
            
            // Hide action button for 2FA tab (since forms are nested)
            const actionBtn = document.getElementById('profile-action-btn');
            if (tabId === 'twofa') {
                actionBtn.classList.add('hidden');
            } else {
                actionBtn.classList.remove('hidden');
            }
        }

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
                        // Replace fallback with image
                        const fallback = document.getElementById('avatarFallback');
                        const img = document.createElement('img');
                        img.id = 'avatarPreview';
                        img.className = 'w-full h-full object-cover';
                        img.src = e.target.result;
                        fallback.parentNode.replaceChild(img, fallback);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Helper to perform AJAX posts
        function submitFormAjax(formId, successCallback) {
            const form = document.getElementById(formId);
            if (!form) return;
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-4 w-4 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
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
                    submitBtn.innerHTML = originalBtnText;
                    
                    if (res.success && res.data && res.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành Công!',
                            text: res.data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            background: '#ffffff',
                            color: '#1e293b',
                            customClass: {
                                popup: 'rounded-3xl border border-slate-100 shadow-2xl'
                            }
                        }).then(() => {
                            if (successCallback) successCallback(res.data);
                        });
                    } else {
                        const msg = (res.data && res.data.message) ? res.data.message : 'Có lỗi xảy ra, vui lòng thử lại.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất Bại',
                            text: msg,
                            confirmButtonText: 'Xác Nhận',
                            confirmButtonColor: '#2563EB',
                            background: '#ffffff',
                            color: '#1e293b',
                            customClass: {
                                popup: 'rounded-3xl border border-slate-100 shadow-2xl',
                                confirmButton: 'rounded-xl px-5 py-2 text-xs font-semibold'
                            }
                        });
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi Kết Nối',
                        text: 'Không thể kết nối tới máy chủ vào lúc này.',
                        confirmButtonText: 'Đồng ý',
                        confirmButtonColor: '#2563EB',
                        background: '#ffffff',
                        color: '#1e293b',
                        customClass: {
                            popup: 'rounded-3xl border border-slate-100 shadow-2xl',
                            confirmButton: 'rounded-xl px-5 py-2 text-xs font-semibold'
                        }
                    });
                });
            });
        }

        // Initialize Form Handlers
        submitFormAjax('settingsForm', function(data) {
            // Update avatar preview globally on header/sidebar
            if (data.user && data.user.avatar_url) {
                const avatarPreviews = document.querySelectorAll('img[alt="Avatar"]');
                avatarPreviews.forEach(img => {
                    img.src = data.user.avatar_url + '?t=' + new Date().getTime();
                });
            }
        });
        
        submitFormAjax('enable2FAForm', function() {
            window.location.reload();
        });
        
        submitFormAjax('disable2FAForm', function() {
            window.location.reload();
        });
    </script>
</body>
</html>
