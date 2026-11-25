<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Optimization -->
    <title><?= htmlspecialchars($post['meta_title'] ?: $post['title']) ?> - VMAX Software Blog</title>
    <meta name="description" content="<?= htmlspecialchars($post['meta_description'] ?: $post['summary']) ?>">
    <link rel="canonical" href="<?= SITE_URL ?>/blog/post/<?= htmlspecialchars($post['slug']) ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?= htmlspecialchars($post['meta_title'] ?: $post['title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($post['meta_description'] ?: $post['summary']) ?>">
    <meta property="og:url" content="<?= SITE_URL ?>/blog/post/<?= htmlspecialchars($post['slug']) ?>">
    <meta property="og:image" content="<?= $post['image_url'] ? SITE_URL . '/' . htmlspecialchars($post['image_url']) : '' ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="<?= htmlspecialchars($post['meta_title'] ?: $post['title']) ?>">
    <meta property="twitter:description" content="<?= htmlspecialchars($post['meta_description'] ?: $post['summary']) ?>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Style standard rich text classes that CKEditor generates inside post body */
        .blog-body-content p { margin-bottom: 1.25rem; line-height: 1.75; }
        .blog-body-content h2 { font-size: 1.5rem; font-weight: 800; margin-top: 2rem; margin-bottom: 1rem; color: #0f172a; }
        .blog-body-content h3 { font-size: 1.25rem; font-weight: 700; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #1e293b; }
        .blog-body-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.25rem; }
        .blog-body-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1.25rem; }
        .blog-body-content li { margin-bottom: 0.5rem; }
        .blog-body-content strong { font-weight: 700; color: #0f172a; }
        .blog-body-content a { color: #2563eb; text-decoration: underline; font-weight: 600; }
        .blog-body-content a:hover { color: #1d4ed8; }
        .blog-body-content blockquote { border-left: 4px solid #cbd5e1; padding-left: 1rem; italic; color: #475569; margin-bottom: 1.25rem; }
        .blog-body-content img { border-radius: 12px; margin: 1.5rem auto; max-width: 100%; height: auto; display: block; border: 1px solid #e2e8f0; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    <!-- Header Navigation -->
    <div class="sticky top-4 z-50 max-w-7xl mx-auto px-4 sm:px-6">
        <nav class="bg-white/80 backdrop-blur-md border border-slate-200/50 px-6 py-3.5 flex justify-between items-center rounded-2xl shadow-sm hover:shadow-md transition duration-300">
            <a href="/" class="flex items-center gap-3">
                <img src="/image/logo-app-vmax45.png" alt="VMAX Logo" class="h-10 w-auto object-contain">
            </a>
            <div class="hidden md:flex gap-8 font-semibold text-slate-600 text-sm">
                <a href="/" class="relative py-1 hover:text-blue-600 transition group">
                    Trang Chủ
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <div class="relative group">
                    <a href="/products" class="relative py-1 hover:text-blue-600 transition flex items-center gap-1 group font-bold">
                        Sản Phẩm
                        <svg class="w-3.5 h-3.5 transition duration-200 group-hover:rotate-180 text-slate-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <!-- Premium Glassmorphic Mega Menu Dropdown -->
                    <div class="absolute left-1/2 -translate-x-1/2 mt-4 w-[960px] bg-white border border-slate-200/60 rounded-[28px] shadow-[0_20px_50px_rgba(0,0,0,0.08)] opacity-0 invisible translate-y-2 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 z-50 p-7 flex flex-col gap-6">
                        
                        <div class="flex gap-8">
                            <!-- Left Section: Combo Packages -->
                            <div class="w-[380px] shrink-0 flex flex-col gap-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Combo Ưu Đãi Đặc Biệt</span>
                                    <span class="px-2 py-0.5 text-[8px] font-bold bg-rose-50 text-rose-600 rounded-full animate-pulse">SIÊU HỜI</span>
                                </div>
                                
                                <div class="flex flex-col gap-3">
                                    <?php
                                    $allMenuProds = (new ProductModel())->getAllActiveProducts();
                                    $menuCombos = array_filter($allMenuProds, function($p) { return $p['is_combo'] == 1 && $p['show_in_menu'] == 1; });
                                    $menuSingles = array_filter($allMenuProds, function($p) { return $p['is_combo'] == 0 && $p['show_in_menu'] == 1; });
                                    
                                    if (empty($menuCombos)) {
                                        $menuCombos = [
                                            ['name' => 'Combo Marketing Facebook', 'short_description' => 'Bộ 3 phần mềm nuôi nick, seeding, spam tin nhắn Facebook', 'image_url' => 'image/sanphamfb.webp', 'slug' => 'combo-marketing-facebook', 'price_monthly' => 500000],
                                            ['name' => 'Combo Phú Quý', 'short_description' => 'Bộ 4 công cụ marketing đa kênh tự động hàng đầu', 'image_url' => 'image/sanphamsp.webp', 'slug' => 'combo-phu-quy', 'price_monthly' => 600000]
                                        ];
                                    }
                                    
                                    $comboIndex = 0;
                                    foreach ($menuCombos as $cObj):
                                        $comboIndex++;
                                        $bgGrad = $comboIndex % 2 === 1 
                                            ? 'from-blue-600 via-indigo-600 to-violet-700 hover:shadow-blue-500/20' 
                                            : 'from-emerald-600 via-teal-600 to-cyan-700 hover:shadow-emerald-500/20';
                                    ?>
                                        <a href="/products" class="relative overflow-hidden rounded-[20px] h-[120px] bg-gradient-to-r <?= $bgGrad ?> text-white p-5 flex flex-col justify-between group/combo hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                                            <div class="absolute -right-4 -top-4 w-28 h-28 bg-white/10 rounded-full blur-2xl transition duration-500 group-hover/combo:scale-125"></div>
                                            <div class="z-10 max-w-[220px]">
                                                <h4 class="font-extrabold text-[12px] tracking-wide uppercase leading-tight"><?= htmlspecialchars($cObj['name']) ?></h4>
                                                <p class="text-[9px] text-white/85 line-clamp-2 mt-1 leading-normal"><?= htmlspecialchars($cObj['short_description']) ?></p>
                                            </div>
                                            <div class="z-10 flex items-center gap-1.5 mt-1">
                                                <span class="text-[9px] font-bold bg-white/20 px-2 py-0.5 rounded-full">Tiết kiệm 45%</span>
                                            </div>
                                            <?php if (!empty($cObj['image_url'])): ?>
                                                <img src="/<?= htmlspecialchars($cObj['image_url']) ?>" alt="<?= htmlspecialchars($cObj['name']) ?>" class="absolute right-3 bottom-2 h-[100px] w-auto object-contain transition duration-500 group-hover/combo:scale-110 z-0">
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Right Section: Individual Software Products -->
                            <div class="flex-1 border-l border-slate-100 pl-8 flex flex-col gap-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Danh Sách Phần Mềm Lẻ</span>
                                    <span class="text-[10px] text-blue-600 font-semibold hover:underline flex items-center gap-0.5">Mới Cập Nhật <span class="w-1.5 h-1.5 bg-blue-600 rounded-full inline-block animate-ping"></span></span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3.5 max-h-[225px] overflow-y-auto pr-1">
                                    <?php
                                    if (empty($menuSingles)) {
                                        $menuSingles = [
                                            ['name' => 'LushLogin', 'short_description' => 'Trình duyệt chống dò quét vân tay antidetect bảo mật cao', 'logo_url' => ''],
                                            ['name' => 'V-AutoPost', 'short_description' => 'Phần mềm tự động hóa marketing, đăng bài đa luồng', 'logo_url' => '']
                                        ];
                                    }
                                    
                                    foreach ($menuSingles as $sObj):
                                        $isHot = in_array(strtolower($sObj['name']), ['lushlogin', 'v-autopost']);
                                    ?>
                                        <a href="/products" class="flex items-start gap-3.5 p-3 rounded-2xl hover:bg-slate-50/80 border border-transparent hover:border-slate-100 hover:shadow-[0_4px_12px_rgba(0,0,0,0.02)] transition-all duration-200 group/prod">
                                            <?php if (!empty($sObj['logo_url'])): ?>
                                                <img src="/<?= htmlspecialchars($sObj['logo_url']) ?>" alt="logo" class="w-10 h-10 object-contain rounded-xl shrink-0 group-hover/prod:scale-105 transition duration-200">
                                            <?php else: ?>
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 text-blue-600 rounded-xl flex items-center justify-center font-extrabold text-sm shrink-0 group-hover/prod:from-blue-600 group-hover/prod:to-indigo-600 group-hover/prod:text-white transition duration-200">
                                                    <?= strtoupper(substr($sObj['name'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="truncate">
                                                <div class="flex items-center gap-1.5">
                                                    <h5 class="font-bold text-[13px] text-slate-800 group-hover/prod:text-blue-600 transition duration-150"><?= htmlspecialchars($sObj['name']) ?></h5>
                                                    <?php if ($isHot): ?>
                                                        <span class="px-1.5 py-0.5 text-[8px] font-extrabold bg-blue-50 text-blue-600 rounded uppercase">HOT</span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-[10px] text-slate-400 whitespace-normal line-clamp-2 mt-1 leading-normal"><?= htmlspecialchars($sObj['short_description']) ?></p>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mega Menu Footer Band -->
                        <div class="border-t border-slate-100 pt-4 flex items-center justify-between text-xs text-slate-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Bảo mật tuyệt đối, tự động cập nhật trọn đời
                            </span>
                            <div class="flex gap-4">
                                <a href="/pricing" class="hover:text-blue-600 transition font-medium flex items-center gap-0.5">Bảng giá dịch vụ <span class="group-hover:translate-x-0.5 transition">→</span></a>
                                <a href="/products" class="text-blue-600 hover:text-blue-700 transition font-bold flex items-center gap-0.5">Xem tất cả sản phẩm <span class="group-hover:translate-x-0.5 transition">→</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="/pricing" class="relative py-1 hover:text-blue-600 transition group">
                    Bảng Giá
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/downloads" class="relative py-1 hover:text-blue-600 transition group">
                    Tải Xuống
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/blog" class="relative py-1 hover:text-blue-600 transition group font-bold text-blue-600">
                    Tin Tức
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600"></span>
                </a>
                <a href="/contact" class="relative py-1 hover:text-blue-600 transition group">
                    Liên Hệ
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </div>
            <div class="flex gap-4 items-center">
                <?php if (Auth::check()): ?>
                    <a href="/dashboard" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-blue-500/20 hover:scale-[1.02] active:scale-[0.98] transition duration-200 text-sm">Bảng Điều Khiển</a>
                <?php else: ?>
                    <a href="/login" class="text-slate-600 font-semibold hover:text-blue-600 transition text-sm px-3 py-2 rounded-xl hover:bg-slate-50">Đăng Nhập</a>
                    <a href="/register" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-blue-500/20 hover:scale-[1.02] active:scale-[0.98] transition duration-200 text-sm">Đăng Ký</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>

    <!-- Main Container (2 Columns layout: Post details & Related posts sidebar) -->
    <main class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid lg:grid-cols-12 gap-10">
            
            <!-- Left Side: Article Body (Col 8) -->
            <div class="lg:col-span-8 space-y-8">
                <article class="bg-white border border-slate-200/60 rounded-3xl p-6 md:p-10 shadow-sm">
                    
                    <!-- Header Info -->
                    <header class="mb-8 border-b border-slate-100 pb-6">
                        <div class="flex flex-wrap items-center gap-3 text-slate-400 text-xs font-semibold mb-3">
                            <span>Tác giả: <strong><?= htmlspecialchars($post['author_name']) ?></strong></span>
                            <span>•</span>
                            <span>Đăng ngày <?= date('d/m/Y H:i', $post['created_at']) ?></span>
                            <span>•</span>
                            <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-500 font-bold">
                                <?php 
                                    $wordCount = str_word_count(strip_tags($post['content']));
                                    $readTime = ceil($wordCount / 200);
                                    echo ($readTime <= 0 ? 1 : $readTime) . ' phút đọc';
                                ?>
                            </span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight mb-4"><?= htmlspecialchars($post['title']) ?></h1>
                        <p class="text-slate-500 italic text-sm md:text-base leading-relaxed"><?= htmlspecialchars($post['summary']) ?></p>
                    </header>

                    <!-- Featured Image Banner -->
                    <?php if (!empty($post['image_url'])): ?>
                        <div class="mb-8 overflow-hidden rounded-2xl border border-slate-150 aspect-[16/9]">
                            <img src="/<?= htmlspecialchars($post['image_url']) ?>" alt="featured banner" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>
                    
                    <!-- Main Body Rich Content -->
                    <section class="blog-body-content text-slate-700 text-sm md:text-base">
                        <?= $post['content'] ?>
                    </section>
                </article>
                
                <div class="text-center pt-4">
                    <a href="/blog" class="inline-flex items-center gap-1 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition">
                        &larr; Quay lại danh sách tin tức
                    </a>
                </div>
            </div>

            <!-- Right Side: Sidebar (Col 4) -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- Related Posts Widget -->
                <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm">
                    <h4 class="font-bold text-slate-900 text-sm uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Bài Viết Khác</h4>
                    <div class="space-y-4">
                        <?php 
                        $allOtherPosts = (new BlogModel())->getPublishedPosts();
                        $relatedPosts = array_filter($allOtherPosts, function($p) use ($post) {
                            return $p['id'] !== $post['id'];
                        });
                        $relatedPosts = array_slice($relatedPosts, 0, 5);

                        if (empty($relatedPosts)): 
                        ?>
                            <p class="text-xs text-slate-400">Không có bài viết nào khác.</p>
                        <?php else: ?>
                            <?php foreach ($relatedPosts as $rp): ?>
                                <a href="/blog/post/<?= htmlspecialchars($rp['slug']) ?>" class="flex items-center gap-3 group">
                                    <?php if (!empty($rp['image_url'])): ?>
                                        <img src="/<?= htmlspecialchars($rp['image_url']) ?>" alt="thumbnail" class="w-12 h-12 object-cover rounded-lg shrink-0 border border-slate-100">
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-[10px] font-bold text-slate-400 shrink-0 uppercase">VMAX</div>
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <h5 class="font-bold text-xs text-slate-800 group-hover:text-blue-600 transition line-clamp-2 leading-snug"><?= htmlspecialchars($rp['title']) ?></h5>
                                        <span class="text-[10px] text-slate-400 block mt-1"><?= date('d/m/Y', $rp['created_at']) ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Promo Widget -->
                <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-700 text-white rounded-3xl p-6 shadow-lg shadow-blue-500/10 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                    <span class="px-2.5 py-0.5 bg-white/20 text-[9px] font-bold rounded-full uppercase tracking-wider">LushLogin Browser</span>
                    <h4 class="font-extrabold text-lg mt-3">Sử dụng thử Antidetect miễn phí</h4>
                    <p class="text-xs text-white/80 mt-2 leading-relaxed font-medium">Bảo vệ tài khoản quảng cáo, tài khoản sàn TMĐT của bạn khỏi checkpoint và quét thiết bị.</p>
                    <div class="mt-5 flex gap-2">
                        <a href="/downloads" class="px-4 py-2 bg-white text-blue-600 font-extrabold text-xs rounded-xl shadow hover:bg-slate-50 transition">Tải Ngay</a>
                        <a href="/pricing" class="px-4 py-2 bg-white/15 text-white border border-white/25 font-bold text-xs rounded-xl hover:bg-white/20 transition">Xem Bảng Giá</a>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <?php include BASE_PATH . '/views/layout/footer.php'; ?>
