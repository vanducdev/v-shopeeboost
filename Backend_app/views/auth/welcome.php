<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào Mừng Đến Với VMAX - VMAX Software</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between">

    <!-- Simple Navigation -->
    <header class="max-w-7xl mx-auto px-6 py-6 w-full flex justify-between items-center">
        <a href="/">
            <img src="/image/logo-app-vmax45.webp" alt="VMAX Logo" class="h-10 w-auto object-contain">
        </a>
        <a href="/logout" class="text-xs text-slate-500 hover:text-rose-600 font-bold transition">
            <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
        </a>
    </header>

    <!-- Main Welcome Container -->
    <main class="max-w-4xl mx-auto px-6 py-8 flex-grow flex items-center w-full">
        <div class="w-full bg-white border border-slate-100/80 rounded-[32px] p-8 md:p-12 shadow-[0_15px_40px_rgba(0,0,0,0.02)] flex flex-col md:flex-row gap-10 items-center">
            
            <!-- Left Side: Welcome Text & Onboarding Steps -->
            <div class="flex-1 space-y-6">
                <div>
                    <span class="inline-flex px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-full uppercase tracking-wider mb-3">
                        🎉 Tạo tài khoản thành công
                    </span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Chào Mừng Bạn Đến Với Hệ Sinh Thái <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">VMAX</span>
                    </h2>
                    <p class="text-slate-500 text-sm mt-2 font-light">Để bắt đầu trải nghiệm đầy đủ các tính năng bản quyền, hãy hoàn thành các bước hướng dẫn nhanh dưới đây:</p>
                </div>

                <!-- Steps Stack -->
                <div class="space-y-4">
                    <!-- Step 1 -->
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">1</div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">Xác thực tài khoản Email</h4>
                            <p class="text-slate-400 text-xs mt-0.5 font-light">Một thư xác minh đã được gửi tới địa chỉ email của bạn. Vui lòng kiểm tra Hộp thư đến (hoặc Spam) và click vào liên kết kích hoạt.</p>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">2</div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">Tải xuống & Cài đặt Client</h4>
                            <p class="text-slate-400 text-xs mt-0.5 font-light">Truy cập trung tâm tải xuống để tải các bộ cài phần mềm antidetect browser hoặc tool MMO tự động.</p>
                            <div class="mt-2">
                                <a href="/downloads" class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-700 font-bold hover:underline">
                                    Đi đến trang tải xuống <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Actions -->
                <div class="pt-4 flex flex-col sm:flex-row gap-3">
                    <a href="/dashboard" class="px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 hover:scale-[1.02] active:scale-[0.98] transition duration-200 text-xs text-center">
                        Vào Bảng Điều Khiển &rarr;
                    </a>
                </div>
            </div>

            <!-- Right Side: 60-Second Video Mockup -->
            <div class="w-full md:w-80 shrink-0 space-y-4">
                <div class="text-center md:text-left">
                    <h4 class="font-bold text-slate-800 text-sm">Xem video hướng dẫn (60 giây)</h4>
                    <p class="text-slate-400 text-[10px] mt-0.5">Tìm hiểu nhanh cách kích hoạt License Key lần đầu</p>
                </div>
                
                <!-- Stylized Video Player Mockup -->
                <div class="relative group cursor-pointer overflow-hidden rounded-2xl border border-slate-200/80 shadow-md aspect-video bg-slate-950 flex items-center justify-center">
                    <!-- Dashboard Mockup Image as video thumbnail -->
                    <img src="/image/img-about-mission.webp" alt="Video thumbnail" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-500">
                    
                    <!-- Play overlay -->
                    <div class="relative z-10 w-12 h-12 rounded-full bg-white/90 text-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-play text-sm pl-0.5"></i>
                    </div>
                    
                    <!-- Time Badge -->
                    <span class="absolute bottom-2.5 right-2.5 bg-slate-900/80 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">0:59</span>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-6 text-center text-xs text-slate-400">
        &copy; <?= date('Y') ?> VMAX Software. Bảo lưu mọi quyền.
    </footer>

</body>
</html>
