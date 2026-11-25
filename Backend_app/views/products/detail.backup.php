<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900">

    <?php include BASE_PATH . '/views/layout/navbar.php'; ?>

    <!-- Main Section Hero/Detail -->
    <main class="max-w-7xl mx-auto px-6 py-12">
        
        <!-- Breadcrumbs -->
        <nav class="flex text-xs font-semibold text-slate-400 gap-2 mb-8 items-center">
            <a href="/" class="hover:text-blue-600 transition">Trang Chủ</a>
            <span>/</span>
            <a href="/products" class="hover:text-blue-600 transition">Sản Phẩm</a>
            <span>/</span>
            <span class="text-slate-600 font-bold"><?= htmlspecialchars($product['name']) ?></span>
        </nav>

        <div class="grid lg:grid-cols-12 gap-10 items-start">
            
            <!-- Left Side: Product Image & Basic Info Card -->
            <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-3xl p-8 shadow-sm flex flex-col items-center">
                <!-- Cover box rendering -->
                <div class="relative w-48 h-64 rounded-2xl overflow-hidden shadow-lg border border-slate-100 bg-white mb-6">
                    <?php if (!empty($product['image_url'])): ?>
                        <img src="<?= ASSET_PATH ?>/<?= ltrim(htmlspecialchars($product['image_url']), '/') ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <!-- Generates a premium 3D-styled Software Box/Book Mockup -->
                        <?php
                        $grad = 'from-blue-600 via-indigo-600 to-violet-700';
                        if ($product['is_combo']) {
                            $grad = 'from-purple-600 via-fuchsia-600 to-pink-600';
                        } elseif (strpos($product['slug'], 'shopee') !== false) {
                            $grad = 'from-orange-500 via-red-500 to-rose-600';
                        } elseif ($product['slug'] === 'v-autopost') {
                            $grad = 'from-emerald-500 via-teal-600 to-cyan-600';
                        }
                        ?>
                        <div class="absolute inset-0 bg-gradient-to-br <?= $grad ?> text-white p-6 flex flex-col justify-between select-none">
                            <div class="absolute top-0 left-0 w-3 h-full bg-black/15 shadow-[1px_0_3px_rgba(0,0,0,0.15)] z-10 pointer-events-none"></div>
                            <div class="absolute top-0 left-3 w-1.5 h-full bg-white/10 z-10 pointer-events-none"></div>

                            <div class="z-20 flex justify-between items-center">
                                <span class="text-[9px] font-extrabold tracking-widest bg-white/20 px-2 py-0.5 rounded uppercase">VMAX APP</span>
                            </div>

                            <div class="z-20 my-auto text-center px-1">
                                <h4 class="font-black text-sm tracking-wide leading-tight drop-shadow-sm uppercase font-mono"><?= htmlspecialchars($product['name']) ?></h4>
                                <div class="w-8 h-0.5 bg-white/40 mx-auto mt-2 rounded"></div>
                            </div>

                            <div class="z-20 flex justify-between items-center text-[8px] font-bold text-white/80 uppercase tracking-widest border-t border-white/10 pt-2">
                                <span>Ver 2026</span>
                                <span>Pro Suite</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <h1 class="text-3xl font-extrabold text-slate-800 text-center mb-2"><?= htmlspecialchars($product['name']) ?></h1>
                <span class="px-3 py-1 bg-blue-50 border border-blue-200/50 text-blue-600 text-xs font-bold rounded-full uppercase tracking-wider mb-6">
                    <?= $product['is_combo'] ? 'Combo Ưu Đãi' : 'Phần Mềm Đơn Lẻ' ?>
                </span>

                <div class="w-full border-t border-slate-100 pt-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Tính năng nổi bật</h3>
                    <ul class="space-y-3 mb-6">
                        <?php foreach ($features as $feat): ?>
                            <li class="flex items-start gap-3 text-sm text-slate-600">
                                <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-base flex-shrink-0"></i>
                                <span><?= htmlspecialchars($feat) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Right Side: Landing detail content & pricing cards -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- Main Header / Overview -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Giới Thiệu Tổng Quan</h2>
                    <p class="text-slate-600 leading-relaxed text-sm md:text-base mb-6">
                        <?= nl2br(htmlspecialchars($product['short_description'])) ?>
                    </p>
                    
                    <?php if (!empty($product['description'])): ?>
                        <div class="prose max-w-none text-slate-700 text-sm md:text-base leading-relaxed border-t border-slate-100 pt-6">
                            <!-- Raw html allowed from admin for detailed rich content -->
                            <?= $product['description'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Detailed Pricing List -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-800 mb-6 pb-2 border-b border-slate-100">Chọn Gói Bản Quyền</h2>
                    
                    <div class="grid sm:grid-cols-2 gap-4">
                        <!-- Monthly -->
                        <div class="border border-slate-200/80 hover:border-blue-500/30 rounded-2xl p-5 bg-slate-50/50 hover:bg-white transition flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">Gói 30 ngày</span>
                                <h3 class="text-lg font-bold text-slate-800 mt-1">Gói Phổ Thông</h3>
                                <div class="my-4">
                                    <span class="text-2xl font-extrabold text-blue-600"><?= number_format($product['price_monthly'], 0, ',', '.') ?>đ</span>
                                    <span class="text-xs text-slate-400">/ 30 ngày</span>
                                </div>
                            </div>
                            <a href="/login" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl text-center block transition shadow-sm">Chọn Mua</a>
                        </div>

                        <!-- Quarterly -->
                        <div class="border border-slate-200/80 hover:border-blue-500/30 rounded-2xl p-5 bg-slate-50/50 hover:bg-white transition flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">Gói 90 ngày</span>
                                <h3 class="text-lg font-bold text-slate-800 mt-1">Gói Tiết Kiệm</h3>
                                <div class="my-4">
                                    <span class="text-2xl font-extrabold text-blue-600"><?= number_format($product['price_quarterly'], 0, ',', '.') ?>đ</span>
                                    <span class="text-xs text-slate-400">/ 90 ngày</span>
                                </div>
                            </div>
                            <a href="/login" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl text-center block transition shadow-sm">Chọn Mua</a>
                        </div>

                        <!-- Yearly -->
                        <div class="border-2 border-blue-500 rounded-2xl p-5 bg-blue-50/10 hover:bg-white transition flex flex-col justify-between relative shadow-[0_4px_20px_rgba(37,99,235,0.06)]">
                            <span class="absolute top-0 end-4 translate-middle-y bg-blue-600 text-white text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full">Được chọn nhiều</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase text-blue-600 tracking-wider">Gói 365 ngày</span>
                                <h3 class="text-lg font-bold text-slate-800 mt-1">Gói Doanh Nghiệp</h3>
                                <div class="my-4">
                                    <span class="text-2xl font-extrabold text-blue-600"><?= number_format($product['price_yearly'], 0, ',', '.') ?>đ</span>
                                    <span class="text-xs text-slate-400">/ 365 ngày</span>
                                </div>
                            </div>
                            <a href="/login" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl text-center block transition shadow-sm">Chọn Mua</a>
                        </div>

                        <!-- Lifetime -->
                        <div class="border border-slate-200/80 hover:border-purple-500/30 rounded-2xl p-5 bg-slate-50/50 hover:bg-white transition flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase text-purple-600 tracking-wider">Trọn đời (VIP)</span>
                                <h3 class="text-lg font-bold text-slate-800 mt-1">Gói Vĩnh Viễn</h3>
                                <div class="my-4">
                                    <span class="text-2xl font-extrabold text-purple-600"><?= number_format($product['price_lifetime'], 0, ',', '.') ?>đ</span>
                                    <span class="text-xs text-slate-400">/ Vĩnh viễn</span>
                                </div>
                            </div>
                            <a href="/login" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl text-center block transition shadow-sm">Chọn Mua</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>

</body>
</html>
