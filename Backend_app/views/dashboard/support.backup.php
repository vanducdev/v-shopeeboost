<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hỗ Trợ Kỹ Thuật - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .glass-panel-glow {
            box-shadow: 0 10px 30px -5px rgba(59, 130, 246, 0.06);
        }
        .chat-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .chat-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.01);
        }
        .chat-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.08);
            border-radius: 99px;
        }
        .chat-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.15);
        }
        @keyframes popIn {
            from { opacity: 0; transform: translateY(10px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-pop-in {
            animation: popIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen flex flex-col md:flex-row">

    <!-- Client Sidebar -->
    <?php require BASE_PATH . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-4 md:p-8 flex flex-col max-w-[1600px] mx-auto w-full overflow-hidden">
        <!-- Header -->
        <header class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6 pb-6 border-b border-slate-200/60">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-ping"></div>
                    <h1 class="text-2xl font-extrabold text-slate-950 tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700">
                        Hỗ Trợ Kỹ Thuật
                    </h1>
                </div>
                <p class="text-slate-500 text-xs mt-1">Gửi ticket phản hồi lỗi phần mềm, yêu cầu kỹ thuật hoặc giải đáp thắc mắc 24/7.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="/support" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-blue-500/15 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tạo Ticket Mới
                </a>
            </div>
        </header>

        <!-- System Alerts -->
        <?php if (!empty($error)): ?>
            <div class="bg-rose-50 border border-rose-200/60 text-rose-700 px-4 py-3.5 rounded-xl text-xs mb-6 flex items-center gap-2.5">
                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <div class="bg-emerald-50 border border-emerald-200/60 text-emerald-700 px-4 py-3.5 rounded-xl text-xs mb-6 flex items-center gap-2.5">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Split Workspace Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start flex-1">
            
            <!-- Left Side: Tickets History List (4 Cols) -->
            <div class="lg:col-span-4 flex flex-col gap-4">
                <div class="glass-panel rounded-2xl p-4 flex flex-col shadow-sm">
                    <h2 class="text-xs font-bold text-slate-400 mb-4 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Lịch sử yêu cầu hỗ trợ
                    </h2>
                    
                    <div class="space-y-3 max-h-[600px] overflow-y-auto chat-scrollbar pr-1">
                        <?php if (empty($tickets)): ?>
                            <div class="text-center py-10 bg-slate-50 border border-slate-200/60 rounded-xl">
                                <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <p class="text-slate-400 text-xs">Bạn chưa gửi ticket hỗ trợ nào</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($tickets as $t): 
                                $isActive = ($activeTicket && $activeTicket['id'] == $t['id']);
                                $priorityColor = 'text-blue-600 bg-blue-50 border-blue-100';
                                if ($t['priority'] === 'high') {
                                    $priorityColor = 'text-rose-600 bg-rose-50 border-rose-100';
                                } elseif ($t['priority'] === 'medium') {
                                    $priorityColor = 'text-amber-600 bg-amber-50 border-amber-100';
                                }
                                
                                $statusColor = 'bg-slate-100 text-slate-500 border-slate-200';
                                $statusLabel = 'Đang xử lý';
                                if ($t['status'] === 'closed') {
                                    $statusColor = 'bg-slate-50 text-slate-400 border-slate-150';
                                    $statusLabel = 'Đã đóng';
                                } elseif ($t['status'] === 'replied') {
                                    $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                    $statusLabel = 'Đã trả lời';
                                }
                            ?>
                                <a href="?ticket_id=<?= $t['id'] ?>" class="block p-3.5 rounded-xl border transition-all duration-300 <?= $isActive ? 'bg-gradient-to-br from-blue-500/10 to-indigo-500/5 border-blue-500/30 shadow-sm shadow-blue-500/5' : 'bg-white border-slate-200/70 hover:border-slate-300 hover:bg-slate-50' ?>">
                                    <div class="flex justify-between items-start gap-2 mb-2">
                                        <span class="text-[10px] font-mono font-bold tracking-wider text-slate-450">#TK-<?= $t['id'] ?></span>
                                        <span class="px-2 py-0.5 text-[9px] font-bold rounded-md border <?= $statusColor ?>">
                                            <?= $statusLabel ?>
                                        </span>
                                    </div>
                                    <h3 class="text-xs font-bold text-slate-900 mb-2 line-clamp-1">
                                        <?= htmlspecialchars($t['subject']) ?>
                                    </h3>
                                    <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-slate-100">
                                        <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded border <?= $priorityColor ?>">
                                            <?= htmlspecialchars($t['priority']) ?>
                                        </span>
                                        <span class="text-[10px] text-slate-400"><?= date('d/m/Y H:i', $t['created_at']) ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Side: Chat Message Interface / Create Ticket Form (8 Cols) -->
            <div class="lg:col-span-8">
                
                <?php if ($activeTicket): ?>
                    <!-- Chat Bubble Interface -->
                    <div class="glass-panel rounded-2xl overflow-hidden flex flex-col h-[650px] animate-pop-in shadow-sm">
                        <!-- Chat Header -->
                        <div class="p-4 bg-slate-50 border-b border-slate-200/60 flex justify-between items-center flex-wrap gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center font-bold text-white text-sm shadow-md shadow-blue-500/10">
                                    TK
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h2 class="text-sm font-bold text-slate-900">#TK-<?= $activeTicket['id'] ?>: <?= htmlspecialchars($activeTicket['subject']) ?></h2>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] text-slate-500">Khởi tạo: <?= date('d/m/Y H:i', $activeTicket['created_at']) ?></span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="text-[10px] uppercase font-bold text-slate-500">Ưu tiên: <?= htmlspecialchars($activeTicket['priority']) ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ticket Status Indicator -->
                            <div class="flex items-center gap-2">
                                <?php if ($activeTicket['status'] !== 'closed'): ?>
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 animate-pulse">
                                        Hoạt động
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-slate-100 text-slate-500 border border-slate-200">
                                        Đã đóng
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Messages Body -->
                        <div id="chat-box" class="flex-1 p-4 overflow-y-auto space-y-4 chat-scrollbar bg-slate-50/20">
                            <?php foreach ($messages as $index => $msg): 
                                $isAdmin = ($msg['role'] === 'admin');
                                $bubbleBg = $isAdmin ? 'bg-white border border-slate-200/80 text-slate-800' : 'bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-sm shadow-blue-500/5';
                                $alignClass = $isAdmin ? 'justify-start' : 'justify-end';
                                $avatarBg = $isAdmin ? 'from-amber-500 to-orange-500' : 'from-blue-600 to-indigo-600';
                                $avatarInitials = $isAdmin ? 'AD' : 'KH';
                            ?>
                                <div class="flex items-end gap-2.5 <?= $alignClass ?> animate-pop-in">
                                    <?php if ($isAdmin): ?>
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br <?= $avatarBg ?> flex items-center justify-center font-extrabold text-white text-[10px] shrink-0 shadow-sm">
                                            <?= $avatarInitials ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="max-w-[80%] flex flex-col">
                                        <div class="text-[10px] text-slate-450 mb-1 px-1 flex items-center gap-1.5 <?= $isAdmin ? '' : 'justify-end' ?>">
                                            <span class="font-bold text-slate-655"><?= htmlspecialchars($msg['username']) ?></span>
                                            <span>•</span>
                                            <span><?= date('H:i d/m/Y', $msg['created_at']) ?></span>
                                        </div>
                                        <div class="rounded-2xl px-4 py-2.5 text-xs font-semibold leading-relaxed <?= $bubbleBg ?>">
                                            <p class="whitespace-pre-wrap"><?= htmlspecialchars($msg['message']) ?></p>
                                            
                                            <?php if (!empty($msg['file_attachment'])): ?>
                                                <div class="mt-2.5 pt-2.5 border-t border-slate-200/40 flex items-center gap-2">
                                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                    </svg>
                                                    <a href="/<?= htmlspecialchars($msg['file_attachment']) ?>" target="_blank" class="underline hover:opacity-80 transition text-[10px] truncate max-w-[200px]">
                                                        Tải tệp đính kèm
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!$isAdmin): ?>
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br <?= $avatarBg ?> flex items-center justify-center font-extrabold text-white text-[10px] shrink-0 shadow-sm">
                                            <?= $avatarInitials ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Chat Footer / Reply Area -->
                        <div class="p-4 bg-slate-50 border-t border-slate-200/60">
                            <?php if ($activeTicket['status'] !== 'closed'): ?>
                                <form method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                    <input type="hidden" name="action" value="reply_ticket">
                                    <input type="hidden" name="ticket_id" value="<?= $activeTicket['id'] ?>">
                                    
                                    <div class="relative">
                                        <textarea name="message" rows="2" required placeholder="Nhập câu trả lời hoặc gửi bổ sung thông tin lỗi..." class="w-full pl-4 pr-12 py-3 bg-white border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-xl outline-none transition text-xs text-slate-800 font-semibold resize-none chat-scrollbar"></textarea>
                                    </div>
                                    
                                    <div class="flex items-center justify-between gap-4 flex-wrap">
                                        <div class="flex items-center gap-2">
                                            <label class="cursor-pointer flex items-center gap-2 px-3 py-1.5 bg-slate-100 hover:bg-slate-200/60 border border-slate-200 rounded-lg text-[11px] text-slate-600 font-bold transition-all">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                </svg>
                                                <span>Đính kèm hình ảnh</span>
                                                <input type="file" name="attachment" class="hidden" onchange="updateFileName(this)">
                                            </label>
                                            <span id="file-name-preview" class="text-[10px] text-slate-400 italic max-w-[150px] truncate"></span>
                                        </div>
                                        
                                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition text-xs shadow-md shadow-blue-500/10 flex items-center gap-1.5">
                                            Gửi phản hồi
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="text-center py-2 text-slate-400 text-xs">
                                    Ticket này đã đóng. Nếu cần hỗ trợ thêm, hãy tạo ticket mới.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <script>
                        // Auto scroll to bottom of chat box
                        const chatBox = document.getElementById('chat-box');
                        if (chatBox) {
                            chatBox.scrollTop = chatBox.scrollHeight;
                        }
                        
                        function updateFileName(input) {
                            const label = document.getElementById('file-name-preview');
                            if (input.files && input.files.length > 0) {
                                label.textContent = input.files[0].name;
                            } else {
                                label.textContent = '';
                            }
                        }
                    </script>

                <?php else: ?>
                    <!-- Create Ticket Form Panel -->
                    <div class="glass-panel glass-panel-glow rounded-2xl p-6 md:p-8 animate-pop-in shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900">Gửi Yêu Cầu Hỗ Trợ Mới</h2>
                                <p class="text-[11px] text-slate-500">Mô tả rõ ràng vấn đề kỹ thuật để bộ phận lập trình viên xử lý nhanh nhất.</p>
                            </div>
                        </div>

                        <form method="POST" action="/support" enctype="multipart/form-data" class="space-y-5">
                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                            <input type="hidden" name="action" value="create_ticket">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="subject" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tiêu đề yêu cầu</label>
                                    <input type="text" id="subject" name="subject" required placeholder="Không thể kết nối API thiết bị..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none transition text-xs text-slate-800 font-semibold">
                                </div>
                                <div class="space-y-1.5">
                                    <label for="priority" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Mức độ ưu tiên</label>
                                    <select id="priority" name="priority" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none transition text-xs text-slate-800 font-semibold">
                                        <option value="low">Thấp (Low)</option>
                                        <option value="medium" selected>Trung bình (Medium)</option>
                                        <option value="high">Khẩn cấp (High)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="space-y-1.5">
                                <label for="message" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nội dung chi tiết</label>
                                <textarea id="message" name="message" rows="5" required placeholder="Mô tả cụ thể lỗi phát sinh, các bước tái lập lỗi, và hệ điều hành của bạn..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl outline-none transition text-xs text-slate-850 font-semibold resize-none chat-scrollbar"></textarea>
                            </div>
                            
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Hình ảnh lỗi hoặc Tệp đính kèm (nếu có)</label>
                                <div class="relative border border-dashed border-slate-200 hover:border-blue-500/50 rounded-xl p-6 transition-all bg-slate-50/50 text-center cursor-pointer group">
                                    <input type="file" id="attachment" name="attachment" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="updateNewTicketFileName(this)">
                                    <svg class="w-8 h-8 text-slate-400 group-hover:text-blue-500 transition-colors mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span id="new-ticket-file-label" class="text-xs text-slate-600 font-bold group-hover:text-slate-800 transition-colors">
                                        Kéo và thả hoặc nhấp để tải tệp lên
                                    </span>
                                    <p class="text-[10px] text-slate-455 mt-1">Hỗ trợ JPG, PNG, GIF hoặc các tệp cấu hình log</p>
                                </div>
                            </div>
                            
                            <div class="flex justify-end pt-2">
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/10 text-xs flex items-center gap-2">
                                    Gửi yêu cầu hỗ trợ
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <script>
                        function updateNewTicketFileName(input) {
                            const label = document.getElementById('new-ticket-file-label');
                            if (input.files && input.files.length > 0) {
                                label.textContent = "Đã chọn: " + input.files[0].name;
                                label.classList.add('text-blue-600');
                            } else {
                                label.textContent = "Kéo và thả hoặc nhấp để tải tệp lên";
                                label.classList.remove('text-blue-600');
                            }
                        }
                    </script>
                <?php endif; ?>

            </div>
        </div>
    </main>

</body>
</html>
