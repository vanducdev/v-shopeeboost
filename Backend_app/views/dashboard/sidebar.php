<?php
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function isClientNavActive($path, $currentUri) {
    if ($currentUri === $path) {
        return 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold shadow-md shadow-blue-500/20';
    }
    return 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 font-semibold';
}

$user = Auth::user();
$username = $user ? ($user['username'] ?? 'User') : 'User';
$email = $user ? ($user['email'] ?? '') : '';
$balance = $user ? ($user['balance'] ?? 0) : 0;
?>

<!-- Font Awesome Icons CDN (Đảm bảo hiển thị đầy đủ icon trên mọi trang) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
.sidebar-scroll::-webkit-scrollbar {
    width: 4px;
}
.sidebar-scroll::-webkit-scrollbar-track {
    background: transparent;
}
.sidebar-scroll::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 9999px;
}
.sidebar-scroll::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>

<!-- Mobile Top Header -->
<div class="flex md:hidden items-center justify-between bg-white border-b border-slate-200 px-5 py-3.5 sticky top-0 z-40 w-full shadow-sm">
    <a href="/" class="flex items-center gap-2">
        <img src="/image/logo-app-vmax-64x.webp" alt="VMAX Logo" class="h-8 w-auto object-contain">
        <span class="text-[10px] font-extrabold bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded">HUB</span>
    </a>
    <div class="flex items-center gap-3">
        <a href="/deposit" class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1.5 rounded-xl border border-emerald-200 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            <?= number_format($balance, 0, ',', '.') ?>đ
        </a>
        <button onclick="toggleSidebarDrawer()" class="p-2 text-slate-700 hover:text-slate-900 focus:outline-none" aria-label="Toggle Menu">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
    </div>
</div>

