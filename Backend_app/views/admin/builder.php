<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trình Dựng Giao Diện VMAX Builder Pro</title>
    
    <!-- GrapesJS Styles & Scripts from UNPKG CDN -->
    <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">
    <link rel="stylesheet" href="https://unpkg.com/grapesjs-preset-webpage/dist/grapesjs-preset-webpage.min.css">
    
    <script src="https://unpkg.com/grapesjs"></script>
    <script src="https://unpkg.com/grapesjs-preset-webpage"></script>
    <script src="https://unpkg.com/grapesjs-blocks-basic"></script>

    <!-- SweetAlert2 & Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body, html { margin: 0; height: 100%; overflow: hidden; background: #0f172a; color: #f8fafc; font-family: sans-serif; }
        
        /* Premium custom styling for GrapesJS editor UI to match Admin Theme */
        .gjs-one-bg { background-color: #0f172a !important; } /* Dark blue/slate background */
        .gjs-two-bg { background-color: #1e293b !important; } /* Panels background */
        .gjs-three-bg { background-color: #334155 !important; } /* Elements background */
        .gjs-four-color { color: #3b82f6 !important; } /* Accent blue */
        .gjs-four-color-h:hover { color: #60a5fa !important; }
        .gjs-pn-btn.gjs-pn-active { color: #3b82f6 !important; background-color: #1e293b !important; box-shadow: none !important; }
        
        .gjs-cv-canvas {
            width: 100%;
            height: calc(100% - 60px);
        }
        
        /* Custom styles for right panel headers */
        .gjs-sm-header, .gjs-clm-header, .gjs-block-category .gjs-title {
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: #94a3b8 !important;
            padding: 10px 15px !important;
            border-bottom: 1px solid #334155 !important;
        }
    </style>
</head>
<body class="flex flex-col h-screen">

    <!-- Builder Header Bar -->
    <header class="h-[60px] bg-slate-900 border-b border-slate-800 px-6 flex items-center justify-between shrink-0 z-40">
        <div class="flex items-center gap-3">
            <a href="/admin/setup" class="flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Quay lại
            </a>
            <div class="w-px h-4 bg-slate-800"></div>
            <div>
                <span class="text-xs font-bold text-white block">Thiết kế: <?= htmlspecialchars($page['title']) ?></span>
                <span class="text-[9px] text-blue-400 font-mono">Slug: <?= htmlspecialchars($page['slug']) ?></span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-[10px] text-slate-500 italic">Đã bật xuất bản tự động</span>
            <button onclick="saveDesign()" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-lg shadow-blue-500/20">
                Lưu Giao Diện
            </button>
        </div>
    </header>

    <!-- Editor Canvas -->
    <div id="gjs" class="flex-1"></div>

    <script>
        // Initialize GrapesJS with Webpage Preset (includes style manager, layer manager, blocks, etc.)
        const editor = grapesjs.init({
            container: '#gjs',
            fromElement: true,
            height: '100%',
            storageManager: false,
            plugins: ['gjs-preset-webpage'],
            pluginsOpts: {
                'gjs-preset-webpage': {
                    // Turn on default blocks and manager features
                    blocksBasicOpts: { flexGrid: true }
                }
            },
            // Load Tailwind CSS inside GrapesJS iframe so that style utility classes work dynamically
            canvas: {
                styles: [
                    'https://cdn.tailwindcss.com'
                ]
            }
        });

        // Load existing design database state (JSON)
        const savedJson = `<?= $page['content_json'] ?>`;
        if (savedJson && savedJson.trim() !== '') {
            try {
                editor.loadProjectData(JSON.parse(savedJson));
            } catch (e) {
                console.error("Lỗi khi tải dữ liệu thiết kế:", e);
            }
        }

        // Add Premium VMAX Tailored components to the Block Manager
        const bm = editor.BlockManager;

        // VMAX Header block
        bm.add('vmax-nav-header', {
            label: '<b>VMAX Navigation</b>',
            category: 'VMAX Components',
            content: `
                <nav class="flex items-center justify-between px-10 py-5 bg-slate-900 text-white font-sans">
                    <a href="#" class="text-xl font-black tracking-tight text-white">VMAX<span class="text-blue-500">SOFTWARE</span></a>
                    <div class="flex items-center gap-6">
                        <a href="#features" class="text-sm font-semibold text-slate-300 hover:text-white transition">Tính năng</a>
                        <a href="#pricing" class="text-sm font-semibold text-slate-300 hover:text-white transition">Bảng giá</a>
                        <a href="#download" class="text-sm font-semibold text-slate-300 hover:text-white transition">Tải xuống</a>
                        <a href="/login" class="px-4 py-2 text-xs font-bold bg-blue-600 hover:bg-blue-700 rounded-xl transition">Đăng nhập</a>
                    </div>
                </nav>
            `
        });

        // VMAX Hero Showcase
        bm.add('vmax-hero-banner', {
            label: '<b>VMAX Hero</b>',
            category: 'VMAX Components',
            content: `
                <section class="py-24 px-6 text-center bg-gradient-to-b from-slate-900 to-slate-950 text-white font-sans">
                    <h1 class="text-5xl font-black tracking-tight mb-4">Giải Pháp Bản Quyền Phần Mềm VMAX</h1>
                    <p class="text-slate-400 text-lg max-w-2xl mx-auto mb-8">Kích hoạt nhanh chóng, bảo mật thiết bị tuyệt đối, chống lỗi HWID và IP. Trải nghiệm hệ thống SaaS tối ưu hóa cao cho các đại lý.</p>
                    <div class="flex justify-center gap-4">
                        <a href="#pricing" class="px-8 py-3.5 text-sm font-bold bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-lg shadow-blue-500/20">Bắt Đầu Ngay</a>
                        <a href="#download" class="px-8 py-3.5 text-sm font-bold bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition">Tải Xuống Tool</a>
                    </div>
                </section>
            `
        });

        // VMAX Features Grid
        bm.add('vmax-features-grid', {
            label: '<b>VMAX Features</b>',
            category: 'VMAX Components',
            content: `
                <section id="features" class="py-20 px-10 bg-white text-slate-900 font-sans">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-extrabold tracking-tight">Tính Năng Ưu Việt</h2>
                        <p class="text-slate-500 text-sm mt-2">Được thiết kế tỉ mỉ để đáp ứng mọi yêu cầu kỹ thuật khắt khe</p>
                    </div>
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="p-6 border border-slate-100 rounded-2xl shadow-sm text-center">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 font-bold">1</div>
                            <h3 class="text-base font-bold mb-2">Tự Động Reset HWID</h3>
                            <p class="text-slate-500 text-xs leading-relaxed">Người dùng có thể tự thực hiện đổi máy, đổi ổ cứng phần mềm qua trang quản lý cá nhân.</p>
                        </div>
                        <div class="p-6 border border-slate-100 rounded-2xl shadow-sm text-center">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-4 font-bold">2</div>
                            <h3 class="text-base font-bold mb-2">Kích Hoạt VietQR Tự Động</h3>
                            <p class="text-slate-500 text-xs leading-relaxed">Cộng tiền tài khoản và gia hạn license tức thì bằng mã chuyển khoản nhanh 24/7.</p>
                        </div>
                        <div class="p-6 border border-slate-100 rounded-2xl shadow-sm text-center">
                            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4 font-bold">3</div>
                            <h3 class="text-base font-bold mb-2">Đồng Bộ API C++ / C#</h3>
                            <p class="text-slate-500 text-xs leading-relaxed">Thư viện SDK tích hợp mượt mà, xác thực license trực tiếp từ server an toàn cao.</p>
                        </div>
                    </div>
                </section>
            `
        });

        // VMAX Pricing Sections
        bm.add('vmax-pricing-cards', {
            label: '<b>VMAX Pricing</b>',
            category: 'VMAX Components',
            content: `
                <section id="pricing" class="py-20 px-10 bg-slate-50 text-slate-900 font-sans">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-extrabold tracking-tight">Gói Bản Quyền Linh Hoạt</h2>
                        <p class="text-slate-500 text-sm mt-2">Lựa chọn gói dịch vụ phù hợp nhất với nhu cầu sử dụng của bạn</p>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold">Gói Starter</h3>
                                <div class="text-3xl font-black text-slate-900 mt-4 mb-2">150.000đ<span class="text-xs text-slate-400 font-normal"> / 30 ngày</span></div>
                                <ul class="space-y-2.5 text-xs text-slate-500 mt-6">
                                    <li>✓ Kích hoạt tối đa 1 PC</li>
                                    <li>✓ Tự động reset HWID (Hạn chế)</li>
                                    <li>✓ Hỗ trợ kỹ thuật qua Telegram</li>
                                </ul>
                            </div>
                            <a href="/renew" class="w-full text-center block py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs mt-8 transition">Gia Hạn Ngay</a>
                        </div>
                        <div class="bg-white p-8 rounded-2xl border-2 border-blue-500 shadow-md relative overflow-hidden flex flex-col justify-between">
                            <span class="absolute right-0 top-0 bg-blue-500 text-white text-[9px] font-bold uppercase py-1 px-3 rounded-bl-xl tracking-wider">Phổ Biến</span>
                            <div>
                                <h3 class="text-lg font-bold">Gói Premium</h3>
                                <div class="text-3xl font-black text-slate-900 mt-4 mb-2">450.000đ<span class="text-xs text-slate-400 font-normal"> / 90 ngày</span></div>
                                <ul class="space-y-2.5 text-xs text-slate-500 mt-6">
                                    <li>✓ Kích hoạt tối đa 2 PC song song</li>
                                    <li>✓ Mở khóa toàn bộ chức năng Pro</li>
                                    <li>✓ Hỗ trợ kỹ thuật ưu tiên 24/7</li>
                                </ul>
                            </div>
                            <a href="/renew" class="w-full text-center block py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs mt-8 transition">Gia Hạn Ngay</a>
                        </div>
                        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold">Gói Lifetime</h3>
                                <div class="text-3xl font-black text-slate-900 mt-4 mb-2">1.200.000đ<span class="text-xs text-slate-400 font-normal"> / Trọn đời</span></div>
                                <ul class="space-y-2.5 text-xs text-slate-550 mt-6">
                                    <li>✓ Kích hoạt vĩnh viễn không hết hạn</li>
                                    <li>✓ Reset HWID không giới hạn số lần</li>
                                    <li>✓ Cập nhật phiên bản mới miễn phí</li>
                                </ul>
                            </div>
                            <a href="/renew" class="w-full text-center block py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl text-xs mt-8 transition">Mua Trọn Đời</a>
                        </div>
                    </div>
                </section>
            `
        });

        // VMAX Stat Counters
        bm.add('vmax-stats', {
            label: '<b>VMAX Statistics</b>',
            category: 'VMAX Components',
            content: `
                <section class="py-16 px-10 bg-blue-600 text-white font-sans text-center">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-5xl mx-auto">
                        <div>
                            <div class="text-4xl font-black">10,000+</div>
                            <div class="text-xs text-blue-100 mt-2 font-semibold uppercase tracking-wider">Người Dùng Hoạt Động</div>
                        </div>
                        <div>
                            <div class="text-4xl font-black">50M+</div>
                            <div class="text-xs text-blue-100 mt-2 font-semibold uppercase tracking-wider">Lượt Xác Thực API</div>
                        </div>
                        <div>
                            <div class="text-4xl font-black">99.9%</div>
                            <div class="text-xs text-blue-100 mt-2 font-semibold uppercase tracking-wider">Uptime Máy Chủ</div>
                        </div>
                        <div>
                            <div class="text-4xl font-black">24/7</div>
                            <div class="text-xs text-blue-100 mt-2 font-semibold uppercase tracking-wider">Hỗ Trợ Đại Lý</div>
                        </div>
                    </div>
                </section>
            `
        });

        // VMAX FAQ Accordion
        bm.add('vmax-faq', {
            label: '<b>VMAX FAQ</b>',
            category: 'VMAX Components',
            content: `
                <section class="py-20 px-10 bg-white text-slate-900 font-sans max-w-4xl mx-auto">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-extrabold tracking-tight">Câu Hỏi Thường Gặp</h2>
                        <p class="text-slate-500 text-sm mt-2">Một số thông tin cơ bản giúp bạn nhanh chóng làm quen hệ thống</p>
                    </div>
                    <div class="space-y-4">
                        <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50">
                            <h4 class="text-sm font-bold text-slate-800 mb-2">Tôi có thể tự reset HWID không?</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Có. Hệ thống tự động cung cấp công cụ tự reset thiết bị liên kết trong trang cá nhân của người dùng mà không cần chờ đợi admin duyệt thủ công.</p>
                        </div>
                        <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50">
                            <h4 class="text-sm font-bold text-slate-800 mb-2">Hình thức thanh toán nạp tiền như thế nào?</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Bạn có thể tạo yêu cầu nạp tiền, quét mã VietQR chuyển khoản nhanh 24/7. Tiền ví sẽ được cộng tự động trong vòng 1-3 phút.</p>
                        </div>
                    </div>
                </section>
            `
        });

        // VMAX Contact block
        bm.add('vmax-contact', {
            label: '<b>VMAX Contact Form</b>',
            category: 'VMAX Components',
            content: `
                <section class="py-20 px-10 bg-slate-50 text-slate-900 font-sans">
                    <div class="grid md:grid-cols-2 gap-10 max-w-5xl mx-auto">
                        <div>
                            <h2 class="text-3xl font-extrabold tracking-tight mb-4">Liên Hệ Hỗ Trợ</h2>
                            <p class="text-slate-550 text-xs leading-relaxed mb-6">Nếu bạn gặp bất kỳ vấn đề gì về bản quyền phần mềm, hãy gửi yêu cầu hỗ trợ hoặc liên hệ trực tiếp cho chúng tôi qua Telegram/Email.</p>
                            <div class="text-xs text-slate-600 space-y-2">
                                <div><strong>Email:</strong> support@vmaxtech.vn</div>
                                <div><strong>Telegram:</strong> @vmax_support_bot</div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-2">Họ & Tên</label>
                                <input type="text" placeholder="Nguyễn Văn A" class="w-full px-3 py-2 border rounded-xl text-xs outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-2">Nội dung tin nhắn</label>
                                <textarea rows="3" placeholder="Nhập câu hỏi của bạn..." class="w-full px-3 py-2 border rounded-xl text-xs outline-none resize-none"></textarea>
                            </div>
                            <button class="w-full py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition">Gửi Lời Nhắn</button>
                        </div>
                    </div>
                </section>
            `
        });

        // VMAX Footer
        bm.add('vmax-nav-footer', {
            label: '<b>VMAX Footer</b>',
            category: 'VMAX Components',
            content: `
                <footer class="py-12 px-10 bg-slate-900 text-slate-400 text-center font-sans border-t border-slate-800">
                    <p class="text-sm font-bold text-white mb-2">VMAX Software Solutions</p>
                    <p class="text-xs max-w-md mx-auto mb-6">Nền tảng quản lý giấy phép bản quyền phần mềm SaaS tự động hóa tốt nhất.</p>
                    <div class="text-[10px] text-slate-500">© 2026 VMAX Software Solutions. Bảo lưu mọi quyền.</div>
                </footer>
            `
        });

        // Action to save design back to server
        function saveDesign() {
            Swal.fire({
                title: 'Đang Lưu Giao Diện...',
                didOpen: () => { Swal.showLoading(); }
            });

            const html = editor.getHtml();
            const css = editor.getCss();
            const json = JSON.stringify(editor.getProjectData());

            // Build full HTML with the dynamic styles
            const fullHtml = html;

            const formData = new FormData();
            formData.append('json', json);
            formData.append('html', html);
            formData.append('css', css);
            formData.append('is_published', 1);

            fetch('/admin/setup/builder?page_id=<?= $page['id'] ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã Lưu Thiết Kế!',
                        text: 'Trang của bạn đã được cập nhật thành công.',
                        confirmButtonText: 'Đóng'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi Lưu Trang',
                        text: data.message || 'Có lỗi xảy ra.'
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi Kết Nối',
                    text: 'Không thể kết nối đến server. Vui lòng kiểm tra lại.'
                });
            });
        }
    </script>
</body>
</html>
