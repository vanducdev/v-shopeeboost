<?php
$pageTitle = 'VMAX Software - Giải Pháp Antidetect Browser & Tự Động Hóa SaaS Hàng Đầu Việt Nam';
$pageDescription = 'VMAX Software cung cấp hệ sinh thái SaaS Antidetect Browser (VmaxLogin), phần mềm tự động nuôi nick Facebook (V-AutoPost) và tự động tương tác đẩy sản phẩm Shopee (V-ShopeeBoost). Đột phá doanh thu MMO.';
$pageKeywords = 'antidetect browser, gpm login, gemlogin, vmax software, nuôi nick facebook, shopee boost, tự động hóa mmo, proxy manager, phần mềm mmo';
include BASE_PATH . '/views/layout/header.php';

// Prepare products data
$allActiveProducts = $products ?? [];
$combos = array_values(array_filter($allActiveProducts, function($p) {
    return isset($p['is_combo']) && $p['is_combo'] == 1;
}));
$singles = array_values(array_filter($allActiveProducts, function($p) {
    return !isset($p['is_combo']) || $p['is_combo'] == 0;
}));

// Fallback combo data if not populated
if (empty($combos)) {
    $combos = [
        [
            'name' => 'Combo Marketing Facebook',
            'slug' => 'combo-marketing-facebook',
            'short_description' => 'Bộ 3 phần mềm bán hàng Facebook tự động đỉnh cao: Nuôi nick, seeding, đăng bài đa luồng không checkpoint.',
            'price_monthly' => 500000,
            'price_yearly' => 3500000,
            'price_lifetime' => 7500000,
            'image_url' => 'image/sanphamfb.webp',
            'badge' => 'BÁN CHẠY NHẤT',
            'tools' => ['VmaxLogin Antidetect', 'V-AutoPost Facebook', 'Tool Seeding & Spam Group/Page']
        ],
        [
            'name' => 'Combo Phú Quý',
            'slug' => 'combo-phu-quy',
            'short_description' => 'Bộ 4 công cụ marketing đa kênh tự động tối ưu doanh thu toàn diện trên cả Facebook và Sàn TMĐT Shopee.',
            'price_monthly' => 600000,
            'price_yearly' => 4500000,
            'price_lifetime' => 9500000,
            'image_url' => 'image/sanphamsp.webp',
            'badge' => 'TIẾT KIỆM 45%',
            'tools' => ['VmaxLogin Antidetect', 'V-AutoPost Facebook', 'V-ShopeeBoost', 'V-ShopeeBoost Pro']
        ]
    ];
}

// Fallback single products if needed
$productDetailsMap = [
    'lushlogin' => [
        'name' => 'VmaxLogin Antidetect',
        'icon' => 'fa-brands fa-chrome',
        'badge' => 'CÔNG NGHỆ LÕI',
        'badge_class' => 'badge-blue',
        'icon_gradient' => 'linear-gradient(135deg, #2563eb, #06b6d4)',
        'version' => 'v2.4.0',
        'features' => ['Vân tay Canvas, WebGL, WebRTC độc lập', 'Quản lý Proxy SOCKS5/HTTP/IPv6 đa luồng', 'Phân quyền làm việc nhóm Cloud Profiles'],
        'default_price' => 200000
    ],
    'vmaxlogin' => [
        'name' => 'VmaxLogin Antidetect',
        'icon' => 'fa-brands fa-chrome',
        'badge' => 'CÔNG NGHỆ LÕI',
        'badge_class' => 'badge-blue',
        'icon_gradient' => 'linear-gradient(135deg, #2563eb, #06b6d4)',
        'version' => 'v2.4.0',
        'features' => ['Vân tay Canvas, WebGL, WebRTC độc lập', 'Quản lý Proxy SOCKS5/HTTP/IPv6 đa luồng', 'Phân quyền làm việc nhóm Cloud Profiles'],
        'default_price' => 200000
    ],
    'v-autopost' => [
        'name' => 'V-AutoPost Facebook',
        'icon' => 'fa-solid fa-share-nodes',
        'badge' => 'PHỔ BIẾN NHẤT',
        'badge_class' => 'badge-emerald',
        'icon_gradient' => 'linear-gradient(135deg, #10b981, #059669)',
        'version' => 'v3.1.2',
        'features' => ['Đăng bài Profile, Fanpage, Group đa luồng', 'Tự động seeding thả tim, bình luận theo kịch bản', 'Nuôi nick, lướt newfeed tránh checkpoint'],
        'default_price' => 300000
    ],
    'v-shopeeboost' => [
        'name' => 'V-ShopeeBoost',
        'icon' => 'fa-solid fa-cart-shopping',
        'badge' => 'ĐẨY SẢN PHẨM TOP',
        'badge_class' => 'badge-orange',
        'icon_gradient' => 'linear-gradient(135deg, #f97316, #ea580c)',
        'version' => 'v1.6.0',
        'features' => ['Tự động đẩy sản phẩm lên top tìm kiếm Shopee', 'Buff tương tác, follow shop bằng tài khoản phụ', 'Tự động hóa tác vụ tăng traffic tự nhiên'],
        'default_price' => 200000
    ],
    'v-shopeeboost-pro' => [
        'name' => 'V-ShopeeBoost Pro',
        'icon' => 'fa-solid fa-gem',
        'badge' => 'DOANH NGHIỆP',
        'badge_class' => 'badge-purple',
        'icon_gradient' => 'linear-gradient(135deg, #8b5cf6, #6366f1)',
        'version' => 'v1.8.5',
        'features' => ['Quản lý tập trung hàng chục gian hàng Shopee', 'Phân tích từ khóa và đối thủ cạnh tranh', 'Copy sản phẩm hàng loạt giữa các shop'],
        'default_price' => 350000
    ]
];
?>

<!-- Embedded Custom Modern SaaS Stylesheet -->
<style>
/* Modern Theme Variables */
:root {
    --vmax-primary: #2563eb;
    --vmax-primary-hover: #1d4ed8;
    --vmax-primary-light: #eff6ff;
    --vmax-indigo: #4f46e5;
    --vmax-emerald: #10b981;
    --vmax-orange: #f97316;
    --vmax-purple: #8b5cf6;
    --vmax-dark: #0f172a;
    --vmax-slate: #1e293b;
    --vmax-muted: #64748b;
    --vmax-border: #e2e8f0;
    --vmax-card-bg: #ffffff;
    --vmax-shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.04);
    --vmax-shadow-md: 0 10px 25px -5px rgba(15, 23, 42, 0.06), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
    --vmax-shadow-lg: 0 20px 35px -5px rgba(15, 23, 42, 0.08), 0 10px 15px -5px rgba(15, 23, 42, 0.04);
    --vmax-shadow-glow: 0 15px 30px rgba(37, 99, 235, 0.15);
}

body {
    background-color: #f8fafc;
    color: #334155;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    overflow-x: hidden;
}

/* Hero Section */
.hero-vmax-wrapper {
    position: relative;
    padding: 70px 0 60px 0;
    overflow: hidden;
}
.hero-mesh-bg {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(79, 70, 229, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(16, 185, 129, 0.04) 0%, transparent 60%);
    z-index: 0;
    pointer-events: none;
}
.hero-grid-pattern {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(148, 163, 184, 0.2) 1px, transparent 1px);
    background-size: 24px 24px;
    opacity: 0.6;
    z-index: 0;
    pointer-events: none;
}

.hero-pill-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    background: rgba(37, 99, 235, 0.08);
    border: 1px solid rgba(37, 99, 235, 0.2);
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 700;
    color: var(--vmax-primary);
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.06);
    backdrop-filter: blur(8px);
}
.pulse-dot {
    width: 8px;
    height: 8px;
    background-color: #10b981;
    border-radius: 50%;
    position: relative;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: pulse-ring 2s infinite;
}
@keyframes pulse-ring {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.hero-main-title {
    font-size: 3.25rem;
    font-weight: 850;
    line-height: 1.15;
    letter-spacing: -0.035em;
    color: var(--vmax-dark);
    margin-bottom: 20px;
}
.hero-gradient-text {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #4f46e5 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.hero-subtitle {
    font-size: 1.125rem;
    line-height: 1.75;
    color: #475569;
    margin-bottom: 28px;
    max-width: 580px;
}

.hero-check-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    background: #ffffff;
    padding: 6px 14px;
    border-radius: 9999px;
    border: 1px solid #e2e8f0;
    box-shadow: var(--vmax-shadow-sm);
}

.btn-vmax-glow {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #ffffff !important;
    font-weight: 700;
    font-size: 15px;
    padding: 14px 28px;
    border-radius: 14px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 10px 25px -3px rgba(37, 99, 235, 0.35);
    transition: all 0.25s ease;
    text-decoration: none;
}
.btn-vmax-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 28px -2px rgba(37, 99, 235, 0.45);
    color: #ffffff !important;
}

.btn-vmax-ghost {
    background: #ffffff;
    color: #1e293b !important;
    font-weight: 700;
    font-size: 15px;
    padding: 14px 26px;
    border-radius: 14px;
    border: 1px solid #cbd5e1;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: var(--vmax-shadow-sm);
    transition: all 0.25s ease;
    text-decoration: none;
}
.btn-vmax-ghost:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    transform: translateY(-2px);
    color: #0f172a !important;
}

/* ========================================================
   ULTRA-MODERN 3D INTERACTIVE HERO SHOWCASE (2026 SAAS)
======================================================== */
.hero-showcase-stage {
    position: relative;
    perspective: 1200px;
}
.hero-glow-backdrop {
    position: absolute;
    inset: -30px;
    background: radial-gradient(circle at 40% 40%, rgba(37, 99, 235, 0.28), rgba(124, 58, 237, 0.18), transparent 70%),
                radial-gradient(circle at 80% 80%, rgba(16, 185, 129, 0.15), transparent 60%);
    filter: blur(40px);
    border-radius: 40px;
    z-index: 0;
    pointer-events: none;
    animation: glow-pulse 6s ease-in-out infinite alternate;
}
@keyframes glow-pulse {
    0% { transform: scale(0.95); opacity: 0.7; }
    100% { transform: scale(1.05); opacity: 1; }
}