<!-- Desktop Sticky Sidebar (Cố định 1 chỗ khi cuộn trang) -->
<aside class="hidden md:flex md:sticky md:top-0 md:h-screen md:self-start w-[270px] bg-white border-r border-slate-200 p-5 flex-col justify-between shrink-0 z-30 shadow-[4px_0_24px_rgba(15,23,42,0.02)] select-none">
    <div class="flex-1 flex flex-col min-h-0">
        <!-- Brand Logo Header -->
        <div class="flex items-center justify-between pb-4 mb-3 border-b border-slate-100 shrink-0">
            <a href="/" class="inline-flex items-center gap-2">
                <img src="/image/logo-app-vmax-64x.webp" alt="VMAX Logo" class="h-9 w-auto object-contain">
                <span class="text-[9px] font-extrabold tracking-wider uppercase px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-200/60">CLIENT HUB</span>
            </a>
            <a href="/" class="text-[11px] font-bold text-slate-400 hover:text-blue-600 flex items-center gap-1 transition" title="Quay về trang chủ">
                <span>Web</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
            </a>
        </div>
        
        <!-- Navigation Menu (Có cuộn riêng mượt mà nếu màn hình ngắn) -->
        <nav class="space-y-4 overflow-y-auto flex-1 pr-1 sidebar-scroll">
            <!-- Group 1: Tổng quan -->
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 px-3 block mb-1.5">TỔNG QUAN</span>
                <div class="space-y-1">
                    <a href="/dashboard" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 <?= isClientNavActive('/dashboard', $currentUri) ?>">
                        <i class="fa-solid fa-chart-pie text-sm w-4 text-center"></i>
                        <span>Bảng điều khiển</span>
                    </a>
                </div>
            </div>

            <!-- Group 2: Dịch vụ & Bản quyền -->
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 px-3 block mb-1.5">DỊCH VỤ & THIẾT BỊ</span>
                <div class="space-y-1">
                    <a href="/licenses" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 <?= isClientNavActive('/licenses', $currentUri) ?>">
                        <i class="fa-solid fa-key text-sm w-4 text-center"></i>
                        <span>Quản lý bản quyền</span>
                    </a>
                    
                    <a href="/devices" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 <?= isClientNavActive('/devices', $currentUri) ?>">
                        <i class="fa-solid fa-laptop-code text-sm w-4 text-center"></i>
                        <span>Thiết bị liên kết (HWID)</span>
                    </a>

                    <a href="/renew" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 <?= isClientNavActive('/renew', $currentUri) ?>">
                        <i class="fa-solid fa-arrows-rotate text-sm w-4 text-center"></i>
                        <span>Gia hạn giấy phép</span>
                    </a>
                </div>
            </div>

            <!-- Group 3: Tài chính & Ví -->
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 px-3 block mb-1.5">TÀI CHÍNH & VÍ TIỀN</span>
                <div class="space-y-1">
                    <a href="/deposit" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 <?= isClientNavActive('/deposit', $currentUri) ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-wallet text-sm w-4 text-center"></i>
                            <span>Nạp tiền hệ thống</span>
                        </div>
                        <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700">VietQR</span>
                    </a>
                    
                    <a href="/transactions" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 <?= isClientNavActive('/transactions', $currentUri) ?>">
                        <i class="fa-solid fa-receipt text-sm w-4 text-center"></i>
                        <span>Lịch sử giao dịch</span>
                    </a>
                    
                    <a href="/affiliate" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 <?= isClientNavActive('/affiliate', $currentUri) ?>">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-hand-holding-dollar text-sm w-4 text-center"></i>
                            <span>Tiếp thị liên kết</span>
                        </div>
                        <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-blue-100 text-blue-700">10%</span>
                    </a>
                </div>
            </div>

            <!-- Group 4: Hỗ trợ & Cài đặt -->
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 px-3 block mb-1.5">HỖ TRỢ</span>
                <div class="space-y-1">
                    <a href="/support" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 <?= isClientNavActive('/support', $currentUri) ?>">
                        <i class="fa-solid fa-headset text-sm w-4 text-center"></i>
                        <span>Hỗ trợ kỹ thuật 24/7</span>
                    </a>
                    
                    <a href="/settings" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-all duration-200 <?= isClientNavActive('/settings', $currentUri) ?>">
                        <i class="fa-solid fa-gear text-sm w-4 text-center"></i>
                        <span>Cài đặt tài khoản</span>
                    </a>
                </div>
            </div>

            <!-- Admin Portal Link (Only for Admin) -->
            <?php if (Auth::isAdmin()): ?>
                <div class="pt-2">
                    <a href="/admin" class="flex items-center justify-between p-3 rounded-2xl bg-gradient-to-r from-purple-900/10 via-indigo-900/10 to-purple-900/15 border border-purple-200/80 hover:border-purple-300 text-purple-900 text-xs font-bold transition shadow-sm hover:shadow group">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-purple-600 text-white flex items-center justify-center shrink-0 shadow-sm group-hover:rotate-12 transition-transform">
                                <i class="fa-solid fa-crown text-xs"></i>
                            </span>
                            <div>
                                <span class="block text-[11.5px] leading-tight">Admin Portal</span>
                                <span class="text-[9.5px] font-normal text-purple-600">Quản trị toàn hệ thống</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[10px] text-purple-400 group-hover:translate-x-0.5 transition-transform"></i>
                    </a>
                </div>
            <?php endif; ?>
        </nav>
    </div>
    
    <!-- Bottom Footer Widgets -->
    <div class="pt-4 border-t border-slate-100 space-y-3">
        <!-- Wallet Card -->
        <div class="p-3.5 bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 rounded-2xl text-white shadow-md relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-blue-500/20 rounded-full blur-xl group-hover:scale-125 transition-transform duration-500"></div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <span class="text-[9.5px] text-blue-300 font-extrabold uppercase tracking-wider block">SỐ DƯ VÍ HIỆN TẠI</span>
                    <span class="text-base font-extrabold text-white tracking-tight mt-0.5 block"><?= number_format($balance, 0, ',', '.') ?>đ</span>
                </div>
                <a href="/deposit" class="w-8 h-8 rounded-xl bg-blue-600 hover:bg-blue-500 text-white flex items-center justify-center shadow transition hover:scale-105" title="Nạp thêm tiền qua VietQR">
                    <i class="fa-solid fa-plus text-xs"></i>
                </a>
            </div>
        </div>

        <!-- User Profile Card -->
        <div class="flex items-center justify-between p-2.5 rounded-2xl bg-slate-50 border border-slate-200/80">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-extrabold text-xs shrink-0 shadow-sm uppercase">
                    <?= strtoupper(substr($username, 0, 1)) ?>
                </div>
                <div class="min-w-0 truncate">
                    <div class="font-bold text-slate-800 text-xs truncate leading-tight"><?= htmlspecialchars($username) ?></div>
                    <div class="text-[10px] text-slate-400 truncate mt-0.5"><?= htmlspecialchars($email) ?></div>
                </div>
            </div>
            <a href="/logout" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Đăng xuất">
                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
            </a>
        </div>
    </div>
