<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'vi' ?>">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tải Xuống Phần Mềm - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">

    <?php include BASE_PATH . '/views/layout/navbar.php'; ?>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-6 py-12 md:py-20 flex-1 w-full">
        
        <!-- Header Section -->
        <div class="text-center max-w-3xl mx-auto mb-12 space-y-4">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-50 text-blue-600 text-xs font-bold rounded-full uppercase tracking-wider border border-blue-100 shadow-sm">
                <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                Trung tâm tải xuống chính thức
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Tải Xuống Phần Mềm <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">VMAX</span>
            </h1>
            <p class="text-slate-500 text-sm md:text-base max-w-xl mx-auto">
                Tải về các phiên bản cài đặt chính thức của hệ sinh thái phần mềm VMAX. An toàn, bảo mật và được cập nhật thường xuyên.
            </p>
        </div>

        <!-- Safe Guarantee & Browser Warning Band -->
        <div class="max-w-5xl mx-auto mb-8 grid md:grid-cols-2 gap-4">
            <div class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-4 flex items-start gap-3 text-emerald-800 text-xs font-medium leading-relaxed">
                <span class="text-lg shrink-0">🛡️</span>
                <span><?= __('dl_security_note') ?></span>
            </div>
            <div class="bg-amber-50/60 border border-amber-100 rounded-2xl p-4 flex items-start gap-3 text-amber-800 text-xs font-medium leading-relaxed">
                <span class="text-lg shrink-0">⚠️</span>
                <span><?= __('dl_browser_warning') ?></span>
            </div>
        </div>

        <!-- Product Filter Tabs -->
        <div class="flex justify-center flex-wrap gap-3 mb-10 select-none">
            <button onclick="filterDownloads('all')" id="tab-all" class="px-5 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-md transition duration-200 cursor-pointer">
                <?= __('dl_filter_all') ?>
            </button>
            <button onclick="filterDownloads('antidetect')" id="tab-antidetect" class="px-5 py-2.5 bg-white border border-slate-200/80 text-slate-600 hover:text-blue-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition duration-200 cursor-pointer">
                🛡️ Antidetect Browser
            </button>
            <button onclick="filterDownloads('facebook')" id="tab-facebook" class="px-5 py-2.5 bg-white border border-slate-200/80 text-slate-600 hover:text-blue-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition duration-200 cursor-pointer">
                <?= __('dl_filter_fb') ?>
            </button>
            <button onclick="filterDownloads('shopee')" id="tab-shopee" class="px-5 py-2.5 bg-white border border-slate-200/80 text-slate-600 hover:text-blue-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition duration-200 cursor-pointer">
                <?= __('dl_filter_shopee') ?>
            </button>
        </div>

        <!-- Software Cards Stack (Horizontal Layout) -->
        <div class="flex flex-col gap-12 max-w-5xl mx-auto">
            
            <!-- Card 0: VmaxLogin Antidetect Browser (FLAGSHIP) -->
            <div data-category="antidetect" class="bg-white border-2 border-blue-500/30 rounded-[32px] p-6 md:p-10 shadow-[0_8px_30px_rgba(37,99,235,0.06)] hover:shadow-[0_20px_50px_rgba(37,99,235,0.12)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <!-- Background decorative glow -->
                <div class="absolute -right-24 -top-24 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="grid md:grid-cols-12 gap-8 items-center relative z-10">
                    <!-- Left Side: Content -->
                    <div class="md:col-span-8 flex flex-col justify-between h-full space-y-6">
                        <div>
                            <!-- Header with version & Core Badge -->
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white font-extrabold text-base shadow-md shrink-0">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="font-extrabold text-2xl text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors duration-300">VmaxLogin Antidetect Browser</h3>
                                        <span class="px-2.5 py-0.5 bg-blue-600 text-white text-[10px] font-bold rounded-full">v2.4.2 Stable</span>
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-extrabold rounded-full">⭐ SẢN PHẨM CỐT LÕI 2026</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-bold tracking-wider uppercase mt-0.5">Trình duyệt chống dò quét vân tay antidetect cao cấp</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <p class="text-slate-500 text-sm leading-relaxed mb-6">
                                Công nghệ cô lập vân tay trình duyệt độc lập 100% Canvas, WebGL, AudioContext, Screen Resolution và WebRTC. Giúp bạn nuôi hàng ngàn tài khoản Facebook, Google Ads, TikTok, Shopee mà không lo checkpoint chéo.
                            </p>

                            <!-- Key Features -->
                            <div class="grid grid-cols-2 gap-3 text-xs text-slate-700 font-semibold mb-6 bg-blue-50/40 rounded-2xl p-4 border border-blue-100/50">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    Quản trị Multi-Profile không giới hạn
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    Vân tay Canvas/WebGL độc lập 100%
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    Tích hợp Proxy SOCKS5/HTTP/IPv6
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    API Automation Puppeteer & Playwright
                                </div>
                            </div>

                            <!-- Tech Specs Grid -->
                            <div class="grid grid-cols-2 gap-x-6 gap-y-2 border-t border-slate-100 pt-5 text-xs text-slate-500 font-semibold mb-2">
                                <div class="flex justify-between border-b border-slate-50 pb-2">
                                    <span class="text-slate-400">Tên tệp:</span>
                                    <span class="text-slate-800 font-mono">VmaxLoginSetup2.4.2.exe</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-50 pb-2">
                                    <span class="text-slate-400">Dung lượng:</span>
                                    <span class="text-slate-800">145.2 MB</span>
                                </div>
                                <div class="flex justify-between col-span-2 py-2">
                                    <span class="text-slate-400">Hệ điều hành:</span>
                                    <span class="text-slate-800">Windows 10 / 11 64bit & MacOS M1/M2/Intel</span>
                                </div>
                            </div>

                            <!-- System Requirements Accordion -->
                            <div class="col-span-2 mb-4">
                                <details class="group bg-slate-50 border border-slate-100 rounded-2xl p-4 [&_summary::-webkit-details-marker]:hidden">
                                    <summary class="flex items-center justify-between text-xs text-slate-700 font-bold cursor-pointer list-none select-none">
                                        <span><i class="fa-solid fa-circle-info text-blue-500 me-1.5"></i> <?= __('dl_sys_req_detail') ?></span>
                                        <span class="transition duration-300 group-open:-rotate-180">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </span>
                                    </summary>
                                    <ul class="list-disc pl-5 mt-2.5 space-y-1.5 text-[11px] text-slate-500 font-medium">
                                        <li><?= __('dl_sys_req_os') ?></li>
                                        <li><?= __('dl_sys_req_dotnet') ?></li>
                                        <li><?= __('dl_sys_req_cpp') ?></li>
                                        <li>RAM: 8 GB trở lên (Khuyến nghị) / 8 GB or more (Recommended)</li>
                                    </ul>
                                </details>
                            </div>

                            <!-- Checksum Info -->
                            <div class="border-t border-slate-100 pt-4 mt-2">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase mb-1"><?= __('dl_checksum') ?>:</span>
                                <div class="flex items-center gap-2 bg-slate-50 border border-slate-100/50 p-2.5 rounded-xl">
                                    <code id="hash-vmaxlogin" class="text-[10px] text-slate-600 font-mono break-all select-all flex-1">e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855</code>
                                    <button onclick="copyHash('hash-vmaxlogin')" class="p-1 text-slate-400 hover:text-blue-600 transition" title="Copy to clipboard">
                                        <svg class="w-3.5 h-3.5 fill-none stroke-current stroke-2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button & Links -->
                        <div class="pt-2 flex flex-wrap gap-3 items-center">
                            <a href="/downloads" class="inline-flex items-center justify-between gap-6 px-6 py-3.5 bg-gradient-to-r from-blue-600 via-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-2xl transition-all duration-300 shadow-md hover:shadow-lg hover:shadow-blue-500/25 hover:scale-[1.02] active:scale-[0.98] group/btn">
                                <div class="text-left">
                                    <span class="block text-[8px] text-blue-100 uppercase font-bold tracking-widest leading-none mb-1">DOWNLOAD STABLE BUILD (145.2 MB) • <?= __('dl_updated') ?>: 2026</span>
                                    <span class="block text-sm font-extrabold tracking-wide">Tải VmaxLogin Setup (v2.4.2)</span>
                                </div>
                                <span class="bg-white/10 group-hover/btn:bg-white/20 p-2 rounded-xl transition duration-300">
                                    <svg class="w-4 h-4 fill-none stroke-current stroke-2 animate-bounce" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </span>
                            </a>

                            <a href="/pricing" class="px-5 py-3 border border-slate-200 hover:border-blue-600 text-slate-600 hover:text-blue-600 text-xs font-bold rounded-2xl transition duration-200 bg-white">
                                <i class="fa-solid fa-tag me-1.5"></i> Bảng Giá Bản Quyền
                            </a>
                        </div>
                    </div>

                    <!-- Right Side: Product Image -->
                    <div class="md:col-span-4 flex justify-center lg:justify-end pr-4">
                        <img src="/image/logologin.webp" alt="VmaxLogin Antidetect Logo" class="h-60 md:h-72 lg:h-80 w-auto object-contain drop-shadow-[0_20px_35px_rgba(37,99,235,0.22)] transition-all duration-750 ease-out group-hover:scale-105 group-hover:-translate-y-2 group-hover:rotate-1">
                    </div>
                </div>
            </div>
            
            <!-- Card 1: VmaxCare -->
            <div data-category="facebook" class="bg-white border border-slate-100/80 rounded-[32px] p-6 md:p-10 shadow-[0_8px_30px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_50px_rgba(37,99,235,0.06)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <!-- Background decorative glow -->
                <div class="absolute -right-24 -top-24 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="grid md:grid-cols-12 gap-8 items-center relative z-10">
                    <!-- Left Side: Content -->
                    <div class="md:col-span-8 flex flex-col justify-between h-full space-y-6">
                        <div>
                            <!-- Header with version -->
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-600/10 flex items-center justify-center text-blue-600 font-extrabold text-sm shadow-sm shrink-0">
                                    VC
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-extrabold text-2xl text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors duration-300">VmaxCare</h3>
                                        <span class="px-2.5 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full">v4.0.0 Stable</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-bold tracking-wider uppercase mt-0.5">Hệ thống nuôi nick Facebook chuyên sâu</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <p class="text-slate-550 text-slate-500 text-sm leading-relaxed mb-6">
                                Giải pháp nuôi nick, seeding bài viết, livestream và quản lý hàng loạt tài khoản Facebook an toàn. Giả lập hành vi thông minh, tự động vượt checkpoint giúp bảo vệ tài khoản tối đa.
                            </p>

                            <!-- Key Features -->
                            <div class="grid grid-cols-2 gap-3 text-xs text-slate-700 font-semibold mb-6 bg-slate-50/50 rounded-2xl p-4 border border-slate-100/50">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    Nuôi nick tự động
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    Tăng mắt & Seeding
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    Tự động đăng bài
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    Vượt Checkpoint FB
                                </div>
                            </div>

                            <!-- Tech Specs Grid -->
                            <div class="grid grid-cols-2 gap-x-6 gap-y-2 border-t border-slate-100 pt-5 text-xs text-slate-500 font-semibold mb-2">
                                <div class="flex justify-between border-b border-slate-50 pb-2">
                                    <span class="text-slate-400">Tên tệp:</span>
                                    <span class="text-slate-800 font-mono">VmaxCareSetup4.0.0.zip</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-50 pb-2">
                                    <span class="text-slate-400">Dung lượng:</span>
                                    <span class="text-slate-800">130.1 MB</span>
                                </div>
                                <div class="flex justify-between col-span-2 py-2">
                                    <span class="text-slate-400">Hệ điều hành:</span>
                                    <span class="text-slate-800">Windows 10 / 11 64bit</span>
                                </div>
                            </div>

                            <!-- System Requirements Accordion -->
                            <div class="col-span-2 mb-4">
                                <details class="group bg-slate-50 border border-slate-100 rounded-2xl p-4 [&_summary::-webkit-details-marker]:hidden">
                                    <summary class="flex items-center justify-between text-xs text-slate-700 font-bold cursor-pointer list-none select-none">
                                        <span><i class="fa-solid fa-circle-info text-blue-500 me-1.5"></i> <?= __('dl_sys_req_detail') ?></span>
                                        <span class="transition duration-300 group-open:-rotate-180">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </span>
                                    </summary>
                                    <ul class="list-disc pl-5 mt-2.5 space-y-1.5 text-[11px] text-slate-500 font-medium">
                                        <li><?= __('dl_sys_req_os') ?></li>
                                        <li><?= __('dl_sys_req_dotnet') ?></li>
                                        <li><?= __('dl_sys_req_cpp') ?></li>
                                        <li>RAM: 8 GB trở lên (Khuyến nghị) / 8 GB or more (Recommended)</li>
                                    </ul>
                                </details>
                            </div>

                            <!-- Checksum Info -->
                            <div class="border-t border-slate-100 pt-4 mt-2">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase mb-1"><?= __('dl_checksum') ?>:</span>
                                <div class="flex items-center gap-2 bg-slate-50 border border-slate-100/50 p-2.5 rounded-xl">
                                    <code id="hash-vmaxcare" class="text-[10px] text-slate-600 font-mono break-all select-all flex-1">7f83b2a1c0d9e8f7a6b5c4d3e2f1a0b9c8d7e6f5a4b3c2d1e0f9a8b7c6d5e4f3</code>
                                    <button onclick="copyHash('hash-vmaxcare')" class="p-1 text-slate-400 hover:text-blue-600 transition" title="Copy to clipboard">
                                        <svg class="w-3.5 h-3.5 fill-none stroke-current stroke-2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button & Links -->
                        <div class="pt-2 flex flex-wrap gap-3 items-center">
                            <a href="/dowload/VmaxCareSetup4.0.0.zip" download="VmaxCareSetup4.0.0.zip" class="inline-flex items-center justify-between gap-6 px-6 py-3.5 bg-gradient-to-r from-blue-600 via-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-2xl transition-all duration-300 shadow-md hover:shadow-lg hover:shadow-blue-500/25 hover:scale-[1.02] active:scale-[0.98] group/btn">
                                <div class="text-left">
                                    <span class="block text-[8px] text-blue-100 uppercase font-bold tracking-widest leading-none mb-1">DOWNLOAD STABLE BUILD (130.1 MB) • <?= __('dl_updated') ?>: 15/06/2026</span>
                                    <span class="block text-sm font-extrabold tracking-wide">Tải VmaxCare Setup (v4.0.0)</span>
                                </div>
                                <span class="bg-white/10 group-hover/btn:bg-white/20 p-2 rounded-xl transition duration-300">
                                    <svg class="w-4 h-4 fill-none stroke-current stroke-2 animate-bounce" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </span>
                            </a>

                            <a href="/blog/post/cach-quan-ly-500-tai-khoan-facebook-ads-khong-bi-checkpoint-voi-vmax" class="px-5 py-3 border border-slate-200 hover:border-blue-600 text-slate-600 hover:text-blue-600 text-xs font-bold rounded-2xl transition duration-200 bg-white">
                                <i class="fa-solid fa-book-open me-1.5"></i> <?= __('dl_guide_btn') ?>
                            </a>
                        </div>
                        <div class="mt-1">
                            <a href="#changelog" class="text-xs text-blue-600 hover:text-blue-700 hover:underline font-bold inline-flex items-center gap-1">
                                <i class="fa-solid fa-clock-rotate-left"></i> <?= __('dl_changelog_btn') ?>
                            </a>
                        </div>
                    </div>

                    <!-- Right Side: Product Image -->
                    <div class="md:col-span-4 flex justify-center lg:justify-end pr-4">
                        <img src="/image/logofb.webp" alt="VmaxCare Logo" class="h-60 md:h-72 lg:h-80 w-auto object-contain drop-shadow-[0_20px_35px_rgba(37,99,235,0.22)] transition-all duration-750 ease-out group-hover:scale-105 group-hover:-translate-y-2 group-hover:rotate-1">
                    </div>
                </div>
            </div>

            <!-- Card 2: VmaxShopeeBosost -->
            <div data-category="shopee" class="bg-white border border-slate-100/80 rounded-[32px] p-6 md:p-10 shadow-[0_8px_30px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_50px_rgba(238,77,45,0.06)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <!-- Background decorative glow -->
                <div class="absolute -right-24 -top-24 w-64 h-64 bg-orange-500/5 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="grid md:grid-cols-12 gap-8 items-center relative z-10">
                    <!-- Left Side: Content -->
                    <div class="md:col-span-8 flex flex-col justify-between h-full space-y-6">
                        <div>
                            <!-- Header with version -->
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-orange-600/10 flex items-center justify-center text-orange-600 font-extrabold text-sm shadow-sm shrink-0">
                                    VS
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-extrabold text-2xl text-slate-900 tracking-tight group-hover:text-orange-600 transition-colors duration-300">VmaxShopeeBosost</h3>
                                        <span class="px-2.5 py-0.5 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-full">v5.0.0 Stable</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-bold tracking-wider uppercase mt-0.5">Tự động hóa & Seeding Shopee đa nhiệm</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <p class="text-slate-550 text-slate-500 text-sm leading-relaxed mb-6">
                                Giải pháp tăng trưởng doanh số Shopee đột phá. Hỗ trợ seeding đánh giá sản phẩm, đẩy top tìm kiếm, buff tương tác livestream và quản lý tập trung nhiều gian hàng cùng lúc.
                            </p>

                            <!-- Key Features -->
                            <div class="grid grid-cols-2 gap-3 text-xs text-slate-700 font-semibold mb-6 bg-slate-50/50 rounded-2xl p-4 border border-slate-100/50">
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    Buff lượt bán & đánh giá
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    Buff mắt Livestream
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    Tự động đẩy sản phẩm
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] shrink-0">✓</span>
                                    Quản trị Multi-Shop
                                </div>
                            </div>

                            <!-- Tech Specs Grid -->
                            <div class="grid grid-cols-2 gap-x-6 gap-y-2 border-t border-slate-100 pt-5 text-xs text-slate-500 font-semibold mb-2">
                                <div class="flex justify-between border-b border-slate-50 pb-2">
                                    <span class="text-slate-400">Tên tệp:</span>
                                    <span class="text-slate-800 font-mono">VmaxShopeeBosostSetup5.0.0.zip</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-50 pb-2">
                                    <span class="text-slate-400">Dung lượng:</span>
                                    <span class="text-slate-800">337.6 MB</span>
                                </div>
                                <div class="flex justify-between col-span-2 py-2">
                                    <span class="text-slate-400">Hệ điều hành:</span>
                                    <span class="text-slate-800">Windows 10 / 11 64bit</span>
                                </div>
                            </div>

                            <!-- System Requirements Accordion -->
                            <div class="col-span-2 mb-4">
                                <details class="group bg-slate-50 border border-slate-100 rounded-2xl p-4 [&_summary::-webkit-details-marker]:hidden">
                                    <summary class="flex items-center justify-between text-xs text-slate-700 font-bold cursor-pointer list-none select-none">
                                        <span><i class="fa-solid fa-circle-info text-orange-500 me-1.5"></i> <?= __('dl_sys_req_detail') ?></span>
                                        <span class="transition duration-300 group-open:-rotate-180">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </span>
                                    </summary>
                                    <ul class="list-disc pl-5 mt-2.5 space-y-1.5 text-[11px] text-slate-500 font-medium">
                                        <li><?= __('dl_sys_req_os') ?></li>
                                        <li><?= __('dl_sys_req_dotnet') ?></li>
                                        <li><?= __('dl_sys_req_cpp') ?></li>
                                        <li>RAM: 8 GB trở lên (Khuyến nghị) / 8 GB or more (Recommended)</li>
                                    </ul>
                                </details>
                            </div>

                            <!-- Checksum Info -->
                            <div class="border-t border-slate-100 pt-4 mt-2">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase mb-1"><?= __('dl_checksum') ?>:</span>
                                <div class="flex items-center gap-2 bg-slate-50 border border-slate-100/50 p-2.5 rounded-xl">
                                    <code id="hash-shopee" class="text-[10px] text-slate-600 font-mono break-all select-all flex-1">a6b5c4d3e2f1a0b9c8d7e6f5a4b3c2d1e0f9a8b7c6d5e4f37f83b2a1c0d9e8f7</code>
                                    <button onclick="copyHash('hash-shopee')" class="p-1 text-slate-400 hover:text-blue-600 transition" title="Copy to clipboard">
                                        <svg class="w-3.5 h-3.5 fill-none stroke-current stroke-2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button & Links -->
                        <div class="pt-2 flex flex-wrap gap-3 items-center">
                            <a href="/dowload/VmaxShopeeBosostSetup5.0.0.zip" download="VmaxShopeeBosostSetup5.0.0.zip" class="inline-flex items-center justify-between gap-6 px-6 py-3.5 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-650 hover:to-red-700 text-white rounded-2xl transition-all duration-300 shadow-md hover:shadow-lg hover:shadow-orange-500/25 hover:scale-[1.02] active:scale-[0.98] group/btn">
                                <div class="text-left">
                                    <span class="block text-[8px] text-orange-100 uppercase font-bold tracking-widest leading-none mb-1">DOWNLOAD STABLE BUILD (337.6 MB) • <?= __('dl_updated') ?>: 18/06/2026</span>
                                    <span class="block text-sm font-extrabold tracking-wide">Tải VmaxShopeeBosost Setup (v5.0.0)</span>
                                </div>
                                <span class="bg-white/10 group-hover/btn:bg-white/20 p-2 rounded-xl transition duration-300">
                                    <svg class="w-4 h-4 fill-none stroke-current stroke-2 animate-bounce" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </span>
                            </a>

                            <a href="/blog/post/he-sinh-thai-phan-mem-vmax-giai-phap-tu-dong-hoa-marketing-toan-dien" class="px-5 py-3 border border-slate-200 hover:border-orange-500 text-slate-600 hover:text-orange-500 text-xs font-bold rounded-2xl transition duration-200 bg-white">
                                <i class="fa-solid fa-book-open me-1.5"></i> <?= __('dl_guide_btn') ?>
                            </a>
                        </div>
                        <div class="mt-1">
                            <a href="#changelog" class="text-xs text-orange-600 hover:text-orange-700 hover:underline font-bold inline-flex items-center gap-1">
                                <i class="fa-solid fa-clock-rotate-left"></i> <?= __('dl_changelog_btn') ?>
                            </a>
                        </div>
                    </div>

                    <!-- Right Side: Product Image -->
                    <div class="md:col-span-4 flex justify-center lg:justify-end pr-4">
                        <img src="/image/logosp.webp" alt="VmaxShopeeBosost Logo" class="h-60 md:h-72 lg:h-80 w-auto object-contain drop-shadow-[0_20px_35px_rgba(238,77,45,0.22)] transition-all duration-750 ease-out group-hover:scale-105 group-hover:-translate-y-2 group-hover:rotate-1">
                    </div>
                </div>
            </div>

        </div>

        <!-- Changelog Section -->
        <section id="changelog" class="max-w-5xl mx-auto mt-16 pt-12 border-t border-slate-200/60">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-extrabold text-slate-900 mb-2">Nhật Ký Cập Nhật (Changelog)</h2>
                <p class="text-slate-500 text-sm">Theo dõi các bản vá lỗi và nâng cấp tính năng mới nhất từ VMAX.</p>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Release 1 -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-2.5 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full">v4.0.0</span>
                        <span class="text-xs text-slate-400 font-bold">15/06/2026</span>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm mb-2">Cập nhật VmaxCare v4.0.0</h4>
                    <ul class="list-disc pl-5 text-xs text-slate-500 space-y-1.5 leading-relaxed">
                        <li>Tối ưu hóa thuật toán tương tác ngầm để vượt checkpoint 282 của Facebook.</li>
                        <li>Sửa lỗi không nhận diện được giao diện mới của Facebook Ads Manager.</li>
                        <li>Tăng tốc độ khởi chạy luồng tương tác lên 25%.</li>
                    </ul>
                </div>
                <!-- Release 2 -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-2.5 py-0.5 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-full">v5.0.0</span>
                        <span class="text-xs text-slate-400 font-bold">18/06/2026</span>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm mb-2">Cập nhật VmaxShopeeBoost v5.0.0</h4>
                    <ul class="list-disc pl-5 text-xs text-slate-500 space-y-1.5 leading-relaxed">
                        <li>Cập nhật cơ chế seeding tương thích với quy định quét đánh giá mới của Shopee.</li>
                        <li>Nâng cấp tính năng tự động đẩy sản phẩm theo khung giờ vàng tối ưu lượt tiếp cận.</li>
                        <li>Tích hợp trình quản lý API Proxy xoay vòng nâng cao.</li>
                    </ul>
                </div>
            </div>
        </section>

    </main>

    <!-- Interactive Scripts -->
    <script>
    function filterDownloads(category) {
        const cards = document.querySelectorAll('[data-category]');
        cards.forEach(card => {
            if (category === 'all' || card.getAttribute('data-category') === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        // Update active class state on filter tabs
        const tabs = ['all', 'antidetect', 'facebook', 'shopee'];
        tabs.forEach(tab => {
            const btn = document.getElementById('tab-' + tab);
            if (!btn) return;
            if (tab === category) {
                btn.className = "px-5 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-md transition duration-200 cursor-pointer";
            } else {
                btn.className = "px-5 py-2.5 bg-white border border-slate-200/80 text-slate-600 hover:text-blue-600 hover:bg-slate-50 text-xs font-semibold rounded-xl transition duration-200 cursor-pointer";
            }
        });
    }

    function copyHash(elementId) {
        const codeElement = document.getElementById(elementId);
        if (codeElement) {
            const textToCopy = codeElement.textContent;
            navigator.clipboard.writeText(textToCopy).then(() => {
                alert("Đã sao chép mã băm bảo mật vào bộ nhớ tạm!");
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        }
    }
    </script>

    <?php include BASE_PATH . '/views/layout/footer.php'; ?>
