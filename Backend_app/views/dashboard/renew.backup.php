<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mua & Gia Hạn Key Bản Quyền - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .tab-btn-active {
            background-color: #3b82f6;
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Client Sidebar -->
    <?php require BASE_PATH . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-6 md:p-10 max-w-7xl mx-auto w-full overflow-x-hidden">
        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8 pb-6 border-b border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mua & Gia Hạn Key Bản Quyền</h1>
                <p class="text-slate-500 text-xs mt-1 font-medium">Sở hữu hoặc gia hạn các gói bản quyền phần mềm VMAX chính hãng.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-50 text-emerald-700 text-xs font-extrabold rounded-2xl border border-emerald-100/50 shadow-sm">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    Số dư: <span id="userBalanceText"><?= number_format($user['balance'] ?? 0, 0, ',', '.') ?></span>đ
                </span>
            </div>
        </header>

        <!-- Product Categories Tab Bar -->
        <div class="flex p-1.5 bg-slate-100 border border-slate-200/30 rounded-2xl gap-1 mb-8 max-w-xl shadow-inner">
            <button onclick="switchProductTab('vmaxlogin')" id="prodTabBtn-vmaxlogin" class="flex-1 py-3 text-xs font-extrabold rounded-xl transition duration-200 tab-btn-active">
                VmaxLogin
            </button>
            <button onclick="switchProductTab('v-autopost')" id="prodTabBtn-v-autopost" class="flex-1 py-3 text-xs font-extrabold rounded-xl transition duration-200 text-slate-500 hover:text-slate-700">
                V-AutoPost
            </button>
            <button onclick="switchProductTab('v-shopeeboost-pro')" id="prodTabBtn-v-shopeeboost-pro" class="flex-1 py-3 text-xs font-extrabold rounded-xl transition duration-200 text-slate-500 hover:text-slate-700">
                V-ShopeeBoost Pro
            </button>
        </div>

        <?php
        $db = DB::getInstance();
        $stmt = $db->query("SELECT * FROM products WHERE slug IN ('vmaxlogin', 'v-autopost', 'v-shopeeboost-pro')");
        $prodsList = $stmt->fetchAll();
        $productsMap = [];
        foreach ($prodsList as $pr) { $productsMap[$pr['slug']] = $pr; }

        $features = [
            'vmaxlogin' => ['Trình duyệt chống vân tay (Antidetect)', 'Tạo hồ sơ độc lập, cookie & IP riêng biệt', 'Hỗ trợ cấu hình Proxy đa luồng nhanh', 'Quản lý hàng ngàn tài khoản Facebook, Google'],
            'v-autopost' => ['Đăng bài tự động đa luồng chuyên nghiệp', 'Tương tác Fanpage, Group, Lướt bảng tin', 'Spam tin nhắn, gửi tin quảng cáo hàng loạt', 'Hỗ trợ hẹn giờ lên lịch đăng thông minh'],
            'v-shopeeboost-pro' => ['Tự động đẩy sản phẩm lên top tìm kiếm', 'Tăng lượt tim sản phẩm & follow tự động', 'Quét từ khóa tối ưu hóa tiêu đề sản phẩm', 'Quản lý nhiều gian hàng Shopee tập trung']
        ];

        $slugs = ['vmaxlogin', 'v-autopost', 'v-shopeeboost-pro'];
        foreach ($slugs as $idx => $slug):
            $product = $productsMap[$slug] ?? null;
            if (!$product) continue;
            $prodLicenses = array_filter($licenses, function($lic) use ($product) { return $lic['product_id'] == $product['id']; });
            $prodFeatures = $features[$slug] ?? [];
        ?>
            <div id="prodTab-<?= $slug ?>" class="product-pane <?= $idx === 0 ? '' : 'hidden' ?> grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm hover:shadow-md transition duration-300">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <span class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            </span>
                            <div>
                                <h2 class="text-xs font-bold text-slate-850 uppercase tracking-widest">Mua mã Key bản quyền mới</h2>
                                <p class="text-[10px] text-slate-400 mt-1 font-semibold">Khởi tạo key bản quyền sạch, kích hoạt tự động.</p>
                            </div>
                        </div>
                        <form onsubmit="handleTransaction(event, 'buy', <?= $product['id'] ?>, '<?= $slug ?>')" class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-4">Chọn gói thời hạn bản quyền</label>
                                <input type="hidden" name="package" id="pkg-buy-<?= $slug ?>" required>
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <button type="button" onclick="selectPackage('buy', '<?= $slug ?>', 'monthly', <?= $product['price_monthly'] ?>)" class="pkg-card-buy-<?= $slug ?>-monthly text-left p-4 border border-slate-100 rounded-2xl hover:border-blue-500/30 hover:bg-slate-50/50 transition flex flex-col justify-between h-[96px]">
                                        <div class="flex justify-between w-full items-start">
                                            <span class="text-[11px] font-bold text-slate-800">Gói 1 Tháng</span>
                                            <span class="text-[9px] bg-slate-50 text-slate-500 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Tiêu chuẩn</span>
                                        </div>
                                        <span class="font-extrabold text-slate-900 text-sm"><?= number_format($product['price_monthly'], 0, ',', '.') ?>đ</span>
                                    </button>
                                    <button type="button" onclick="selectPackage('buy', '<?= $slug ?>', 'quarterly', <?= $product['price_quarterly'] ?>)" class="pkg-card-buy-<?= $slug ?>-quarterly text-left p-4 border border-slate-100 rounded-2xl hover:border-blue-500/30 hover:bg-slate-50/50 transition flex flex-col justify-between h-[96px]">
                                        <div class="flex justify-between w-full items-start">
                                            <span class="text-[11px] font-bold text-slate-800">Gói 3 Tháng</span>
                                            <span class="text-[9px] bg-emerald-50 text-emerald-600 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Tiết kiệm</span>
                                        </div>
                                        <span class="font-extrabold text-slate-900 text-sm"><?= number_format($product['price_quarterly'], 0, ',', '.') ?>đ</span>
                                    </button>
                                    <button type="button" onclick="selectPackage('buy', '<?= $slug ?>', 'yearly', <?= $product['price_yearly'] ?>)" class="pkg-card-buy-<?= $slug ?>-yearly text-left p-4 border border-slate-100 rounded-2xl hover:border-blue-500/30 hover:bg-slate-50/50 transition flex flex-col justify-between h-[96px]">
                                        <div class="flex justify-between w-full items-start">
                                            <span class="text-[11px] font-bold text-slate-800">Gói 1 Năm</span>
                                            <span class="text-[9px] bg-amber-50 text-amber-600 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Phổ biến</span>
                                        </div>
                                        <span class="font-extrabold text-slate-900 text-sm"><?= number_format($product['price_yearly'], 0, ',', '.') ?>đ</span>
                                    </button>
                                    <button type="button" onclick="selectPackage('buy', '<?= $slug ?>', 'lifetime', <?= $product['price_lifetime'] ?>)" class="pkg-card-buy-<?= $slug ?>-lifetime text-left p-4 border border-slate-100 rounded-2xl hover:border-blue-500/30 hover:bg-slate-50/50 transition flex flex-col justify-between h-[96px]">
                                        <div class="flex justify-between w-full items-start">
                                            <span class="text-[11px] font-bold text-slate-800">Gói Trọn Đời</span>
                                            <span class="text-[9px] bg-purple-50 text-purple-600 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Vô hạn</span>
                                        </div>
                                        <span class="font-extrabold text-slate-900 text-sm"><?= number_format($product['price_lifetime'], 0, ',', '.') ?>đ</span>
                                    </button>
                                </div>
                            </div>
                            <div class="cost-box-buy-<?= $slug ?> bg-slate-50 border border-slate-100 p-5 rounded-2xl space-y-3 shadow-inner hidden">
                                <div class="flex justify-between text-xs text-slate-500">
                                    <span>Giá tiền:</span>
                                    <span class="font-bold text-slate-800 val-price">0đ</span>
                                </div>
                                <div class="flex justify-between text-xs border-t border-slate-200/50 pt-2.5 text-slate-500">
                                    <span>Số dư ví còn lại:</span>
                                    <span class="font-extrabold text-emerald-600 val-remaining">0đ</span>
                                </div>
                            </div>
                            <button type="submit" class="btn-submit-buy-<?= $slug ?> w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-blue-500/10">
                                Xác Nhận Mua Key Bản Quyền
                            </button>
                        </form>
                    </div>
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm hover:shadow-md transition duration-300">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                            <span class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.5"></path></svg>
                            </span>
                            <div>
                                <h2 class="text-xs font-bold text-slate-850 uppercase tracking-widest">Gia hạn Key đang sử dụng</h2>
                                <p class="text-[10px] text-slate-400 mt-1 font-semibold">Cộng dồn thêm thời hạn sử dụng cho mã Key hiện tại của bạn.</p>
                            </div>
                        </div>
                        <?php if (empty($prodLicenses)): ?>
                            <div class="py-6 text-center text-slate-400 text-xs font-medium">Bạn chưa kích hoạt mã Key nào cho sản phẩm này để có thể gia hạn.</div>
                        <?php else: ?>
                            <form onsubmit="handleTransaction(event, 'renew', null, '<?= $slug ?>')" class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Chọn mã Key muốn gia hạn</label>
                                    <select name="license_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200/60 focus:border-emerald-500 rounded-xl outline-none transition text-xs font-bold text-slate-800">
                                        <?php foreach ($prodLicenses as $lic): ?>
                                            <option value="<?= $lic['id'] ?>"><?= htmlspecialchars($lic['license_key']) ?> (Hạn: <?= date('d/m/Y', $lic['expires_at']) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-4">Chọn thời hạn gia hạn</label>
                                    <input type="hidden" name="package" id="pkg-renew-<?= $slug ?>" required>
                                    <div class="grid sm:grid-cols-2 gap-4">
                                        <button type="button" onclick="selectPackage('renew', '<?= $slug ?>', 'monthly', <?= $product['price_monthly'] ?>)" class="pkg-card-renew-<?= $slug ?>-monthly text-left p-4 border border-slate-100 rounded-2xl hover:border-emerald-500/30 hover:bg-slate-50/50 transition flex flex-col justify-between h-[96px]">
                                            <div class="flex justify-between w-full items-start">
                                                <span class="text-[11px] font-bold text-slate-800">Cộng 1 Tháng</span>
                                                <span class="text-[9px] bg-slate-50 text-slate-500 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Tiêu chuẩn</span>
                                            </div>
                                            <span class="font-extrabold text-slate-900 text-sm"><?= number_format($product['price_monthly'], 0, ',', '.') ?>đ</span>
                                        </button>
                                        <button type="button" onclick="selectPackage('renew', '<?= $slug ?>', 'quarterly', <?= $product['price_quarterly'] ?>)" class="pkg-card-renew-<?= $slug ?>-quarterly text-left p-4 border border-slate-100 rounded-2xl hover:border-emerald-500/30 hover:bg-slate-50/50 transition flex flex-col justify-between h-[96px]">
                                            <div class="flex justify-between w-full items-start">
                                                <span class="text-[11px] font-bold text-slate-800">Cộng 3 Tháng</span>
                                                <span class="text-[9px] bg-emerald-50 text-emerald-600 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Tiết kiệm</span>
                                            </div>
                                            <span class="font-extrabold text-slate-900 text-sm"><?= number_format($product['price_quarterly'], 0, ',', '.') ?>đ</span>
                                        </button>
                                        <button type="button" onclick="selectPackage('renew', '<?= $slug ?>', 'yearly', <?= $product['price_yearly'] ?>)" class="pkg-card-renew-<?= $slug ?>-yearly text-left p-4 border border-slate-100 rounded-2xl hover:border-emerald-500/30 hover:bg-slate-50/50 transition flex flex-col justify-between h-[96px]">
                                            <div class="flex justify-between w-full items-start">
                                                <span class="text-[11px] font-bold text-slate-800">Cộng 1 Năm</span>
                                                <span class="text-[9px] bg-amber-50 text-amber-600 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Phổ biến</span>
                                            </div>
                                            <span class="font-extrabold text-slate-900 text-sm"><?= number_format($product['price_yearly'], 0, ',', '.') ?>đ</span>
                                        </button>
                                        <button type="button" onclick="selectPackage('renew', '<?= $slug ?>', 'lifetime', <?= $product['price_lifetime'] ?>)" class="pkg-card-renew-<?= $slug ?>-lifetime text-left p-4 border border-slate-100 rounded-2xl hover:border-emerald-500/30 hover:bg-slate-50/50 transition flex flex-col justify-between h-[96px]">
                                            <div class="flex justify-between w-full items-start">
                                                <span class="text-[11px] font-bold text-slate-800">Cộng Trọn Đời</span>
                                                <span class="text-[9px] bg-purple-50 text-purple-600 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Vô hạn</span>
                                            </div>
                                            <span class="font-extrabold text-slate-900 text-sm"><?= number_format($product['price_lifetime'], 0, ',', '.') ?>đ</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="cost-box-renew-<?= $slug ?> bg-slate-50 border border-slate-100 p-5 rounded-2xl space-y-3 shadow-inner hidden">
                                    <div class="flex justify-between text-xs text-slate-500">
                                        <span>Giá tiền gia hạn:</span>
                                        <span class="font-bold text-slate-800 val-price">0đ</span>
                                    </div>
                                    <div class="flex justify-between text-xs border-t border-slate-200/50 pt-2.5 text-slate-500">
                                        <span>Số dư ví còn lại:</span>
                                        <span class="font-extrabold text-emerald-600 val-remaining">0đ</span>
                                    </div>
                                </div>
                                <button type="submit" class="btn-submit-renew-<?= $slug ?> w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-emerald-500/10">
                                    Xác Nhận Gia Hạn Key
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-slate-900 via-indigo-955 to-slate-900 text-white p-6 rounded-3xl shadow-md border border-slate-800">
                        <span class="text-[9px] bg-white/20 text-white font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-widest">Tính năng nổi bật</span>
                        <h3 class="font-extrabold text-sm tracking-tight text-blue-400 mt-4 mb-4 uppercase"><?= htmlspecialchars($product['name']) ?></h3>
                        <ul class="space-y-3 text-[11px] text-slate-300">
                            <?php foreach ($prodFeatures as $feat): ?>
                                <li class="flex items-start gap-2.5">
                                    <span class="p-0.5 bg-blue-500/20 text-blue-400 rounded border border-blue-500/30 shrink-0 mt-0.5">✓</span>
                                    <span class="leading-relaxed"><?= htmlspecialchars($feat) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <script>
        let currentBalance = <?= $user['balance'] ?? 0 ?>;

        function switchProductTab(slug) {
            document.querySelectorAll('.product-pane').forEach(el => el.classList.add('hidden'));
            const targetPane = document.getElementById(`prodTab-${slug}`);
            if (targetPane) targetPane.classList.remove('hidden');
            const tabButtons = ['vmaxlogin', 'v-autopost', 'v-shopeeboost-pro'];
            tabButtons.forEach(btnSlug => {
                const btn = document.getElementById(`prodTabBtn-${btnSlug}`);
                if (btnSlug === slug) {
                    btn.className = "flex-1 py-3 text-xs font-extrabold rounded-xl transition duration-200 tab-btn-active";
                } else {
                    btn.className = "flex-1 py-3 text-xs font-extrabold rounded-xl transition duration-200 text-slate-500 hover:text-slate-700";
                }
            });
        }

        function selectPackage(action, slug, packageType, price) {
            document.getElementById(`pkg-${action}-${slug}`).value = packageType;
            document.querySelectorAll(`.pkg-card-${action}-${slug}-${packageType}`).forEach(card => {
                document.querySelectorAll(`.pkg-card-${action}-${slug}-monthly, .pkg-card-${action}-${slug}-quarterly, .pkg-card-${action}-${slug}-yearly, .pkg-card-${action}-${slug}-lifetime`).forEach(c => {
                    c.classList.remove('border-blue-600', 'bg-blue-50/30', 'border-emerald-600', 'bg-emerald-50/30');
                    c.classList.add('border-slate-100');
                });
                if (action === 'buy') {
                    card.classList.add('border-blue-600', 'bg-blue-50/30');
                } else {
                    card.classList.add('border-emerald-600', 'bg-emerald-50/30');
                }
                card.classList.remove('border-slate-100');
            });
            const costBox = document.querySelector(`.cost-box-${action}-${slug}`);
            const btnSubmit = document.querySelector(`.btn-submit-${action}-${slug}`);
            costBox.querySelector('.val-price').innerText = price.toLocaleString('vi-VN') + 'đ';
            const remaining = currentBalance - price;
            const remainingEl = costBox.querySelector('.val-remaining');
            remainingEl.innerText = remaining.toLocaleString('vi-VN') + 'đ';
            if (remaining < 0) {
                remainingEl.className = "font-extrabold text-rose-600 val-remaining text-xs";
                btnSubmit.disabled = true;
                btnSubmit.innerText = "Số Dư Không Đủ";
                btnSubmit.className = `btn-submit-${action}-${slug} w-full py-3.5 bg-slate-200 text-slate-400 font-bold rounded-2xl text-xs cursor-not-allowed`;
            } else {
                remainingEl.className = "font-extrabold text-emerald-600 val-remaining text-xs";
                btnSubmit.disabled = false;
                btnSubmit.innerText = action === 'buy' ? "Xác Nhận Mua Key Bản Quyền" : "Xác Nhận Gia Hạn Key";
                if (action === 'buy') {
                    btnSubmit.className = `btn-submit-${action}-${slug} w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-blue-500/10`;
                } else {
                    btnSubmit.className = `btn-submit-${action}-${slug} w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-emerald-500/10`;
                }
            }
            costBox.classList.remove('hidden');
        }

        function handleTransaction(event, action, productId, slug) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            formData.append('csrf_token', '<?= Auth::getCsrfToken() ?>');
            formData.append('action', action);
            if (productId) formData.append('product_id', productId);
            Swal.fire({ title: 'Đang xử lý...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            fetch('/renew?ajax=1', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    currentBalance = data.balance;
                    document.getElementById('userBalanceText').innerText = currentBalance.toLocaleString('vi-VN');
                    Swal.fire({
                        title: 'Thành Công!',
                        html: `<div class="text-left space-y-3.5 text-xs text-slate-750 bg-slate-50 p-4 border border-slate-100 rounded-2xl">
                                    <div class="text-center font-bold text-sm text-emerald-600 pb-2 border-b border-slate-200/50">${data.message}</div>
                                    <div class="pt-2"><strong>Mã Key bản quyền:</strong></div>
                                    <div class="flex items-center justify-between bg-blue-50/70 border border-blue-100/50 px-3 py-3 rounded-xl">
                                        <code class="font-mono font-extrabold text-blue-600 text-xs" id="successKey">${data.key}</code>
                                        <button onclick="navigator.clipboard.writeText('${data.key}')" class="text-[10px] font-extrabold text-blue-600 hover:underline">Sao chép</button>
                                    </div>
                               </div>`,
                        icon: 'success',
                        confirmButtonText: 'Đóng',
                        confirmButtonColor: '#2563eb'
                    }).then(() => { location.href = '/transactions'; });
                } else {
                    Swal.fire({ title: 'Thất Bại', text: data.message, icon: 'error', confirmButtonText: 'Đóng', confirmButtonColor: '#dc2626' });
                }
            })
            .catch(() => { Swal.fire({ title: 'Lỗi', text: 'Không thể kết nối máy chủ.', icon: 'error', confirmButtonText: 'Đóng', confirmButtonColor: '#dc2626' }); });
        }
    </script>
</body>
</html>