</aside>

<!-- Mobile Drawer Modal -->
<div id="mobileSidebarDrawer" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden md:hidden transition-opacity">
    <div class="fixed inset-y-0 left-0 w-[280px] bg-white p-5 flex flex-col justify-between shadow-2xl overflow-y-auto">
        <div>
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <img src="/image/logo-app-vmax-64x.webp" alt="VMAX Logo" class="h-8 w-auto">
                <button onclick="toggleSidebarDrawer()" class="p-2 text-slate-400 hover:text-slate-700">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <nav class="space-y-1">
                <a href="/dashboard" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs <?= isClientNavActive('/dashboard', $currentUri) ?>">
                    <i class="fa-solid fa-chart-pie text-sm w-4"></i>
                    <span>Bảng điều khiển</span>
                </a>
                <a href="/licenses" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs <?= isClientNavActive('/licenses', $currentUri) ?>">
                    <i class="fa-solid fa-key text-sm w-4"></i>
                    <span>Quản lý bản quyền</span>
                </a>
                <a href="/devices" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs <?= isClientNavActive('/devices', $currentUri) ?>">
                    <i class="fa-solid fa-laptop-code text-sm w-4"></i>
                    <span>Thiết bị liên kết</span>
                </a>
                <a href="/deposit" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs <?= isClientNavActive('/deposit', $currentUri) ?>">
                    <i class="fa-solid fa-wallet text-sm w-4"></i>
                    <span>Nạp tiền hệ thống</span>
                </a>
                <a href="/renew" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs <?= isClientNavActive('/renew', $currentUri) ?>">
                    <i class="fa-solid fa-arrows-rotate text-sm w-4"></i>
                    <span>Gia hạn bản quyền</span>
                </a>
                <a href="/transactions" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs <?= isClientNavActive('/transactions', $currentUri) ?>">
                    <i class="fa-solid fa-receipt text-sm w-4"></i>
                    <span>Lịch sử giao dịch</span>
                </a>
                <a href="/affiliate" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs <?= isClientNavActive('/affiliate', $currentUri) ?>">
                    <i class="fa-solid fa-hand-holding-dollar text-sm w-4"></i>
                    <span>Tiếp thị liên kết</span>
                </a>
                <a href="/support" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs <?= isClientNavActive('/support', $currentUri) ?>">
                    <i class="fa-solid fa-headset text-sm w-4"></i>
                    <span>Hỗ trợ kỹ thuật</span>
                </a>
                <a href="/settings" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs <?= isClientNavActive('/settings', $currentUri) ?>">
                    <i class="fa-solid fa-gear text-sm w-4"></i>
                    <span>Cài đặt tài khoản</span>
                </a>
                <?php if (Auth::isAdmin()): ?>
                    <a href="/admin" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-purple-700 bg-purple-50">
                        <i class="fa-solid fa-crown text-sm w-4"></i>
                        <span>Trang quản trị (Admin)</span>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
        <div class="pt-4 border-t border-slate-100">
            <a href="/logout" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 transition">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Đăng xuất</span>
            </a>
        </div>
    </div>
</div>

<script>
function toggleSidebarDrawer() {
    const drawer = document.getElementById('mobileSidebarDrawer');
    if (drawer) {
        drawer.classList.toggle('hidden');
    }
}
</script>
