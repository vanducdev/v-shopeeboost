<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin Tức & Hướng Dẫn Kỹ Thuật - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900">

    <?php include BASE_PATH . '/views/layout/navbar.php'; ?>
<!-- Header Hero Section -->
    <header class="max-w-7xl mx-auto px-6 py-16 text-center">
        <span class="inline-flex px-3.5 py-1.5 bg-blue-50 border border-blue-100 text-blue-600 text-xs font-bold rounded-full uppercase tracking-wider mb-4 animate-pulse">
            VMAX Tech Blog
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">Tin Tức & Hướng Dẫn Kỹ Thuật</h1>
        <p class="text-slate-600 max-w-2xl mx-auto leading-relaxed text-sm md:text-base">Chia sẻ kinh nghiệm MMO, hướng dẫn tối ưu vân tay trình duyệt antidetect, nuôi tài khoản đa nền tảng và cập nhật tính năng mới.</p>
    </header>

    <!-- Main Container Layout (2 Columns: Content & Sidebar) -->
    <main class="max-w-7xl mx-auto px-6 pb-24">
        <?php if (empty($posts)): ?>
            <div class="bg-white border border-slate-200/60 rounded-3xl p-16 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2m2 2a2 2 0 012 2v8a2 2 0 01-2 2h-3"></path></svg>
                <p class="font-bold text-slate-500">Chưa có bài đăng nào được xuất bản</p>
                <p class="text-xs text-slate-400 mt-1">Vui lòng quay lại sau hoặc đăng bài mới từ trang Admin.</p>
            </div>
        <?php else: 
            $featuredPost = $posts[0];
            $otherPosts = array_slice($posts, 1);
        ?>
            <div class="grid lg:grid-cols-12 gap-10">
                <!-- Left Side: Main Column (Col 8) -->
                <div class="lg:col-span-8 space-y-10">
                    
                    <!-- 1. Featured Hero Post (Latest Post) -->
                    <article class="bg-white border border-slate-200/60 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition duration-300">
                        <a href="/blog/post/<?= htmlspecialchars($featuredPost['slug']) ?>" class="block relative aspect-[21/9] overflow-hidden bg-slate-100">
                            <?php if (!empty($featuredPost['image_url'])): ?>
                                <img src="/<?= htmlspecialchars($featuredPost['image_url']) ?>" alt="featured banner" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-tr from-blue-600/10 to-indigo-600/10 flex items-center justify-center text-blue-600 font-extrabold text-2xl">VMAX SOFTWARE</div>
                            <?php endif; ?>
                            <span class="absolute top-4 left-4 px-3 py-1 bg-blue-600 text-white text-[10px] font-bold rounded-full uppercase tracking-wider">MỚI NHẤT</span>
                        </a>
                        <div class="p-8">
                            <div class="flex items-center gap-3 text-slate-400 text-xs font-semibold mb-3">
                                <span>Tác giả: <strong><?= htmlspecialchars($featuredPost['author_name']) ?></strong></span>
                                <span>•</span>
                                <span><?= date('d/m/Y', $featuredPost['created_at']) ?></span>
                            </div>
                            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-3 hover:text-blue-600 transition">
                                <a href="/blog/post/<?= htmlspecialchars($featuredPost['slug']) ?>"><?= htmlspecialchars($featuredPost['title']) ?></a>
                            </h2>
                            <p class="text-slate-600 text-sm md:text-base leading-relaxed mb-6 line-clamp-3"><?= htmlspecialchars($featuredPost['summary']) ?></p>
                            <a href="/blog/post/<?= htmlspecialchars($featuredPost['slug']) ?>" class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 font-bold text-sm">
                                Đọc tiếp bài viết <span>&rarr;</span>
                            </a>
                        </div>
                    </article>

                    <!-- 2. Other Posts Grid -->
                    <?php if (!empty($otherPosts)): ?>
                        <div class="grid md:grid-cols-2 gap-6">
                            <?php foreach ($otherPosts as $pt): ?>
                                <article class="bg-white border border-slate-200/60 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between">
                                    <div>
                                        <a href="/blog/post/<?= htmlspecialchars($pt['slug']) ?>" class="block aspect-[16/10] overflow-hidden bg-slate-100">
                                            <?php if (!empty($pt['image_url'])): ?>
                                                <img src="/<?= htmlspecialchars($pt['image_url']) ?>" alt="post thumbnail" class="w-full h-full object-cover hover:scale-105 transition duration-500">
                                            <?php else: ?>
                                                <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300 font-bold text-lg">VMAX</div>
                                            <?php endif; ?>
                                        </a>
                                        <div class="p-6">
                                            <div class="flex items-center gap-2 text-slate-400 text-[11px] font-semibold mb-2">
                                                <span><?= date('d/m/Y', $pt['created_at']) ?></span>
                                                <span>•</span>
                                                <span><?= htmlspecialchars($pt['author_name']) ?></span>
                                            </div>
                                            <h3 class="font-extrabold text-slate-900 text-lg hover:text-blue-600 transition line-clamp-2 leading-snug">
                                                <a href="/blog/post/<?= htmlspecialchars($pt['slug']) ?>"><?= htmlspecialchars($pt['title']) ?></a>
                                            </h3>
                                            <p class="text-slate-500 text-xs mt-2 line-clamp-2 leading-relaxed"><?= htmlspecialchars($pt['summary']) ?></p>
                                        </div>
                                    </div>
                                    <div class="px-6 pb-6 pt-0">
                                        <a href="/blog/post/<?= htmlspecialchars($pt['slug']) ?>" class="text-blue-600 hover:text-blue-700 font-bold text-xs flex items-center gap-1">
                                            Đọc chi tiết <span>&rarr;</span>
                                        </a>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Right Side: Sidebar Column (Col 4) -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Widget 1: About VMAX Tech Blog -->
                    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm">
                        <h4 class="font-bold text-slate-900 text-sm uppercase tracking-wider mb-3 border-b border-slate-100 pb-2">Về Chúng Tôi</h4>
                        <p class="text-slate-650 text-xs leading-relaxed">Cổng thông tin chính thức của VMAX Software. Nơi cập nhật các kỹ thuật, kiến thức nâng cao về trình duyệt chống dò vân tay (antidetect browser), tự động hóa quy trình nuôi nick tài khoản phục vụ MMO & TMĐT chuyên nghiệp.</p>
                    </div>

                    <!-- Widget 2: Recent Posts List -->
                    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm">
                        <h4 class="font-bold text-slate-900 text-sm uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Xem Nhiều Nhất</h4>
                        <div class="space-y-4">
                            <?php 
                            $recentWidgets = array_slice($posts, 0, 5);
                            foreach ($recentWidgets as $rp): 
                            ?>
                                <a href="/blog/post/<?= htmlspecialchars($rp['slug']) ?>" class="flex items-center gap-3.5 group">
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
                        </div>
                    </div>

                    <!-- Widget 3: Premium CTA Promo -->
                    <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-700 text-white rounded-3xl p-6 shadow-lg shadow-blue-500/10 relative overflow-hidden">
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                        <span class="px-2.5 py-0.5 bg-white/20 text-[9px] font-bold rounded-full uppercase tracking-wider">Hệ Sinh Thái VMAX</span>
                        <h4 class="font-extrabold text-lg mt-3">Quản lý hàng ngàn tài khoản an toàn tuyệt đối</h4>
                        <p class="text-xs text-white/80 mt-2 leading-relaxed">Tải ngay phần mềm Antidetect Browser LushLogin với hệ thống vân tay trình duyệt thật chống khóa nick.</p>
                        <div class="mt-5 flex gap-2 flex-wrap">
                            <a href="/downloads" class="px-4 py-2 bg-white text-blue-600 font-extrabold text-xs rounded-xl shadow hover:bg-slate-50 hover:scale-[1.02] transition">Tải Ngay</a>
                            <a href="/pricing" class="px-4 py-2 bg-white/15 text-white border border-white/25 font-bold text-xs rounded-xl hover:bg-white/20 transition">Xem Bảng Giá</a>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php include BASE_PATH . '/views/layout/footer.php'; ?>
