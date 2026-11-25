<?php
$pageTitle = 'Liên Hệ Hỗ Trợ - VMAX Software';
$useTailwind = true;
include BASE_PATH . '/views/layout/header.php';
?>


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
                        <!-- Left Section: Combo Packages (Dynamic & Visually Striking) -->
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
                                        ['name' => 'Combo Marketing Facebook', 'short_description' => 'Bộ 3 phần mềm nuôi nick, seeding, spam tin nhắn Facebook', 'image_url' => 'image/sanphamfb.png', 'slug' => 'combo-marketing-facebook', 'price_monthly' => 500000],
                                        ['name' => 'Combo Phú Quý', 'short_description' => 'Bộ 4 công cụ marketing đa kênh tự động hàng đầu', 'image_url' => 'image/sanphamsp.png', 'slug' => 'combo-phu-quy', 'price_monthly' => 600000]
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
                        
                        <!-- Right Section: Individual Software Products (Elegant Grid) -->
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
                <a href="/blog" class="relative py-1 hover:text-blue-600 transition group">
                    Tin Tức
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/contact" class="relative py-1 text-blue-600 font-bold transition group">
                    Liên Hệ
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600"></span>
                </a>
            </div>
            <div class="flex gap-4 items-center">
                <a href="/login" class="text-slate-600 font-semibold hover:text-blue-600 transition text-sm px-3 py-2 rounded-xl hover:bg-slate-50">Đăng Nhập</a>
                <a href="/register" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-blue-500/20 hover:scale-[1.02] active:scale-[0.98] transition duration-200 text-sm">Đăng Ký</a>
            </div>
        </nav>
    </div>

    <!-- Contact Page Header -->
    <header class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">
            Liên Hệ Hỗ Trợ Đội Ngũ VMAX
        </h1>
        <p class="text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Bạn có câu hỏi, phản hồi hay cần hỗ trợ kỹ thuật? Đội ngũ VMAX luôn sẵn sàng đồng hành cùng bạn 24/7.
        </p>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-4xl mx-auto px-6 pb-20">
        <div class="grid md:grid-cols-2 gap-8 items-stretch">
            
            <!-- Contact Card: Zalo Support (Premium Style) -->
            <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-700 rounded-3xl p-8 text-white shadow-xl flex flex-col justify-between hover:scale-[1.01] transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div>
                    <span class="px-3 py-1 bg-white/20 text-xs font-bold rounded-full uppercase tracking-wider">Phản Hồi Nhanh</span>
                    <h2 class="text-3xl font-extrabold mt-4 mb-2">Hỗ Trợ Qua Zalo</h2>
                    <p class="text-white/80 text-sm leading-relaxed mb-6">
                        Quét mã hoặc nhấn trực tiếp để kết nối với bộ phận chăm sóc khách hàng qua Zalo. Nhận hỗ trợ kỹ thuật cài đặt phần mềm và mua mã License Key bản quyền siêu tốc.
                    </p>
                    
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur border border-white/20 p-4 rounded-2xl mb-6">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center font-black text-blue-600 text-xl shrink-0">
                            Z
                        </div>
                        <div>
                            <p class="text-xs text-white/60 font-medium">Số điện thoại Zalo</p>
                            <p class="text-lg font-bold">0359.261.551</p>
                        </div>
                    </div>
                </div>
                
                <a href="https://zalo.me/0359261551" target="_blank" class="w-full text-center py-4 bg-white text-blue-700 font-extrabold rounded-2xl shadow-lg hover:bg-slate-50 active:scale-[0.98] transition-all duration-200">
                    Nhắn Tin Qua Zalo Ngay
                </a>
            </div>

            <!-- Contact Card: Email & Website -->
            <div class="bg-white border border-slate-200/60 rounded-3xl p-8 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                <div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full uppercase tracking-wider">Thông Tin Khác</span>
                    <h2 class="text-2xl font-bold text-slate-800 mt-4 mb-4">Các Kênh Liên Hệ Khác</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-slate-50 text-slate-600 border border-slate-100 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-semibold">Email hỗ trợ</p>
                                <p class="text-sm font-bold text-slate-700"><a href="mailto:support@vmaxtech.vn" class="text-blue-600 hover:underline">support@vmaxtech.vn</a></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-slate-50 text-slate-600 border border-slate-100 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-semibold">Địa chỉ trụ sở chính</p>
                                <p class="text-sm font-bold text-slate-700">Tòa nhà VMAX Technology, Hà Nội, Việt Nam</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-slate-50 text-slate-600 border border-slate-100 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-semibold">Trang web chính thức</p>
                                <a href="https://app.vmaxtech.vn/" target="_blank" class="text-sm font-bold text-blue-600 hover:underline">https://app.vmaxtech.vn</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="pt-6 border-t border-slate-100 text-xs text-slate-400 text-center">
                    Giờ làm việc: 08:00 - 23:00 hàng ngày (kể cả Thứ Bảy, Chủ Nhật)
                </div>
            </div>
            
        </div>

        <!-- Contact Form Card -->
        <div class="mt-10 bg-white border border-slate-200/60 rounded-3xl p-6 md:p-10 shadow-sm hover:shadow-md transition-all duration-300">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Gửi Câu Hỏi Cho Chúng Tôi</h3>
            <p class="text-xs text-slate-500 mb-6">Để lại thông tin và câu hỏi của bạn, đội ngũ VMAX sẽ phản hồi qua email trong thời gian sớm nhất.</p>
            
            <form class="space-y-4" onsubmit="handleContactSubmit(event)">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5" for="contact-name">Họ và tên</label>
                        <input type="text" id="contact-name" name="name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:border-blue-600 focus:bg-white transition duration-200" placeholder="Nguyễn Văn A">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5" for="contact-email">Địa chỉ Email</label>
                        <input type="email" id="contact-email" name="email" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:border-blue-600 focus:bg-white transition duration-200" placeholder="nguyenvana@gmail.com">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5" for="contact-message">Nội dung câu hỏi / yêu cầu tư vấn</label>
                    <textarea id="contact-message" name="message" required rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:border-blue-600 focus:bg-white transition duration-200" placeholder="Tôi cần tư vấn thêm về phần mềm..."></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-2xl transition duration-200 shadow-md hover:shadow-lg hover:shadow-blue-500/20 active:scale-[0.98] cursor-pointer">
                        Gửi Tin Nhắn &rarr;
                    </button>
                </div>
                <div id="contact-success-msg" class="hidden text-emerald-600 font-bold text-xs mt-2 bg-emerald-50 border border-emerald-100 p-3 rounded-xl">
                    <span>🎉 Gửi tin nhắn thành công! Đội ngũ hỗ trợ sẽ phản hồi bạn qua email sớm nhất.</span>
                </div>
            </form>
        </div>

        <!-- Form Handler Script -->
        <script>
        function handleContactSubmit(event) {
            event.preventDefault();
            const successMsg = document.getElementById('contact-success-msg');
            if (successMsg) {
                successMsg.classList.remove('hidden');
                event.target.reset();
                setTimeout(() => {
                    successMsg.classList.add('hidden');
                }, 5000);
            }
        }
        </script>
        
    </main>

<?php include BASE_PATH . '/views/layout/footer.php'; ?>
