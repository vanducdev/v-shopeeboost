<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài Đặt Website - VMAX Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        input[type="text"], input[type="color"], select, textarea {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            outline: none;
            transition: all 0.2s ease;
        }
        input[type="text"]:focus, select:focus, textarea:focus {
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
        }
        .tab-btn.active {
            border-bottom: 2px solid #2563eb;
            color: #2563eb;
            font-weight: 750;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 min-h-screen overflow-y-auto">
        <header class="sticky top-0 z-30 px-8 py-4 flex items-center justify-between border-b border-slate-200 bg-white/95 backdrop-blur-md">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Cài Đặt Website</h1>
                <p class="text-slate-500 text-xs">Cấu hình nhận diện thương hiệu, màu sắc, ngôn ngữ và các trang tĩnh</p>
            </div>
        </header>

        <div class="px-6 md:px-8 py-8 max-w-5xl">
            <!-- Messages -->
            <?php if (!empty($message)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-red-50 text-red-700 border border-red-200"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Navigation Tabs -->
            <div class="flex border-b border-slate-200 mb-6 bg-white rounded-t-2xl px-6 pt-3 shadow-sm">
                <a href="/admin/setup" class="tab-btn px-4 py-3 text-xs font-semibold text-slate-500 hover:text-slate-800 transition <?= !isset($is_lang_tab) ? 'active' : '' ?>">Cấu hình & Tạo Trang</a>
                <a href="/admin/setup/languages?lang_select=vi" class="tab-btn px-4 py-3 text-xs font-semibold text-slate-500 hover:text-slate-800 transition <?= isset($is_lang_tab) && $lang === 'vi' ? 'active' : '' ?>">Ngôn Ngữ Tiếng Việt (VI)</a>
                <a href="/admin/setup/languages?lang_select=en" class="tab-btn px-4 py-3 text-xs font-semibold text-slate-500 hover:text-slate-800 transition <?= isset($is_lang_tab) && $lang === 'en' ? 'active' : '' ?>">Ngôn Ngữ Tiếng Anh (EN)</a>
            </div>

            <!-- Tab Content 1: Configuration & Static Pages -->
            <?php if (!isset($is_lang_tab)): ?>
                <div class="space-y-6">
                    <form method="POST" action="/admin/setup" enctype="multipart/form-data" class="space-y-6">
                        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                        <input type="hidden" name="action" value="save_settings">

                        <!-- Brand Setup -->
                        <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm">
                            <h2 class="text-sm font-bold text-slate-800 mb-5 pb-4 border-b border-slate-100 flex items-center gap-2">Nhận Diện Thương Hiệu</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Tên Website</label>
                                        <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? 'VMAX Software') ?>" class="w-full px-3 py-2.5 rounded-xl text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Tài Khoản VietQR / Momo Info</label>
                                        <textarea name="bank_transfer_info" rows="4" class="w-full px-3 py-2.5 rounded-xl text-xs resize-none"><?= htmlspecialchars($settings['bank_transfer_info'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Tải Lên Logo Mới</label>
                                        <input type="file" name="site_logo" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-700">
                                        <?php if (!empty($settings['site_logo'])): ?>
                                            <div class="mt-2.5 p-2 bg-slate-50 border border-slate-100 rounded-lg inline-block">
                                                <img src="/<?= htmlspecialchars($settings['site_logo']) ?>" class="max-h-12 w-auto object-contain">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Tải Lên Favicon Mới (Icon Web)</label>
                                        <input type="file" name="site_favicon" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-700">
                                        <?php if (!empty($settings['site_favicon'])): ?>
                                            <div class="mt-2.5 p-1 bg-slate-50 border border-slate-100 rounded-lg inline-block">
                                                <img src="/<?= htmlspecialchars($settings['site_favicon']) ?>" class="w-6 h-6 object-contain">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">Tải Lên Ảnh QR Ngân Hàng</label>
                                        <input type="file" name="qr_bank" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-700">
                                        <?php if (!empty($settings['qr_bank_path'])): ?>
                                            <div class="mt-2.5 p-2 bg-slate-50 border border-slate-100 rounded-lg inline-block">
                                                <img src="/<?= htmlspecialchars($settings['qr_bank_path']) ?>" class="max-h-20 w-auto object-contain">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Colors and Theme -->
                        <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm">
                            <h2 class="text-sm font-bold text-slate-800 mb-5 pb-4 border-b border-slate-100 flex items-center gap-2">Màu Sắc & Chủ Đề Chủ Đạo</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center gap-3">
                                    <input type="color" name="site_primary_color" value="<?= htmlspecialchars($settings['site_primary_color'] ?? '#2563eb') ?>" class="w-12 h-12 rounded-xl border border-slate-200 cursor-pointer p-0.5">
                                    <div>
                                        <span class="text-xs font-bold text-slate-800 block">Màu Chủ Đạo (Primary Color)</span>
                                        <span class="text-[10px] text-slate-400 font-mono">Dùng cho các nút bấm chính, tiêu đề chính</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="color" name="site_secondary_color" value="<?= htmlspecialchars($settings['site_secondary_color'] ?? '#4f46e5') ?>" class="w-12 h-12 rounded-xl border border-slate-200 cursor-pointer p-0.5">
                                    <div>
                                        <span class="text-xs font-bold text-slate-800 block">Màu Phụ Trợ (Secondary Color)</span>
                                        <span class="text-[10px] text-slate-400 font-mono">Dùng cho các hiệu ứng hover, chi tiết phụ</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-8 py-3 text-xs font-bold text-white bg-blue-650 hover:bg-blue-800 rounded-xl transition shadow-lg shadow-blue-500/10">Lưu Thiết Lập Website</button>
                        </div>
                    </form>

                    <!-- Page Builder Manager -->
                    <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm">
                        <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                            <h2 class="text-sm font-bold text-slate-800">Quản Lý Trang & Landing Pages Tự Thiết Kế</h2>
                            <button onclick="openCreatePageModal()" class="px-3.5 py-1.5 text-[10px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition">+ Tạo Trang Mới</button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-semibold bg-slate-50/20">
                                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Tiêu Đề Trang</th>
                                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Đường Dẫn (Slug)</th>
                                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Trạng Thái</th>
                                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Ngày Tạo</th>
                                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody class="text-slate-700 font-medium">
                                    <?php if (empty($pages)): ?>
                                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-450 text-xs italic">Chưa có trang thiết kế nào. Bấm "+ Tạo Trang Mới" để bắt đầu kéo thả.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($pages as $p): ?>
                                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                                <td class="px-6 py-4 font-bold text-slate-800"><?= htmlspecialchars($p['title']) ?></td>
                                                <td class="px-6 py-4 font-mono text-xs text-blue-650">
                                                    <a href="<?= htmlspecialchars($p['slug']) ?>" target="_blank" class="hover:underline"><?= htmlspecialchars($p['slug']) ?></a>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-md <?= $p['is_published'] ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200' ?>">
                                                        <?= $p['is_published'] ? 'Đã Xuất Bản' : 'Bản Nháp' ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-[10px] text-slate-400 font-mono"><?= date('d/m/Y H:i', $p['created_at']) ?></td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <a href="/admin/setup/builder?page_id=<?= $p['id'] ?>" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100">Kéo Thả UI (Edit)</a>
                                                        <form method="POST" action="/admin/setup" onsubmit="return confirm('Bạn có chắc chắn muốn xóa trang này?');">
                                                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                                            <input type="hidden" name="action" value="delete_page">
                                                            <input type="hidden" name="page_id" value="<?= $p['id'] ?>">
                                                            <button type="submit" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-red-50 text-red-600 border-red-200 hover:bg-red-100">Xóa</button>
                                                        </form>
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
            <?php else: ?>
                <!-- Tab Content 2: Languages Editor -->
                <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                        <h2 class="text-sm font-bold text-slate-800">Chỉnh Sửa Bản Dịch Tệp Ngôn Ngữ: <span class="uppercase text-blue-650"><?= htmlspecialchars($lang) ?></span></h2>
                        <span class="text-[10px] text-slate-400 font-bold uppercase">Tổng cộng: <?= count($translations) ?> từ khóa dịch</span>
                    </div>

                    <form method="POST" action="/admin/setup/languages?lang_select=<?= htmlspecialchars($lang) ?>" class="space-y-6">
                        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">

                        <div class="max-h-[500px] overflow-y-auto pr-2 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ($translations as $key => $val): ?>
                                    <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl space-y-2">
                                        <label class="block text-[9px] font-bold text-slate-400 font-mono select-all"><?= htmlspecialchars($key) ?></label>
                                        <input type="text" name="translations[<?= htmlspecialchars($key) ?>]" value="<?= htmlspecialchars($val) ?>" class="w-full px-3 py-2 rounded-lg text-xs bg-white border border-slate-200">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="px-8 py-3 text-xs font-bold text-white bg-blue-650 hover:bg-blue-800 rounded-xl transition shadow-lg shadow-blue-500/10">Lưu Bản Dịch Ngôn Ngữ</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Create Page Modal / Prompt wrapper -->
    <script>
    function openCreatePageModal() {
        Swal.fire({
            title: 'Tạo Trang Thiết Kế Mới',
            html: `
                <div class="text-left space-y-3">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Tiêu Đề Trang</label>
                        <input type="text" id="swal-page-title" class="swal2-input w-full m-0 text-xs px-3 py-2 border rounded-xl" placeholder="Ví dụ: Trang Khuyến Mãi">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Đường Dẫn Slug (Bắt đầu bằng gạch chéo)</label>
                        <input type="text" id="swal-page-slug" class="swal2-input w-full m-0 text-xs px-3 py-2 border rounded-xl font-mono" placeholder="Ví dụ: /khuyen-mai">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Tạo & Thiết Kế Ngay',
            cancelButtonText: 'Hủy',
            preConfirm: () => {
                const title = document.getElementById('swal-page-title').value.trim();
                const slug = document.getElementById('swal-page-slug').value.trim();
                if (!title || !slug) {
                    Swal.showValidationMessage('Vui lòng điền đầy đủ tiêu đề và slug!');
                    return false;
                }
                return { title, slug };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit dynamically via a dynamically created form to keep views cleanly routed
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/setup';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = '<?= Auth::getCsrfToken() ?>';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'create_page';

                const titleInput = document.createElement('input');
                titleInput.type = 'hidden';
                titleInput.name = 'title';
                titleInput.value = result.value.title;

                const slugInput = document.createElement('input');
                slugInput.type = 'hidden';
                slugInput.name = 'slug';
                slugInput.value = result.value.slug;

                form.appendChild(csrfInput);
                form.appendChild(actionInput);
                form.appendChild(titleInput);
                form.appendChild(slugInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    </script>
</body>
</html>
