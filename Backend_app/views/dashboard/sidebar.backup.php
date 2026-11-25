<?php
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
function isActiveClient($path, $currentUri) {
    return $currentUri === $path 
        ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-[0_4px_12px_rgba(37,99,235,0.22)] scale-[1.02]' 
        : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 hover:translate-x-1 hover:scale-[1.01]';
}
$user = Auth::user();
$avatarUrl = $user ? ($user['avatar_url'] ?? '') : '';
$username = $user ? ($user['username'] ?? '') : 'User';
?>

<!-- Global Page Loader -->
<div id="global-page-loader" style="position: fixed; inset: 0; background: #ffffff; z-index: 99999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.4s ease, visibility 0.4s ease; opacity: 1; visibility: visible; font-family: 'Inter', sans-serif;">
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
        <!-- Beautiful animated circular track -->
        <div style="width: 72px; height: 72px; border: 4px solid #f1f5f9; border-radius: 50%; position: relative;">
            <div style="position: absolute; inset: -4px; border: 4px solid transparent; border-top-color: #3b82f6; border-right-color: rgba(59, 130, 246, 0.2); border-radius: 50%; animation: loader-spin 1s linear infinite;"></div>
        </div>
        <!-- VMAX Logo in the center -->
        <img src="/image/logo-app-vmax-64x.png" alt="VMAX Logo" style="width: 32px; height: 32px; position: absolute; top: 20px; animation: loader-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;">
        <!-- Loading text -->
        <div style="margin-top: 20px; font-size: 10px; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; animation: loader-pulse 1.5s infinite; text-align: center;">VMAX SOFTWARE</div>
    </div>
</div>

<style>
    @keyframes loader-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes loader-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .6; transform: scale(0.95); }
    }
</style>

<script>
    (function() {
        function hideLoader() {
            var loader = document.getElementById("global-page-loader");
            if (loader) {
                loader.style.opacity = "0";
                loader.style.visibility = "hidden";
                setTimeout(function() {
                    if (loader && loader.parentNode) {
                        loader.parentNode.removeChild(loader);
                    }
                }, 400);
            }
        }
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", hideLoader);
        } else {
            hideLoader();
        }
        window.addEventListener("load", hideLoader);
        setTimeout(hideLoader, 1500);
    })();
</script>

<!-- Mobile Dashboard Top Header -->
<div class="flex md:hidden items-center justify-between bg-white border-b border-slate-200/60 px-6 py-4 sticky top-0 z-40 w-full shadow-sm">
    <a href="/">
        <img src="/image/logo-app-vmax45.png" alt="VMAX Logo" class="h-8 w-auto object-contain">
    </a>
    <div class="flex items-center gap-4">
        <!-- Quick wallet balance -->
        <span class="text-xs font-bold text-slate-600 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100"><?= number_format($user['balance'] ?? 0, 0, ',', '.') ?>đ</span>
        <button onclick="toggleSidebarDrawer()" class="p-2 text-slate-600 hover:text-slate-900 focus:outline-none" aria-label="Toggle Menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>
</div>

