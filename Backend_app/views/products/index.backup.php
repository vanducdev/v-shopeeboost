<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản Phẩm Phần Mềm - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900">

        <?php include BASE_PATH . '/views/layout/navbar.php'; ?>
<!-- Header Banner -->
    <header class="max-w-7xl mx-auto px-6 py-12 text-center">
        <span class="inline-flex px-3 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-full uppercase tracking-wider mb-3">
            Hệ sinh thái phần mềm VMAX
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 bg-clip-text text-transparent">
            Các Giải Pháp Tự Động Hóa Tiêu Biểu
        </h1>
        <p class="text-slate-600 max-w-2xl mx-auto leading-relaxed text-sm md:text-base">
            Chúng tôi nghiên cứu và phát triển các sản phẩm trình duyệt antidetect, phần mềm tự động hóa phục vụ tiếp thị liên kết và tối ưu hiệu suất kinh doanh số của bạn.
        </p>

        <!-- Stats Counter -->
        <div class="grid grid-cols-3 gap-4 max-w-xl mx-auto mt-8 border-t border-b border-slate-200/50 py-6 text-center">
            <div>
                <span class="block text-2xl font-extrabold text-blue-600">5+</span>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Phần Mềm Lẻ</span>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-blue-600">10k+</span>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Khách Hàng</span>
            </div>
            <div>
                <span class="block text-2xl font-extrabold text-blue-600">99.9%</span>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Độ Ổn Định</span>
            </div>
        </div>
    </header>

    <?php
    // Prepare products data
    $productsList = [];
    foreach ($products as $prod) {
        $slug = $prod['slug'];
        $category = 'single';
        $badge = 'Phần mềm lẻ';
        $features = [];
        $imageUrl = $prod['image_url'] ?? '';

        if ($slug === 'lushlogin') {
            $category = 'single';
            $badge = 'Antidetect Browser';
            $imageUrl = 'image/logologin.webp';
            $features = [
                'Tạo cấu hình vân tay trình duyệt riêng biệt hoàn toàn',
                'Hỗ trợ API, CLI điều khiển từ xa tự động',
                'Đồng bộ đám mây (Cloud sync) nhanh chóng, bảo mật',
                'Tránh checkpoint chéo tài khoản quảng cáo, tài khoản shop'
            ];
        } elseif ($slug === 'v-autopost') {
            $category = 'single';
            $badge = 'Facebook Automation';
            $imageUrl = 'image/logofb.webp';
            $features = [
                'Đăng bài tự động hàng loạt lên Fanpage, Group, trang cá nhân',
                'Tự động tương tác, seeding bài viết tự nhiên như người dùng thật',
                'Quản lý kịch bản tương tác đa luồng chuyên nghiệp',
                'Tự động gửi tin nhắn, chăm sóc khách hàng mục tiêu'
            ];
        } elseif ($slug === 'combo-marketing-facebook') {
            $category = 'combo';
            $badge = 'Combo Ưu Đãi';
            $features = [
                'Trọn bộ VmaxLogin Browser + V-AutoPost + Seeding nâng cao',
                'Xây dựng hệ thống bán hàng tự động chuyên nghiệp',
                'Tiết kiệm 45% chi phí so với mua lẻ'
            ];
        } elseif ($slug === 'combo-phu-quy') {
            $category = 'combo';
            $badge = 'Combo Toàn Diện';
            $features = [
                'Sở hữu toàn bộ danh mục phần mềm của VMAX Software',
                'Đầy đủ tính năng, giải pháp marketing đa nền tảng tối tân',
                'Tiết kiệm chi phí tối đa cho doanh nghiệp'
            ];
        } elseif (strpos($slug, 'shopee') !== false) {
            $category = 'shopee';
            $badge = 'Shopee Automation';
            $imageUrl = 'image/logosp.webp';
            $features = [
                'Tự động tăng lượt thích, theo dõi sản phẩm Shopee',
                'Tự động đẩy sản phẩm Shopee theo chu kỳ thông minh',
                'Tăng điểm hiển thị tự nhiên của gian hàng, thu hút khách hàng',
                'Tối ưu hóa chuyển đổi, bứt phá doanh số bán hàng'
            ];
        } else {
            $features = [
                'Đầy đủ tính năng quản lý và tự động hóa cao cấp',
                'Giao diện hiện đại, dễ dàng cài đặt và vận hành',
                'Cập nhật tự động phiên bản mới liên tục miễn phí'
            ];
        }

        $productsList[] = array_merge($prod, [
            'category' => $category,
            'badge' => $badge,
            'features' => $features,
            'image_url' => $imageUrl
        ]);
    }
    ?>

    <!-- Category Filter Switcher -->
    <div class="flex justify-center mb-12">
        <div class="inline-flex bg-slate-200/50 p-1 rounded-2xl border border-slate-200/40">
            <button onclick="filterProducts('all')" id="btn-filter-all" class="filter-btn px-5 py-2.5 font-bold text-xs md:text-sm rounded-xl transition duration-200 bg-blue-600 text-white outline-none">
                Tất cả sản phẩm
            </button>
            <button onclick="filterProducts('single')" id="btn-filter-single" class="filter-btn px-5 py-2.5 font-bold text-xs md:text-sm rounded-xl transition duration-200 text-slate-700 hover:text-slate-900 bg-white border border-transparent outline-none ml-1">
                Phần mềm lẻ
            </button>
            <button onclick="filterProducts('combo')" id="btn-filter-combo" class="filter-btn px-5 py-2.5 font-bold text-xs md:text-sm rounded-xl transition duration-200 text-slate-700 hover:text-slate-900 bg-white border border-transparent outline-none ml-1">
                Gói Combo
            </button>
            <button onclick="filterProducts('shopee')" id="btn-filter-shopee" class="filter-btn px-5 py-2.5 font-bold text-xs md:text-sm rounded-xl transition duration-200 text-slate-700 hover:text-slate-900 bg-white border border-transparent outline-none ml-1">
                Tự động hóa Shopee
            </button>
        </div>
    </div>

    <!-- Catalogue Grid -->
    <main class="max-w-7xl mx-auto px-6 pb-20">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($productsList as $pObj): ?>
                <div class="product-card bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-blue-500/20 transition duration-300 flex flex-col justify-between group" data-category="<?= $pObj['category'] ?>">
                    <div>
                        <!-- Badge at top right -->
                        <div class="flex justify-end mb-3">
                            <span class="px-2.5 py-0.5 bg-slate-50 border border-slate-200/60 text-slate-500 text-[10px] font-bold rounded-full group-hover:bg-blue-50 group-hover:border-blue-200/50 group-hover:text-blue-600 transition">
                                <?= $pObj['badge'] ?>
                            </span>
                        </div>

                        <!-- Product Box Mockup Cover -->
                        <div class="flex justify-center mb-6 relative">
                            <!-- Glow background effect on card hover -->
                            <div class="absolute inset-0 bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                            
                            <?php if (!empty($pObj['image_url'])): ?>
                                <div class="relative w-36 h-48 rounded-2xl overflow-hidden shadow-md group-hover:shadow-xl transition-all duration-300 group-hover:scale-[1.03] border border-slate-100 bg-white">
                                    <img src="<?= ASSET_PATH ?>/<?= ltrim(htmlspecialchars($pObj['image_url']), '/') ?>" alt="<?= htmlspecialchars($pObj['name']) ?>" class="w-full h-full object-cover">
                                </div>
                            <?php else: ?>
                                <!-- Generates a premium 3D-styled Software Box/Book Mockup -->
                                <?php
                                // Choose gradient based on product category/name to make it colorful and harmonized
                                $grad = 'from-blue-600 via-indigo-600 to-violet-700';
                                if ($pObj['category'] === 'shopee') {
                                    $grad = 'from-orange-500 via-red-500 to-rose-600';
                                } elseif ($pObj['category'] === 'combo') {
                                    $grad = 'from-purple-600 via-fuchsia-600 to-pink-600';
                                } elseif ($pObj['slug'] === 'v-autopost') {
                                    $grad = 'from-emerald-500 via-teal-600 to-cyan-600';
                                }
                                ?>
                                <div class="relative w-36 h-48 rounded-2xl overflow-hidden shadow-md group-hover:shadow-xl transition-all duration-300 group-hover:scale-[1.03] bg-gradient-to-br <?= $grad ?> text-white p-4 flex flex-col justify-between select-none border border-white/10">
                                    <!-- Spine 3D Shadow Overlay -->
                                    <div class="absolute top-0 left-0 w-2.5 h-full bg-black/15 shadow-[1px_0_3px_rgba(0,0,0,0.15)] z-10 pointer-events-none"></div>
                                    <div class="absolute top-0 left-2.5 w-1 h-full bg-white/10 z-10 pointer-events-none"></div>

                                    <!-- Brand badge -->
                                    <div class="z-20 flex justify-between items-center">
                                        <span class="text-[8px] font-extrabold tracking-widest bg-white/20 px-2 py-0.5 rounded uppercase">VMAX APP</span>
                                    </div>

                                    <!-- Title on the Book Cover -->
                                    <div class="z-20 my-auto text-center px-1">
                                        <h4 class="font-black text-xs tracking-wide leading-tight drop-shadow-sm uppercase font-mono"><?= htmlspecialchars($pObj['name']) ?></h4>
                                        <div class="w-6 h-0.5 bg-white/40 mx-auto mt-2 rounded"></div>
                                    </div>

                                    <!-- Box/Spine Details -->
                                    <div class="z-20 flex justify-between items-center text-[7px] font-bold text-white/80 uppercase tracking-widest border-t border-white/10 pt-2">
                                        <span>Ver 2026</span>
                                        <span>Pro Suite</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Title & Description -->
                        <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-blue-600 transition text-center"><?= htmlspecialchars($pObj['name']) ?></h3>
                        <p class="text-slate-500 text-xs line-clamp-3 mb-6 leading-relaxed text-center"><?= htmlspecialchars($pObj['short_description'] ?: $pObj['description']) ?></p>

                        <!-- Bullet Features -->
                        <ul class="space-y-2 mb-6 border-t border-slate-100 pt-5">
                            <?php foreach (array_slice($pObj['features'], 0, 3) as $feat): ?>
                                <li class="flex items-start gap-2.5 text-xs text-slate-600">
                                    <span class="text-blue-500 font-bold">&#8226;</span>
                                    <span><?= htmlspecialchars($feat) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Footer & CTA -->
                    <div class="border-t border-slate-100 pt-5 mt-auto">
                        <div class="flex justify-between items-baseline mb-4">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Giá khởi điểm từ</span>
                            <strong class="text-base font-extrabold text-slate-900"><?= number_format($pObj['price_monthly'], 0, ',', '.') ?>đ<span class="text-slate-400 text-[10px] font-normal">/tháng</span></strong>
                        </div>

                        <div class="flex flex-col gap-2">
                            <a href="/product/<?= htmlspecialchars($pObj['slug']) ?>" class="w-full py-2.5 bg-slate-950 hover:bg-blue-600 text-white font-bold rounded-xl text-xs transition duration-200 shadow-sm text-center block no-underline">
                                Tìm hiểu chi tiết
                            </a>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="/login" class="py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-center text-[10px] transition">Mua Ngay</a>
                                <a href="/downloads" class="py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-center text-[10px] transition">Tải Bản Dựng</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Product Modal Overlay -->
    <div id="product-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300">
        <div class="bg-white rounded-[32px] border border-slate-200 max-w-3xl w-full max-h-[90vh] overflow-y-auto p-6 md:p-8 relative shadow-2xl transition-transform duration-300 scale-95" id="modal-content-wrapper">
            <!-- Close Button -->
            <button onclick="closeProductModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 font-bold text-xl outline-none">
                &times;
            </button>
            
            <!-- Modal Body Content -->
            <div id="modal-body-content"></div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-12 px-6 text-center text-slate-500 text-sm">
        <p>&copy; <?= date('Y') ?> VMAX Technology Solutions. Bảo lưu mọi quyền.</p>
    </footer>

    <!-- Interactive Grid Filter & Modals Logic -->
    <script>
        const ASSET_PATH = '<?= ASSET_PATH ?>';

        function filterProducts(category) {
            // Hide all product cards
            document.querySelectorAll('.product-card').forEach(card => {
                card.classList.add('hidden');
            });

            // Reset button active classes
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-white', 'text-slate-700', 'border-transparent');
            });

            // Set active button class
            const activeBtn = document.getElementById('btn-filter-' + category);
            activeBtn.classList.remove('bg-white', 'text-slate-700', 'border-transparent');
            activeBtn.classList.add('bg-blue-600', 'text-white');

            // Show filtered cards
            if (category === 'all') {
                document.querySelectorAll('.product-card').forEach(card => {
                    card.classList.remove('hidden');
                });
            } else {
                document.querySelectorAll('.product-card[data-category="' + category + '"]').forEach(card => {
                    card.classList.remove('hidden');
                });
            }
        }

        function openProductModal(prodJson) {
            const prod = JSON.parse(decodeURIComponent(prodJson));
            const modal = document.getElementById('product-modal');
            const wrapper = document.getElementById('modal-content-wrapper');
            const body = document.getElementById('modal-body-content');
            
            // Format pricing formatting
            const priceM = new Intl.NumberFormat('vi-VN').format(prod.price_monthly) + 'đ';
            const priceQ = new Intl.NumberFormat('vi-VN').format(prod.price_quarterly) + 'đ';
            const priceY = new Intl.NumberFormat('vi-VN').format(prod.price_yearly) + 'đ';
            const priceL = new Intl.NumberFormat('vi-VN').format(prod.price_lifetime) + 'đ';

            // Get features based on product
            let featuresHTML = '';
            prod.features.forEach(f => {
                featuresHTML += `
                    <li class="flex items-start gap-2.5 text-xs text-slate-600">
                        <span class="text-blue-500 font-bold">&#8226;</span>
                        <span>${f}</span>
                    </li>
                `;
            });

            // Generate the box cover HTML for modal dynamically
            let coverHTML = '';
            if (prod.image_url) {
                coverHTML = `
                    <div class="relative w-36 h-48 rounded-2xl overflow-hidden shadow-md border border-slate-100 bg-white mx-auto mb-6">
                        <img src="${ASSET_PATH}/${prod.image_url.replace(/^\//, '')}" alt="${prod.name}" class="w-full h-full object-cover">
                    </div>
                `;
            } else {
                let grad = 'from-blue-600 via-indigo-600 to-violet-700';
                if (prod.category === 'shopee') {
                    grad = 'from-orange-500 via-red-500 to-rose-600';
                } else if (prod.category === 'combo') {
                    grad = 'from-purple-600 via-fuchsia-600 to-pink-600';
                } else if (prod.slug === 'v-autopost') {
                    grad = 'from-emerald-500 via-teal-600 to-cyan-600';
                }
                coverHTML = `
                    <div class="relative w-36 h-48 rounded-2xl overflow-hidden shadow-md bg-gradient-to-br ${grad} text-white p-4 flex flex-col justify-between select-none border border-white/10 mx-auto mb-6">
                        <div class="absolute top-0 left-0 w-2.5 h-full bg-black/15 shadow-[1px_0_3px_rgba(0,0,0,0.15)] z-10 pointer-events-none"></div>
                        <div class="absolute top-0 left-2.5 w-1 h-full bg-white/10 z-10 pointer-events-none"></div>
                        <div class="z-20 flex justify-between items-center">
                            <span class="text-[8px] font-extrabold tracking-widest bg-white/20 px-2 py-0.5 rounded uppercase">VMAX APP</span>
                        </div>
                        <div class="z-20 my-auto text-center px-1">
                            <h4 class="font-black text-xs tracking-wide leading-tight drop-shadow-sm uppercase font-mono">${prod.name}</h4>
                            <div class="w-6 h-0.5 bg-white/40 mx-auto mt-2 rounded"></div>
                        </div>
                        <div class="z-20 flex justify-between items-center text-[7px] font-bold text-white/80 uppercase tracking-widest border-t border-white/10 pt-2">
                            <span>Ver 2026</span>
                            <span>Pro Suite</span>
                        </div>
                    </div>
                `;
            }

            body.innerHTML = `
                <div class="flex items-center gap-4 mb-6">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full mb-1">
                            ${prod.badge}
                        </span>
                        <h3 class="text-2xl font-extrabold text-slate-900">${prod.name}</h3>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div class="flex flex-col">
                        ${coverHTML}
                        <h4 class="font-bold text-slate-800 text-sm mb-3">Giới thiệu sản phẩm</h4>
                        <p class="text-slate-500 text-xs leading-relaxed mb-6">${prod.description}</p>
                        
                        <h4 class="font-bold text-slate-800 text-sm mb-3">Tính năng nổi bật</h4>
                        <ul class="space-y-2">
                            ${featuresHTML}
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-800 text-sm mb-3">Bảng giá bản quyền</h4>
                        <div class="border border-slate-100 rounded-2xl overflow-hidden bg-slate-50 text-xs">
                            <div class="flex justify-between items-center p-3 border-b border-white">
                                <span class="text-slate-500">Gói 30 Ngày</span>
                                <strong class="text-slate-800">${priceM}</strong>
                            </div>
                            <div class="flex justify-between items-center p-3 border-b border-white">
                                <span class="text-slate-500">Gói 90 Ngày</span>
                                <strong class="text-slate-800">${priceQ}</strong>
                            </div>
                            <div class="flex justify-between items-center p-3 border-b border-white">
                                <span class="text-slate-500">Gói 365 Ngày</span>
                                <strong class="text-slate-800">${priceY}</strong>
                            </div>
                            <div class="flex justify-between items-center p-3 font-semibold text-blue-600">
                                <span>Gói Trọn Đời</span>
                                <strong>${priceL}</strong>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-blue-50/50 rounded-2xl border border-blue-50/80 text-[10px] text-blue-700 leading-relaxed">
                            <strong>* Hỗ trợ kỹ thuật đặc quyền:</strong> Được hỗ trợ cài đặt, cấu hình kịch bản bán hàng và khắc phục sự cố qua Zalo/Ultraview hoàn toàn miễn phí trong suốt thời hạn bản quyền.
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 border-t border-slate-100 pt-6">
                    <a href="/login" class="flex-1 py-3.5 bg-blue-600 text-white font-bold rounded-xl text-center shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition text-xs">Mua Bản Quyền</a>
                    <a href="/downloads" class="flex-1 py-3.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-center hover:bg-slate-50 transition text-xs">Tải Bản Dựng Thử Nghiệm</a>
                </div>
            `;

            // Show modal with transitions
            modal.classList.remove('hidden');
            setTimeout(() => {
                wrapper.classList.remove('scale-95');
                wrapper.classList.add('scale-100');
            }, 10);
        }

        function closeProductModal() {
            const modal = document.getElementById('product-modal');
            const wrapper = document.getElementById('modal-content-wrapper');
            wrapper.classList.remove('scale-100');
            wrapper.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // Close modal when clicking outside content
        document.getElementById('product-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProductModal();
            }
        });
    </script>

    <?php require BASE_PATH . '/views/layout/contact_button.php'; ?>
</body>
</html>
