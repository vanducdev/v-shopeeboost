<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager - VMAX Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        input[type="file"] { background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; }
        input[type="file"]::-webkit-file-upload-button { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; font-weight: 600; font-size: 11px; padding: 6px 12px; border-radius: 8px; cursor: pointer; margin-right: 12px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 min-h-screen overflow-y-auto">
        <header class="sticky top-0 z-30 px-8 py-4 flex items-center justify-between border-b border-slate-200 bg-white/95 backdrop-blur-md">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">File Manager</h1>
                <p class="text-slate-500 text-xs">Tải lên và quản lý các bộ cài đặt .exe và tài nguyên hình ảnh</p>
            </div>
        </header>

        <div class="px-6 md:px-8 py-8 max-w-4xl">
            <?php if (!empty($message)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="mb-6 px-4 py-3 rounded-xl text-xs font-semibold bg-red-50 text-red-700 border border-red-200"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Upload Zone -->
            <div class="bg-white border border-slate-200/60 rounded-2xl p-6 mb-6 shadow-sm">
                <h2 class="text-sm font-bold text-slate-800 mb-5 pb-4 border-b border-slate-100 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </div>
                    Tải Lên Tệp Mới
                </h2>
                <form method="POST" action="/admin/files" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4 items-end">
                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                    <div class="flex-1 w-full">
                        <input type="file" name="file_upload" required class="w-full px-4 py-3 rounded-xl text-xs cursor-pointer outline-none">
                    </div>
                    <button type="submit" class="px-6 py-3 text-xs font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-500/10 whitespace-nowrap">
                        Bắt Đầu Upload
                    </button>
                </form>
            </div>

            <!-- File List -->
            <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800">Các Tệp Tin Trong Thư Mục Upload</h2>
                    <span class="text-[10px] text-slate-450 font-bold uppercase"><?= count($files) ?> tệp</span>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php if (empty($files)): ?>
                        <div class="px-6 py-12 text-center text-slate-450 text-xs italic">Chưa có tệp tin nào được tải lên.</div>
                    <?php else: ?>
                        <?php foreach ($files as $file): 
                            $fullPath = BASE_PATH . '/assets/uploads/' . $file;
                            $fileSize = is_file($fullPath) ? filesize($fullPath) : 0;
                            $sizeLabel = $fileSize > 1024*1024 ? round($fileSize/(1024*1024),2).' MB' : round($fileSize/1024,1).' KB';
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['png','jpg','jpeg','gif','webp','svg']);
                            $isExe = in_array($ext, ['exe','zip','rar','7z','msi']);
                        ?>
                            <div class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50/50 transition">
                                <!-- File type icon -->
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 <?= $isImage ? 'bg-violet-50 border border-violet-100' : ($isExe ? 'bg-blue-50 border border-blue-100' : 'bg-slate-50 border border-slate-150') ?>">
                                    <?php if ($isImage): ?>
                                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <?php elseif ($isExe): ?>
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <?php else: ?>
                                        <svg class="w-5 h-5 text-slate-655" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-bold text-slate-800 truncate"><?= htmlspecialchars($file) ?></div>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-[10px] text-slate-450 font-mono"><?= $sizeLabel ?></span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-md uppercase font-bold text-[9px] tracking-wider <?= $isImage ? 'bg-violet-50 text-violet-650' : ($isExe ? 'bg-blue-50 text-blue-650' : 'bg-slate-50 text-slate-500') ?>"><?= strtoupper($ext) ?></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="/assets/uploads/<?= htmlspecialchars($file) ?>" download class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100">Tải Xuống</a>
                                    <form method="POST" action="/admin/files" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tệp: <?= addslashes(htmlspecialchars($file)) ?>?');">
                                        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                        <input type="hidden" name="action" value="delete_file">
                                        <input type="hidden" name="filename" value="<?= htmlspecialchars($file) ?>">
                                        <button type="submit" class="px-3 py-1.5 text-[10px] font-bold rounded-lg border transition bg-red-55/60 text-red-650 border-red-200 hover:bg-red-100">Xóa</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