.hero-glass-device {
    position: relative;
    z-index: 2;
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255, 255, 255, 0.9);
    border-radius: 28px;
    box-shadow: 0 30px 70px -15px rgba(15, 23, 42, 0.12), 
                0 0 0 1px rgba(226, 232, 240, 0.8),
                0 15px 35px -5px rgba(37, 99, 235, 0.1);
    overflow: hidden;
    transition: transform 0.25s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.25s ease;
}
.hero-glass-device:hover {
    box-shadow: 0 40px 80px -15px rgba(37, 99, 235, 0.2), 
                0 0 0 1px rgba(37, 99, 235, 0.3),
                0 20px 45px -5px rgba(124, 58, 237, 0.15);
}

/* Device Title Bar */
.device-header {
    background: rgba(248, 250, 252, 0.9);
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.device-dots {
    display: flex;
    gap: 7px;
}
.device-dot {
    width: 11px;
    height: 11px;
    border-radius: 50%;
    transition: transform 0.15s ease;
}
.device-dot:hover { transform: scale(1.2); }
.device-dot.red { background: #ff5f56; box-shadow: 0 0 6px rgba(255, 95, 86, 0.5); }
.device-dot.yellow { background: #ffbd2e; box-shadow: 0 0 6px rgba(255, 189, 46, 0.5); }
.device-dot.green { background: #27c93f; box-shadow: 0 0 6px rgba(39, 201, 63, 0.5); }

/* Interactive Tabs on Device */
.device-tab-bar {
    display: flex;
    gap: 6px;
    background: #f1f5f9;
    padding: 5px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
}
.device-tab-btn {
    border: none;
    background: transparent;
    padding: 6px 14px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
}
.device-tab-btn.active {
    background: #ffffff;
    color: #0f172a;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
}
.device-tab-btn:hover:not(.active) {
    color: #1e293b;
    background: rgba(255, 255, 255, 0.6);
}

/* Tab Panels */
.device-tab-content {
    display: none;
    animation: fadeInSlide 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.device-tab-content.active {
    display: block;
}
@keyframes fadeInSlide {
    0% { opacity: 0; transform: translateY(8px) scale(0.99); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}

/* Showcase Body Elements */
.showcase-body {
    padding: 24px;
}
.metric-row-pill {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}
.status-node {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
}
.status-node-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    box-shadow: 0 0 10px #10b981;
    animation: radar-ping 1.5s infinite;
}
@keyframes radar-ping {
    0% { transform: scale(0.8); opacity: 1; }
    50% { transform: scale(1.4); opacity: 0.4; }
    100% { transform: scale(0.8); opacity: 1; }
}

/* Glass Profile Card Item */
.profile-motion-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.profile-motion-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.1);
    border-color: #cbd5e1;
}

.profile-avatar-pill {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 700;
    color: #ffffff;
    flex-shrink: 0;
}

/* Floating Badges */
.hero-floating-bubble {
    position: absolute;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.95);
    border-radius: 9999px;
    padding: 10px 18px;
    box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(226, 232, 240, 0.8);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
    z-index: 10;
    pointer-events: auto;
    transition: transform 0.25s ease;
}
.hero-floating-bubble:hover {
    transform: translateY(-4px) scale(1.03);
}
.bubble-top-right {
    top: -18px;
    right: 15px;
    animation: float-bubble-1 4.5s ease-in-out infinite;
}
.bubble-bottom-left {
    bottom: -18px;
    left: 15px;
    animation: float-bubble-2 5s ease-in-out infinite;
}
@keyframes float-bubble-1 {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-9px); }
}
@keyframes float-bubble-2 {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(9px); }
}

/* Animated Progress Bars */
.animated-progress-track {
    height: 8px;
    background: #f1f5f9;
    border-radius: 9999px;
    overflow: hidden;
    position: relative;
}
.animated-progress-fill {
    height: 100%;
    border-radius: 9999px;
    position: relative;
    overflow: hidden;
}
.animated-progress-fill::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: progress-shimmer 2s infinite;
}
@keyframes progress-shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Brand Marquee */
.marquee-container {
    padding: 24px 0;
    border-top: 1px solid #f1f5f9;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
    position: relative;
    overflow: hidden;
}
.marquee-wrapper {
    display: flex;
    gap: 20px;
    animation: marquee-slide 28s linear infinite;
    width: max-content;
}
.marquee-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 20px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    white-space: nowrap;
}
@keyframes marquee-slide {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* Stats Section */
.stat-box-modern {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 24px 20px;
    text-align: center;
    box-shadow: var(--vmax-shadow-sm);
    transition: all 0.25s ease;
}
.stat-box-modern:hover {
    transform: translateY(-4px);
    box-shadow: var(--vmax-shadow-md);
    border-color: #cbd5e1;
}
.stat-number {
    font-size: 2.2rem;
    font-weight: 850;
    letter-spacing: -0.03em;
    color: var(--vmax-dark);
    margin-bottom: 4px;
}

/* Section Header Component */
.section-badge-pill {
    display: inline-block;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 4px 14px;
    border-radius: 9999px;
    margin-bottom: 12px;
}
.badge-blue { background: rgba(37, 99, 235, 0.1); color: var(--vmax-primary); }
.badge-emerald { background: rgba(16, 185, 129, 0.1); color: var(--vmax-emerald); }
.badge-orange { background: rgba(249, 115, 22, 0.1); color: var(--vmax-orange); }
.badge-purple { background: rgba(139, 92, 246, 0.1); color: var(--vmax-purple); }

.section-title {
    font-size: 2.25rem;
    font-weight: 800;
    letter-spacing: -0.025em;
    color: var(--vmax-dark);
    margin-bottom: 12px;
}
.section-subtitle {
    font-size: 1rem;
    color: #64748b;
    max-width: 620px;
    margin: 0 auto;
}

/* Combo Section Cards */
.combo-card-hero {
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid #e2e8f0;
    padding: 32px;
    position: relative;
    box-shadow: var(--vmax-shadow-md);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
}
.combo-card-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #2563eb, #8b5cf6);
}
.combo-card-hero.combo-fb::before {
    background: linear-gradient(90deg, #1877f2, #10b981);
}
.combo-card-hero:hover {
    transform: translateY(-6px);
    box-shadow: 0 25px 40px -10px rgba(15, 23, 42, 0.12);
    border-color: #cbd5e1;
}

.combo-badge-floating {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    font-size: 11px;
    font-weight: 800;
    padding: 5px 12px;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
}

.combo-tool-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
}

/* Single Product Modern Cards */
.prod-card-modern {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 26px 22px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: var(--vmax-shadow-sm);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}
.prod-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 18px 30px -5px rgba(15, 23, 42, 0.08);
    border-color: #cbd5e1;
}

.prod-icon-circle {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 22px;
    box-shadow: 0 8px 16px -2px rgba(15, 23, 42, 0.15);
    margin-bottom: 18px;
}

/* Modern Tab Switcher for Pricing */
.pricing-tabs-wrapper {
    background: #f1f5f9;
    padding: 6px;
    border-radius: 16px;
    display: inline-flex;
    gap: 6px;
    border: 1px solid #e2e8f0;
    margin-bottom: 40px;
}
.pricing-tab-btn {
    border: none;
    background: transparent;
    padding: 10px 22px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    color: #64748b;
    transition: all 0.2s ease;
    cursor: pointer;
}
.pricing-tab-btn.active {
    background: #ffffff;
    color: var(--vmax-dark);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
}
.pricing-tab-btn .save-pill {
    font-size: 10px;
    font-weight: 800;
    background: #dcfce7;
    color: #15803d;
    padding: 2px 7px;
    border-radius: 9999px;
    margin-left: 6px;
}

.pricing-card-modern {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 22px;
    padding: 32px 26px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: var(--vmax-shadow-sm);
    transition: all 0.25s ease;
    position: relative;
}
.pricing-card-modern.popular {
    border: 2px solid var(--vmax-primary);
    box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.18);
    transform: scale(1.02);
}
.pricing-card-modern:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 35px -5px rgba(15, 23, 42, 0.1);
}
.popular-ribbon {
    position: absolute;
    top: -14px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    font-size: 11px;
    font-weight: 800;
    padding: 4px 16px;
    border-radius: 9999px;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    white-space: nowrap;
}

