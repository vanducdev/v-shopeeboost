<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hỗ Trợ Khách Hàng - VMAX Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .chat-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .chat-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.02);
        }
        .chat-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 99px;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Admin Sidebar -->
    <?php require BASE_PATH . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-4 md:p-8 max-w-7xl mx-auto w-full flex flex-col min-h-screen">
        <header class="mb-6 flex justify-between items-center pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Hộp Thư Hỗ Trợ Khách Hàng</h1>
                <p class="text-slate-500 text-xs mt-1">Đọc và trả lời trực tiếp các yêu cầu hỗ trợ, thắc mắc kỹ thuật từ người dùng.</p>
            </div>
        </header>

        <!-- Alerts -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl text-xs mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2.5 rounded-xl text-xs mb-4">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Split Workspace Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch flex-1">
            
            <!-- Left Side: Tickets List (4 Cols) -->
            <div class="lg:col-span-4 bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm flex flex-col max-h-[700px]">
                <h2 class="text-xs font-extrabold text-slate-400 mb-4 uppercase tracking-wider">Danh sách Ticket (<?= count($tickets) ?>)</h2>
                <div class="space-y-2.5 overflow-y-auto chat-scrollbar flex-1 pr-1">
                    <?php if (empty($tickets)): ?>
                        <div class="text-center py-10 text-slate-400 italic text-xs">Không có yêu cầu hỗ trợ nào.</div>
                    <?php else: ?>
                        <?php foreach ($tickets as $t): 
                            $isActive = ($activeTicket && $activeTicket['id'] == $t['id']);
                            $priorityColor = 'text-blue-600 bg-blue-50 border-blue-100';
                            if ($t['priority'] === 'high') {
                                $priorityColor = 'text-red-600 bg-red-50 border-red-100';
                            } elseif ($t['priority'] === 'medium') {
                                $priorityColor = 'text-amber-600 bg-amber-50 border-amber-100';
                            }

                            $statusColor = 'bg-slate-100 text-slate-500 border-slate-200';
                            if ($t['status'] === 'closed') {
                                $statusColor = 'bg-slate-50 text-slate-400 border-slate-150';
                            } elseif ($t['status'] === 'replied') {
                                $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                            }
                        ?>
                            <a href="?ticket_id=<?= $t['id'] ?>" class="block p-3 rounded-xl border transition-all duration-200 <?= $isActive ? 'bg-blue-50/50 border-blue-400/80 shadow-sm' : 'bg-white border-slate-100 hover:bg-slate-50' ?>">
                                <div class="flex justify-between items-start gap-2 mb-1.5">
                                    <span class="text-[10px] font-mono text-slate-400">#TK-<?= $t['id'] ?></span>
                                    <span class="px-2 py-0.5 text-[9px] font-bold rounded-md border <?= $statusColor ?>">
                                        <?= $t['status'] === 'closed' ? 'Đã đóng' : ($t['status'] === 'replied' ? 'Đã trả lời' : 'Mới') ?>
                                    </span>
                                </div>
                                <h3 class="text-xs font-bold text-slate-900 mb-2 truncate"><?= htmlspecialchars($t['subject']) ?></h3>
                                <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-50">
                                    <span class="text-[10px] text-slate-500 font-bold">BY: <?= htmlspecialchars($t['username']) ?></span>
                                    <span class="px-1.5 py-0.5 text-[8px] font-bold uppercase rounded border <?= $priorityColor ?>"><?= htmlspecialchars($t['priority']) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Side: Active Ticket chat dialog (8 Cols) -->
            <div class="lg:col-span-8 flex flex-col bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden max-h-[700px]">
                <?php if ($activeTicket): ?>
                    <!-- Active Ticket Header -->
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono font-bold text-slate-400">#TK-<?= $activeTicket['id'] ?></span>
                                <h2 class="text-sm font-bold text-slate-900"><?= htmlspecialchars($activeTicket['subject']) ?></h2>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Người gửi: <strong><?= htmlspecialchars($activeTicket['username']) ?></strong> | Ngày tạo: <?= date('d/m/Y H:i', $activeTicket['created_at']) ?></p>
                        </div>
                        <div class="flex gap-2">
                            <?php if ($activeTicket['status'] !== 'closed'): ?>
                                <form method="POST" action="/admin/tickets">
                                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                    <input type="hidden" name="ticket_id" value="<?= $activeTicket['id'] ?>">
                                    <input type="hidden" name="action" value="close_ticket">
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-650 hover:bg-red-100 text-xs font-bold rounded-lg border border-red-200 transition" style="color: #dc2626;">Đóng Ticket</button>
                                </form>
                            <?php else: ?>
                                <span class="px-3 py-1.5 bg-slate-100 text-slate-400 text-xs font-bold rounded-lg border border-slate-200">Đã đóng</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Conversation Thread Box -->
                    <div class="flex-1 p-4 overflow-y-auto chat-scrollbar space-y-4 bg-slate-50/20">
                        <?php foreach ($messages as $msg): 
                            $isAdmin = ($msg['role'] === 'admin');
                        ?>
                            <div class="flex flex-col <?= $isAdmin ? 'items-end' : 'items-start' ?>">
                                <div class="flex items-center gap-1.5 mb-1 text-[10px] text-slate-400">
                                    <strong><?= htmlspecialchars($msg['username']) ?></strong>
                                    <span>(<?= $isAdmin ? 'Admin' : 'Khách hàng' ?>)</span>
                                </div>
                                <div class="max-w-[80%] rounded-2xl p-3 text-xs shadow-sm <?= $isAdmin ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white border border-slate-100 text-slate-800 rounded-tl-none' ?>">
                                    <div class="leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($msg['message']) ?></div>
                                    
                                    <!-- Render file attachment if exists -->
                                    <?php if (!empty($msg['file_attachment'])): 
                                        $filePath = '/' . $msg['file_attachment'];
                                        $fileName = basename($msg['file_attachment']);
                                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                    ?>
                                        <div class="mt-2.5 pt-2 border-t <?= $isAdmin ? 'border-blue-500/50' : 'border-slate-100' ?>">
                                            <?php if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])): ?>
                                                <a href="<?= $filePath ?>" target="_blank" class="block group relative rounded-lg overflow-hidden border border-slate-200/40">
                                                    <img src="<?= $filePath ?>" alt="Attachment" class="max-h-40 w-auto object-cover group-hover:opacity-90 transition">
                                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-[10px] text-white font-bold">Xem ảnh lớn</div>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= $filePath ?>" target="_blank" class="flex items-center gap-1.5 font-bold <?= $isAdmin ? 'text-blue-100 hover:text-white' : 'text-blue-650 hover:text-blue-800' ?>">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    Tải tệp đính kèm: <?= htmlspecialchars($fileName) ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <span class="text-[9px] text-slate-400 mt-1"><?= date('d/m/Y H:i', $msg['created_at']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Conversation Footer Input Reply -->
                    <div class="p-4 border-t border-slate-100 bg-white">
                        <?php if ($activeTicket['status'] !== 'closed'): ?>
                            <form method="POST" action="/admin/tickets" class="flex gap-2">
                                <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                <input type="hidden" name="ticket_id" value="<?= $activeTicket['id'] ?>">
                                <input type="hidden" name="action" value="reply">
                                <textarea name="message" required rows="2" placeholder="Nhập câu trả lời cho khách hàng..." class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:border-blue-500 focus:bg-white resize-none transition"></textarea>
                                <button type="submit" class="px-4 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition flex items-center justify-center">Gửi Phản Hồi</button>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-2 text-slate-400 italic text-xs bg-slate-50 rounded-xl">Ticket này đã đóng. Không thể gửi thêm phản hồi.</div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="flex-1 flex flex-col items-center justify-center p-10 text-center bg-slate-50/50">
                        <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <h3 class="text-sm font-bold text-slate-700 mb-1">Hộp Hội Thoại Hỗ Trợ</h3>
                        <p class="text-xs text-slate-400 max-w-xs">Hãy chọn một ticket ở danh sách bên trái để đọc toàn bộ lịch sử trò chuyện và bắt đầu hồi đáp khách hàng.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

</body>
</html>
