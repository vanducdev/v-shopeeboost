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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .card-surface {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-surface:hover {
            border-color: #cbd5e1;
        }

        .ticket-item {
            transition: all 0.15s ease;
        }
        .ticket-item:hover {
            border-color: #93c5fd;
            background-color: #f8fafc;
        }
        .ticket-item-active {
            border-color: #2563eb !important;
            background-color: #eff6ff !important;
            box-shadow: 0 0 0 1px #2563eb;
        }

        /* Subtle scrollbars */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 min-h-screen flex flex-col md:flex-row antialiased">

    <!-- Client Sidebar -->
    <?php require BASE_PATH . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="flex-1 p-4 md:p-7 lg:p-8 max-w-[1500px] mx-auto w-full overflow-x-hidden flex flex-col">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-6 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">
                        Hỗ Trợ Kỹ Thuật
                    </h1>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                        Helpdesk 24/7
                    </span>
                </div>
                <p class="text-slate-500 text-xs mt-0.5">
                    Gửi yêu cầu hỗ trợ, phản hồi lỗi phần mềm hoặc yêu cầu kỹ thuật viên hỗ trợ UltraView cài đặt từ xa.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="/support" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-plus text-[11px]"></i>
                    <span>Tạo Ticket Mới</span>
                </a>
                
                <a href="https://zalo.me/0359261551" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#0068ff] hover:bg-blue-600 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-comment-dots text-[11px]"></i>
                    <span>Zalo Kỹ Thuật (0359.261.551)</span>
                </a>
            </div>
        </div>

        <!-- Status Alerts -->
        <?php if (!empty($error)): ?>
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-bold mb-5 flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-bold mb-5 flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php
        $totalTickets = count($tickets ?? []);
        $openTickets = 0;
        $repliedTickets = 0;
        foreach ($tickets ?? [] as $tk) {
            if ($tk['status'] === 'open') $openTickets++;
            if ($tk['status'] === 'replied') $repliedTickets++;
        }
        ?>

        <!-- Compact 4 KPI Stats Overview -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">KỸ THUẬT TRỰC TUYẾN</span>
                    <span class="text-xs font-black text-slate-900 tracking-tight mt-1 block flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Đang Online 24/7</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-headset"></i>
                </div>
            </div>

            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">TỔNG YÊU CẦU ĐÃ GỬI</span>
                    <span class="text-xl font-black text-slate-900 tracking-tight mt-0.5 block">
                        <?= $totalTickets ?> <span class="text-xs font-semibold text-slate-400">ticket</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-ticket"></i>
                </div>
            </div>

            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">THỜI GIAN PHẢN HỒI</span>
                    <span class="text-xs font-black text-slate-900 tracking-tight mt-1 block flex items-center gap-1.5">
                        <i class="fa-solid fa-bolt text-amber-500"></i>
                        <span>Dưới 15 Phút</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>

            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">KÊNH HỖ TRỢ TỪ XA</span>
                    <span class="text-xs font-black text-indigo-700 tracking-tight mt-1 block flex items-center gap-1">
                        <i class="fa-solid fa-desktop text-indigo-500"></i>
                        <span>UltraView & AnyDesk</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-remote-desktop"></i>
                </div>
            </div>
        </section>

        <!-- Main Support Workspace (Split Columns) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start flex-1">
            
            <!-- Left Side: Tickets List (4 Cols) -->
            <div class="lg:col-span-4 space-y-3">
                <div class="card-surface rounded-2xl p-4">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                        <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                            <span>Danh Sách Ticket (<?= $totalTickets ?>)</span>
                        </h2>
                        <a href="/support" class="text-blue-600 hover:underline text-[11px] font-bold">+ Tạo Mới</a>
                    </div>

                    <!-- Search Input -->
                    <div class="relative mb-3">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input 
                            type="text" 
                            id="searchTicketInput" 
                            onkeyup="searchTickets()" 
                            placeholder="Tìm kiếm ticket..." 
                            class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl text-xs outline-none transition"
                        >
                    </div>

                    <!-- Ticket List Container -->
                    <div class="space-y-2 max-h-[560px] overflow-y-auto pr-1">
                        <?php if (empty($tickets)): ?>
                            <div class="text-center py-8 text-slate-400 text-xs">
                                <i class="fa-solid fa-inbox text-2xl mb-1.5 text-slate-300 block"></i>
                                Bạn chưa gửi ticket hỗ trợ nào.
                            </div>
                        <?php else: ?>
                            <?php foreach ($tickets as $t): 
                                $isActive = ($activeTicket && $activeTicket['id'] == $t['id']);
                                $statusClass = 'bg-slate-100 text-slate-600 border-slate-200';
                                $statusText = 'Đang xử lý';

                                if ($t['status'] === 'replied') {
                                    $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                    $statusText = 'Đã trả lời';
                                } else if ($t['status'] === 'closed') {
                                    $statusClass = 'bg-slate-50 text-slate-400 border-slate-200';
                                    $statusText = 'Đã đóng';
                                }

                                $priorityBadge = 'bg-slate-100 text-slate-600';
                                if ($t['priority'] === 'high') {
                                    $priorityBadge = 'bg-rose-50 text-rose-600 border border-rose-200';
                                } else if ($t['priority'] === 'medium') {
                                    $priorityBadge = 'bg-amber-50 text-amber-600 border border-amber-200';
                                }
                            ?>
                                <a 
                                    href="?ticket_id=<?= $t['id'] ?>" 
                                    class="ticket-item block p-3 rounded-xl border border-slate-200 <?= $isActive ? 'ticket-item-active' : 'bg-white' ?>"
                                    data-search="<?= strtolower(htmlspecialchars($t['subject'] . ' #tk-' . $t['id'])) ?>"
                                >
                                    <div class="flex items-center justify-between gap-2 mb-1.5">
                                        <span class="text-[10px] font-mono font-bold text-blue-700">#TK-<?= $t['id'] ?></span>
                                        <span class="px-2 py-0.5 text-[9px] font-extrabold rounded-full border <?= $statusClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </div>

                                    <h3 class="text-xs font-bold text-slate-900 leading-snug line-clamp-1 mb-2">
                                        <?= htmlspecialchars($t['subject']) ?>
                                    </h3>

                                    <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1.5 border-t border-slate-100">
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase <?= $priorityBadge ?>">
                                            <?= htmlspecialchars($t['priority']) ?>
                                        </span>
                                        <span><?= date('H:i d/m/Y', $t['created_at']) ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Fast Contact Help Card -->
                <div class="card-surface rounded-2xl p-4 bg-gradient-to-br from-blue-50/40 via-white to-slate-50 border-blue-100 space-y-2.5 text-xs">
                    <span class="font-extrabold text-slate-800 text-xs block flex items-center gap-1.5">
                        <i class="fa-solid fa-phone-volume text-blue-600"></i>
                        <span>Cần Hỗ Trợ Khẩn Cấp?</span>
                    </span>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Với các sự cố gián đoạn tài khoản hoặc cần cài UltraView gấp, bạn có thể gọi hotline trực tiếp.
                    </p>
                    <div class="pt-1 flex items-center justify-between">
                        <span class="font-mono font-black text-blue-700 text-xs">0359.261.551</span>
                        <a href="tel:0359261551" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-[10px] transition">
                            Gọi Ngay
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side: Chat Conversation Thread OR New Ticket Form (8 Cols) -->
            <div class="lg:col-span-8">
                
                <?php if ($activeTicket): ?>
                    <!-- CONVERSATION CHAT VIEW -->
                    <div class="card-surface rounded-2xl overflow-hidden flex flex-col h-[650px]">
                        
                        <!-- Chat Header -->
                        <div class="p-4 bg-white border-b border-slate-200 flex justify-between items-center flex-wrap gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-xs shadow-sm">
                                    <i class="fa-solid fa-comments"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-mono font-bold text-slate-400">#TK-<?= $activeTicket['id'] ?></span>
                                        <h2 class="text-xs font-black text-slate-900 leading-snug">
                                            <?= htmlspecialchars($activeTicket['subject']) ?>
                                        </h2>
                                    </div>
                                    <div class="flex items-center gap-2 mt-0.5 text-[10px] text-slate-500">
                                        <span>Gửi lúc: <?= date('H:i d/m/Y', $activeTicket['created_at']) ?></span>
                                        <span>•</span>
                                        <span class="font-bold uppercase">Ưu tiên: <?= htmlspecialchars($activeTicket['priority']) ?></span>
                                    </div>
                                </div>
                            </div>

                            <a href="/support" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition flex items-center gap-1">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                <span>Ticket Mới</span>
                            </a>
                        </div>

                        <!-- Chat Messages Body -->
                        <div id="chatBoxContainer" class="flex-1 p-4 overflow-y-auto space-y-4 bg-slate-50/50">
                            <?php foreach ($messages as $msg): 
                                $isAdmin = ($msg['role'] === 'admin');
                                $bubbleBg = $isAdmin ? 'bg-white border border-slate-200 text-slate-800' : 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-sm';
                                $alignClass = $isAdmin ? 'justify-start' : 'justify-end';
                                $avatarLabel = $isAdmin ? 'VMAX Support' : htmlspecialchars($msg['username']);
                            ?>
                                <div class="flex items-end gap-2.5 <?= $alignClass ?>">
                                    <?php if ($isAdmin): ?>
                                        <div class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                            <i class="fa-solid fa-shield-halved text-[10px] text-blue-400"></i>
                                        </div>
                                    <?php endif; ?>

                                    <div class="max-w-[85%] sm:max-w-[75%] flex flex-col">
                                        <div class="text-[10px] text-slate-400 mb-1 px-1 flex items-center gap-1.5 <?= $isAdmin ? '' : 'justify-end' ?>">
                                            <span class="font-bold <?= $isAdmin ? 'text-blue-700' : 'text-slate-600' ?>">
                                                <?= $isAdmin ? 'Kỹ Thuật Viên VMAX' : $avatarLabel ?>
                                            </span>
                                            <span>•</span>
                                            <span><?= date('H:i d/m/Y', $msg['created_at']) ?></span>
                                        </div>

                                        <div class="rounded-2xl px-4 py-2.5 text-xs font-medium leading-relaxed <?= $bubbleBg ?>">
                                            <p class="whitespace-pre-wrap"><?= htmlspecialchars($msg['message']) ?></p>

                                            <?php if (!empty($msg['file_attachment'])): ?>
                                                <div class="mt-2.5 pt-2 border-t <?= $isAdmin ? 'border-slate-100' : 'border-white/20' ?> flex items-center gap-2">
                                                    <i class="fa-solid fa-paperclip text-xs"></i>
                                                    <a href="/<?= htmlspecialchars($msg['file_attachment']) ?>" target="_blank" class="underline hover:opacity-80 text-[10px] font-bold truncate max-w-[200px]">
                                                        Xem tệp đính kèm
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!$isAdmin): ?>
                                        <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-bold shrink-0 uppercase">
                                            <?= strtoupper(substr($msg['username'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Chat Reply Input -->
                        <div class="p-3.5 bg-white border-t border-slate-200">
                            <?php if ($activeTicket['status'] !== 'closed'): ?>
                                <form method="POST" action="/support" enctype="multipart/form-data" class="space-y-2.5">
                                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                    <input type="hidden" name="action" value="reply_ticket">
                                    <input type="hidden" name="ticket_id" value="<?= $activeTicket['id'] ?>">

                                    <textarea 
                                        name="message" 
                                        rows="2" 
                                        required 
                                        placeholder="Nhập câu trả lời hoặc gửi bổ sung thông tin UltraView / lỗi..." 
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl text-xs font-medium text-slate-900 outline-none transition resize-none"
                                    ></textarea>

                                    <div class="flex items-center justify-between gap-3 flex-wrap">
                                        <div class="flex items-center gap-2">
                                            <label class="cursor-pointer flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg text-[11px] text-slate-700 font-bold transition">
                                                <i class="fa-solid fa-paperclip text-[10px]"></i>
                                                <span>Đính kèm ảnh</span>
                                                <input type="file" name="attachment" class="hidden" onchange="previewFileName(this)">
                                            </label>
                                            <span id="fileNameLabel" class="text-[10px] text-slate-400 italic truncate max-w-[150px]"></span>
                                        </div>

                                        <button 
                                            type="submit" 
                                            class="px-5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold rounded-xl text-xs shadow-sm transition flex items-center gap-1.5"
                                        >
                                            <i class="fa-solid fa-paper-plane text-[10px]"></i>
                                            <span>Gửi Phản Hồi</span>
                                        </button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="text-center py-2 text-slate-400 text-xs">
                                    Ticket này đã hoàn thành và được đóng.
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                    <script>
                        const box = document.getElementById('chatBoxContainer');
                        if (box) box.scrollTop = box.scrollHeight;

                        function previewFileName(input) {
                            const lbl = document.getElementById('fileNameLabel');
                            if (input.files && input.files.length > 0) {
                                lbl.innerText = input.files[0].name;
                            } else {
                                lbl.innerText = '';
                            }
                        }
                    </script>

                <?php else: ?>
                    <!-- CREATE NEW TICKET FORM VIEW -->
                    <div class="card-surface rounded-2xl p-5 md:p-6">
                        <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-slate-100">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </span>
                            <div>
                                <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Gửi Yêu Cầu Hỗ Trợ Mới</h2>
                                <p class="text-[10px] text-slate-400">Mô tả rõ ràng vấn đề kỹ thuật để chuyên viên xử lý nhanh nhất</p>
                            </div>
                        </div>

                        <form method="POST" action="/support" enctype="multipart/form-data" class="space-y-4">
                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                            <input type="hidden" name="action" value="create_ticket">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Tiêu đề vấn đề</label>
                                    <input 
                                        type="text" 
                                        name="subject" 
                                        required 
                                        placeholder="Ví dụ: Lỗi không mở được Profile Chrome trên VmaxLogin..." 
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl text-xs font-bold text-slate-800 outline-none transition"
                                    >
                                </div>

                                <div>
                                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Mức độ ưu tiên</label>
                                    <select name="priority" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl text-xs font-bold text-slate-800 outline-none transition appearance-none cursor-pointer">
                                        <option value="low">Thấp (Tư vấn chung)</option>
                                        <option value="medium" selected>Trung bình (Lỗi phần mềm)</option>
                                        <option value="high">Khẩn cấp (Gián đoạn kinh doanh / Cần UltraView ngay)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Nội dung chi tiết & ID UltraView (nếu có)</label>
                                <textarea 
                                    name="message" 
                                    rows="5" 
                                    required 
                                    placeholder="Mô tả chi tiết các bước xảy ra lỗi, phiên bản Windows của bạn, và ID/Mật khẩu UltraView nếu cần nhân viên kỹ thuật kết nối trực tiếp..." 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:bg-white rounded-xl text-xs font-medium text-slate-800 outline-none transition resize-none"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Ảnh chụp màn hình lỗi hoặc tệp đính kèm (nếu có)</label>
                                <div class="border border-dashed border-slate-200 hover:border-blue-400 rounded-xl p-5 text-center bg-slate-50/50 hover:bg-slate-50 transition relative cursor-pointer">
                                    <input type="file" name="attachment" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewNewTicketFile(this)">
                                    <i class="fa-solid fa-cloud-arrow-up text-xl text-slate-400 mb-1.5 block"></i>
                                    <span id="newTicketFileLabel" class="text-xs font-bold text-slate-600 block">
                                        Nhấp để chọn ảnh chụp lỗi từ máy tính
                                    </span>
                                    <span class="text-[10px] text-slate-400 mt-0.5 block">Hỗ trợ PNG, JPG, GIF hoặc tệp log văn bản</span>
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold rounded-xl text-xs shadow-md shadow-blue-500/10 transition flex items-center justify-center gap-2 cursor-pointer mt-2"
                            >
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                <span>Gửi Yêu Cầu Hỗ Trợ Ngay</span>
                            </button>
                        </form>
                    </div>

                    <script>
                        function previewNewTicketFile(input) {
                            const lbl = document.getElementById('newTicketFileLabel');
                            if (input.files && input.files.length > 0) {
                                lbl.innerText = "Đã chọn: " + input.files[0].name;
                                lbl.classList.add('text-blue-600');
                            } else {
                                lbl.innerText = "Nhấp để chọn ảnh chụp lỗi từ máy tính";
                                lbl.classList.remove('text-blue-600');
                            }
                        }
                    </script>
                <?php endif; ?>

            </div>

        </div>

    </main>

    <script>
        function searchTickets() {
            const query = (document.getElementById('searchTicketInput').value || '').toLowerCase().trim();
            const items = document.querySelectorAll('.ticket-item');

            items.forEach(it => {
                const searchData = it.getAttribute('data-search') || '';
                if (!query || searchData.includes(query)) {
                    it.style.display = '';
                } else {
                    it.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>