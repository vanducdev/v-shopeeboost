<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Blog & SEO - VMAX Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CKEditor 4 Standard-All from CDN for rich formatting, fonts, colors, and raw source HTML code mode -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-6 md:p-10 max-w-[1600px] mx-auto w-full">
        <header class="mb-10 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Quản Lý Blog & SEO</h1>
                <p class="text-slate-500 text-sm mt-1">Đăng bài viết chia sẻ, tối ưu hóa SEO meta để lên top Google.</p>
            </div>
            <div>
                <button onclick="toggleForm('create')" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition shadow-sm hover:shadow-md">
                    + Viết Bài Mới
                </button>
            </div>
        </header>

        <!-- Message/Alerts -->
        <?php if (!empty($message)): ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-250 text-emerald-750 font-semibold rounded-xl text-sm">
                <?= $message ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 bg-rose-50 border border-rose-250 text-rose-750 font-semibold rounded-xl text-sm">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- Write Post Form Container (Hidden by default, shown on create/edit) -->
        <section id="formContainer" class="hidden bg-white border border-slate-200/60 rounded-2xl p-8 shadow-sm mb-10">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                <h2 id="formTitle" class="text-xl font-bold text-slate-800">Tạo Bài Viết Chuẩn SEO</h2>
                <button onclick="toggleForm(null)" class="text-slate-400 hover:text-slate-600 text-sm font-semibold">Đóng Form &larr;</button>
            </div>

            <form method="POST" action="/admin/blog" enctype="multipart/form-data" onsubmit="prepareSubmit()" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                <input type="hidden" id="actionField" name="action" value="create">
                <input type="hidden" id="postIdField" name="post_id" value="">
                <input type="hidden" id="currentImageField" name="current_image" value="">

                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Basic Info -->
                    <div class="md:col-span-3 space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tiêu Đề Bài Viết</label>
                            <input type="text" id="titleField" name="title" onkeyup="generateSlug(this.value)" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 font-semibold transition" placeholder="Ví dụ: Hướng dẫn nuôi nick Facebook không bị checkpoint">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Đường dẫn thân thiện (Slug URL)</label>
                            <div class="flex gap-2">
                                <span class="bg-slate-100 border border-slate-200 px-3 py-3 rounded-xl text-xs text-slate-400 font-semibold flex items-center">/blog/post/</span>
                                <input type="text" id="slugField" name="slug" required class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 font-semibold transition" placeholder="huong-dan-nuoi-nick-facebook">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tóm tắt ngắn (Hiện ở danh sách tin tức)</label>
                            <textarea id="summaryField" name="summary" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition" placeholder="Tóm tắt ngắn nội dung bài viết thu hút người xem..."></textarea>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Nội dung chi tiết (Visual / HTML Code Editor)</label>
                                <span class="text-[10px] text-slate-400">Bấm nút <strong>"Nguồn" (Source)</strong> để chuyển đổi giữa viết Văn Bản và viết Code HTML</span>
                            </div>
                            <textarea id="contentField" name="content" rows="12" class="w-full hidden"></textarea>
                        </div>
                    </div>

                    <!-- Sidebar: Media & SEO Options -->
                    <div class="md:col-span-1 space-y-6 bg-slate-50/50 border border-slate-100 p-6 rounded-2xl">
                        <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Tối Ưu SEO & Media</h3>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ảnh Đại Diện (Featured Image)</label>
                            <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <div id="imagePreviewContainer" class="mt-3 hidden">
                                <span class="text-[10px] text-slate-400 block mb-1">Ảnh hiện tại:</span>
                                <img id="imagePreview" src="" class="h-24 w-auto object-cover rounded-lg border border-slate-200">
                            </div>
                        </div>

                        <!-- SEO Meta Title -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Meta Title (Tiêu Đề SEO)</label>
                                <span id="metaTitleCounter" class="text-xs font-bold text-slate-400">0 ký tự</span>
                            </div>
                            <input type="text" id="metaTitleField" name="meta_title" onkeyup="updateSeoCounters()" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition" placeholder="Tiêu đề hiển thị trên Google">
                        </div>

                        <!-- SEO Meta Description -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Meta Description (Mô tả SEO)</label>
                                <span id="metaDescCounter" class="text-xs font-bold text-slate-400">0 ký tự</span>
                            </div>
                            <textarea id="metaDescField" name="meta_description" onkeyup="updateSeoCounters()" rows="4" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 transition" placeholder="Mô tả tóm tắt dài khoảng 150-160 ký tự cho kết quả tìm kiếm..."></textarea>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Trạng thái xuất bản</label>
                            <select id="statusField" name="status" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 font-bold transition">
                                <option value="draft">Bản nháp (Draft)</option>
                                <option value="published">Xuất bản ngay (Published)</option>
                            </select>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-blue-500/10">
                                Lưu Bài Viết
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>

        <!-- Blog Posts Lists Table -->
        <section class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Danh Sách Bài Viết Đã Đăng</h3>
                <span class="text-xs text-slate-400 font-bold uppercase"><?= count($posts) ?> bài viết</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100">
                            <th class="pb-3 font-semibold">Ảnh</th>
                            <th class="pb-3 font-semibold">Tiêu đề bài viết</th>
                            <th class="pb-3 font-semibold">Tác giả</th>
                            <th class="pb-3 font-semibold">Trạng thái</th>
                            <th class="pb-3 font-semibold">Ngày tạo</th>
                            <th class="pb-3 font-semibold text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-medium">
                        <?php if (empty($posts)): ?>
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Chưa có bài viết nào. Hãy bấm "Viết Bài Mới" để bắt đầu!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($posts as $pt): ?>
                                <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                    <td class="py-4">
                                        <?php if (!empty($pt['image_url'])): ?>
                                            <img src="/<?= htmlspecialchars($pt['image_url']) ?>" class="w-12 h-12 object-cover rounded-lg border border-slate-200">
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-slate-350 text-xs">No img</div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="max-w-md truncate">
                                            <span class="font-bold text-slate-800 text-sm block"><?= htmlspecialchars($pt['title']) ?></span>
                                            <span class="text-xs text-slate-400 block mt-0.5">Slug: /blog/post/<?= htmlspecialchars($pt['slug']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-slate-500"><?= htmlspecialchars($pt['author_name']) ?></td>
                                    <td>
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full <?= $pt['status'] === 'published' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' ?>">
                                            <?= $pt['status'] === 'published' ? 'Xuất bản' : 'Bản nháp' ?>
                                        </span>
                                    </td>
                                    <td class="text-slate-400 text-xs"><?= date('d/m/Y H:i', $pt['created_at']) ?></td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <button onclick="editPost(<?= htmlspecialchars(json_encode($pt)) ?>)" class="px-3 py-1.5 bg-blue-50 text-blue-600 font-bold rounded-lg text-xs hover:bg-blue-100 transition">Sửa</button>
                                            <form method="POST" action="/admin/blog" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này không?');">
                                                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                                <input type="hidden" name="post_id" value="<?= $pt['id'] ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="px-3 py-1.5 bg-slate-50 text-slate-600 font-bold rounded-lg text-xs hover:bg-slate-100 transition">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- JavaScript Actions -->
    <script>
        // Initialize CKEditor 4 with Advanced Formatting options
        CKEDITOR.replace('contentField', {
            versionCheck: false,
            extraPlugins: 'font,colorbutton,justify,indentblock,dialogadvtab,showblocks',
            font_names: 'Arial/Arial, Helvetica, sans-serif;' +
                        'Comic Sans MS/Comic Sans MS, cursive;' +
                        'Courier New/Courier New, Courier, monospace;' +
                        'Georgia/Georgia, serif;' +
                        'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;' +
                        'Tahoma/Tahoma, Geneva, sans-serif;' +
                        'Times New Roman/Times New Roman, Times, serif;' +
                        'Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;' +
                        'Verdana/Verdana, Geneva, sans-serif;' +
                        'Inter/Inter, sans-serif',
            fontSize_sizes: '8/8px;9/9px;10/10px;11/11px;12/12px;14/14px;16/16px;18/18px;20/20px;22/22px;24/24px;28/28px;36/36px;48/48px;72/72px',
            toolbar: [
                { name: 'document', items: [ 'Source', '-', 'Preview', 'Print' ] },
                { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll' ] },
                '/',
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak' ] },
                '/',
                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] }
            ],
            height: 450,
            removeButtons: 'About'
        });

        function toggleForm(mode) {
            const formContainer = document.getElementById('formContainer');
            if (!mode) {
                formContainer.classList.add('hidden');
                return;
            }

            formContainer.classList.remove('hidden');
            window.scrollTo({ top: formContainer.offsetTop - 50, behavior: 'smooth' });

            if (mode === 'create') {
                document.getElementById('formTitle').innerText = 'Tạo Bài Viết Chuẩn SEO Mới';
                document.getElementById('actionField').value = 'create';
                document.getElementById('postIdField').value = '';
                document.getElementById('titleField').value = '';
                document.getElementById('slugField').value = '';
                document.getElementById('summaryField').value = '';
                CKEDITOR.instances.contentField.setData(''); // Reset editor content
                document.getElementById('metaTitleField').value = '';
                document.getElementById('metaDescField').value = '';
                document.getElementById('statusField').value = 'published';
                document.getElementById('imagePreviewContainer').classList.add('hidden');
                document.getElementById('currentImageField').value = '';
                updateSeoCounters();
            }
        }

        function editPost(postObj) {
            toggleForm('edit');
            document.getElementById('formTitle').innerText = 'Chỉnh Sửa Bài Viết';
            document.getElementById('actionField').value = 'edit';
            document.getElementById('postIdField').value = postObj.id;
            document.getElementById('titleField').value = postObj.title;
            document.getElementById('slugField').value = postObj.slug;
            document.getElementById('summaryField').value = postObj.summary;
            
            // Set content in CKEditor instance
            CKEDITOR.instances.contentField.setData(postObj.content || '');
            
            document.getElementById('metaTitleField').value = postObj.meta_title;
            document.getElementById('metaDescField').value = postObj.meta_description;
            document.getElementById('statusField').value = postObj.status;
            
            if (postObj.image_url) {
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
                document.getElementById('imagePreview').src = '/' + postObj.image_url;
                document.getElementById('currentImageField').value = postObj.image_url;
            } else {
                document.getElementById('imagePreviewContainer').classList.add('hidden');
                document.getElementById('currentImageField').value = '';
            }
            updateSeoCounters();
        }

        function prepareSubmit() {
            // Force synchronize editor content to the textarea before form submission
            CKEDITOR.instances.contentField.updateElement();
        }

        function generateSlug(text) {
            let slug = text.toLowerCase();
            // Convert Vietnamese tones/characters
            slug = slug.replace(/[áàảãạăắằẳẵặâấầẩẫậ]/g, 'a');
            slug = slug.replace(/[éèẻẽẹêếềểễệ]/g, 'e');
            slug = slug.replace(/[íìỉĩị]/g, 'i');
            slug = slug.replace(/[óòỏõọôốồổỗộơớờởỡợ]/g, 'o');
            slug = slug.replace(/[úùủũụưứừửữự]/g, 'u');
            slug = slug.replace(/[ýỳỷỹỵ]/g, 'y');
            slug = slug.replace(/đ/g, 'd');
            
            // Clean non-alphanumeric chars
            slug = slug.replace(/[^a-z0-9\s-]/g, '');
            // Convert multiple spaces/hyphens to single hyphen
            slug = slug.replace(/[\s-]+/g, '-');
            // Trim leading/trailing hyphens
            slug = slug.trim().replace(/^-+|-+$/g, '');
            
            document.getElementById('slugField').value = slug;
        }

        function updateSeoCounters() {
            const metaTitle = document.getElementById('metaTitleField').value;
            const metaDesc = document.getElementById('metaDescField').value;
            
            const titleLen = metaTitle.length;
            const descLen = metaDesc.length;
            
            const titleCounter = document.getElementById('metaTitleCounter');
            const descCounter = document.getElementById('metaDescCounter');
            
            // SEO rules: Title ~50-60 chars, Desc ~150-160 chars
            titleCounter.innerText = `${titleLen} ký tự (Khuyên dùng: 50-60)`;
            if (titleLen >= 50 && titleLen <= 60) {
                titleCounter.className = 'text-xs font-bold text-emerald-600';
            } else if (titleLen > 0) {
                titleCounter.className = 'text-xs font-bold text-amber-500';
            } else {
                titleCounter.className = 'text-xs font-bold text-slate-400';
            }
            
            descCounter.innerText = `${descLen} ký tự (Khuyên dùng: 150-160)`;
            if (descLen >= 150 && descLen <= 160) {
                descCounter.className = 'text-xs font-bold text-emerald-600';
            } else if (descLen > 0) {
                descCounter.className = 'text-xs font-bold text-amber-500';
            } else {
                descCounter.className = 'text-xs font-bold text-slate-400';
            }
        }
    </script>
</body>
</html>
