<?php
$prodsBySlug = [];
if (!empty($products)) {
    foreach ($products as $p) {
        $prodsBySlug[$p['slug']] = $p;
    }
}

// Fallbacks if database query is empty or missing specific products
$vmaxlogin = $prodsBySlug['lushlogin'] ?? [
    'name' => 'VmaxLogin',
    'price_monthly' => 200000,
    'price_quarterly' => 500000,
    'price_yearly' => 1800000,
    'price_lifetime' => 3900000
];

$autopost = $prodsBySlug['v-autopost'] ?? [
    'name' => 'V-AutoPost',
    'price_monthly' => 300000,
    'price_quarterly' => 650000,
    'price_yearly' => 3500000,
    'price_lifetime' => 6000000
];

$comboFb = $prodsBySlug['combo-marketing-facebook'] ?? [
    'name' => 'Combo Marketing Facebook',
    'price_monthly' => 500000,
    'price_quarterly' => 1200000,
    'price_yearly' => 3500000,
    'price_lifetime' => 7500000,
    'short_description' => 'Bộ 3 phần mềm bán hàng Facebook tự động đỉnh cao.'
];

$comboPhuQuy = $prodsBySlug['combo-phu-quy'] ?? [
    'name' => 'Combo Phú Quý',
    'price_monthly' => 600000,
    'price_quarterly' => 1500000,
    'price_yearly' => 4500000,
    'price_lifetime' => 9500000,
    'short_description' => 'Bộ 4 công cụ marketing tự động tối ưu doanh thu.'
];
?>
<?php
$pageTitle = 'Bảng Giá Bản Quyền - VMAX Software';
$useTailwind = true;
include BASE_PATH . '/views/layout/header.php';
?>


        <?php include BASE_PATH . '/views/layout/navbar.php'; ?>

    <!-- Pricing Title -->
    <header class="max-w-7xl mx-auto px-6 py-12 text-center">
        <span class="inline-flex px-3 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-full uppercase tracking-wider mb-3">
            Bảng giá dịch vụ mới nhất 2026
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 bg-clip-text text-transparent">
            Bảng Giá Gói Bản Quyền Dịch Vụ
        </h1>
        <p class="text-slate-600 max-w-2xl mx-auto leading-relaxed text-sm md:text-base">
            Chọn gói cước phù hợp nhất với nhu cầu làm việc và quy mô kinh doanh của bạn. Tất cả các gói đều hỗ trợ kích hoạt tự động tức thì 24/7.
        </p>
    </header>

    <!-- Tab Switcher -->
    <div class="flex justify-center mb-12">
        <div class="inline-flex bg-slate-200/50 p-1 rounded-2xl border border-slate-200/40">
            <button onclick="switchTab('vmaxlogin')" id="btn-vmaxlogin" class="tab-btn px-6 py-3 font-bold text-xs md:text-sm rounded-xl transition duration-200 bg-white text-slate-800 shadow-sm outline-none">
                VmaxLogin Browser
            </button>
            <button onclick="switchTab('vautopost')" id="btn-vautopost" class="tab-btn px-6 py-3 font-bold text-xs md:text-sm rounded-xl transition duration-200 text-slate-600 hover:text-slate-900 outline-none">
                Tự động hóa Facebook (V-AutoPost)
            </button>
            <button onclick="switchTab('combo')" id="btn-combo" class="tab-btn px-6 py-3 font-bold text-xs md:text-sm rounded-xl transition duration-200 text-slate-600 hover:text-slate-900 outline-none">
                Gói Combo Tiết Kiệm
            </button>
        </div>
    </div>

    <!-- Pricing Matrix -->
    <main class="max-w-7xl mx-auto px-6 pb-20">
        
        <!-- PANEL: VMAXLOGIN -->
        <div id="panel-vmaxlogin" class="tab-panel transition-all duration-300">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- MONTHLY -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-blue-500/30 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-slate-800">Gói 30 Ngày</h3>
                        </div>
                        <div class="mb-6">
                            <span class="text-3xl font-extrabold text-slate-900"><?= number_format($vmaxlogin['price_monthly'], 0, ',', '.') ?>đ</span>
                            <span class="text-slate-400 text-xs font-semibold">/ tháng</span>
                        </div>
                        <ul class="space-y-3.5 text-slate-600 text-xs mb-8">
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span>Bản quyền đầy đủ tính năng antidetect browser</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span>Giới hạn kích hoạt trên 1 thiết bị</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span>Cập nhật tự động phiên bản mới miễn phí</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span>Hỗ trợ kỹ thuật qua Zalo/Ultraview</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/login" class="w-full text-center py-3.5 bg-slate-900 text-white font-bold rounded-2xl hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 transition duration-200 block text-xs">Mua Ngay</a>
                </div>

                <!-- QUARTERLY -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-blue-500/30 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-slate-800">Gói 90 Ngày</h3>
                            <span class="px-2 py-0.5 text-[10px] font-extrabold bg-blue-50 text-blue-600 rounded">Tiết kiệm 15%</span>
                        </div>
                        <div class="mb-6">
                            <span class="text-3xl font-extrabold text-slate-900"><?= number_format($vmaxlogin['price_quarterly'], 0, ',', '.') ?>đ</span>
                            <span class="text-slate-400 text-xs font-semibold">/ 3 tháng</span>
                        </div>
                        <ul class="space-y-3.5 text-slate-600 text-xs mb-8">
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span>Bản quyền đầy đủ tính năng antidetect browser</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span>Giới hạn kích hoạt trên 2 thiết bị</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span>Cập nhật tự động phiên bản mới miễn phí</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span>Hỗ trợ kỹ thuật qua Zalo/Ultraview</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/login" class="w-full text-center py-3.5 bg-slate-900 text-white font-bold rounded-2xl hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 transition duration-200 block text-xs">Mua Ngay</a>
                </div>

                <!-- YEARLY -->
                <div class="bg-white border-2 border-blue-500 rounded-3xl p-6 shadow-md hover:shadow-2xl transition duration-300 flex flex-col justify-between relative group">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-blue-600 text-white font-extrabold text-[9px] uppercase tracking-wider px-3 py-1 rounded-full">Khuyên dùng</span>
                    <div>
                        <div class="flex justify-between items-center mb-4 mt-1">
                            <h3 class="text-lg font-bold text-slate-800">Gói 365 Ngày</h3>
                            <span class="px-2 py-0.5 text-[10px] font-extrabold bg-blue-100 text-blue-700 rounded">Tiết kiệm 25%</span>
                        </div>
                        <div class="mb-6">
                            <span class="text-3xl font-extrabold text-slate-900"><?= number_format($vmaxlogin['price_yearly'], 0, ',', '.') ?>đ</span>
                            <span class="text-slate-400 text-xs font-semibold">/ năm</span>
                        </div>
                        <ul class="space-y-3.5 text-slate-600 text-xs mb-8">
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-600 font-bold">&#8226;</span>
                                <span>Bản quyền đầy đủ tính năng antidetect browser</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-600 font-bold">&#8226;</span>
                                <span>Giới hạn kích hoạt trên 5 thiết bị</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-600 font-bold">&#8226;</span>
                                <span>Cập nhật tự động phiên bản mới miễn phí</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-600 font-bold">&#8226;</span>
                                <span class="font-semibold text-slate-800">Ưu tiên xử lý sự cố & hỗ trợ kỹ thuật nhanh</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/login" class="w-full text-center py-3.5 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 hover:scale-[1.02] transition duration-200 block text-xs">Mua Ngay</a>
                </div>

                <!-- LIFETIME -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-blue-500/30 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-slate-800">Gói Trọn Đời</h3>
                            <span class="px-2 py-0.5 text-[10px] font-extrabold bg-amber-50 text-amber-600 rounded">Giá trị nhất</span>
                        </div>
                        <div class="mb-6">
                            <span class="text-3xl font-extrabold text-slate-900"><?= number_format($vmaxlogin['price_lifetime'], 0, ',', '.') ?>đ</span>
                            <span class="text-slate-400 text-xs font-semibold">/ vĩnh viễn</span>
                        </div>
                        <ul class="space-y-3.5 text-slate-600 text-xs mb-8">
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span>Bản quyền sử dụng trọn đời sản phẩm</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span>Giới hạn kích hoạt lên tới 10 thiết bị</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span>Không cần phí duy trì hoặc gia hạn định kỳ</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-blue-500 font-bold">&#8226;</span>
                                <span class="font-semibold text-slate-800">Hỗ trợ kỹ thuật đặc quyền 24/7 từ chuyên gia</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/login" class="w-full text-center py-3.5 bg-slate-900 text-white font-bold rounded-2xl hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 transition duration-200 block text-xs">Mua Ngay</a>
                </div>
            </div>
        </div>

        <!-- PANEL: V-AUTOPOST -->
        <div id="panel-vautopost" class="tab-panel hidden transition-all duration-300">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- MONTHLY -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-emerald-500/30 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-slate-800">Gói 30 Ngày</h3>
                        </div>
                        <div class="mb-6">
                            <span class="text-3xl font-extrabold text-slate-900"><?= number_format($autopost['price_monthly'], 0, ',', '.') ?>đ</span>
                            <span class="text-slate-400 text-xs font-semibold">/ tháng</span>
                        </div>
                        <ul class="space-y-3.5 text-slate-600 text-xs mb-8">
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span>Đầy đủ tính năng cao cấp của V-AutoPost</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span>Giới hạn kích hoạt trên 1 thiết bị</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span>Tự động cập nhật phiên bản mới liên tục</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span>Hỗ trợ kỹ thuật qua Zalo/Ultraview</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/login" class="w-full text-center py-3.5 bg-slate-900 text-white font-bold rounded-2xl hover:bg-emerald-600 hover:shadow-lg hover:shadow-emerald-500/20 transition duration-200 block text-xs">Mua Ngay</a>
                </div>

                <!-- QUARTERLY -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-emerald-500/30 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-slate-800">Gói 90 Ngày</h3>
                            <span class="px-2 py-0.5 text-[10px] font-extrabold bg-emerald-50 text-emerald-600 rounded">Tiết kiệm 15%</span>
                        </div>
                        <div class="mb-6">
                            <span class="text-3xl font-extrabold text-slate-900"><?= number_format($autopost['price_quarterly'], 0, ',', '.') ?>đ</span>
                            <span class="text-slate-400 text-xs font-semibold">/ 3 tháng</span>
                        </div>
                        <ul class="space-y-3.5 text-slate-600 text-xs mb-8">
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span>Đầy đủ tính năng cao cấp của V-AutoPost</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span>Giới hạn kích hoạt trên 2 thiết bị</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span>Tự động cập nhật phiên bản mới liên tục</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span>Hỗ trợ kỹ thuật qua Zalo/Ultraview</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/login" class="w-full text-center py-3.5 bg-slate-900 text-white font-bold rounded-2xl hover:bg-emerald-600 hover:shadow-lg hover:shadow-emerald-500/20 transition duration-200 block text-xs">Mua Ngay</a>
                </div>

                <!-- YEARLY -->
                <div class="bg-white border-2 border-emerald-500 rounded-3xl p-6 shadow-md hover:shadow-2xl transition duration-300 flex flex-col justify-between relative group">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-emerald-600 text-white font-extrabold text-[9px] uppercase tracking-wider px-3 py-1 rounded-full">Khuyên dùng</span>
                    <div>
                        <div class="flex justify-between items-center mb-4 mt-1">
                            <h3 class="text-lg font-bold text-slate-800">Gói 365 Ngày</h3>
                            <span class="px-2 py-0.5 text-[10px] font-extrabold bg-emerald-100 text-emerald-700 rounded">Tiết kiệm 35%</span>
                        </div>
                        <div class="mb-6">
                            <span class="text-3xl font-extrabold text-slate-900"><?= number_format($autopost['price_yearly'], 0, ',', '.') ?>đ</span>
                            <span class="text-slate-400 text-xs font-semibold">/ năm</span>
                        </div>
                        <ul class="space-y-3.5 text-slate-600 text-xs mb-8">
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-600 font-bold">&#8226;</span>
                                <span>Đầy đủ tính năng cao cấp của V-AutoPost</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-600 font-bold">&#8226;</span>
                                <span>Giới hạn kích hoạt trên 5 thiết bị</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-600 font-bold">&#8226;</span>
                                <span>Tự động cập nhật phiên bản mới liên tục</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-600 font-bold">&#8226;</span>
                                <span class="font-semibold text-slate-800">Ưu tiên xử lý sự cố & hỗ trợ kỹ thuật nhanh</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/login" class="w-full text-center py-3.5 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 hover:scale-[1.02] transition duration-200 block text-xs">Mua Ngay</a>
                </div>

                <!-- LIFETIME -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-emerald-500/30 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-slate-800">Gói Trọn Đời</h3>
                            <span class="px-2 py-0.5 text-[10px] font-extrabold bg-amber-50 text-amber-600 rounded">Hời nhất</span>
                        </div>
                        <div class="mb-6">
                            <span class="text-3xl font-extrabold text-slate-900"><?= number_format($autopost['price_lifetime'], 0, ',', '.') ?>đ</span>
                            <span class="text-slate-400 text-xs font-semibold">/ vĩnh viễn</span>
                        </div>
                        <ul class="space-y-3.5 text-slate-600 text-xs mb-8">
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span>Bản quyền sử dụng trọn đời sản phẩm</span>
                            </li>
                            <li class="flex items-start gap-2.5 text-blue-700 font-semibold">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span>Không giới hạn thiết bị kích hoạt</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span>Không cần gia hạn hoặc duy trì định kỳ</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold">&#8226;</span>
                                <span class="font-semibold text-slate-800">Hỗ trợ kỹ thuật đặc quyền từ chuyên gia</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/login" class="w-full text-center py-3.5 bg-slate-900 text-white font-bold rounded-2xl hover:bg-emerald-600 hover:shadow-lg hover:shadow-emerald-500/20 transition duration-200 block text-xs">Mua Ngay</a>
                </div>
            </div>
        </div>

        <!-- PANEL: COMBO -->
        <div id="panel-combo" class="tab-panel hidden transition-all duration-300">
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Combo Facebook Marketing -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-8 shadow-sm hover:shadow-xl transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="px-3 py-1 text-[10px] font-extrabold bg-rose-50 text-rose-600 rounded-full">TIẾT KIỆM 45%</span>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-800 mb-2"><?= htmlspecialchars($comboFb['name']) ?></h3>
                        <p class="text-slate-500 text-xs mb-6"><?= htmlspecialchars($comboFb['short_description']) ?></p>
                        
                        <div class="space-y-3 mb-8">
                            <div class="flex justify-between items-center py-2.5 border-b border-slate-100 text-xs">
                                <span class="text-slate-600">Gói 30 Ngày</span>
                                <strong class="text-slate-800"><?= number_format($comboFb['price_monthly'], 0, ',', '.') ?>đ</strong>
                            </div>
                            <div class="flex justify-between items-center py-2.5 border-b border-slate-100 text-xs">
                                <span class="text-slate-600">Gói 90 Ngày</span>
                                <strong class="text-slate-800"><?= number_format($comboFb['price_quarterly'], 0, ',', '.') ?>đ</strong>
                            </div>
                            <div class="flex justify-between items-center py-2.5 border-b border-slate-100 text-xs">
                                <span class="text-slate-600">Gói 365 Ngày</span>
                                <strong class="text-slate-800"><?= number_format($comboFb['price_yearly'], 0, ',', '.') ?>đ</strong>
                            </div>
                            <div class="flex justify-between items-center py-2.5 text-xs text-rose-600 font-semibold">
                                <span>Gói Trọn Đời (Khuyên dùng)</span>
                                <strong><?= number_format($comboFb['price_lifetime'], 0, ',', '.') ?>đ</strong>
                            </div>
                        </div>

                        <ul class="space-y-3 text-slate-600 text-xs mb-8 border-t border-slate-100 pt-6">
                            <li class="flex items-start gap-2.5">
                                <span class="text-rose-500 font-bold">&#8226;</span>
                                <span>Bao gồm VmaxLogin + V-AutoPost + Tool Seeding Facebook</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-rose-500 font-bold">&#8226;</span>
                                <span>Giải pháp bán hàng hoàn hảo, tối ưu chi phí</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-rose-500 font-bold">&#8226;</span>
                                <span>Không giới hạn tài khoản và cấu hình profile</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/login" class="w-full text-center py-3.5 bg-rose-600 text-white font-bold rounded-2xl hover:bg-rose-700 shadow-md shadow-rose-500/10 transition duration-200 block text-xs">Đăng Ký Combo</a>
                </div>

                <!-- Combo Phú Quý -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-8 shadow-sm hover:shadow-xl transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="px-3 py-1 text-[10px] font-extrabold bg-violet-50 text-violet-600 rounded-full">FULL PHÂN KHÚC</span>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-800 mb-2"><?= htmlspecialchars($comboPhuQuy['name']) ?></h3>
                        <p class="text-slate-500 text-xs mb-6"><?= htmlspecialchars($comboPhuQuy['short_description']) ?></p>
                        
                        <div class="space-y-3 mb-8">
                            <div class="flex justify-between items-center py-2.5 border-b border-slate-100 text-xs">
                                <span class="text-slate-600">Gói 30 Ngày</span>
                                <strong class="text-slate-800"><?= number_format($comboPhuQuy['price_monthly'], 0, ',', '.') ?>đ</strong>
                            </div>
                            <div class="flex justify-between items-center py-2.5 border-b border-slate-100 text-xs">
                                <span class="text-slate-600">Gói 90 Ngày</span>
                                <strong class="text-slate-800"><?= number_format($comboPhuQuy['price_quarterly'], 0, ',', '.') ?>đ</strong>
                            </div>
                            <div class="flex justify-between items-center py-2.5 border-b border-slate-100 text-xs">
                                <span class="text-slate-600">Gói 365 Ngày</span>
                                <strong class="text-slate-800"><?= number_format($comboPhuQuy['price_yearly'], 0, ',', '.') ?>đ</strong>
                            </div>
                            <div class="flex justify-between items-center py-2.5 text-xs text-violet-600 font-semibold">
                                <span>Gói Trọn Đời (Khuyên dùng)</span>
                                <strong><?= number_format($comboPhuQuy['price_lifetime'], 0, ',', '.') ?>đ</strong>
                            </div>
                        </div>

                        <ul class="space-y-3 text-slate-600 text-xs mb-8 border-t border-slate-100 pt-6">
                            <li class="flex items-start gap-2.5">
                                <span class="text-violet-500 font-bold">&#8226;</span>
                                <span>Toàn bộ các phần mềm lẻ và Combo hiện tại</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-violet-500 font-bold">&#8226;</span>
                                <span>Tự động hóa xây dựng phễu bán hàng đa nền tảng</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <span class="text-violet-500 font-bold">&#8226;</span>
                                <span>Hỗ trợ triển khai hệ thống quy mô lớn 24/7</span>
                            </li>
                        </ul>
                    </div>
                    <a href="/login" class="w-full text-center py-3.5 bg-violet-600 text-white font-bold rounded-2xl hover:bg-violet-700 shadow-md shadow-violet-500/10 transition duration-200 block text-xs">Đăng Ký Combo</a>
                </div>
            </div>
        </div>

        <!-- FEATURE COMPARISON TABLE -->
        <section class="mt-24 border-t border-slate-200/80 pt-16">
            <h2 class="text-2xl font-extrabold text-slate-900 text-center mb-3">So Sánh Tính Năng Các Gói Dịch Vụ</h2>
            <p class="text-slate-500 text-center max-w-xl mx-auto text-xs mb-12">So sánh chi tiết các thông số kỹ thuật và quyền lợi giữa VmaxLogin Browser và phần mềm tự động hóa Facebook V-AutoPost.</p>
            
            <div class="overflow-x-auto rounded-3xl border border-slate-200/70 bg-white shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/70">
                            <th class="p-5 font-bold text-slate-800 text-xs md:text-sm">Tính năng & Quyền lợi</th>
                            <th class="p-5 font-bold text-slate-800 text-xs md:text-sm text-center">VmaxLogin Browser</th>
                            <th class="p-5 font-bold text-slate-800 text-xs md:text-sm text-center">V-AutoPost Facebook</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <tr>
                            <td class="p-5 font-semibold text-slate-700">Mục đích chính</td>
                            <td class="p-5 text-slate-600 text-center">Trình duyệt antidetect, chống trùng vân tay</td>
                            <td class="p-5 text-slate-600 text-center">Đăng bài tự động, tương tác và nuôi nick</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-slate-700">Số lượng tài khoản</td>
                            <td class="p-5 text-slate-600 text-center">Không giới hạn số lượng Profile</td>
                            <td class="p-5 text-slate-600 text-center">Không giới hạn số lượng nick Facebook</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-slate-700">Cơ chế hoạt động</td>
                            <td class="p-5 text-slate-600 text-center">Hoạt động độc lập, chống quét chéo</td>
                            <td class="p-5 text-slate-600 text-center">Chạy đa luồng giả lập hành vi người dùng</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-slate-700">Số thiết bị sử dụng (Gói Trọn đời)</td>
                            <td class="p-5 text-slate-600 text-center">Tối đa 10 thiết bị</td>
                            <td class="p-5 text-emerald-600 font-semibold text-center">Không giới hạn thiết bị</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-slate-700">Tự động hóa & API</td>
                            <td class="p-5 text-slate-600 text-center">Hỗ trợ Selenium, Puppeteer, Playwright</td>
                            <td class="p-5 text-slate-600 text-center">Hỗ trợ tự động hóa bằng click & kịch bản cài sẵn</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-slate-700">Dịch vụ đi kèm</td>
                            <td class="p-5 text-slate-600 text-center">Cloud sync lưu trữ cấu hình vân tay</td>
                            <td class="p-5 text-slate-600 text-center">Cập nhật kịch bản tránh checkpoint liên tục</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-slate-700">Cập nhật phần mềm</td>
                            <td class="p-5 text-slate-600 text-center">Miễn phí trọn đời</td>
                            <td class="p-5 text-slate-600 text-center">Miễn phí trọn đời</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-slate-700">Kênh hỗ trợ kỹ thuật</td>
                            <td class="p-5 text-slate-600 text-center">Zalo / Ultraview / Đặc quyền</td>
                            <td class="p-5 text-slate-600 text-center">Zalo / Ultraview / Đặc quyền</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- FAQ SECTION -->
        <section class="mt-24 max-w-4xl mx-auto">
            <h2 class="text-2xl font-extrabold text-slate-900 text-center mb-10">Câu Hỏi Thường Gặp</h2>
            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <details class="group bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between font-bold text-slate-800 text-sm cursor-pointer list-none select-none">
                        Tôi có thể nâng cấp gói cước giữa chừng không?
                        <span class="transition duration-300 group-open:-rotate-180">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </summary>
                    <p class="text-slate-500 text-xs leading-relaxed mt-3">Có, hệ thống hỗ trợ nâng cấp gói cước linh hoạt bằng cách bù phần chênh lệch chi phí thực tế giữa các gói sử dụng.</p>
                </details>

                <!-- FAQ Item 2 -->
                <details class="group bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between font-bold text-slate-800 text-sm cursor-pointer list-none select-none">
                        Tôi có được hướng dẫn cài đặt sử dụng không?
                        <span class="transition duration-300 group-open:-rotate-180">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </summary>
                    <p class="text-slate-500 text-xs leading-relaxed mt-3">Đội ngũ hỗ trợ kỹ thuật của VMAX sẽ hướng dẫn chi tiết qua Zalo và Ultraview cho đến khi bạn sử dụng thành thạo phần mềm.</p>
                </details>

                <!-- FAQ Item 3 -->
                <details class="group bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between font-bold text-slate-800 text-sm cursor-pointer list-none select-none">
                        Phần mềm có được tự động cập nhật khi có bản mới không?
                        <span class="transition duration-300 group-open:-rotate-180">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </summary>
                    <p class="text-slate-500 text-xs leading-relaxed mt-3">Có, tất cả sản phẩm của VMAX Software đều hỗ trợ tự động kiểm tra và nâng cấp lên phiên bản mới nhất hoàn toàn miễn phí.</p>
                </details>
            </div>
        </section>

    </main>

    <!-- Interactive Tabs Logic -->
    <script>
        function switchTab(tabId) {
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
            });
            // Show active panel
            document.getElementById('panel-' + tabId).classList.remove('hidden');

            // Reset button active classes
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-slate-800', 'shadow-sm');
                btn.classList.add('text-slate-600', 'hover:text-slate-900');
            });
            // Set active button class
            const activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.classList.remove('text-slate-600', 'hover:text-slate-900');
            activeBtn.classList.add('bg-white', 'text-slate-800', 'shadow-sm');
        }
    </script>

<?php include BASE_PATH . '/views/layout/footer.php'; ?>






