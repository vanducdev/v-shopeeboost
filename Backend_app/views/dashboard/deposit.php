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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .preset-btn {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
        }
        .preset-btn:hover {
            border-color: #93c5fd;
            background: #f8fafc;
        }
        .preset-btn-active {
            border-color: #2563eb !important;
            background: #eff6ff !important;
            color: #1d4ed8 !important;
            box-shadow: 0 0 0 1px #2563eb;
        }

        /* QR scanner laser line */
        .scanner-container {
            position: relative;
            overflow: hidden;
        }
        .scanner-line {
            position: absolute;
            width: 100%;
            height: 2.5px;
            background: linear-gradient(to right, transparent, #3b82f6, #60a5fa, #3b82f6, transparent);
            box-shadow: 0 0 10px #3b82f6;
            left: 0;
            top: 0;
            animation: scan 2.8s ease-in-out infinite;
            z-index: 10;
        }
        @keyframes scan {
            0% { top: 0%; opacity: 0.8; }
            50% { top: 100%; opacity: 1; }
            100% { top: 0%; opacity: 0.8; }
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
    <main class="flex-1 p-4 md:p-7 lg:p-8 max-w-[1500px] mx-auto w-full overflow-x-hidden">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-6 pb-4 border-b border-slate-200/80">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">
                        Nạp Tiền Hệ Thống
                    </h1>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                        VietQR 24/7 Auto
                    </span>
                </div>
                <p class="text-slate-500 text-xs mt-0.5">
                    Nạp tiền vào ví tài khoản tự động qua mã quét QR ngân hàng MB Bank, kích hoạt trong 10 giây.
                </p>
            </div>

            <!-- Current Wallet Balance Card -->
            <div class="flex items-center gap-2 flex-wrap">
                <div class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200 rounded-xl shadow-sm text-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-slate-500 font-semibold">Số dư ví hiện tại:</span>
                    <span class="font-extrabold text-slate-900 tracking-tight text-sm">
                        <span id="userBalanceText"><?= number_format($user['balance'] ?? 0, 0, ',', '.') ?></span>đ
                    </span>
                </div>

                <a href="/renew" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-cart-shopping text-blue-600 text-[11px]"></i>
                    <span>Mua & Gia Hạn Key</span>
                </a>
            </div>
        </div>

        <!-- 4 Compact Metric Overview Badges -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">SỐ DƯ KHẢ DỤNG</span>
                    <span class="text-xl font-black text-emerald-600 tracking-tight mt-0.5 block">
                        <?= number_format($user['balance'] ?? 0, 0, ',', '.') ?>đ
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>

            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">CỔNG THANH TOÁN</span>
                    <span class="text-xs font-black text-slate-900 tracking-tight mt-1 block flex items-center gap-1.5">
                        <i class="fa-solid fa-qrcode text-blue-600"></i>
                        <span>VietQR Pro 24/7</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
            </div>

            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">TỐC ĐỘ DUYỆT TIỀN</span>
                    <span class="text-xs font-black text-slate-900 tracking-tight mt-1 block flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>3 - 10 Giây</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-bolt"></i>
                </div>
            </div>

            <div class="card-surface rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">PHÍ GIAO DỊCH</span>
                    <span class="text-xs font-black text-slate-900 tracking-tight mt-1 block flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-check text-emerald-500"></i>
                        <span>0% Miễn phí</span>
                    </span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
            </div>
        </section>

        <!-- Compact Progress Stepper -->
        <div class="mb-6 max-w-xl mx-auto px-4">
            <div class="flex items-center justify-between text-[11px] font-extrabold relative">
                <div class="absolute top-[14px] left-0 right-0 h-0.5 bg-slate-200 z-0 rounded-full"></div>
                <div class="absolute top-[14px] left-0 <?= $pendingDeposit ? 'w-1/2' : 'w-0' ?> h-0.5 bg-blue-600 z-0 transition-all duration-300 rounded-full"></div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center gap-1 bg-[#f8fafc] px-2">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold <?= $pendingDeposit ? 'bg-emerald-500 text-white' : 'bg-blue-600 text-white' ?>">
                        <?= $pendingDeposit ? '✓' : '1' ?>
                    </span>
                    <span class="<?= !$pendingDeposit ? 'text-blue-600 font-extrabold' : 'text-slate-500' ?>">Nhập số tiền</span>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center gap-1 bg-[#f8fafc] px-2">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold <?= $pendingDeposit ? 'bg-blue-600 text-white animate-pulse' : 'bg-white border border-slate-300 text-slate-400' ?>">
                        2
                    </span>
                    <span class="<?= $pendingDeposit ? 'text-blue-600 font-extrabold' : 'text-slate-500' ?>">Quét mã QR Bank</span>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center gap-1 bg-[#f8fafc] px-2">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-white border border-slate-300 text-slate-400">
                        3
                    </span>
                    <span class="text-slate-500">Cộng ví tự động</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left 2 Cols: Main Deposit Area -->
            <div class="lg:col-span-2 space-y-6">
                
                <?php if ($pendingDeposit): ?>
                    <!-- STATE A: PENDING VIETQR BILLING CARD -->
                    <div class="card-surface rounded-2xl p-5 md:p-6 relative overflow-hidden">
                        
                        <!-- Top status bar -->
                        <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-spinner animate-spin"></i>
                                </span>
                                <div>
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Trạng thái giao dịch</span>
                                    <span class="text-xs font-extrabold text-amber-700">Đang chờ bạn quét mã chuyển khoản...</span>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Hết hạn sau</span>
                                <div class="inline-flex items-center gap-1.5 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-lg">
                                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></span>
                                    <span id="countdown" class="font-mono font-black text-rose-600 text-xs">15:00</span>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code + Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                            
                            <!-- QR Code Box -->
                            <div class="space-y-3.5 text-center">
                                <div class="relative bg-slate-50 border border-slate-200 p-3.5 rounded-2xl flex justify-center shadow-inner scanner-container group">
                                    <div class="scanner-line"></div>
                                    <?php 
                                    $vietQrUrl = "https://img.vietqr.io/image/MB-0359261551-print.png?amount=" . $pendingDeposit['amount'] . "&addInfo=" . urlencode($pendingDeposit['reference_code']) . "&accountName=NGUYEN%20VAN%20DUC";
                                    ?>
                                    <img id="qrImage" src="<?= $vietQrUrl ?>" alt="VietQR Bank" class="w-56 h-56 object-contain rounded-xl bg-white p-2 border border-slate-100">
                                </div>

                                <div class="flex justify-center p-1 bg-slate-100 rounded-xl w-fit mx-auto text-xs">
                                    <button onclick="toggleQr('vietqr')" id="btnVietQr" class="px-3 py-1.5 bg-white text-blue-700 font-bold rounded-lg shadow-sm border border-slate-200/50 transition">
                                        Mã VietQR Tự Động
                                    </button>
                                    <button onclick="toggleQr('static')" id="btnStatic" class="px-3 py-1.5 text-slate-600 font-bold rounded-lg hover:text-slate-900 transition">
                                        Mã QR Gốc MB
                                    </button>
                                </div>

                                <p class="text-[11px] text-slate-400">
                                    Mở app ngân hàng bất kỳ (MB, Vietcombank, Techcombank, Momo,...) để quét mã.
                                </p>
                            </div>

                            <!-- Payment Details Box -->
                            <div class="space-y-3">
                                <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl space-y-3 text-xs">
                                    
                                    <!-- Bank -->
                                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded bg-blue-600 text-white flex items-center justify-center font-bold text-[10px]">MB</span>
                                            <div>
                                                <span class="text-[9px] text-slate-400 font-extrabold uppercase block">Ngân hàng</span>
                                                <span class="font-extrabold text-slate-800 text-xs">MB Bank (Quân Đội)</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Account Number -->
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-[9px] text-slate-400 font-extrabold uppercase block">Số tài khoản</span>
                                            <span class="font-mono font-black text-blue-700 text-sm">0359261551</span>
                                        </div>
                                        <button onclick="copyText(this, '0359261551')" class="px-2.5 py-1 bg-white border border-slate-200 hover:border-blue-400 text-slate-700 text-[10px] font-bold rounded-lg transition shadow-sm">
                                            Sao chép
                                        </button>
                                    </div>

                                    <!-- Account Name -->
                                    <div>
                                        <span class="text-[9px] text-slate-400 font-extrabold uppercase block">Chủ tài khoản</span>
                                        <span class="font-extrabold text-slate-900 text-xs uppercase">NGUYEN VAN DUC</span>
                                    </div>

                                    <!-- Amount -->
                                    <div class="flex items-center justify-between pt-1">
                                        <div>
                                            <span class="text-[9px] text-slate-400 font-extrabold uppercase block">Số tiền cần chuyển</span>
                                            <span class="font-black text-emerald-600 text-base"><?= number_format($pendingDeposit['amount'], 0, ',', '.') ?>đ</span>
                                        </div>
                                        <button onclick="copyText(this, '<?= $pendingDeposit['amount'] ?>')" class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold rounded-lg transition">
                                            Sao chép
                                        </button>
                                    </div>

                                    <!-- Reference Code (Memo) -->
                                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl space-y-1">
                                        <span class="text-[9px] font-black text-amber-800 uppercase tracking-wider block">
                                            Nội dung chuyển khoản (Bắt buộc ghi đúng 100%)
                                        </span>
                                        <div class="flex items-center justify-between gap-2">
                                            <code class="font-mono font-black text-amber-900 text-xs select-all"><?= htmlspecialchars($pendingDeposit['reference_code']) ?></code>
                                            <button onclick="copyText(this, '<?= htmlspecialchars($pendingDeposit['reference_code']) ?>')" class="px-2.5 py-1 bg-amber-200/80 hover:bg-amber-300 text-amber-900 text-[10px] font-extrabold rounded-lg transition">
                                                Sao chép
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- Cancel Button -->
                                <form method="POST" action="/deposit">
                                    <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                                    <input type="hidden" name="action" value="cancel_deposit">
                                    <input type="hidden" name="deposit_id" value="<?= $pendingDeposit['id'] ?>">
                                    <button type="submit" class="w-full py-2 bg-slate-50 hover:bg-rose-50 hover:text-rose-600 border border-slate-200 text-slate-500 font-bold rounded-xl text-xs transition">
                                        Hủy hóa đơn này & Tạo lệnh nạp mới
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                    <script>
                        // Timer logic
                        let timeLeft = 900 - (Math.floor(Date.now() / 1000) - <?= $pendingDeposit['created_at'] ?>);
                        if (timeLeft < 0) timeLeft = 0;

                        const countdownEl = document.getElementById('countdown');
                        const timerInterval = setInterval(() => {
                            if (timeLeft <= 0) {
                                clearInterval(timerInterval);
                                countdownEl.innerHTML = "Hết hạn";
                                location.reload();
                                return;
                            }
                            timeLeft--;
                            const mins = Math.floor(timeLeft / 60);
                            const secs = timeLeft % 60;
                            countdownEl.innerHTML = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                        }, 1000);

                        // Polling transaction status every 2.5 seconds
                        const pollInterval = setInterval(() => {
                            fetch(`/deposit?check_status=1&deposit_id=<?= $pendingDeposit['id'] ?>`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success && data.status === 'approved') {
                                        clearInterval(pollInterval);
                                        clearInterval(timerInterval);
                                        
                                        Swal.fire({
                                            title: 'Nạp Tiền Thành Công!',
                                            html: `Hệ thống đã nhận được <strong>${data.amount.toLocaleString('vi-VN')}đ</strong> và cộng trực tiếp vào ví của bạn.`,
                                            icon: 'success',
                                            confirmButtonText: 'Đóng',
                                            confirmButtonColor: '#2563eb',
                                            customClass: { popup: 'rounded-2xl' }
                                        }).then(() => {
                                            location.href = '/deposit';
                                        });
                                    } else if (data.success && data.status === 'rejected') {
                                        clearInterval(pollInterval);
                                        clearInterval(timerInterval);
                                        
                                        Swal.fire({
                                            title: 'Giao Dịch Đã Hủy',
                                            text: 'Hóa đơn nạp tiền đã bị hủy hoặc quá thời gian thanh toán.',
                                            icon: 'info',
                                            confirmButtonText: 'Tạo lệnh mới',
                                            confirmButtonColor: '#2563eb',
                                            customClass: { popup: 'rounded-2xl' }
                                        }).then(() => {
                                            location.reload();
                                        });
                                    }
                                })
                                .catch(err => console.error("Polling check: ", err));
                        }, 2500);

                        function toggleQr(type) {
                            const qrImage = document.getElementById('qrImage');
                            const btnVietQr = document.getElementById('btnVietQr');
                            const btnStatic = document.getElementById('btnStatic');
                            if (type === 'static') {
                                qrImage.src = "/qrbank/qr-bank.jpg";
                                btnStatic.className = "px-3 py-1.5 bg-white text-blue-700 font-bold rounded-lg shadow-sm border border-slate-200/50 transition";
                                btnVietQr.className = "px-3 py-1.5 text-slate-600 font-bold rounded-lg hover:text-slate-900 transition";
                            } else {
                                qrImage.src = "<?= $vietQrUrl ?>";
                                btnVietQr.className = "px-3 py-1.5 bg-white text-blue-700 font-bold rounded-lg shadow-sm border border-slate-200/50 transition";
                                btnStatic.className = "px-3 py-1.5 text-slate-600 font-bold rounded-lg hover:text-slate-900 transition";
                            }
                        }

                        function copyText(btn, txt) {
                            navigator.clipboard.writeText(txt).then(() => {
                                const orig = btn.innerText;
                                btn.innerText = "Đã chép ✓";
                                btn.classList.add('bg-emerald-500', 'text-white');
                                setTimeout(() => {
                                    btn.innerText = orig;
                                    btn.classList.remove('bg-emerald-500', 'text-white');
                                }, 1500);
                            });
                        }
                    </script>

                <?php else: ?>
                    <!-- STATE B: CREATE DEPOSIT FORM -->
                    <div class="card-surface rounded-2xl p-5 md:p-6">
                        <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-slate-100">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </span>
                            <div>
                                <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Tạo Lệnh Nạp Tiền VietQR</h2>
                                <p class="text-[10px] text-slate-400">Chọn mệnh giá hoặc nhập số tiền tùy ý bạn muốn nạp vào ví</p>
                            </div>
                        </div>

                        <form method="POST" action="/deposit" class="space-y-5">
                            <input type="hidden" name="csrf_token" value="<?= Auth::getCsrfToken() ?>">
                            <input type="hidden" name="action" value="create_qr">

                            <!-- Amount Input -->
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Số tiền nạp (Tối thiểu 10.000đ)</label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="amount" 
                                        id="amountField" 
                                        required 
                                        min="10000" 
                                        step="1000"
                                        oninput="formatAmountDisplay()" 
                                        class="w-full pl-4 pr-16 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xl font-black text-slate-900 focus:outline-none focus:border-blue-500 focus:bg-white transition" 
                                        placeholder="Ví dụ: 100000"
                                    >
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-extrabold text-slate-500 bg-slate-200/70 px-2.5 py-1 rounded-lg">
                                        VNĐ
                                    </span>
                                </div>

                                <!-- Dynamic Format Output -->
                                <div id="amountWord" class="text-xs mt-2 font-bold hidden bg-blue-50 border border-blue-100 text-blue-700 px-3.5 py-2 rounded-xl flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                    <span>Xác nhận nạp: <strong class="font-black text-blue-800" id="formattedAmountVal">0 VNĐ</strong></span>
                                </div>
                            </div>

                            <!-- Fast Select Presets -->
                            <div>
                                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Mệnh giá đề xuất nhanh</label>
                                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                                    <button type="button" onclick="setAmount(this, 50000)" class="preset-btn py-2.5 rounded-xl text-xs font-bold text-slate-700">50.000đ</button>
                                    <button type="button" onclick="setAmount(this, 100000)" class="preset-btn py-2.5 rounded-xl text-xs font-bold text-slate-700">100.000đ</button>
                                    <button type="button" onclick="setAmount(this, 200000)" class="preset-btn py-2.5 rounded-xl text-xs font-bold text-slate-700">200.000đ</button>
                                    <button type="button" onclick="setAmount(this, 500000)" class="preset-btn py-2.5 rounded-xl text-xs font-bold text-slate-700">500.000đ</button>
                                    <button type="button" onclick="setAmount(this, 1000000)" class="preset-btn py-2.5 rounded-xl text-xs font-bold text-slate-700">1.000.000đ</button>
                                    <button type="button" onclick="setAmount(this, 2000000)" class="preset-btn py-2.5 rounded-xl text-xs font-bold text-slate-700">2.000.000đ</button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold rounded-xl text-xs md:text-sm shadow-md shadow-blue-500/10 transition flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i class="fa-solid fa-qrcode"></i>
                                <span>Tạo Mã QR VietQR Thanh Toán Tự Động</span>
                            </button>
                        </form>
                    </div>

                    <script>
                        function setAmount(btn, val) {
                            document.getElementById('amountField').value = val;
                            formatAmountDisplay();
                            
                            document.querySelectorAll('.preset-btn').forEach(el => {
                                el.classList.remove('preset-btn-active');
                            });
                            btn.classList.add('preset-btn-active');
                        }

                        function formatAmountDisplay() {
                            const val = parseInt(document.getElementById('amountField').value);
                            const displayWord = document.getElementById('amountWord');
                            const formattedVal = document.getElementById('formattedAmountVal');
                            if (!val || isNaN(val)) {
                                displayWord.classList.add('hidden');
                                return;
                            }
                            formattedVal.innerText = val.toLocaleString('vi-VN') + ' VNĐ';
                            displayWord.classList.remove('hidden');
                        }
                    </script>
                <?php endif; ?>

                <!-- Recent Deposits History Table -->
                <div class="card-surface rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                        <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-slate-500"></i>
                            <span>Lịch Sử Nạp Tiền Gần Đây</span>
                        </h3>
                        <a href="/transactions" class="text-blue-600 hover:underline text-xs font-bold">Xem tất cả →</a>
                    </div>
                    
                    <?php if (empty($recentDeposits)): ?>
                        <div class="text-center py-8 text-slate-400 text-xs">
                            <i class="fa-solid fa-receipt text-2xl mb-2 text-slate-300 block"></i>
                            Chưa có lịch sử giao dịch nạp tiền nào.
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="text-slate-400 border-b border-slate-100 text-[10px] uppercase tracking-wider">
                                        <th class="pb-2.5 font-bold">Mã GD</th>
                                        <th class="pb-2.5 font-bold">Thời gian</th>
                                        <th class="pb-2.5 font-bold text-right">Số tiền</th>
                                        <th class="pb-2.5 font-bold text-center">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                    <?php foreach ($recentDeposits as $dep): ?>
                                        <tr class="hover:bg-slate-50/70 transition">
                                            <td class="py-3 font-mono font-bold text-blue-700 select-all"><?= htmlspecialchars($dep['reference_code']) ?></td>
                                            <td class="py-3 text-slate-500 text-[11px]"><?= date('H:i d/m/Y', $dep['created_at']) ?></td>
                                            <td class="py-3 text-right font-extrabold text-slate-900"><?= number_format($dep['amount'], 0, ',', '.') ?>đ</td>
                                            <td class="py-3 text-center">
                                                <?php if ($dep['status'] === 'approved'): ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-full border border-emerald-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Thành công
                                                    </span>
                                                <?php elseif ($dep['status'] === 'rejected'): ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-extrabold rounded-full border border-rose-200">
                                                        Đã hủy
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-extrabold rounded-full border border-amber-200">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                        Chờ quét QR
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

            <!-- Right 1 Col: Guide & Bank Info -->
            <div class="space-y-5">
                
                <!-- Deposit Guide Card -->
                <div class="card-surface rounded-2xl p-5">
                    <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-slate-100">
                        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-circle-info"></i>
                        </span>
                        <div>
                            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Quy Trình Nạp Tiền</h3>
                            <p class="text-[10px] text-slate-400">4 bước đơn giản trong 10 giây</p>
                        </div>
                    </div>

                    <div class="space-y-3.5 text-xs text-slate-600">
                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-black text-[10px] shrink-0 mt-0.5">1</span>
                            <div>
                                <span class="font-bold text-slate-800">Nhập số tiền nạp</span>
                                <p class="text-[11px] text-slate-400 mt-0.5">Tối thiểu 10.000đ, không giới hạn tối đa.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-black text-[10px] shrink-0 mt-0.5">2</span>
                            <div>
                                <span class="font-bold text-slate-800">Quét mã VietQR</span>
                                <p class="text-[11px] text-slate-400 mt-0.5">Mở app Mobile Banking (MB, VCB, MoMo,...) quét mã.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-black text-[10px] shrink-0 mt-0.5">3</span>
                            <div>
                                <span class="font-bold text-slate-800">Kiểm tra nội dung</span>
                                <p class="text-[11px] text-slate-400 mt-0.5">Mã QR đã tự động điền sẵn nội dung và số tiền chuẩn xác 100%.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-[10px] shrink-0 mt-0.5">4</span>
                            <div>
                                <span class="font-bold text-emerald-700">Tự động cộng ví</span>
                                <p class="text-[11px] text-slate-400 mt-0.5">Ngay khi ngân hàng báo có, ví sẽ được cộng tiền sau 3-10 giây.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security & Support Notice -->
                <div class="card-surface rounded-2xl p-5 bg-gradient-to-br from-slate-50 via-white to-emerald-50/20 border-slate-200">
                    <h4 class="text-xs font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-headset text-blue-600"></i>
                        <span>Cần Hỗ Trợ Nạp Tiền Nhanh?</span>
                    </h4>

                    <p class="text-[11px] text-slate-500 leading-relaxed mb-3">
                        Nếu sau 1 phút chưa thấy ví được cộng tiền do lỗi nghẽn mạng ngân hàng, vui lòng liên hệ Admin qua Hotline/Zalo kèm mã giao dịch để được kiểm tra và cộng ví thủ công tức thì.
                    </p>

                    <a href="/support" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-xs transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-comments"></i>
                        <span>Liên Hệ Hỗ Trợ 24/7</span>
                    </a>
                </div>

            </div>

        </div>

    </main>

</body>
</html>