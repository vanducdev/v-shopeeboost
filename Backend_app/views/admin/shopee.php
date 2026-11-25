<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Shopee & App - VMAX Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 min-h-screen overflow-y-auto">
        <header class="sticky top-0 z-30 px-8 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 bg-white/95 backdrop-blur-md">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Quản Lý V-ShopeeBoost Pro</h1>
                <p class="text-slate-500 text-xs">Cấu hình tương tác, truyền tin tức, thông báo hình ảnh và nâng cấp phiên bản phần mềm</p>
            </div>
            
            <!-- Tab Controls -->
            <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
                <button onclick="switchTab('interactions')" id="tab-btn-interactions" class="tab-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all duration-200 bg-white text-slate-800 shadow-sm">
                    Yêu Cầu Tương Tác
                </button>
                <button onclick="switchTab('announcements')" id="tab-btn-announcements" class="tab-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all duration-200 text-slate-500 hover:text-slate-800">
                    Thông Báo & Tin Tức
                </button>
                <button onclick="switchTab('versions')" id="tab-btn-versions" class="tab-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all duration-200 text-slate-500 hover:text-slate-800">
                    Phiên Bản & Cập Nhật
                </button>
            </div>
        </header>

        <div class="px-6 md:px-8 py-8">
            <!-- Messages / Errors -->
            <?php if (!empty($error)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-red-50 text-red-700 border border-red-200"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($message)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <!-- ================= TAB: INTERACTIONS ================= -->
            <div id="tab-content-interactions" class="tab-pane space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-sm font-bold text-slate-800">Yêu cầu tương tác gần đây</h2>
                    <button onclick="exportAllSubAccounts()" class="px-3 py-1.5 text-xs font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition">Xuất Tất Cả Tài Khoản Phụ</button>
                </div>

                <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-100 text-slate-400 font-semibold bg-slate-50/20">
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Khách Hàng (License/Device)</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Link Shop Chính</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Số Lượng TK Phụ</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Tài Khoản Phụ (user|pass|SPC_F)</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Thời Gian Gửi</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700 font-medium">
                                <?php if (empty($interactions)): ?>
                                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs italic">Chưa có yêu cầu tương tác Shopee nào được gửi lên.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($interactions as $item): 
                                        $subs = explode("\n", str_replace("\r", "", $item['sub_accounts']));
                                        $subs = array_filter(array_map('trim', $subs));
                                        $subCount = count($subs);
                                    ?>
                                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition align-top">
                                            <td class="px-6 py-4">
                                                <div class="text-xs font-bold text-slate-800">Key: <span class="text-indigo-600"><?= htmlspecialchars($item['license_key'] ?? 'N/A') ?></span></div>
                                                <div class="text-[9px] font-mono text-slate-400 mt-0.5">Device: <?= htmlspecialchars(substr($item['device_id'] ?? 'N/A', 0, 16)) ?>...</div>
                                            </td>
                                            <td class="px-6 py-4 max-w-xs">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <img class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0" src="<?= htmlspecialchars($item['shop_avatar'] ?: 'https://cf.shopee.vn/file/default_avatar') ?>" onerror="this.src='https://cf.shopee.vn/file/default_avatar'">
                                                    <div>
                                                        <div class="text-xs font-black text-slate-800"><?= htmlspecialchars($item['shop_name'] ?: 'Tên shop Shopee') ?></div>
                                                        <div class="text-[9px] text-slate-400">Shop ID: <?= htmlspecialchars($item['id']) ?></div>
                                                        <div class="flex items-center gap-1 mt-1">
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-amber-50 text-amber-600 border border-amber-100">★ <?= number_format($item['rating_star'] ?? 5.0, 1) ?></span>
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-blue-50 text-blue-600 border border-blue-100"><?= number_format($item['follower_count'] ?? 0) ?> Flws</span>
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-slate-100 text-slate-600 border border-slate-200"><?= $item['item_count'] ?? 0 ?> SP</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="<?= htmlspecialchars($item['main_shop_link']) ?>" target="_blank" class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 break-all underline flex items-center gap-1">
                                                    <span>Link Shop</span>
                                                    <svg class="w-3 h-3 inline shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                    <?= $subCount ?> tài khoản
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="relative max-w-sm">
                                                    <textarea readonly class="w-full h-16 text-[10px] font-mono p-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-600 focus:outline-none resize-none" id="textarea-sub-<?= $item['id'] ?>"><?= htmlspecialchars($item['sub_accounts']) ?></textarea>
                                                    <button onclick="copySubAccounts(<?= $item['id'] ?>)" class="absolute top-2 right-2 bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-950 p-1 rounded shadow-sm text-[9px] font-bold flex items-center gap-1 transition">
                                                        Copy
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-[10px] text-slate-500 font-mono">
                                                <?= date('d/m/Y H:i', $item['created_at']) ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button onclick="confirmDeleteInteraction(<?= $item['id'] ?>)" class="px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition bg-red-50 text-red-600 border-red-200 hover:bg-red-100">Xóa</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ================= TAB: ANNOUNCEMENTS ================= -->
            <div id="tab-content-announcements" class="tab-pane hidden space-y-8">
                <!-- Create Announcement Form -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 max-w-4xl">
                    <h2 class="text-sm font-black text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        Đăng Tin Tức / Thông Báo Cho Ứng Dụng Desktop
                    </h2>
                    
                    <form method="POST" action="/admin/shopee" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                        <input type="hidden" name="action" value="add_announcement">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tiêu đề thông báo</label>
                                <input type="text" name="title" required placeholder="Cập nhật tính năng Shopee, thông báo bảo trì..." class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nội dung thông báo (Chi tiết)</label>
                                <textarea name="content" required rows="4" placeholder="Nhập mô tả chi tiết các thay đổi..." class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 resize-none"></textarea>
                            </div>
                        </div>

                        <div class="space-y-4 flex flex-col justify-between">
                            <div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Đường dẫn Hình ảnh (Image URL)</label>
                                    <input type="text" name="image_url" id="ann-image-input" oninput="previewImage()" placeholder="https://example.com/banner.png" class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div class="mt-3">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Đường dẫn Hành động (Action Link - Tùy chọn)</label>
                                    <input type="text" name="action_link" placeholder="Nhấp để chuyển tiếp sang trang sản phẩm, bài hướng dẫn..." class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500">
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 justify-end pt-4">
                                <button type="submit" class="px-5 py-3 text-xs font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition">Đăng thông báo lên app</button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Image Preview Area -->
                    <div id="image-preview-container" class="mt-4 hidden border border-dashed border-slate-200 rounded-xl p-3 bg-slate-50/50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Xem trước hình ảnh thông báo:</p>
                        <img id="ann-image-preview" src="" class="max-h-36 rounded-lg object-contain border bg-white shadow-sm">
                    </div>
                </div>

                <!-- Existing Announcements List -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800">Các thông báo đang hiển thị</h3>
                    
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-100 text-slate-400 font-semibold bg-slate-50/20">
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Hình Ảnh</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Nội Dung Thông Báo</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Ngày Đăng</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700">
                                <?php if (empty($announcements)): ?>
                                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400 text-xs italic">Chưa có thông báo nào được tạo.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($announcements as $ann): ?>
                                        <tr class="border-b border-slate-50 hover:bg-slate-50/30 transition">
                                            <td class="px-6 py-4">
                                                <?php if ($ann['image_url']): ?>
                                                    <img src="<?= htmlspecialchars($ann['image_url']) ?>" class="w-20 h-12 rounded object-cover border bg-slate-100">
                                                <?php else: ?>
                                                    <span class="text-[10px] text-slate-400 italic">Không ảnh</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-xs font-bold text-slate-900"><?= htmlspecialchars($ann['title']) ?></div>
                                                <div class="text-[11px] text-slate-500 mt-1 line-clamp-2"><?= htmlspecialchars($ann['content']) ?></div>
                                                <?php if ($ann['action_link']): ?>
                                                    <a href="<?= htmlspecialchars($ann['action_link']) ?>" target="_blank" class="inline-block text-[9px] font-bold text-indigo-600 hover:underline mt-1 break-all"><?= htmlspecialchars($ann['action_link']) ?></a>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 text-[10px] text-slate-500 font-mono">
                                                <?= date('d/m/Y H:i', $ann['created_at']) ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex justify-end">
                                                    <button onclick="deleteAnnouncement(<?= $ann['id'] ?>)" class="px-2 py-1 text-[10px] font-bold rounded-lg border bg-red-50 text-red-600 border-red-100 hover:bg-red-100 transition">Gỡ bỏ</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ================= TAB: VERSIONS ================= -->
            <div id="tab-content-versions" class="tab-pane hidden space-y-8">
                <!-- Create Version Form -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 max-w-4xl">
                    <h2 class="text-sm font-black text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Phát Hành Cập Nhật Phiên Bản Mới
                    </h2>
                    
                    <form method="POST" action="/admin/shopee" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                        <input type="hidden" name="action" value="add_version">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mã phiên bản (Phiên bản mới nhất)</label>
                                <input type="text" name="version" required placeholder="Ví dụ: 1.2.0, 1.2.5..." class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Đường dẫn tải xuống bộ cài (Setup Download URL)</label>
                                <input type="text" name="download_url" required placeholder="https://app.vmaxtech.vn/download/v-shopeeboost-setup.exe" class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 font-mono">
                            </div>
                            <div class="flex items-center gap-2.5 bg-indigo-50/50 border border-indigo-100 p-3.5 rounded-xl">
                                <input type="checkbox" name="is_mandatory" value="1" id="is_mandatory" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                <label for="is_mandatory" class="text-xs font-bold text-slate-700 cursor-pointer select-none">
                                    Bắt buộc cập nhật phiên bản này
                                    <p class="text-[9px] font-medium text-slate-400 normal-case mt-0.5">Nếu tích chọn, người dùng sẽ không thể tiếp tục sử dụng app cũ cho đến khi hoàn thành cài đặt bản mới.</p>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-4 flex flex-col justify-between">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nhật ký thay đổi (Changelog)</label>
                                <textarea name="changelog" rows="5" required placeholder="- Sửa lỗi Canvas Shopee Verify&#10;- Nâng cấp hiệu ứng giao diện Dashboard&#10;- Bổ sung thanh tác vụ nổi Floating Action Bar..." class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 resize-none font-mono"></textarea>
                            </div>
                            
                            <div class="flex justify-end pt-4">
                                <button type="submit" class="px-5 py-3 text-xs font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition">Phát hành bản cập nhật</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Existing Versions List -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800">Lịch sử phát hành phiên bản</h3>
                    
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden font-medium">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-100 text-slate-400 font-semibold bg-slate-50/20">
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Phiên Bản</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Changelog</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Loại Cập Nhật</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Link Tải Xuống</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Thời Gian</th>
                                    <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700">
                                <?php if (empty($versions)): ?>
                                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-400 text-xs italic">Chưa phát hành phiên bản nào.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($versions as $ver): ?>
                                        <tr class="border-b border-slate-50 hover:bg-slate-50/30 transition align-top">
                                            <td class="px-6 py-4">
                                                <span class="px-2.5 py-1 text-xs font-mono font-bold rounded-lg border bg-slate-100 text-slate-800 border-slate-200"><?= htmlspecialchars($ver['version']) ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-[11px] font-mono text-slate-600 whitespace-pre-line"><?= htmlspecialchars($ver['changelog']) ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php if ($ver['is_mandatory']): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">Bắt buộc</span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-250">Tùy chọn</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 max-w-xs">
                                                <a href="<?= htmlspecialchars($ver['download_url']) ?>" class="text-[11px] text-blue-600 hover:underline break-all font-mono"><?= htmlspecialchars($ver['download_url']) ?></a>
                                            </td>
                                            <td class="px-6 py-4 text-[10px] text-slate-500 font-mono">
                                                <?= date('d/m/Y H:i', $ver['created_at']) ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex justify-end">
                                                    <button onclick="deleteVersion(<?= $ver['id'] ?>)" class="px-2 py-1 text-[10px] font-bold rounded-lg border bg-red-50 text-red-600 border-red-100 hover:bg-red-100 transition">Gỡ bỏ</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Hidden Action Form for CSRF Protection -->
    <form id="action-form" method="POST" action="/admin/shopee" class="hidden">
        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
        <input type="hidden" name="id" id="form-action-id">
        <input type="hidden" name="action" id="form-action-type">
    </form>

    <script>
    function switchTab(tabId) {
        // Toggle tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'text-slate-800', 'shadow-sm');
            btn.classList.add('text-slate-500', 'hover:text-slate-800');
        });
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.add('bg-white', 'text-slate-800', 'shadow-sm');
            activeBtn.classList.remove('text-slate-500', 'hover:text-slate-800');
        }

        // Toggle contents
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
        document.getElementById('tab-content-' + tabId).classList.remove('hidden');
        
        // Save current tab to localStorage
        localStorage.setItem('shopee_admin_tab', tabId);
    }

    // Restore last active tab
    window.addEventListener('DOMContentLoaded', () => {
        const activeTab = localStorage.getItem('shopee_admin_tab') || 'interactions';
        switchTab(activeTab);
    });

    function previewImage() {
        const input = document.getElementById('ann-image-input');
        const container = document.getElementById('image-preview-container');
        const preview = document.getElementById('ann-image-preview');
        
        if (input.value.trim()) {
            preview.src = input.value.trim();
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function copySubAccounts(id) {
        const textarea = document.getElementById('textarea-sub-' + id);
        if (textarea) {
            textarea.select();
            document.execCommand('copy');
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Đã sao chép tài khoản phụ!',
                showConfirmButton: false,
                timer: 1500
            });
        }
    }

    function confirmDeleteInteraction(id) {
        Swal.fire({
            title: 'Xác Nhận Xóa Yêu Cầu?',
            html: '<p style="color:#64748b;font-size:13px;">Dữ liệu yêu cầu tương tác này sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Đồng Ý Xóa',
            cancelButtonText: 'Hủy'
        }).then((r) => {
            if (r.isConfirmed) {
                document.getElementById('form-action-id').value = id;
                document.getElementById('form-action-type').value = 'delete';
                document.getElementById('action-form').submit();
            }
        });
    }

    function deleteAnnouncement(id) {
        Swal.fire({
            title: 'Gỡ Bỏ Thông Báo Này?',
            html: '<p style="color:#64748b;font-size:13px;">Thông báo sẽ bị xóa và biến mất khỏi ứng dụng Client.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Đồng Ý Xóa',
            cancelButtonText: 'Hủy'
        }).then((r) => {
            if (r.isConfirmed) {
                document.getElementById('form-action-id').value = id;
                document.getElementById('form-action-type').value = 'delete_announcement';
                document.getElementById('action-form').submit();
            }
        });
    }

    function deleteVersion(id) {
        Swal.fire({
            title: 'Gỡ Bỏ Phiên Bản Này?',
            html: '<p style="color:#64748b;font-size:13px;">Gỡ bản cập nhật này sẽ khiến thiết bị Client nhận bản cũ hơn kế tiếp.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Đồng Ý Xóa',
            cancelButtonText: 'Hủy'
        }).then((r) => {
            if (r.isConfirmed) {
                document.getElementById('form-action-id').value = id;
                document.getElementById('form-action-type').value = 'delete_version';
                document.getElementById('action-form').submit();
            }
        });
    }

    function exportAllSubAccounts() {
        const textareas = document.querySelectorAll('textarea[id^="textarea-sub-"]');
        let combined = '';
        textareas.forEach(t => {
            if (t.value.trim()) {
                combined += t.value.trim() + '\n';
            }
        });

        if (!combined) {
            Swal.fire('Thông Báo', 'Không có tài khoản phụ nào để xuất.', 'info');
            return;
        }

        const blob = new Blob([combined], { type: 'text/plain;charset=utf-8' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'shopee_sub_accounts_' + new Date().toISOString().slice(0, 10) + '.txt';
        link.click();
    }
    </script>
</body>
</html>
