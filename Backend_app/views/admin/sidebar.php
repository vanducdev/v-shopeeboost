<?php
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
function isActiveAdmin($path, $currentUri) {
    if ($path === '/admin') {
        return $currentUri === '/admin'
            ? 'bg-gradient-to-r from-indigo-500/20 to-violet-500/20 text-indigo-300 border border-indigo-500/30 font-semibold shadow-inner'
            : 'text-slate-400 hover:bg-white/5 hover:text-slate-200';
    }
    return strpos($currentUri, $path) === 0
        ? 'bg-gradient-to-r from-indigo-500/20 to-violet-500/20 text-indigo-300 border border-indigo-500/30 font-semibold shadow-inner'
        : 'text-slate-400 hover:bg-white/5 hover:text-slate-200';
}
?>

<!-- Mobile Admin Header -->
<div class="flex md:hidden items-center justify-between px-5 py-4 sticky top-0 z-40 w-full shadow-lg border-b border-white/5" style="background: rgba(10,11,18,0.95); backdrop-filter: blur(20px);">
    <a href="/">
        <img src="/image/logo-app-vmax45.png" alt="VMAX Logo" class="h-8 w-auto object-contain">
    </a>
    <div class="flex items-center gap-3">
        <span class="text-[9px] px-2.5 py-1 bg-indigo-500/20 text-indigo-300 border border-indigo-500/40 rounded-lg font-bold uppercase tracking-widest">Admin</span>
        <button onclick="toggleAdminSidebarDrawer()" class="p-2 text-slate-400 hover:text-white focus:outline-none rounded-lg hover:bg-white/5 transition" aria-label="Toggle Admin Menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>
</div>