/* Bento Grid Modern */
.bento-modern-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 24px;
}
.bento-cell {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 22px;
    padding: 28px;
    box-shadow: var(--vmax-shadow-sm);
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.bento-cell:hover {
    transform: translateY(-4px);
    box-shadow: var(--vmax-shadow-md);
    border-color: #cbd5e1;
}

/* Testimonials Card */
.testi-card-modern {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 28px;
    box-shadow: var(--vmax-shadow-sm);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.testi-stars {
    color: #f59e0b;
    font-size: 14px;
    display: flex;
    gap: 4px;
    margin-bottom: 14px;
}

/* FAQ modern accordion */
.accordion-vmax .accordion-item {
    border: 1px solid #e2e8f0;
    border-radius: 14px !important;
    margin-bottom: 12px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: var(--vmax-shadow-sm);
}
.accordion-vmax .accordion-button {
    font-weight: 700;
    font-size: 15px;
    color: #1e293b;
    padding: 18px 22px;
    background: #ffffff;
}
.accordion-vmax .accordion-button:not(.collapsed) {
    color: var(--vmax-primary);
    background: #eff6ff;
    box-shadow: none;
}
.accordion-vmax .accordion-body {
    padding: 16px 22px 20px 22px;
    color: #475569;
    font-size: 14px;
    line-height: 1.7;
}

/* Bottom CTA Banner */
.cta-banner-dark {
    background: radial-gradient(circle at 10% 20%, #1e3a8a 0%, #0f172a 70%),
                radial-gradient(circle at 90% 80%, #4338ca 0%, #0f172a 70%);
    border-radius: 28px;
    padding: 60px 32px;
    position: relative;
    overflow: hidden;
    color: #ffffff;
    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
}
.cta-banner-pattern {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
    background-size: 24px 24px;
    opacity: 0.3;
}

/* Responsive adjustments */
@media (max-width: 991px) {
    .hero-main-title { font-size: 2.5rem; }
    .hero-vmax-wrapper { padding: 40px 0; }
    .floating-glass-card { display: none; }
    .pricing-card-modern.popular { transform: none; }
}
@media (max-width: 768px) {
    .hero-main-title { font-size: 2rem; }
    .pricing-tabs-wrapper { flex-wrap: wrap; }
    .bento-modern-grid { grid-template-columns: 1fr; }
    .bento-cell { grid-column: span 12 !important; }
}
</style>

<!-- Navigation Bar -->
<?php include BASE_PATH . '/views/layout/navbar.php'; ?>

<!-- 1. Hero Section -->
<section class="hero-vmax-wrapper">
    <div class="hero-mesh-bg"></div>
    <div class="hero-grid-pattern"></div>
    
    <div class="container-xl position-relative" style="z-index: 2;">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6" data-aos="fade-right">
                <!-- Pill Badge -->
                <div class="hero-pill-badge">
                    <span class="pulse-dot"></span>
                    <span>VMAX 2026 • HỆ SINH THÁI TỰ ĐỘNG HÓA MMO TOÀN DIỆN</span>
                </div>

                <!-- Main Headline -->
                <h1 class="hero-main-title">
                    Quản Lý Hàng Ngàn Profile & <span class="hero-gradient-text">Tự Động Hóa Bán Hàng</span> An Toàn
                </h1>

                <!-- Subtitle -->
                <p class="hero-subtitle">
                    Hệ sinh thái phần mềm tiên phong tại Việt Nam: Trình duyệt <strong>Antidetect Browser</strong> chống dò quét vân tay máy tính, phần mềm <strong>nuôi nick Facebook</strong> đa luồng và <strong>tự động đẩy sản phẩm Shopee</strong>.
                </p>

                <!-- Micro Checkpoints -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="hero-check-pill"><i class="fa-solid fa-circle-check text-success"></i> 100% Vân tay máy sạch</span>
                    <span class="hero-check-pill"><i class="fa-solid fa-circle-check text-success"></i> Chống checkpoint chéo</span>
                    <span class="hero-check-pill"><i class="fa-solid fa-circle-check text-success"></i> Kích hoạt VietQR tức thì</span>
                    <span class="hero-check-pill"><i class="fa-solid fa-circle-check text-success"></i> Hỗ trợ Ultraview 24/7</span>
                </div>

                <!-- CTA Buttons -->
                <div class="d-flex gap-3 flex-wrap mb-4">
                    <a href="/register" class="btn-vmax-glow">
                        <span>Dùng Thử Miễn Phí</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="#products" class="btn-vmax-ghost">
                        <i class="fa-solid fa-layer-group text-primary"></i>
                        <span>Xem Hệ Sinh Thái</span>
                    </a>
                </div>

                <!-- Social Proof Avatars -->
                <div class="d-flex align-items-center gap-3 pt-2">
                    <div class="d-flex" style="margin-left: 6px;">
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 34px; height: 34px; background: #2563eb; margin-left: -6px; border: 2px solid #fff; font-size: 11px;">AD</span>
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 34px; height: 34px; background: #10b981; margin-left: -6px; border: 2px solid #fff; font-size: 11px;">FB</span>
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 34px; height: 34px; background: #f97316; margin-left: -6px; border: 2px solid #fff; font-size: 11px;">SP</span>
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 34px; height: 34px; background: #8b5cf6; margin-left: -6px; border: 2px solid #fff; font-size: 11px;">MM</span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-1 text-warning small" style="font-size: 12px;">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <span class="text-dark fw-bold ms-1" style="font-size: 13px;">4.9 / 5</span>
                        </div>
                        <span class="text-muted small" style="font-size: 12px;">Được tin dùng bởi <strong>10.000+</strong> Sellers, Agency & Team MMO</span>
                    </div>
                </div>
            </div>

            <!-- Hero Mockup Right Column (Interactive 3D Glass Device) -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="hero-showcase-stage" id="heroShowcaseStage">
                    <div class="hero-glow-backdrop"></div>

                    <!-- Floating Bubble 1 (Top Right) -->
                    <div class="hero-floating-bubble bubble-top-right">
                        <span class="status-node-dot"></span>
                        <span>10.000+ Khách hàng tin dùng • 0 Checkpoint</span>
                    </div>

                    <!-- Floating Bubble 2 (Bottom Left) -->
                    <div class="hero-floating-bubble bubble-bottom-left">
                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <span>VietQR Kích hoạt tự động trong 10 giây</span>
                    </div>

                    <!-- Main Glass Device Card -->
                    <div class="hero-glass-device">
                        <!-- Device Header -->
                        <div class="device-header">
                            <div class="device-dots">
                                <span class="device-dot red"></span>
                                <span class="device-dot yellow"></span>
                                <span class="device-dot green"></span>
                            </div>

                            <!-- Interactive Tab Switcher -->
                            <div class="device-tab-bar">
                                <button type="button" class="device-tab-btn active" data-tab="tab-vmaxlogin" onclick="switchHeroShowcase('tab-vmaxlogin', this)">
                                    <i class="fa-brands fa-chrome text-primary"></i>
                                    <span>VmaxLogin</span>
                                </button>
                                <button type="button" class="device-tab-btn" data-tab="tab-vautopost" onclick="switchHeroShowcase('tab-vautopost', this)">
                                    <i class="fa-solid fa-share-nodes text-success"></i>
                                    <span>V-AutoPost</span>
                                </button>
                                <button type="button" class="device-tab-btn" data-tab="tab-shopee" onclick="switchHeroShowcase('tab-shopee', this)">
                                    <i class="fa-solid fa-bag-shopping text-warning"></i>
                                    <span>ShopeeBoost</span>
                                </button>
                            </div>

                            <div class="d-none d-sm-flex align-items-center gap-1.5">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace" style="font-size: 10px; padding: 3px 8px;">
                                    ● Live Engine
                                </span>
                            </div>
                        </div>

                        <!-- Showcase Body -->
                        <div class="showcase-body">
                            <!-- TAB 1: VMAXLOGIN ANTIDETECT -->
                            <div class="device-tab-content active" id="tab-vmaxlogin">
                                <!-- Top Status Bar -->
                                <div class="metric-row-pill">
                                    <div class="status-node">
                                        <span class="status-node-dot"></span>
                                        <span>Canvas & WebGL Spoofing: <strong>100% Clean</strong></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 small text-muted font-monospace" style="font-size: 11px;">
                                        <span><i class="fa-solid fa-shield-halved text-success"></i> 0 Checkpoint</span>
                                        <span>•</span>
                                        <span><i class="fa-solid fa-network-wired text-primary"></i> SOCKS5 VN</span>
                                    </div>
                                </div>

                                <!-- Profile Item 1 -->
                                <div class="profile-motion-item">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="profile-avatar-pill" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
                                            <i class="fa-brands fa-facebook-f"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13px;">
                                                <span>FB_Agency_BM2500</span>
                                                <span class="badge bg-primary text-white" style="font-size: 9px; padding: 2px 6px;">Live Camp</span>
                                            </div>
                                            <div class="text-muted small" style="font-size: 11px;">
                                                Chrome 124 • Win 11 • IP: 103.142.26.18 (VN)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                            <i class="fa-solid fa-circle-check text-xs me-1"></i> Đang chạy
                                        </span>
                                    </div>
                                </div>

                                <!-- Profile Item 2 -->
                                <div class="profile-motion-item">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="profile-avatar-pill" style="background: linear-gradient(135deg, #10b981, #059669);">
                                            <i class="fa-brands fa-google"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13px;">
                                                <span>Google_Ads_Enterprise</span>
                                                <span class="badge bg-success text-white" style="font-size: 9px; padding: 2px 6px;">High Budget</span>
                                            </div>
                                            <div class="text-muted small" style="font-size: 11px;">
                                                Chrome 124 • Win 11 • IP: 14.161.45.92 (VN)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                            <i class="fa-solid fa-circle-check text-xs me-1"></i> Đang chạy
                                        </span>
                                    </div>
                                </div>

                                <!-- Profile Item 3 -->
                                <div class="profile-motion-item mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="profile-avatar-pill" style="background: linear-gradient(135deg, #0f172a, #334155);">
                                            <i class="fa-brands fa-tiktok"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13px;">
                                                <span>TikTok_Shop_Affiliate</span>
                                                <span class="badge bg-secondary text-white" style="font-size: 9px; padding: 2px 6px;">Creator</span>
                                            </div>
                                            <div class="text-muted small" style="font-size: 11px;">
                                                Chrome 123 • macOS Sonoma • IP: 115.79.138.50 (IPv6)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                            Sẵn sàng mở
                                        </span>
                                    </div>
                                </div>

                                <!-- Quick KPI Summary -->
                                <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded-3 text-muted small" style="font-size: 11px;">
                                    <span><i class="fa-solid fa-window-restore text-primary me-1"></i> <strong>128/150</strong> Profiles online</span>
                                    <span><i class="fa-solid fa-gauge-high text-success me-1"></i> Ping Proxy: <strong>18ms</strong></span>
                                    <span><i class="fa-solid fa-bolt text-warning me-1"></i> Multi-threading: <strong>Bật</strong></span>
                                </div>
                            </div>

                            <!-- TAB 2: V-AUTOPOST FACEBOOK -->
                            <div class="device-tab-content" id="tab-vautopost">
                                <!-- Top Status Bar -->
                                <div class="metric-row-pill">
                                    <div class="status-node">
                                        <span class="status-node-dot" style="background: #10b981;"></span>
                                        <span>Tiến Trình Nuôi Nick: <strong>50 Luồng Chạy Song Song</strong></span>
                                    </div>
                                    <div class="badge bg-success-subtle text-success small font-monospace" style="font-size: 11px;">
                                        1.250 Tài Khoản Live
                                    </div>
                                </div>

                                <!-- Task 1 -->
                                <div class="p-3 bg-white border rounded-3 mb-2.5 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                                        <span class="fw-bold text-dark small" style="font-size: 12.5px;">
                                            <i class="fa-solid fa-heart text-danger me-1"></i> Tự Động Seeding & Lướt Newfeed
                                        </span>
                                        <span class="text-success fw-bold small" style="font-size: 11.5px;">85% (170/200 nick)</span>
                                    </div>
                                    <div class="animated-progress-track mb-1">
                                        <div class="animated-progress-fill" style="width: 85%; background: linear-gradient(90deg, #10b981, #059669);"></div>
                                    </div>
                                    <span class="text-muted" style="font-size: 10.5px;">Thả tim bài viết bạn bè, xem story, bình luận tránh checkpoint</span>
                                </div>

                                <!-- Task 2 -->
                                <div class="p-3 bg-white border rounded-3 mb-2.5 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                                        <span class="fw-bold text-dark small" style="font-size: 12.5px;">
                                            <i class="fa-solid fa-bullhorn text-primary me-1"></i> Đăng Bài Đa Luồng Lên Group
                                        </span>
                                        <span class="text-primary fw-bold small" style="font-size: 11.5px;">92% (28/30 nhóm)</span>
                                    </div>
                                    <div class="animated-progress-track mb-1">
                                        <div class="animated-progress-fill" style="width: 92%; background: linear-gradient(90deg, #2563eb, #1d4ed8);"></div>
                                    </div>
                                    <span class="text-muted" style="font-size: 10.5px;">Tự động Spintax nội dung, kèm hình ảnh và video đa định dạng</span>
                                </div>

                                <!-- Task 3 -->
                                <div class="p-3 bg-white border rounded-3 mb-3 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                                        <span class="fw-bold text-dark small" style="font-size: 12.5px;">
                                            <i class="fa-solid fa-envelope text-purple me-1"></i> Gửi Tin Nhắn Chăm Sóc Theo UID
                                        </span>
                                        <span class="text-purple fw-bold small" style="font-size: 11.5px;">100% Hoàn Thành</span>
                                    </div>
                                    <div class="animated-progress-track mb-1">
                                        <div class="animated-progress-fill" style="width: 100%; background: linear-gradient(90deg, #8b5cf6, #7c3aed);"></div>
                                    </div>
                                    <span class="text-muted" style="font-size: 10.5px;">Tiếp cận 500 khách hàng tiềm năng đã lọc theo ngành nghề</span>
                                </div>

                                <!-- Quick KPI Summary -->
                                <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded-3 text-muted small" style="font-size: 11px;">
                                    <span><i class="fa-solid fa-users text-primary me-1"></i> Nick an toàn: <strong>100%</strong></span>
                                    <span><i class="fa-solid fa-clock text-info me-1"></i> Lên lịch: <strong>24/7</strong></span>
                                    <span><i class="fa-solid fa-shield-check text-success me-1"></i> Tự đổi IP Dcom/Proxy</span>
                                </div>
                            </div>

                            <!-- TAB 3: SHOPEEBOOST PRO -->
                            <div class="device-tab-content" id="tab-shopee">
                                <!-- Top Status Bar -->
                                <div class="metric-row-pill">
                                    <div class="status-node">
                                        <span class="status-node-dot" style="background: #f97316;"></span>
                                        <span>Hệ Thống Đẩy Top Shopee: <strong>Hoạt Động 24/7</strong></span>
                                    </div>
                                    <div class="badge bg-warning-subtle text-warning small font-monospace" style="font-size: 11px;">
                                        +4.820 Traffic Hôm Nay
                                    </div>
                                </div>

                                <!-- Shopee Feature 1 -->
                                <div class="profile-motion-item">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="profile-avatar-pill" style="background: linear-gradient(135deg, #f97316, #ea580c);">
                                            <i class="fa-solid fa-arrow-trend-up"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 13px;">Tự Động Đẩy Sản Phẩm Top Search</div>
                                            <div class="text-muted small" style="font-size: 11px;">
                                                Tự động kích hoạt đẩy 5 sản phẩm chủ lực sau mỗi 4 giờ
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                        Đang chạy
                                    </span>
                                </div>

                                <!-- Shopee Feature 2 -->
                                <div class="profile-motion-item">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="profile-avatar-pill" style="background: linear-gradient(135deg, #ec4899, #db2777);">
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 13px;">Tương Tác Shop Phụ & Buff Follow</div>
                                            <div class="text-muted small" style="font-size: 11px;">
                                                20 Tài khoản phụ tự động like, follow và kéo uy tín shop
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                        Đang chạy
                                    </span>
                                </div>

                                <!-- Shopee Feature 3 -->
                                <div class="profile-motion-item mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="profile-avatar-pill" style="background: linear-gradient(135deg, #8b5cf6, #6366f1);">
                                            <i class="fa-solid fa-shop"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 13px;">Quản Trị Đa Gian Hàng Shopee</div>
                                            <div class="text-muted small" style="font-size: 11px;">
                                                Đồng bộ đơn hàng, tin nhắn và sao chép sản phẩm 1-click
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                        12 Shop kết nối
                                    </span>
                                </div>

                                <!-- Quick KPI Summary -->
                                <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded-3 text-muted small" style="font-size: 11px;">
                                    <span><i class="fa-solid fa-chart-line text-warning me-1"></i> Tăng trưởng: <strong>+42% click</strong></span>
                                    <span><i class="fa-solid fa-rotate text-info me-1"></i> Đẩy sản phẩm: <strong>48 lần/ngày</strong></span>
                                    <span><i class="fa-solid fa-box text-success me-1"></i> Copy đa shop: <strong>Bật</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Logo Partner Marquee -->
<div class="marquee-container">
    <div class="marquee-wrapper">
        <span class="marquee-item"><i class="fa-brands fa-facebook text-primary"></i> Facebook Ads & Groups</span>
        <span class="marquee-item"><i class="fa-brands fa-google text-danger"></i> Google Ads & Profiles</span>
        <span class="marquee-item"><i class="fa-brands fa-tiktok text-dark"></i> TikTok Shop Marketing</span>
        <span class="marquee-item"><i class="fa-solid fa-bag-shopping text-warning"></i> Shopee Automation</span>
        <span class="marquee-item"><i class="fa-brands fa-amazon text-warning"></i> Amazon Seller Central</span>
        <span class="marquee-item"><i class="fa-solid fa-network-wired text-info"></i> Proxy SOCKS5 / IPv4 / IPv6</span>
        <span class="marquee-item"><i class="fa-solid fa-qrcode text-success"></i> VietQR Auto Activation</span>
        <span class="marquee-item"><i class="fa-brands fa-telegram text-primary"></i> Telegram Bot Notification</span>
        <!-- Duplicated for infinite loop -->
        <span class="marquee-item"><i class="fa-brands fa-facebook text-primary"></i> Facebook Ads & Groups</span>
        <span class="marquee-item"><i class="fa-brands fa-google text-danger"></i> Google Ads & Profiles</span>
        <span class="marquee-item"><i class="fa-brands fa-tiktok text-dark"></i> TikTok Shop Marketing</span>
        <span class="marquee-item"><i class="fa-solid fa-bag-shopping text-warning"></i> Shopee Automation</span>
        <span class="marquee-item"><i class="fa-brands fa-amazon text-warning"></i> Amazon Seller Central</span>
        <span class="marquee-item"><i class="fa-solid fa-network-wired text-info"></i> Proxy SOCKS5 / IPv4 / IPv6</span>
        <span class="marquee-item"><i class="fa-solid fa-qrcode text-success"></i> VietQR Auto Activation</span>
        <span class="marquee-item"><i class="fa-brands fa-telegram text-primary"></i> Telegram Bot Notification</span>
    </div>
</div>

<!-- 3. Key Metrics / Stats Section -->
<section class="py-5">
    <div class="container-xl">
        <div class="row row-cols-2 row-cols-lg-5 g-3 justify-content-center">
            <div class="col" data-aos="fade-up" data-aos-delay="0">
                <div class="stat-box-modern">
                    <div class="stat-number text-primary">10.000+</div>
                    <div class="text-muted small fw-semibold">Khách Hàng Tin Dùng</div>
                </div>
            </div>
            <div class="col" data-aos="fade-up" data-aos-delay="50">
                <div class="stat-box-modern">
                    <div class="stat-number text-success">250.000+</div>
                    <div class="text-muted small fw-semibold">Profile Đang Hoạt Động</div>
                </div>
            </div>
            <div class="col" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-box-modern">
                    <div class="stat-number text-info">99.9%</div>
                    <div class="text-muted small fw-semibold">Thời Gian Uptime Máy Chủ</div>
                </div>
            </div>
            <div class="col" data-aos="fade-up" data-aos-delay="150">
                <div class="stat-box-modern">
                    <div class="stat-number text-warning">100%</div>
                    <div class="text-muted small fw-semibold">Kích Hoạt VietQR Tự Động</div>
                </div>
            </div>
            <div class="col" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-box-modern">
                    <div class="stat-number text-purple">24/7</div>
                    <div class="text-muted small fw-semibold">Hỗ Trợ Kỹ Thuật Trực Tiếp</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. DEDICATED COMBO SECTION: Tiết kiệm đến 45% -->
<section class="py-5" style="background: linear-gradient(180deg, #f8fafc 0%, #edf2f7 100%);">
    <div class="container-xl">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge-pill badge-orange">
                <i class="fa-solid fa-fire text-danger me-1"></i> TIẾT KIỆM LÊN TỚI 45%
            </span>
            <h2 class="section-title">Gói Combo Giải Pháp Đột Phá</h2>
            <p class="section-subtitle">
                Được thiết kế trọn bộ cho cá nhân và Agency cần tiếp cận khách hàng đa kênh, nuôi tài khoản tự động và tăng tốc doanh thu MMO.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($combos as $index => $c): ?>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="combo-card-hero <?= $index == 0 ? 'combo-fb' : '' ?>">
                    <span class="combo-badge-floating">
                        <?= htmlspecialchars($c['badge'] ?? 'HOT DEAL') ?>
                    </span>

                    <div>
                        <!-- Title & Description -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 p-2 bg-light border d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <img src="/<?= htmlspecialchars($c['image_url'] ?? 'image/sanphamfb.webp') ?>" alt="<?= htmlspecialchars($c['name']) ?>" style="max-height: 40px; max-width: 40px; object-fit: contain;">
                            </div>
                            <div>
                                <h3 class="fw-bold h4 text-dark mb-1"><?= htmlspecialchars($c['name']) ?></h3>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 11px;">
                                    Đầy đủ bản quyền trọn bộ
                                </span>
                            </div>
                        </div>

                        <p class="text-secondary small mb-4" style="line-height: 1.6;">
                            <?= htmlspecialchars($c['short_description']) ?>
                        </p>

                        <!-- Software Included Checklist -->
                        <div class="mb-4">
                            <div class="small fw-bold text-dark text-uppercase tracking-wider mb-2" style="font-size: 11px;">
                                <i class="fa-solid fa-box-open text-primary me-1"></i> Phần mềm bao gồm trong gói:
                            </div>
                            <div class="row g-2">
                                <?php 
                                $tools = $c['tools'] ?? ['VmaxLogin Antidetect Browser', 'V-AutoPost Facebook', 'Tool Tự Động Hóa Chuyên Sâu'];
                                foreach ($tools as $t): 
                                ?>
                                <div class="col-sm-6">
                                    <div class="combo-tool-pill">
                                        <i class="fa-solid fa-circle-check text-success"></i>
                                        <span><?= htmlspecialchars($t) ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Price & Action -->
                    <div class="border-top pt-4 mt-2">
                        <div class="d-flex align-items-baseline justify-content-between mb-3">
                            <div>
                                <span class="small text-muted d-block" style="font-size: 12px;">Giá ưu đãi trọn gói:</span>
                                <div class="d-flex align-items-baseline gap-2">
                                    <span class="h3 fw-bold text-primary mb-0" style="letter-spacing: -0.02em;">
                                        <?= number_format($c['price_monthly'] ?? 500000, 0, ',', '.') ?>đ
                                    </span>
                                    <span class="text-muted small">/ tháng</span>
                                    <span class="text-muted text-decoration-line-through small" style="font-size: 13px;">
                                        <?= number_format(($c['price_monthly'] ?? 500000) * 1.5, 0, ',', '.') ?>đ
                                    </span>
                                </div>
                            </div>
                            <span class="badge bg-danger-subtle text-danger fw-bold px-2.5 py-1.5 rounded-pill" style="font-size: 11px;">
                                Tiết kiệm 40%
                            </span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="/login" class="btn btn-primary fw-bold py-2.5 rounded-3 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none;">
                                <span>Đăng Ký Gói Combo Ngay</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 5. SINGLE SOFTWARE SHOWCASE -->
<section class="py-5" id="products">
    <div class="container-xl">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge-pill badge-blue">
                HỆ SINH THÁI ĐỘC LẬP
            </span>
            <h2 class="section-title">Danh Sách Phần Mềm Chuyên Biệt</h2>
            <p class="section-subtitle">
                Từng công cụ được tối ưu sâu cho từng tác vụ riêng biệt: Quản trị vân tay trình duyệt, tương tác mạng xã hội và sàn thương mại điện tử.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php
            // Ensure 4 distinct software cards
            $displaySingles = [
                'lushlogin' => [
                    'name' => 'VmaxLogin Antidetect',
                    'slug' => 'lushlogin',
                    'desc' => 'Trình duyệt chống dò quét vân tay antidetect bảo mật cao, độc lập Canvas, WebGL, Audio và WebRTC.',
                    'config' => $productDetailsMap['lushlogin']
                ],
                'v-autopost' => [
                    'name' => 'V-AutoPost Facebook',
                    'slug' => 'v-autopost',
                    'desc' => 'Phần mềm tự động hóa marketing, đăng bài Profile/Fanpage/Group đa luồng và nuôi nick tránh checkpoint.',
                    'config' => $productDetailsMap['v-autopost']
                ],
                'v-shopeeboost' => [
                    'name' => 'V-ShopeeBoost',
                    'slug' => 'v-shopeeboost',
                    'desc' => 'Công cụ tự động đẩy sản phẩm lên top tìm kiếm Shopee, buff tương tác shop an toàn từ tài khoản phụ.',
                    'config' => $productDetailsMap['v-shopeeboost']
                ],
                'v-shopeeboost-pro' => [
                    'name' => 'V-ShopeeBoost Pro',
                    'slug' => 'v-shopeeboost-pro',
                    'desc' => 'Phiên bản cao cấp hỗ trợ quản trị đa gian hàng, phân tích đối thủ cạnh tranh và sao chép sản phẩm hàng loạt.',
                    'config' => $productDetailsMap['v-shopeeboost-pro']
                ]
            ];

            // Match with database prices if available
            foreach ($singles as $dbItem) {
                $slugKey = strtolower($dbItem['slug']);
                if (isset($displaySingles[$slugKey])) {
                    $displaySingles[$slugKey]['desc'] = $dbItem['short_description'] ?: $displaySingles[$slugKey]['desc'];
                    if (!empty($dbItem['price_monthly'])) {
                        $displaySingles[$slugKey]['config']['default_price'] = $dbItem['price_monthly'];
                    }
                }
            }

            foreach ($displaySingles as $k => $item):
                $cfg = $item['config'];
            ?>
            <div class="col-md-6 col-lg-3" data-aos="fade-up">
                <div class="prod-card-modern">
                    <div>
                        <!-- Header Icon & Version -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="prod-icon-circle" style="background: <?= $cfg['icon_gradient'] ?>;">
                                <i class="<?= $cfg['icon'] ?>"></i>
                            </div>
                            <span class="badge bg-light text-secondary border px-2 py-1 font-monospace" style="font-size: 11px;">
                                <?= $cfg['version'] ?>
                            </span>
                        </div>

                        <!-- Badge -->
                        <span class="section-badge-pill <?= $cfg['badge_class'] ?>" style="font-size: 10px; padding: 3px 10px; margin-bottom: 8px;">
                            <?= $cfg['badge'] ?>
                        </span>

                        <h3 class="fw-bold h5 text-dark mb-2"><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="text-secondary small mb-3" style="font-size: 0.85rem; line-height: 1.5; min-height: 60px;">
                            <?= htmlspecialchars($item['desc']) ?>
                        </p>

                        <!-- Checklist -->
                        <ul class="list-unstyled mb-4">
                            <?php foreach ($cfg['features'] as $f): ?>
                            <li class="small text-secondary mb-2 d-flex align-items-start" style="font-size: 0.82rem; line-height: 1.4;">
                                <i class="fa-solid fa-check text-success me-2 mt-1" style="font-size: 0.75rem;"></i>
                                <span><?= htmlspecialchars($f) ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Price & Button -->
                    <div class="border-top pt-3 mt-auto">
                        <div class="d-flex justify-content-between align-items-baseline mb-3">
                            <span class="small text-muted" style="font-size: 12px;">Giá chỉ từ:</span>
                            <span class="text-primary fw-bold h5 mb-0">
                                <?= number_format($cfg['default_price'], 0, ',', '.') ?>đ<small class="text-muted fw-normal" style="font-size: 11px;">/th</small>
                            </span>
                        </div>
                        <div class="d-grid">
                            <a href="/products" class="btn btn-outline-primary fw-bold py-2 rounded-3 text-sm d-flex align-items-center justify-content-center gap-2">
                                <span>Xem Chi Tiết</span>
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 6. INTERACTIVE PRICING SWITCHER -->
<section class="py-5 bg-white border-top border-bottom" id="pricing-comparison">
    <div class="container-xl text-center">
        <div class="mb-4" data-aos="fade-up">
            <span class="section-badge-pill badge-purple">
                BẢNG GIÁ MINH BẠCH
            </span>
            <h2 class="section-title">Chọn Gói Dịch Vụ Phù Hợp</h2>
            <p class="section-subtitle">
                Tất cả các gói đều hỗ trợ tự động gia hạn, bảo hành 100% trong suốt thời gian sử dụng và hỗ trợ kỹ thuật qua Ultraview.
            </p>
        </div>

        <!-- Billing Period Tabs -->
        <div class="pricing-tabs-wrapper" data-aos="fade-up">
            <button class="pricing-tab-btn active" onclick="switchBillingCycle('monthly', this)">
                1 Tháng
            </button>
            <button class="pricing-tab-btn" onclick="switchBillingCycle('quarterly', this)">
                3 Tháng <span class="save-pill">Tiết kiệm 15%</span>
            </button>
            <button class="pricing-tab-btn" onclick="switchBillingCycle('yearly', this)">
                1 Năm <span class="save-pill">Tiết kiệm 35%</span>
            </button>
            <button class="pricing-tab-btn" onclick="switchBillingCycle('lifetime', this)">
                Trọn Đời <span class="save-pill" style="background:#fee2e2;color:#b91c1c;">Đặc quyền VIP</span>
            </button>
        </div>

        <!-- 4 Pricing Cards -->
        <div class="row g-4 align-items-stretch justify-content-center text-start" data-aos="fade-up">
            <!-- Plan 1: Starter -->
            <div class="col-lg-3 col-md-6">
                <div class="pricing-card-modern">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="fw-bold h5 text-dark mb-0">Gói Starter</h4>
                            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 11px;">Cá nhân</span>
                        </div>
                        <p class="small text-secondary mb-4" style="min-height: 40px;">Dành cho cá nhân mới bắt đầu làm quen với Antidetect & MMO.</p>
                        
                        <div class="mb-4">
                            <span class="h2 fw-bold text-dark plan-price" data-monthly="200.000đ" data-quarterly="500.000đ" data-yearly="1.800.000đ" data-lifetime="3.900.000đ">200.000đ</span>
                            <span class="text-muted small plan-period">/ tháng</span>
                        </div>

                        <ul class="list-unstyled mb-4 small" style="line-height: 2;">
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Kích hoạt: <strong>1 Thiết bị</strong></li>
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Profile khởi tạo không giới hạn</li>
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Miễn phí cập nhật phiên bản</li>
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Hỗ trợ kỹ thuật qua Zalo / Ultraview</li>
                        </ul>
                    </div>
                    <div class="d-grid mt-auto">
                        <a href="/login" class="btn btn-outline-secondary fw-bold py-2.5 rounded-3">Chọn Gói Này</a>
                    </div>
                </div>
            </div>

            <!-- Plan 2: Team -->
            <div class="col-lg-3 col-md-6">
                <div class="pricing-card-modern">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="fw-bold h5 text-dark mb-0">Gói Đội Ngũ</h4>
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1" style="font-size: 11px;">Team nhỏ</span>
                        </div>
                        <p class="small text-secondary mb-4" style="min-height: 40px;">Tối ưu cho nhóm 2-3 người cùng vận hành chiến dịch quảng cáo.</p>
                        
                        <div class="mb-4">
                            <span class="h2 fw-bold text-dark plan-price" data-monthly="350.000đ" data-quarterly="850.000đ" data-yearly="2.900.000đ" data-lifetime="5.900.000đ">350.000đ</span>
                            <span class="text-muted small plan-period">/ tháng</span>
                        </div>

                        <ul class="list-unstyled mb-4 small" style="line-height: 2;">
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Kích hoạt: <strong>2 - 3 Thiết bị</strong></li>
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Cloud Sync Profile theo thời gian thực</li>
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Proxy SOCKS5/HTTP đa luồng</li>
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Hỗ trợ ưu tiên phản hồi nhanh</li>
                        </ul>
                    </div>
                    <div class="d-grid mt-auto">
                        <a href="/login" class="btn btn-outline-primary fw-bold py-2.5 rounded-3">Chọn Gói Này</a>
                    </div>
                </div>
            </div>

            <!-- Plan 3: Pro (Popular) -->
            <div class="col-lg-3 col-md-6">
                <div class="pricing-card-modern popular">
                    <span class="popular-ribbon">
                        <i class="fa-solid fa-crown text-warning me-1"></i> KHUYÊN DÙNG NHẤT
                    </span>
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3 mt-1">
                            <h4 class="fw-bold h5 text-primary mb-0">Gói Agency Pro</h4>
                            <span class="badge bg-primary text-white px-2 py-1" style="font-size: 11px;">Phổ biến</span>
                        </div>
                        <p class="small text-secondary mb-4" style="min-height: 40px;">Cấu hình mạnh mẽ nhất dành cho Agency và Team chuyên nghiệp.</p>
                        
                        <div class="mb-4">
                            <span class="h2 fw-bold text-primary plan-price" data-monthly="600.000đ" data-quarterly="1.500.000đ" data-yearly="4.500.000đ" data-lifetime="8.900.000đ">600.000đ</span>
                            <span class="text-muted small plan-period">/ tháng</span>
                        </div>

                        <ul class="list-unstyled mb-4 small" style="line-height: 2;">
                            <li><i class="fa-solid fa-circle-check text-primary me-2"></i> Kích hoạt: <strong>5 Thiết bị đồng thời</strong></li>
                            <li><i class="fa-solid fa-circle-check text-primary me-2"></i> API Điều Khiển Puppeteer / Playwright</li>
                            <li><i class="fa-solid fa-circle-check text-primary me-2"></i> Chống checkpoint chéo tuyệt đối</li>
                            <li><i class="fa-solid fa-circle-check text-primary me-2"></i> Hỗ trợ riêng 1:1 qua Ultraview</li>
                        </ul>
                    </div>
                    <div class="d-grid mt-auto">
                        <a href="/login" class="btn btn-primary fw-bold py-2.5 rounded-3" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); border:none;">Đăng Ký Gói Pro</a>
                    </div>
                </div>
            </div>

            <!-- Plan 4: Lifetime VIP -->
            <div class="col-lg-3 col-md-6">
                <div class="pricing-card-modern">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="fw-bold h5 text-dark mb-0">Gói Vĩnh Viễn VIP</h4>
                            <span class="badge bg-purple-subtle text-purple border border-purple-subtle px-2 py-1" style="font-size: 11px;">Trọn đời</span>
                        </div>
                        <p class="small text-secondary mb-4" style="min-height: 40px;">Thanh toán 1 lần duy nhất, sở hữu bản quyền vĩnh viễn không lo gia hạn.</p>
                        
                        <div class="mb-4">
                            <span class="h2 fw-bold text-dark plan-price" data-monthly="3.900.000đ" data-quarterly="3.900.000đ" data-yearly="3.900.000đ" data-lifetime="3.900.000đ">3.900.000đ</span>
                            <span class="text-muted small plan-period">/ trọn đời</span>
                        </div>

                        <ul class="list-unstyled mb-4 small" style="line-height: 2;">
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Kích hoạt: <strong>10 Thiết bị</strong></li>
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Nâng cấp mọi tính năng mới trọn đời</li>
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Không phí duy trì server hàng tháng</li>
                            <li><i class="fa-solid fa-circle-check text-success me-2"></i> Nhóm riêng tư vấn MMO từ chuyên gia</li>
                        </ul>
                    </div>
                    <div class="d-grid mt-auto">
                        <a href="/login" class="btn btn-outline-dark fw-bold py-2.5 rounded-3">Sở Hữu Trọn Đời</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6.5. DEDICATED COMPETITOR COMPARISON MATRIX -->
<section class="py-5" style="background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);">
    <div class="container-xl">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge-pill badge-emerald">
                <i class="fa-solid fa-chart-simple me-1"></i> BẢNG SO SÁNH NỔI BẬT
            </span>
            <h2 class="section-title">Tại Sao VMAX Vượt Trội Hơn Đối Thủ?</h2>
            <p class="section-subtitle">
                So sánh thực tế giữa VMAX Software với các giải pháp quốc tế đắt đỏ và các phần mềm trôi nổi trên thị trường.
            </p>
        </div>

        <div class="table-responsive rounded-4 border shadow-sm bg-white" data-aos="fade-up">
            <table class="table table-hover align-middle mb-0" style="min-width: 720px;">
                <thead style="background: #0f172a; color: #ffffff;">
                    <tr>
                        <th class="py-3 px-4 text-white" style="width: 34%; font-size: 14px;">Tiêu Chí Đánh Giá</th>
                        <th class="py-3 px-4 text-center text-primary bg-primary bg-opacity-25" style="width: 26%; font-size: 15px; border-left: 2px solid #2563eb; border-right: 2px solid #2563eb;">
                            <div class="fw-bold d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-crown text-warning"></i> VMAX SOFTWARE
                            </div>
                            <small class="text-primary-subtle d-block fw-normal" style="font-size: 11px;">Tối ưu cho người dùng VN</small>
                        </th>
                        <th class="py-3 px-4 text-center text-slate-300" style="width: 20%; font-size: 13px;">Phần Mềm Quốc Tế<br><small class="text-slate-400" style="font-size: 10.5px;">(AdsPower, GoLogin)</small></th>
                        <th class="py-3 px-4 text-center text-slate-300" style="width: 20%; font-size: 13px;">Tool Crack / Trôi Nổi<br><small class="text-slate-400" style="font-size: 10.5px;">(Không rõ nguồn gốc)</small></th>
                    </tr>
                </thead>
                <tbody class="small">
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-dark">
                            <i class="fa-solid fa-tag text-primary me-2"></i> Chi phí sử dụng
                        </td>
                        <td class="py-3 px-4 text-center fw-bold text-success bg-primary bg-opacity-10" style="border-left: 2px solid #2563eb; border-right: 2px solid #2563eb;">
                            Chỉ từ 200.000đ/tháng <br><span class="badge bg-success-subtle text-success">Tiết kiệm 60%</span>
                        </td>
                        <td class="py-3 px-4 text-center text-danger">Từ 600.000đ - 1.800.000đ/tháng</td>
                        <td class="py-3 px-4 text-center text-muted">Miễn phí nhưng rủi ro cao</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-dark">
                            <i class="fa-solid fa-globe text-primary me-2"></i> Giới hạn khởi tạo Profile
                        </td>
                        <td class="py-3 px-4 text-center fw-bold text-success bg-primary bg-opacity-10" style="border-left: 2px solid #2563eb; border-right: 2px solid #2563eb;">
                            <i class="fa-solid fa-circle-check text-success me-1"></i> Không giới hạn
                        </td>
                        <td class="py-3 px-4 text-center text-muted">Bị bóp theo từng gói (10-50 profiles)</td>
                        <td class="py-3 px-4 text-center text-muted">Không hỗ trợ đồng bộ</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-dark">
                            <i class="fa-solid fa-fingerprint text-primary me-2"></i> Chất lượng vân tay Antidetect
                        </td>
                        <td class="py-3 px-4 text-center fw-bold text-success bg-primary bg-opacity-10" style="border-left: 2px solid #2563eb; border-right: 2px solid #2563eb;">
                            100% Canvas, WebGL, Audio độc lập
                        </td>
                        <td class="py-3 px-4 text-center text-success">Chất lượng tốt</td>
                        <td class="py-3 px-4 text-center text-danger">Dễ dính Blacklist & Checkpoint</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-dark">
                            <i class="fa-solid fa-computer text-primary me-2"></i> Đổi máy tính (Reset HWID)
                        </td>
                        <td class="py-3 px-4 text-center fw-bold text-success bg-primary bg-opacity-10" style="border-left: 2px solid #2563eb; border-right: 2px solid #2563eb;">
                            <i class="fa-solid fa-bolt text-warning me-1"></i> Tự động 1-click trên Web
                        </td>
                        <td class="py-3 px-4 text-center text-muted">Giới hạn số lần đăng nhập</td>
                        <td class="py-3 px-4 text-center text-danger">Khóa cứng hoặc cài lại từ đầu</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-dark">
                            <i class="fa-solid fa-qrcode text-primary me-2"></i> Cổng thanh toán & Kích hoạt
                        </td>
                        <td class="py-3 px-4 text-center fw-bold text-success bg-primary bg-opacity-10" style="border-left: 2px solid #2563eb; border-right: 2px solid #2563eb;">
                            VietQR tự động trong 10s
                        </td>
                        <td class="py-3 px-4 text-center text-danger">Bắt buộc thẻ Visa/Mastercard quốc tế</td>
                        <td class="py-3 px-4 text-center text-muted">Chuyển khoản thủ công chờ đợi</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-dark">
                            <i class="fa-solid fa-headset text-primary me-2"></i> Hỗ trợ kỹ thuật
                        </td>
                        <td class="py-3 px-4 text-center fw-bold text-success bg-primary bg-opacity-10" style="border-left: 2px solid #2563eb; border-right: 2px solid #2563eb;">
                            Tiếng Việt 1-1 qua Ultraview 24/7
                        </td>
                        <td class="py-3 px-4 text-center text-muted">Ticket tiếng Anh chờ 24-48h</td>
                        <td class="py-3 px-4 text-center text-danger">Không có hỗ trợ</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- 7. BENTO GRID FEATURES -->
<section class="py-5">
    <div class="container-xl">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge-pill badge-emerald">
                CÔNG NGHỆ TIÊN TIẾN
            </span>
            <h2 class="section-title">Tại Sao Nên Lựa Chọn VMAX Platform?</h2>
            <p class="section-subtitle">
                Xây dựng trên nền tảng công nghệ tối tân giúp tối ưu hóa hiệu năng, bảo mật tối đa và tiết kiệm đến 80% thời gian vận hành.
            </p>
        </div>

        <div class="bento-modern-grid" data-aos="fade-up">
            <!-- Box 1 (Span 8) -->
            <div class="bento-cell" style="grid-column: span 8;">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="rounded-3 p-3 bg-primary-subtle text-primary">
                        <i class="fa-solid fa-fingerprint fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Công Nghệ Giả Lập Vân Tay Độc Lập 100%</h4>
                        <p class="text-secondary small mb-0">Tùy biến sâu từng thông số phần cứng trình duyệt: Canvas, WebGL, AudioContext, Client Rects, WebRTC và User-Agent tránh triệt để việc các nền tảng quét liên kết tài khoản.</p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 pt-2">
                    <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill small">Canvas Emulation</span>
                    <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill small">WebGL Noise</span>
                    <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill small">WebRTC Shield</span>
                    <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill small">OS Spoofing</span>
                </div>
            </div>

            <!-- Box 2 (Span 4) -->
            <div class="bento-cell" style="grid-column: span 4;">
                <div>
                    <div class="rounded-3 p-3 bg-success-subtle text-success d-inline-block mb-3">
                        <i class="fa-solid fa-qrcode fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Kích Hoạt VietQR Tức Thì</h4>
                    <p class="text-secondary small mb-0">Hệ thống nạp tiền và kích hoạt bản quyền tự động 24/7 qua cổng VietQR ngân hàng, nhận key và sử dụng trong 10 giây.</p>
                </div>
            </div>

            <!-- Box 3 (Span 4) -->
            <div class="bento-cell" style="grid-column: span 4;">
                <div>
                    <div class="rounded-3 p-3 bg-warning-subtle text-warning d-inline-block mb-3">
                        <i class="fa-solid fa-code fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Tích Hợp API Automation</h4>
                    <p class="text-secondary small mb-0">Cung cấp RESTful API và cổng WebSocket kết nối liền mạch với Puppeteer, Playwright và Selenium cho lập trình viên.</p>
                </div>
            </div>

            <!-- Box 4 (Span 4) -->
            <div class="bento-cell" style="grid-column: span 4;">
                <div>
                    <div class="rounded-3 p-3 bg-info-subtle text-info d-inline-block mb-3">
                        <i class="fa-solid fa-cloud-arrow-up fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Đồng Bộ Profile Đám Mây</h4>
                    <p class="text-secondary small mb-0">Mã hóa và lưu trữ cookies, bookmarks, proxy trên cloud, hỗ trợ chia sẻ profile cho nhân viên trong team dễ dàng.</p>
                </div>
            </div>

            <!-- Box 5 (Span 4) -->
            <div class="bento-cell" style="grid-column: span 4;">
                <div>
                    <div class="rounded-3 p-3 bg-danger-subtle text-danger d-inline-block mb-3">
                        <i class="fa-solid fa-shield-halved fa-2x"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Mã Hóa AES-256 Chuẩn Cao Cấp</h4>
                    <p class="text-secondary small mb-0">Mật khẩu và dữ liệu tài khoản nhạy cảm của bạn được mã hóa đầu cuối, đảm bảo không bao giờ bị rò rỉ.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 8. APP SCREENSHOTS & REAL SHOWCASE -->
<section class="py-5 bg-white border-top border-bottom" id="app-previews">
    <div class="container-xl">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge-pill badge-blue">
                GIAO DIỆN TRỰC QUAN
            </span>
            <h2 class="section-title">Trải Nghiệm Ứng Dụng Thực Tế</h2>
            <p class="section-subtitle">
                Giao diện được trau chuốt tối giản, trực quan, hỗ trợ tiếng Việt hoàn toàn giúp bạn làm chủ phần mềm chỉ trong 5 phút.
            </p>
        </div>

        <div class="row g-4">
            <!-- App 1: VmaxLogin -->
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="0">
                <div class="hero-mockup-window h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="hero-mockup-header">
                            <div class="window-dots">
                                <span class="window-dot red"></span>
                                <span class="window-dot yellow"></span>
                                <span class="window-dot green"></span>
                            </div>
                            <span class="small fw-bold text-muted">VmaxLogin Desktop</span>
                        </div>
                        <div class="p-3 text-center position-relative" style="cursor: pointer;" onclick="openLightbox('/image/logologin.webp')">
                            <img src="/image/logologin.webp" alt="VmaxLogin Desktop" class="img-fluid rounded" style="max-height: 220px; object-fit: contain;">
                            <span class="position-absolute bottom-0 end-0 m-3 badge bg-dark text-white opacity-75 small">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Phóng to
                            </span>
                        </div>
                        <div class="p-3 pt-0">
                            <h4 class="fw-bold h5 text-dark mb-1">VmaxLogin Antidetect</h4>
                            <p class="text-secondary small mb-3">Tạo và phân loại hàng ngàn profile trình duyệt với tag màu sắc, proxy riêng biệt và thông số vân tay chống quét.</p>
                        </div>
                    </div>
                    <div class="p-3 pt-0">
                        <a href="/products" class="btn btn-outline-primary btn-sm w-100 fw-bold py-2">Khám Phá VmaxLogin</a>
                    </div>
                </div>
            </div>

            <!-- App 2: V-AutoPost -->
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="hero-mockup-window h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="hero-mockup-header">
                            <div class="window-dots">
                                <span class="window-dot red"></span>
                                <span class="window-dot yellow"></span>
                                <span class="window-dot green"></span>
                            </div>
                            <span class="small fw-bold text-muted">V-AutoPost Facebook</span>
                        </div>
                        <div class="p-3 text-center position-relative" style="cursor: pointer;" onclick="openLightbox('/image/logofb.webp')">
                            <img src="/image/logofb.webp" alt="V-AutoPost Facebook" class="img-fluid rounded" style="max-height: 220px; object-fit: contain;">
                            <span class="position-absolute bottom-0 end-0 m-3 badge bg-dark text-white opacity-75 small">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Phóng to
                            </span>
                        </div>
                        <div class="p-3 pt-0">
                            <h4 class="fw-bold h5 text-dark mb-1">V-AutoPost Marketing</h4>
                            <p class="text-secondary small mb-3">Hệ thống nuôi nick Facebook đa luồng, hỗ trợ lên lịch đăng bài, tự động seeding và spam tin nhắn thông minh.</p>
                        </div>
                    </div>
                    <div class="p-3 pt-0">
                        <a href="/products" class="btn btn-outline-success btn-sm w-100 fw-bold py-2">Khám Phá V-AutoPost</a>
                    </div>
                </div>
            </div>

            <!-- App 3: V-ShopeeBoost -->
            <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="hero-mockup-window h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="hero-mockup-header">
                            <div class="window-dots">
                                <span class="window-dot red"></span>
                                <span class="window-dot yellow"></span>
                                <span class="window-dot green"></span>
                            </div>
                            <span class="small fw-bold text-muted">V-ShopeeBoost Tool</span>
                        </div>
                        <div class="p-3 text-center position-relative" style="cursor: pointer;" onclick="openLightbox('/image/logosp.webp')">
                            <img src="/image/logosp.webp" alt="V-ShopeeBoost Tool" class="img-fluid rounded" style="max-height: 220px; object-fit: contain;">
                            <span class="position-absolute bottom-0 end-0 m-3 badge bg-dark text-white opacity-75 small">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Phóng to
                            </span>
                        </div>
                        <div class="p-3 pt-0">
                            <h4 class="fw-bold h5 text-dark mb-1">V-ShopeeBoost Pro</h4>
                            <p class="text-secondary small mb-3">Công cụ tự động đẩy sản phẩm lên top tìm kiếm, tăng lượt follow shop và quản trị đa gian hàng Shopee.</p>
                        </div>
                    </div>
                    <div class="p-3 pt-0">
                        <a href="/products" class="btn btn-outline-warning btn-sm w-100 fw-bold py-2">Khám Phá ShopeeBoost</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. REVIEWS / TESTIMONIALS -->
<section class="py-5">
    <div class="container-xl">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge-pill badge-orange">
                ĐÁNH GIÁ THỰC TẾ
            </span>
            <h2 class="section-title">Khách Hàng Nói Gì Về VMAX?</h2>
            <p class="section-subtitle">
                Được kiểm chứng hiệu quả thực chiến bởi hơn 10.000 chuyên gia MMO, Agency quảng cáo và người bán hàng online.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="testi-card-modern">
                    <div>
                        <div class="testi-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-secondary small mb-4" style="line-height: 1.7;">
                            "VMAX Antidetect Browser thực sự là cứu cánh cho team mình. Team chạy hơn 500 tài khoản Ads Facebook & Google ngân sách lớn cực kỳ mượt mà, không hề bị checkpoint chéo nhờ thông số vân tay Canvas và WebGL quá sạch."
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 border-top pt-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 42px; height: 42px; background: linear-gradient(135deg, #2563eb, #4f46e5);">
                            TH
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">Trần Hoàng</h6>
                            <span class="text-muted small" style="font-size: 12px;">Founder, Ads Agency • Hà Nội</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testi-card-modern">
                    <div>
                        <div class="testi-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-secondary small mb-4" style="line-height: 1.7;">
                            "Phần mềm V-AutoPost giúp bên mình tự động hóa 90% thời gian chăm sóc fanpage, đăng bài và tương tác seeding các nhóm. Giao diện tiếng Việt cực dễ cấu hình, chạy đa luồng rất nhanh và ổn định."
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 border-top pt-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 42px; height: 42px; background: linear-gradient(135deg, #10b981, #059669);">
                            MA
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">Minh Anh</h6>
                            <span class="text-muted small" style="font-size: 12px;">Top Seller Shopee & TikTok Shop</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="testi-card-modern">
                    <div>
                        <div class="testi-stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-secondary small mb-4" style="line-height: 1.7;">
                            "Cực kỳ thích tính năng quản lý thiết bị trên website SaaS. Đổi máy hay reset thiết bị chỉ 1 click là xong. Nạp tiền qua mã VietQR kích hoạt key ngay lập tức trong vài giây, support kỹ thuật rất nhiệt tình."
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 border-top pt-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 42px; height: 42px; background: linear-gradient(135deg, #f97316, #ea580c);">
                            QD
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">Quốc Duy</h6>
                            <span class="text-muted small" style="font-size: 12px;">Affiliate Marketer • TP. Hồ Chí Minh</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 10. FAQ SECTION -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container-xl" style="max-width: 800px;">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge-pill badge-blue">GIẢI ĐÁP THẮC MẮC</span>
            <h2 class="section-title">Câu Hỏi Thường Gặp</h2>
            <p class="section-subtitle">Tất cả những thông tin bạn cần biết trước khi bắt đầu sử dụng VMAX Platform.</p>
        </div>

        <div class="accordion accordion-vmax" id="faqAccordion" data-aos="fade-up">
            <!-- Q1 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Antidetect Browser hoạt động như thế nào và có an toàn không?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        VmaxLogin hoạt động bằng cách cô lập từng môi trường trình duyệt với thông số phần cứng riêng biệt (Canvas, WebGL, AudioContext, Screen Resolution, CPU Cores, RAM). Các website như Facebook hay Google sẽ nhận diện mỗi profile là một máy tính vật lý hoàn toàn khác nhau, ngăn chặn triệt để tình trạng khóa tài khoản liên đới.
                    </div>
                </div>
            </div>

            <!-- Q2 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Tôi có thể đổi máy tính khác sau khi kích hoạt bản quyền không?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Hoàn toàn được! Bạn có thể truy cập vào cổng quản lý cá nhân trên website tại mục <strong>Thiết bị (Devices)</strong> và nhấn nút <strong>Xóa / Reset thiết bị</strong> chỉ với 1 cú click để chuyển bản quyền sang máy tính mới bất kỳ lúc nào.
                    </div>
                </div>
            </div>

            <!-- Q3 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        Thanh toán và kích hoạt phần mềm mất bao lâu?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Hệ thống tích hợp cổng thanh toán tự động qua <strong>VietQR ngân hàng</strong>. Sau khi chuyển khoản đúng cú pháp, hệ thống sẽ tự động kích hoạt license key trong vòng <strong>5 - 15 giây</strong> mà không cần nhân viên duyệt thủ công.
                    </div>
                </div>
            </div>

            <!-- Q4 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                        Phần mềm có tự động cập nhật khi các nền tảng thay đổi thuật toán không?
                    </button>
                </h2>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Có. Đội ngũ kỹ thuật viên VMAX liên tục theo dõi các bản cập nhật của Facebook, Google, Shopee. Mỗi khi mở phần mềm, hệ thống sẽ tự động phát hiện và cập nhật lên phiên bản mới nhất hoàn toàn miễn phí.
                    </div>
                </div>
            </div>

            <!-- Q5 -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                        Tôi có được hỗ trợ cài đặt và hướng dẫn sử dụng không?
                    </button>
                </h2>
                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Có. Đội ngũ hỗ trợ kỹ thuật luôn sẵn sàng hỗ trợ trực tiếp 1:1 qua <strong>Ultraviewer, Anydesk, Zalo Hotline</strong> để cài đặt, hướng dẫn vận hành và khắc phục mọi sự cố kỹ thuật trong suốt quá trình bạn sử dụng phần mềm.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 11. BOTTOM HIGH-CONVERSION CTA BANNER -->
<section class="container-xl py-5">
    <div class="cta-banner-dark" data-aos="zoom-in">
        <div class="cta-banner-pattern"></div>
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col-lg-8 mb-4 mb-lg-0 text-center text-lg-start">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill mb-3 fw-bold small">
                    <i class="fa-solid fa-rocket me-1"></i> BẮT ĐẦU NGAY HÔM NAY
                </span>
                <h2 class="fw-bold h1 text-white mb-2">Sẵn Sàng Đột Phá Doanh Thu Cùng VMAX?</h2>
                <p class="text-slate-300 mb-0" style="font-size: 1.05rem; max-width: 580px;">
                    Đăng ký tài khoản miễn phí để nhận ngay thời gian dùng thử và trải nghiệm sức mạnh của hệ sinh thái tự động hóa hàng đầu Việt Nam.
                </p>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <a href="/register" class="btn-vmax-glow px-4 py-3" style="font-size: 16px;">
                    <span>Tạo Tài Khoản Dùng Thử</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
                <div class="text-slate-400 small mt-2">Không cần thẻ tín dụng • Kích hoạt tức thì</div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Modal for Screenshots -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body text-end p-0">
                <button type="button" class="btn-close btn-close-white mb-2" data-bs-dismiss="modal" aria-label="Close"></button>
                <img id="lightboxImg" src="" alt="Screenshot" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</div>

<!-- Script for Interactive Pricing Switcher -->
<script>
// Script for Hero Showcase Tab Switcher & 3D Tilt
function switchHeroShowcase(tabId, btn) {
    document.querySelectorAll('.device-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.device-tab-content').forEach(c => c.classList.remove('active'));
    
    if (btn) btn.classList.add('active');
    const target = document.getElementById(tabId);
    if (target) target.classList.add('active');
}

// Auto-rotate tabs every 6 seconds unless hovered
(function() {
    const tabs = ['tab-vmaxlogin', 'tab-vautopost', 'tab-shopee'];
    let currentIdx = 0;
    let isHovered = false;
    const stageEl = document.getElementById('heroShowcaseStage');
    
    if (stageEl) {
        stageEl.addEventListener('mouseenter', () => { isHovered = true; });
        stageEl.addEventListener('mouseleave', () => { isHovered = false; });
        
        setInterval(() => {
            if (!isHovered) {
                currentIdx = (currentIdx + 1) % tabs.length;
                const tabId = tabs[currentIdx];
                const btn = document.querySelector(`.device-tab-btn[data-tab="${tabId}"]`);
                switchHeroShowcase(tabId, btn);
            }
        }, 6000);

        // 3D Parallax Mouse Tilt on Device
        const card = stageEl.querySelector('.hero-glass-device');
        stageEl.addEventListener('mousemove', (e) => {
            const rect = stageEl.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            const rotateX = -(y / rect.height) * 8;
            const rotateY = (x / rect.width) * 8;
            if (card) {
                card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg)`;
            }
        });
        stageEl.addEventListener('mouseleave', () => {
            if (card) {
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
            }
        });
    }
})();

function switchBillingCycle(cycle, btn) {
    // Update active tab button style
    document.querySelectorAll('.pricing-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Period text mapping
    const periodMap = {
        'monthly': '/ tháng',
        'quarterly': '/ 3 tháng',
        'yearly': '/ năm',
        'lifetime': '/ trọn đời'
    };

    // Update prices on all plans
    document.querySelectorAll('.plan-price').forEach(el => {
        const val = el.getAttribute('data-' + cycle);
        if (val) {
            el.textContent = val;
        }
    });

    // Update periods
    document.querySelectorAll('.plan-period').forEach(el => {
        el.textContent = periodMap[cycle] || '/ tháng';
    });
}
</script>

<?php include BASE_PATH . '/views/layout/footer.php'; ?>
