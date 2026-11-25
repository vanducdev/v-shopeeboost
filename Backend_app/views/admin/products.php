<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm - VMAX Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-6 md:p-10 max-w-7xl mx-auto w-full">
        <header class="mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Quản Lý Sản Phẩm</h1>
            <p class="text-slate-500 text-sm mt-1">Quản lý danh sách các phần mềm cung cấp bản quyền trên SaaS.</p>
        </header>

        <?php if (!empty($message)): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm mb-6">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <!-- Product Add Form -->
        <section class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm mb-10">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Thêm Phần Mềm Mới</h2>
            <form method="POST" action="/admin/products" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                <input type="hidden" name="action" value="create_product">
                
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tên phần mềm</label>
                        <input type="text" id="name" name="name" required placeholder="LushLogin Professional" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label for="logo" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Ảnh Logo đại diện (Icon nhỏ)</label>
                        <input type="file" id="logo" name="logo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600">
                    </div>
                    <div>
                        <label for="image" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Ảnh Banner Combo (Nếu là Combo)</label>
                        <input type="file" id="image" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600">
                    </div>
                </div>
                <div>
                    <label for="short_description" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Mô tả ngắn</label>
                    <input type="text" id="short_description" name="short_description" required placeholder="Trình duyệt chống dò vân tay antidetect..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                </div>
                <div>
                    <label for="description" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Mô tả chi tiết</label>
                    <textarea id="description" name="description" rows="3" required placeholder="Nhập đầy đủ thông tin chi tiết về sản phẩm..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm"></textarea>
                </div>
                
                <!-- Toggle settings -->
                <div class="flex items-center gap-6 bg-slate-50 p-4 rounded-xl">
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="show_in_menu" value="1" checked class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        Hiển thị ở Menu Sản phẩm
                    </label>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_combo" value="1" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        Là Combo ưu đãi (Bên trái)
                    </label>
                </div>
                
                <!-- Pricing grids -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="price_monthly" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Giá 30 ngày (VNĐ)</label>
                        <input type="number" id="price_monthly" name="price_monthly" value="150000" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label for="price_quarterly" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Giá 90 ngày (VNĐ)</label>
                        <input type="number" id="price_quarterly" name="price_quarterly" value="400000" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label for="price_yearly" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Giá 365 ngày (VNĐ)</label>
                        <input type="number" id="price_yearly" name="price_yearly" value="1200000" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label for="price_lifetime" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Giá trọn đời (VNĐ)</label>
                        <input type="number" id="price_lifetime" name="price_lifetime" value="2500000" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                    </div>
                </div>
                
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition text-sm">Thêm sản phẩm</button>
            </form>
        </section>

        <!-- Products table -->
        <section class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-3">Các Phần Mềm Đang Cung Cấp</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100">
                            <th class="pb-3 font-semibold">Tên phần mềm</th>
                            <th class="pb-3 font-semibold">Slug</th>
                            <th class="pb-3 font-semibold">Phân Loại</th>
                            <th class="pb-3 font-semibold">Trình Menu</th>
                            <th class="pb-3 font-semibold">Giá 30 ngày</th>
                            <th class="pb-3 font-semibold">Trạng thái</th>
                            <th class="pb-3 font-semibold">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-medium">
                        <?php foreach ($products as $pr): ?>
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                <td class="py-4">
                                    <div class="flex items-center gap-2">
                                        <?php if (!empty($pr['logo_url'])): ?>
                                            <img src="/<?= htmlspecialchars($pr['logo_url']) ?>" alt="logo" class="w-6 h-6 object-contain rounded">
                                        <?php else: ?>
                                            <div class="w-6 h-6 bg-slate-100 text-slate-400 rounded flex items-center justify-center font-bold text-xs"><?= strtoupper(substr($pr['name'], 0, 1)) ?></div>
                                        <?php endif; ?>
                                        <span class="font-bold"><?= htmlspecialchars($pr['name']) ?></span>
                                    </div>
                                </td>
                                <td><code class="text-xs bg-slate-50 px-2 py-1 rounded text-slate-500"><?= htmlspecialchars($pr['slug']) ?></code></td>
                                <td>
                                    <span class="inline-block px-2 py-0.5 text-xs font-bold rounded-lg <?= $pr['is_combo'] ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-600' ?>">
                                        <?= $pr['is_combo'] ? 'Combo ưu đãi' : 'Sản phẩm lẻ' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="inline-block px-2 py-0.5 text-xs font-bold rounded-lg <?= $pr['show_in_menu'] ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' ?>">
                                        <?= $pr['show_in_menu'] ? 'Hiển thị' : 'Ẩn' ?>
                                    </span>
                                </td>
                                <td class="text-blue-600 font-bold"><?= number_format($pr['price_monthly'], 0, ',', '.') ?>đ</td>
                                <td>
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full <?= $pr['status'] === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' ?>">
                                        <?= htmlspecialchars($pr['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <button onclick='openEditModal(<?= json_encode($pr, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)' class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-100 transition">Sửa</button>
                                        <form method="POST" action="/admin/products" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" class="m-0">
                                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                            <input type="hidden" name="product_id" value="<?= $pr['id'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 text-xs font-bold rounded-lg hover:bg-red-100 transition">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Edit Product Modal -->
    <div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-2xl w-full p-6 relative shadow-2xl overflow-y-auto max-h-[90vh]">
            <button onclick="closeEditModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 font-bold text-xl outline-none">&times;</button>
            <h3 class="text-lg font-bold text-slate-800 mb-4">Chỉnh Sửa Sản Phẩm</h3>
            <form method="POST" action="/admin/products" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                <input type="hidden" name="action" value="update_product">
                <input type="hidden" id="edit_product_id" name="product_id">
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tên phần mềm</label>
                        <input type="text" id="edit_name" name="name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label for="edit_status" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Trạng thái</label>
                        <select id="edit_status" name="status" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_logo" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Logo mới (Bỏ trống nếu giữ nguyên)</label>
                        <input type="file" id="edit_logo" name="logo" class="w-full text-xs text-slate-500">
                    </div>
                    <div>
                        <label for="edit_image" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Banner mới (Bỏ trống nếu giữ nguyên)</label>
                        <input type="file" id="edit_image" name="image" class="w-full text-xs text-slate-500">
                    </div>
                </div>

                <div>
                    <label for="edit_short_description" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Mô tả ngắn</label>
                    <input type="text" id="edit_short_description" name="short_description" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label for="edit_description" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Mô tả chi tiết (HTML)</label>
                    <textarea id="edit_description" name="description" rows="5" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:border-blue-500 text-sm"></textarea>
                </div>

                <div class="flex items-center gap-6 bg-slate-50 p-4 rounded-xl">
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" id="edit_show_in_menu" name="show_in_menu" value="1" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        Hiển thị ở Menu
                    </label>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" id="edit_is_combo" name="is_combo" value="1" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        Là Combo
                    </label>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <div>
                        <label for="edit_price_monthly" class="block text-xs font-semibold text-slate-500 tracking-wider mb-1">Giá 30 ngày</label>
                        <input type="number" id="edit_price_monthly" name="price_monthly" required class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs">
                    </div>
                    <div>
                        <label for="edit_price_quarterly" class="block text-xs font-semibold text-slate-500 tracking-wider mb-1">Giá 90 ngày</label>
                        <input type="number" id="edit_price_quarterly" name="price_quarterly" required class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs">
                    </div>
                    <div>
                        <label for="edit_price_yearly" class="block text-xs font-semibold text-slate-500 tracking-wider mb-1">Giá 365 ngày</label>
                        <input type="number" id="edit_price_yearly" name="price_yearly" required class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs">
                    </div>
                    <div>
                        <label for="edit_price_lifetime" class="block text-xs font-semibold text-slate-500 tracking-wider mb-1">Giá trọn đời</label>
                        <input type="number" id="edit_price_lifetime" name="price_lifetime" required class="w-full px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs">
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-200 transition">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-bold rounded-xl text-xs hover:bg-blue-700 transition">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(prod) {
            document.getElementById('edit_product_id').value = prod.id;
            document.getElementById('edit_name').value = prod.name;
            document.getElementById('edit_status').value = prod.status;
            document.getElementById('edit_short_description').value = prod.short_description;
            document.getElementById('edit_description').value = prod.description || '';
            document.getElementById('edit_price_monthly').value = prod.price_monthly;
            document.getElementById('edit_price_quarterly').value = prod.price_quarterly;
            document.getElementById('edit_price_yearly').value = prod.price_yearly;
            document.getElementById('edit_price_lifetime').value = prod.price_lifetime;
            
            document.getElementById('edit_show_in_menu').checked = (parseInt(prod.show_in_menu) === 1);
            document.getElementById('edit_is_combo').checked = (parseInt(prod.is_combo) === 1);
            
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }
    </script>
</body>
</html>