<!-- Desktop Sidebar -->
<aside class="hidden md:flex w-72 flex-col justify-between shrink-0 min-h-screen border-r border-white/5 relative" style="background: linear-gradient(180deg, #0a0b12 0%, #0d0f1e 50%, #0a0b12 100%);">
    <!-- Glow top -->
    <div class="absolute top-0 left-0 right-0 h-64 pointer-events-none" style="background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(99,102,241,0.12) 0%, transparent 70%);"></div>

    <div class="relative z-10 p-6 flex flex-col h-full">
        <!-- Logo -->
        <a href="/" class="inline-block mb-10 pl-2 group">
            <img src="/image/logo-app-vmax45.png" alt="VMAX Logo" class="h-9 w-auto object-contain group-hover:opacity-90 transition">
        </a>

        <!-- Navigation Groups -->
        <nav class="flex-1 space-y-1">
            <!-- Overview -->
            <div class="mb-4">
                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-600 px-3 mb-2">Tổng Quan</p>
                <a href="/admin" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Thống Kê Tổng Quan
                </a>
            </div>

            <!-- Users & Licenses -->
            <div class="mb-4">
                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-600 px-3 mb-2">Người Dùng & Bản Quyền</p>
                <a href="/admin/users" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/users', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Quản Lý Users
                </a>
                <a href="/admin/licenses" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/licenses', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    Cấp Phát Keys
                </a>
                <a href="/admin/devices" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/devices', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Quản Lý Thiết Bị
                </a>
                <a href="/admin/shopee" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/shopee', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Tương Tác Shopee
                </a>
            </div>

            <!-- Commerce -->
            <div class="mb-4">
                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-600 px-3 mb-2">Sản Phẩm & Tài Chính</p>
                <a href="/admin/products" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/products', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Quản Lý Sản Phẩm
                </a>
                <a href="/admin/deposits" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/deposits', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Duyệt Tiền Nạp
                </a>
                <a href="/admin/payments" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/payments', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Đơn Hàng Thanh Toán
                </a>
                <a href="/admin/affiliate" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/affiliate', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Rút Tiền Affiliate
                </a>
            </div>

            <!-- Content & System -->
            <div class="mb-4">
                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-600 px-3 mb-2">Nội Dung & Hệ Thống</p>
                <a href="/admin/tickets" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/tickets', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Ticket Hỗ Trợ
                </a>
                <a href="/admin/blog" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/blog', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Quản Lý Blog
                </a>
                <a href="/admin/settings" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/settings', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Cấu Hình Hệ Thống
                </a>
                <a href="/admin/setup" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/setup', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Cài Đặt Website
                </a>
                <a href="/admin/files" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all duration-150 <?= isActiveAdmin('/admin/files', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    File Manager
                </a>
            </div>
        </nav>

        <!-- Footer -->
        <div class="border-t border-white/5 pt-5 mt-2 space-y-3">
            <!-- Admin badge -->
            <div class="px-3 py-2.5 rounded-xl bg-white/3 border border-white/5 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-200"><?= htmlspecialchars($_SESSION['username'] ?? 'Administrator') ?></div>
                    <div class="text-[10px] text-indigo-400 font-bold uppercase">Super Admin</div>
                </div>
            </div>
            <a href="/logout" class="flex items-center gap-2 justify-center w-full text-center py-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 font-bold rounded-xl text-xs transition border border-red-500/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Đăng Xuất
            </a>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Drawer -->
<div id="admin-sidebar-drawer-overlay" class="fixed inset-0 z-50 transition-all duration-300 opacity-0 pointer-events-none" style="background: rgba(0,0,0,0.7); backdrop-filter: blur(6px);" onclick="toggleAdminSidebarDrawer()">
    <div id="admin-sidebar-drawer-content" class="fixed top-0 left-0 w-[280px] max-w-[80vw] h-full p-6 shadow-2xl transition-transform duration-300 -translate-x-full flex flex-col justify-between border-r border-white/5" style="background: linear-gradient(180deg, #0a0b12 0%, #0d0f1e 100%);" onclick="event.stopPropagation()">
        <div>
            <div class="flex items-center justify-between mb-8 px-2">
                <a href="/"><img src="/image/logo-app-vmax45.png" alt="VMAX Logo" class="h-8 w-auto object-contain"></a>
                <button onclick="toggleAdminSidebarDrawer()" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-white/5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="space-y-0.5">
                <a href="/admin" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Thống Kê Tổng Quan
                </a>
                <a href="/admin/users" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/users', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Quản Lý Users
                </a>
                <a href="/admin/licenses" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/licenses', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    Cấp Phát Keys
                </a>
                <a href="/admin/devices" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/devices', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Quản Lý Thiết Bị
                </a>
                <a href="/admin/shopee" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/shopee', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Tương Tác Shopee
                </a>
                <a href="/admin/products" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/products', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Quản Lý Sản Phẩm
                </a>
                <a href="/admin/blog" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/blog', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Quản Lý Blog
                </a>
                <a href="/admin/deposits" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/deposits', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Duyệt Tiền Nạp
                </a>
                <a href="/admin/payments" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/payments', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Đơn Hàng Thanh Toán
                </a>
                <a href="/admin/tickets" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/tickets', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Ticket Hỗ Trợ
                </a>
                <a href="/admin/affiliate" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/affiliate', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Rút Tiền Affiliate
                </a>
                <a href="/admin/settings" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/settings', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Cấu Hình Hệ Thống
                </a>
                <a href="/admin/setup" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/setup', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Cài Đặt Website
                </a>
                <a href="/admin/files" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all <?= isActiveAdmin('/admin/files', $currentUri) ?>">
                    <svg class="w-4 h-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    File Manager
                </a>
            </nav>
        </div>
        <div class="border-t border-white/5 pt-5">
            <a href="/logout" class="flex items-center gap-2 justify-center w-full text-center py-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 font-bold rounded-xl text-xs transition border border-red-500/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Đăng Xuất
            </a>
        </div>
    </div>
</div>

<script>
function toggleAdminSidebarDrawer() {
    const overlay = document.getElementById('admin-sidebar-drawer-overlay');
    const content = document.getElementById('admin-sidebar-drawer-content');
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