<!-- Desktop Client Sidebar (Hidden on mobile) -->
<aside class="hidden md:flex w-[280px] bg-white border-r border-slate-200/60 p-6 flex-col justify-between shrink-0 shadow-[4px_0_24px_rgba(0,0,0,0.015)] min-h-screen transition-all duration-300">
    <div>
        <!-- Brand Logo -->
        <div class="mb-8 pl-2">
            <a href="/" class="inline-block transition-transform duration-300 hover:scale-[1.03]">
                <img src="/image/logo-app-vmax45.png" alt="VMAX Logo" class="h-9 w-auto object-contain">
            </a>
        </div>
        
        <nav class="space-y-1.5">
            <a href="/dashboard" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/dashboard', $currentUri) ?>">
                <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/dashboard' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Bảng điều khiển
            </a>
            
            <a href="/licenses" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/licenses', $currentUri) ?>">
                <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/licenses' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2z"></path></svg>
                Quản lý bản quyền
            </a>
            
            <a href="/devices" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/devices', $currentUri) ?>">
                <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/devices' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Thiết bị liên kết
            </a>
            
            <a href="/deposit" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/deposit', $currentUri) ?>">
                <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/deposit' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Nạp Tiền Hệ Thống
            </a>
            
            <a href="/renew" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/renew', $currentUri) ?>">
                <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/renew' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Gia Hạn Giấy Phép Key
            </a>
            
            <a href="/transactions" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/transactions', $currentUri) ?>">
                <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/transactions' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 112-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Lịch Sử Giao Dịch
            </a>
            
            <a href="/affiliate" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/affiliate', $currentUri) ?>">
                <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/affiliate' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Tiếp thị liên kết
            </a>
            
            <a href="/support" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/support', $currentUri) ?>">
                <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/support' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Hỗ trợ kỹ thuật
            </a>
            
            <?php if (Auth::isAdmin()): ?>
                <div class="pt-3 mt-3 border-t border-slate-100">
                    <a href="/admin" class="group flex items-center justify-between px-3.5 py-2.5 text-purple-600 hover:text-purple-750 hover:bg-purple-50/70 hover:translate-x-1 rounded-xl text-xs transition-all duration-300 font-bold border border-transparent hover:border-purple-200/30 shadow-sm hover:shadow-purple-500/5">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-purple-500 transition-transform duration-500 group-hover:rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Trang quản trị</span>
                        </div>
                        <span class="text-[9px] px-1.5 py-0.5 bg-purple-100 text-purple-700 rounded-md font-bold uppercase tracking-wider scale-[0.9]">Admin</span>
                    </a>
                </div>
            <?php endif; ?>
        </nav>
    </div>
    
    <div class="border-t border-slate-100 pt-6 mt-6">
        <!-- Balance Widget -->
        <div class="mb-4 p-4 bg-gradient-to-br from-blue-600 via-indigo-600 to-indigo-700 rounded-2xl flex items-center justify-between border border-blue-500/10 shadow-[0_8px_20px_rgba(37,99,235,0.15)] text-white relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/10 rounded-full blur-lg group-hover:scale-125 transition-transform duration-500"></div>
            <div class="absolute -left-6 -top-6 w-16 h-16 bg-white/10 rounded-full blur-lg group-hover:scale-125 transition-transform duration-500"></div>
            
            <div class="flex items-center gap-3 z-10">
                <span class="p-2 bg-white/15 backdrop-blur-md rounded-xl border border-white/20 text-white">
                    <svg class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </span>
                <div>
                    <span class="text-[9px] text-blue-200 font-bold uppercase tracking-wider block">Số dư ví</span>
                    <span class="font-extrabold text-base tracking-tight text-white"><?= number_format($user['balance'] ?? 0, 0, ',', '.') ?>đ</span>
                </div>
            </div>
            <a href="/deposit" class="p-1.5 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-300 shadow-sm z-10 hover:scale-105" title="Nạp thêm tiền">
                <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            </a>
        </div>

        <!-- Account Info -->
        <div class="flex items-center justify-between mb-4 bg-slate-50/60 p-3 rounded-2xl border border-slate-100 relative">
            <div class="flex items-center gap-3">
                <?php if (!empty($avatarUrl)): ?>
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-xl blur-[2px] opacity-75 group-hover:opacity-100 transition duration-300"></div>
                        <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="Avatar" class="relative w-9 h-9 rounded-xl object-cover border border-white shrink-0 shadow-sm">
                    </div>
                <?php else: ?>
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-sm shrink-0 uppercase shadow-sm border border-white/20">
                        <?= strtoupper(substr($username, 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="truncate">
                    <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest leading-none">Khách hàng</div>
                    <div class="font-bold text-slate-800 text-xs truncate mt-1.5" title="<?= htmlspecialchars($username) ?>"><?= htmlspecialchars($username) ?></div>
                </div>
            </div>
            <!-- Pulse Active Dot on the right -->
            <div class="flex items-center pr-1.5">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2.5">
            <a href="/settings" class="flex items-center justify-center gap-1.5 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200/50 text-slate-600 hover:text-slate-900 font-bold rounded-xl text-[10px] transition-all duration-200 hover:scale-[1.02]">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Cài đặt
            </a>
            <a href="/logout" class="flex items-center justify-center gap-1.5 py-2.5 bg-rose-50/50 hover:bg-rose-500 hover:text-white border border-rose-100/50 text-rose-600 font-bold rounded-xl text-[10px] transition-all duration-200 hover:scale-[1.02] shadow-sm hover:shadow-rose-500/10">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Đăng xuất
            </a>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Drawer (Slides in on request) -->
<div id="sidebar-drawer-overlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 transition-opacity duration-300 opacity-0 pointer-events-none" onclick="toggleSidebarDrawer()">
    <div id="sidebar-drawer-content" class="fixed top-0 left-0 w-[280px] max-w-[80vw] h-full bg-white p-6 shadow-2xl transition-transform duration-300 -translate-x-full flex flex-col justify-between" onclick="event.stopPropagation()">
        <div>
            <!-- Close Header -->
            <div class="flex items-center justify-between mb-8 pl-2">
                <a href="/">
                    <img src="/image/logo-app-vmax45.png" alt="VMAX Logo" class="h-8 w-auto object-contain">
                </a>
                <button onclick="toggleSidebarDrawer()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <nav class="space-y-1.5">
                <a href="/dashboard" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/dashboard', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/dashboard' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Bảng điều khiển
                </a>
                
                <a href="/licenses" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/licenses', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/licenses' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2z"></path></svg>
                    Quản lý bản quyền
                </a>
                
                <a href="/devices" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/devices', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/devices' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Thiết bị liên kết
                </a>
                
                <a href="/deposit" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/deposit', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/deposit' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Nạp Tiền Hệ Thống
                </a>
                
                <a href="/renew" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/renew', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/renew' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Gia Hạn Giấy Phép Key
                </a>
                
                <a href="/transactions" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/transactions', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/transactions' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 112-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Lịch Sử Giao Dịch
                </a>
                
                <a href="/affiliate" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/affiliate', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/affiliate' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Tiếp thị liên kết
                </a>
                
                <a href="/support" class="group flex items-center gap-3 px-3.5 py-3 rounded-xl text-xs font-medium transition-all duration-300 <?= isActiveClient('/support', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 transition-colors duration-200 <?= $currentUri === '/support' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Hỗ trợ kỹ thuật
                </a>
                
                <?php if (Auth::isAdmin()): ?>
                    <div class="pt-3 mt-3 border-t border-slate-100">
                        <a href="/admin" class="group flex items-center justify-between px-3.5 py-2.5 text-purple-600 hover:text-purple-750 hover:bg-purple-50/70 hover:translate-x-1 rounded-xl text-xs transition-all duration-300 font-bold border border-transparent hover:border-purple-200/30 shadow-sm">
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-purple-500 transition-transform duration-500 group-hover:rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>Trang quản trị</span>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
        
        <div class="border-t border-slate-100 pt-6 mt-6">
            <div class="grid grid-cols-2 gap-2.5">
                <a href="/settings" class="flex items-center justify-center gap-1.5 py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200/50 text-slate-600 hover:text-slate-900 font-bold rounded-xl text-[10px] transition-all duration-200">
                    Cài đặt
                </a>
                <a href="/logout" class="flex items-center justify-center gap-1.5 py-2.5 bg-rose-500 text-white font-bold rounded-xl text-[10px] transition-all duration-200">
                    Đăng xuất
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSidebarDrawer() {
    const overlay = document.getElementById('sidebar-drawer-overlay');
    const content = document.getElementById('sidebar-drawer-content');
    if (overlay && content) {
        const isOpen = overlay.classList.contains('pointer-events-auto');
        if (isOpen) {
            overlay.classList.remove('pointer-events-auto', 'opacity-100');
            overlay.classList.add('pointer-events-none', 'opacity-0');
            content.classList.remove('translate-x-0');
            content.classList.add('-translate-x-full');
            document.body.style.overflow = '';
        } else {
            overlay.classList.remove('pointer-events-none', 'opacity-0');
            overlay.classList.add('pointer-events-auto', 'opacity-100');
            content.classList.remove('-translate-x-full');
            content.classList.add('translate-x-0');
            document.body.style.overflow = 'hidden';
        }
    }
}
</script>

<?php require BASE_PATH . '/views/layout/contact_button.php'; ?>
