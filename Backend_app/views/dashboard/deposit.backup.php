<?php
$db = DB::getInstance();
// Fetch 5 most recent deposits of this user
$stmt = $db->query("SELECT * FROM deposits WHERE user_id = ? ORDER BY created_at DESC LIMIT 5", [$user['id']]);
$recentDeposits = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nạp Tiền Hệ Thống - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        /* QR scanner animation */
        .scanner-container {
            position: relative;
            overflow: hidden;
        }
        .scanner-line {
            position: absolute;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, transparent, #3b82f6, #60a5fa, #3b82f6, transparent);
            box-shadow: 0 0 12px #3b82f6;
            left: 0;
            top: 0;
            animation: scan 3s ease-in-out infinite;
            z-index: 10;
        }
        @keyframes scan {
            0% { top: 0%; }
            50% { top: 100%; }
            100% { top: 0%; }
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
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Nạp tiền hệ thống
                </h1>
                <p class="text-slate-500 text-xs mt-1 font-medium">Nạp tiền vào ví tài khoản để mua hoặc gia hạn giấy phép key bản quyền phần mềm.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-50 text-emerald-700 text-xs font-extrabold rounded-2xl border border-emerald-100 shadow-sm">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    Số dư: <?= number_format($user['balance'] ?? 0, 0, ',', '.') ?>đ
                </span>
            </div>
        </header>

        <!-- Visual Progress Stepper -->
        <div class="mb-10 max-w-3xl mx-auto px-4">
            <div class="flex items-center justify-between text-[10px] font-extrabold text-slate-400 relative">
                <!-- Line background -->
                <div class="absolute top-[18px] left-0 right-0 h-1 bg-slate-200/50 -translate-y-1/2 z-0 rounded-full"></div>
                <div class="absolute top-[18px] left-0 <?= $pendingDeposit ? 'w-1/2' : 'w-0' ?> h-1 bg-gradient-to-r from-blue-500 to-indigo-600 -translate-y-1/2 z-0 transition-all duration-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.3)]"></div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center gap-2 bg-[#f8fafc] px-4">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center border-2 <?= $pendingDeposit ? 'bg-emerald-500 border-emerald-500 text-white shadow-md shadow-emerald-500/20' : 'bg-blue-600 border-blue-600 text-white shadow-md shadow-blue-500/20' ?> text-xs transition-all duration-300">
                        <?php if ($pendingDeposit): ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        <?php else: ?>
                            1
                        <?php endif; ?>
                    </span>
                    <span class="<?= !$pendingDeposit ? 'text-blue-600 font-extrabold' : 'text-slate-500' ?> uppercase tracking-wider">1. Nhập số tiền</span>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center gap-2 bg-[#f8fafc] px-4">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center border-2 <?= $pendingDeposit ? 'bg-blue-600 border-blue-600 text-white animate-pulse shadow-md shadow-blue-500/20' : 'bg-white border-slate-200 text-slate-400' ?> text-xs transition-all duration-300">2</span>
                    <span class="<?= $pendingDeposit ? 'text-blue-600 font-extrabold' : 'text-slate-500' ?> uppercase tracking-wider">2. Quét mã QR Bank</span>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center gap-2 bg-[#f8fafc] px-4">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center border-2 bg-white border-slate-200 text-slate-400 text-xs transition-all duration-300">3</span>
                    <span class="text-slate-500 uppercase tracking-wider">3. Hoàn tất</span>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Deposit Creation Form or QR Billing details -->
            <div class="lg:col-span-2 space-y-6">
                
                <?php if ($pendingDeposit): ?>
                    <!-- Pending QR Bank billing card -->
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm transition hover:shadow-md relative overflow-hidden">
                        
                        <!-- Top status bar -->
                        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                            <div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Trạng thái giao dịch</span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 text-[10px] font-extrabold rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span>
                                    Chờ chuyển khoản...
                                </span>
                            </div>
                            <div class="text-right flex items-center gap-2.5">
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Hết hạn sau</span>
                                    <div class="flex items-center gap-1.5 bg-rose-50 border border-rose-100 px-3 py-1 rounded-xl">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></span>
                                        <span id="countdown" class="font-mono font-extrabold text-rose-600 text-sm">15:00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Grid content: QR code + Info -->
                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            
                            <!-- QR Codes Area -->
                            <div class="space-y-5 text-center">
                                <div class="relative bg-slate-50 border border-slate-200 p-4 rounded-2xl flex justify-center shadow-inner group scanner-container">
                                    <!-- Animated scanner line -->
                                    <div class="scanner-line"></div>
                                    <?php 
                                    $vietQrUrl = "https://img.vietqr.io/image/MB-0359261551-print.png?amount=" . $pendingDeposit['amount'] . "&addInfo=" . urlencode($pendingDeposit['reference_code']) . "&accountName=NGUYEN%20VAN%20DUC";
                                    ?>
                                    <img id="qrImage" src="<?= $vietQrUrl ?>" alt="VietQR Bank" class="w-64 h-64 object-contain rounded-lg transition duration-300 group-hover:scale-[1.01]">
                                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 bg-slate-950/80 text-white text-[9px] font-bold px-2.5 py-1 rounded-full opacity-0 group-hover:opacity-100 transition">Quét để chuyển khoản nhanh</div>
                                </div>
                                
                                <div class="flex justify-center p-1 bg-slate-100 border border-slate-200/50 rounded-2xl w-fit mx-auto">
                                    <button onclick="toggleQr('vietqr')" id="btnVietQr" class="px-4 py-2 bg-white text-slate-800 text-[10px] font-extrabold rounded-xl shadow-sm border border-slate-200/30 transition-all duration-300">Mã QR VietQR</button>
                                    <button onclick="toggleQr('static')" id="btnStatic" class="px-4 py-2 text-slate-500 hover:text-slate-800 text-[10px] font-extrabold rounded-xl transition-all duration-300">Mã QR Gốc</button>
                                </div>
                            </div>

                            <!-- Account Details Area -->
                            <div class="space-y-4">
                                <div class="bg-slate-50 border border-slate-200/60 p-5 rounded-2xl space-y-4 shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <img src="/image/logo-app-vmax-64x.png" class="w-6 h-6 object-contain rounded" alt="MB logo">
                                        <div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Ngân hàng thụ hưởng</span>
                                            <span class="font-extrabold text-slate-800 text-xs">MB Bank (Ngân hàng Quân Đội)</span>
                                        </div>
                                    </div>

                                    <div>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Số tài khoản</span>
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="font-bold text-blue-600 text-base" id="accNum">0359261551</span>
                                            <button onclick="copyText(this, '0359261551')" class="text-[10px] bg-blue-50 text-blue-650 font-extrabold px-3 py-1.5 rounded-xl hover:bg-blue-100 transition-all duration-200">Sao chép</button>
                                        </div>
                                    </div>

                                    <div>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Tên người nhận</span>
                                        <span class="font-bold text-slate-800 text-sm uppercase">NGUYEN VAN DUC</span>
                                    </div>

                                    <div>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Số tiền cần chuyển</span>
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="font-extrabold text-emerald-600 text-lg"><?= number_format($pendingDeposit['amount'], 0, ',', '.') ?>đ</span>
                                            <button onclick="copyText(this, '<?= $pendingDeposit['amount'] ?>')" class="text-[10px] bg-emerald-50 text-emerald-600 font-extrabold px-3 py-1.5 rounded-xl hover:bg-emerald-105 transition-all duration-200">Sao chép</button>
                                        </div>
                                    </div>

                                    <div class="bg-amber-50/50 border border-amber-200/80 p-4.5 rounded-2xl relative overflow-hidden">
                                        <span class="text-[9px] font-extrabold text-amber-800 uppercase tracking-wider block mb-1.5">Nội dung chuyển khoản (Ghi chính xác 100%)</span>
                                        <div class="flex items-center justify-between">
                                            <span class="font-mono font-extrabold text-amber-700 text-sm tracking-wider" id="memoText"><?= htmlspecialchars($pendingDeposit['reference_code']) ?></span>
                                            <button onclick="copyText(this, '<?= htmlspecialchars($pendingDeposit['reference_code']) ?>')" class="text-[10px] bg-amber-100 text-amber-800 font-extrabold px-3 py-1.5 rounded-xl hover:bg-amber-200 transition-all duration-200">Sao chép</button>
                                        </div>
                                        <div class="absolute -right-6 -top-6 w-16 h-16 bg-amber-400/10 rounded-full blur-xl"></div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-2 flex flex-col gap-2.5">
                                    <div class="p-3.5 bg-blue-50 border border-blue-100/50 rounded-xl text-blue-800 text-[10.5px] leading-relaxed font-medium">
                                        <strong>Chú ý:</strong> Hệ thống sẽ tự động duyệt giao dịch của bạn ngay sau khi nhận được thông báo từ phía Ngân hàng.
                                    </div>
                                    <form method="POST" action="/deposit">
                                        <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                        <input type="hidden" name="action" value="cancel_deposit">
                                        <input type="hidden" name="deposit_id" value="<?= $pendingDeposit['id'] ?>">
                                        <button type="submit" class="w-full py-3 bg-slate-50 hover:bg-rose-50 hover:text-rose-600 border border-slate-200 text-slate-500 font-bold rounded-2xl text-xs transition duration-200 shadow-sm">
                                            Hủy hóa đơn QR này & Tạo yêu cầu mới
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <script>
                        // Timer logic
                        let timeLeft = 900 - (Math.floor(Date.now() / 1000) - <?= $pendingDeposit['created_at'] ?>);
                        if (timeLeft < 0) timeLeft = 0;

                        const countdownEl = document.getElementById('countdown');
                        const interval = setInterval(() => {
                            if (timeLeft <= 0) {
                                clearInterval(interval);
                                countdownEl.innerHTML = "Hết hạn";
                                location.reload();
                                return;
                            }
                            timeLeft--;
                            const mins = Math.floor(timeLeft / 60);
                            const secs = timeLeft % 60;
                            countdownEl.innerHTML = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                        }, 1000);

                        // Polling transaction status every 3 seconds
                        const pollInterval = setInterval(() => {
                            fetch(`/deposit?check_status=1&deposit_id=<?= $pendingDeposit['id'] ?>`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success && data.status === 'approved') {
                                        clearInterval(pollInterval);
                                        clearInterval(interval);
                                        
                                        Swal.fire({
                                            title: 'Nạp Tiền Thành Công!',
                                            html: `Bạn đã nạp thành công <strong>${data.amount.toLocaleString('vi-VN')}đ</strong> vào tài khoản.<br>Giao dịch hoàn tất!`,
                                            icon: 'success',
                                            confirmButtonText: 'Đóng',
                                            confirmButtonColor: '#2563eb',
                                            timer: 5000,
                                            timerProgressBar: true
                                        }).then(() => {
                                            location.href = '/deposit';
                                        });
                                    } else if (data.success && data.status === 'rejected') {
                                        clearInterval(pollInterval);
                                        clearInterval(interval);
                                        
                                        Swal.fire({
                                            title: 'Giao Dịch Bị Hủy',
                                            text: 'Yêu cầu nạp tiền của bạn đã bị từ chối hoặc hủy bỏ.',
                                            icon: 'error',
                                            confirmButtonText: 'Quay lại',
                                            confirmButtonColor: '#dc2626'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    }
                                })
                                .catch(err => console.error("Lỗi polling: ", err));
                        }, 3000);

                        // Toggle QR image
                        function toggleQr(type) {
                            const qrImage = document.getElementById('qrImage');
                            const btnVietQr = document.getElementById('btnVietQr');
                            const btnStatic = document.getElementById('btnStatic');
                            if (type === 'static') {
                                qrImage.src = "/qrbank/qr-bank.jpg";
                                btnStatic.className = "px-4 py-2 bg-white text-slate-800 text-[10px] font-extrabold rounded-xl shadow-sm border border-slate-200/30 transition-all duration-300";
                                btnVietQr.className = "px-4 py-2 text-slate-500 hover:text-slate-800 text-[10px] font-extrabold rounded-xl transition-all duration-300";
                            } else {
                                qrImage.src = "<?= $vietQrUrl ?>";
                                btnVietQr.className = "px-4 py-2 bg-white text-slate-800 text-[10px] font-extrabold rounded-xl shadow-sm border border-slate-200/30 transition-all duration-300";
                                btnStatic.className = "px-4 py-2 text-slate-500 hover:text-slate-800 text-[10px] font-extrabold rounded-xl transition-all duration-300";
                            }
                        }

                        // Copy Clipboard Helper with visual feedback
                        function copyText(btn, txt) {
                            navigator.clipboard.writeText(txt).then(() => {
                                const origText = btn.innerText;
                                const origClass = btn.className;
                                
                                btn.innerText = "Đã chép ✓";
                                btn.className = "text-[10px] bg-emerald-500 text-white font-extrabold px-3 py-1.5 rounded-xl transition-all duration-200 shadow-sm shadow-emerald-500/20";
                                
                                setTimeout(() => {
                                    btn.innerText = origText;
                                    btn.className = origClass;
                                }, 1500);
                            });
                        }
                    </script>

                <?php else: ?>
                    <!-- Create Deposit Request Form -->
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm transition hover:shadow-md">
                        <h2 class="text-xs font-extrabold text-slate-800 mb-6 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-3 bg-blue-650 rounded-full"></span>
                            Tạo yêu cầu nạp tiền mới
                        </h2>
                        
                        <form method="POST" action="/deposit" class="space-y-6">
                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                            <input type="hidden" name="action" value="create_qr">

                            <!-- Amount input -->
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Nhập số tiền nạp (VNĐ)</label>
                                <div class="relative">
                                    <input type="number" name="amount" id="amountField" required min="10000" oninput="formatAmountDisplay()" class="w-full pl-5 pr-14 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-2xl font-extrabold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition shadow-inner" placeholder="Ví dụ: 100000">
                                    <span class="absolute right-5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 bg-slate-200/50 px-2 py-1 rounded-lg">VNĐ</span>
                                </div>
                                <!-- Dynamic formatted text -->
                                <div id="amountWord" class="text-xs mt-3 font-semibold hidden bg-blue-50 border border-blue-100/50 text-blue-700 px-4 py-2.5 rounded-xl flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                    <span>Thanh toán: <strong class="font-extrabold text-blue-600">0 VNĐ</strong></span>
                                </div>
                            </div>

                            <!-- Fast Select presets -->
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2.5">Chọn nhanh mệnh giá nạp tiền</label>
                                <div class="grid grid-cols-3 gap-3" id="presetContainer">
                                    <button type="button" onclick="setAmount(this, 50000)" class="preset-btn py-3.5 border border-slate-200 rounded-xl text-xs font-extrabold hover:bg-slate-50 transition text-slate-600">50.000đ</button>
                                    <button type="button" onclick="setAmount(this, 100000)" class="preset-btn py-3.5 border border-slate-200 rounded-xl text-xs font-extrabold hover:bg-slate-50 transition text-slate-600">100.000đ</button>
                                    <button type="button" onclick="setAmount(this, 200000)" class="preset-btn py-3.5 border border-slate-200 rounded-xl text-xs font-extrabold hover:bg-slate-50 transition text-slate-600">200.000đ</button>
                                    <button type="button" onclick="setAmount(this, 500000)" class="preset-btn py-3.5 border border-slate-200 rounded-xl text-xs font-extrabold hover:bg-slate-50 transition text-slate-600">500.000đ</button>
                                    <button type="button" onclick="setAmount(this, 1000000)" class="preset-btn py-3.5 border border-slate-200 rounded-xl text-xs font-extrabold hover:bg-slate-50 transition text-slate-600">1.000.000đ</button>
                                    <button type="button" onclick="setAmount(this, 2000000)" class="preset-btn py-3.5 border border-slate-200 rounded-xl text-xs font-extrabold hover:bg-slate-50 transition text-slate-600">2.000.000đ</button>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-2xl text-xs transition shadow-md shadow-blue-500/20 hover:shadow-blue-500/30 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                Tạo QR Bank Chuyển Khoản
                            </button>
                        </form>
                    </div>

                    <script>
                        function setAmount(btn, val) {
                            document.getElementById('amountField').value = val;
                            formatAmountDisplay();
                            
                            // Highlight active selection
                            document.querySelectorAll('.preset-btn').forEach(el => {
                                el.classList.remove('bg-blue-50', 'border-blue-300', 'text-blue-600');
                                el.classList.add('border-slate-200', 'text-slate-600', 'hover:bg-slate-50');
                            });
                            btn.classList.add('bg-blue-50', 'border-blue-300', 'text-blue-600');
                            btn.classList.remove('border-slate-200', 'text-slate-600', 'hover:bg-slate-50');
                        }

                        function formatAmountDisplay() {
                            const val = parseInt(document.getElementById('amountField').value);
                            const displayWord = document.getElementById('amountWord');
                            if (!val || isNaN(val)) {
                                displayWord.classList.add('hidden');
                                return;
                            }
                            displayWord.innerHTML = `<span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span><span>Thanh toán: <strong class="font-extrabold text-blue-700">${val.toLocaleString('vi-VN')} VNĐ</strong></span>`;
                            displayWord.classList.remove('hidden');
                        }
                    </script>
                <?php endif; ?>

                <!-- Recent Deposits History Table -->
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm transition hover:shadow-md mt-6">
                    <h3 class="text-xs font-extrabold text-slate-800 mb-5 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-3 bg-emerald-500 rounded-full"></span>
                        Lịch sử nạp tiền gần đây
                    </h3>
                    
                    <?php if (empty($recentDeposits)): ?>
                        <div class="text-center py-8 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                            <svg class="w-8 h-8 text-slate-400 mx-auto mb-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 112-2h2a2 2 0 012 2"></path></svg>
                            <span class="text-slate-400 text-xs font-semibold">Chưa có lịch sử giao dịch nạp tiền.</span>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                                        <th class="pb-3 pl-2">Mã GD</th>
                                        <th class="pb-3">Thời gian</th>
                                        <th class="pb-3 text-right">Số tiền</th>
                                        <th class="pb-3 text-center">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    <?php foreach ($recentDeposits as $dep): ?>
                                        <tr class="hover:bg-slate-50/50 transition duration-150">
                                            <td class="py-3.5 pl-2 font-mono font-bold text-slate-600 select-all"><?= htmlspecialchars($dep['reference_code']) ?></td>
                                            <td class="py-3.5 text-slate-500"><?= date('H:i d/m/Y', $dep['created_at']) ?></td>
                                            <td class="py-3.5 text-right font-extrabold text-slate-800"><?= number_format($dep['amount'], 0, ',', '.') ?>đ</td>
                                            <td class="py-3.5 text-center">
                                                <?php if ($dep['status'] === 'approved'): ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-full border border-emerald-100">
                                                        Thành công
                                                    </span>
                                                <?php elseif ($dep['status'] === 'rejected'): ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 text-[10px] font-extrabold rounded-full border border-rose-100">
                                                        Đã hủy
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 text-[10px] font-extrabold rounded-full border border-amber-100">
                                                        Chờ thanh toán
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Help side column -->
            <div class="space-y-6">
                <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white p-6 rounded-3xl shadow-md border border-slate-800">
                    <h3 class="font-extrabold text-xs uppercase tracking-widest mb-4 text-blue-400 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Hướng dẫn nạp tiền
                    </h3>
                    <ul class="space-y-4 text-xs text-slate-300 leading-relaxed">
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 bg-blue-500/25 text-blue-400 border border-blue-500/35 rounded-full flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">1</span>
                            <span>Nhập số tiền nạp lớn hơn <strong>10.000đ</strong> và tạo QR.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 bg-blue-500/25 text-blue-400 border border-blue-500/35 rounded-full flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">2</span>
                            <span>Mở ứng dụng ngân hàng và thực hiện **Quét mã QR**.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 bg-blue-500/25 text-blue-400 border border-blue-500/35 rounded-full flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">3</span>
                            <span>Xác nhận thông tin số tài khoản <strong>0359261551</strong>, tên người nhận <strong>NGUYEN VAN DUC</strong> và **GHI ĐÚNG NỘI DUNG CHUYỂN KHOẢN** hiển thị trên màn hình.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 bg-blue-500/25 text-blue-400 border border-blue-500/35 rounded-full flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">4</span>
                            <span>Chuyển khoản thành công, vui lòng giữ trang này mở, hệ thống sẽ tự động cộng ví trong ít phút.</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
