<?php
// VMAX Software - Ultra-Modern SaaS Navigation Bar 2026
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$queryParams = $_GET;

$queryParams['lang'] = 'vi';
$viUrl = $currentPath . '?' . http_build_query($queryParams);

$queryParams['lang'] = 'en';
$enUrl = $currentPath . '?' . http_build_query($queryParams);

$allMenuProds = [];
try {
    $allMenuProds = (new ProductModel())->getAllActiveProducts();
} catch (Exception $e) {
    // Fallback if model not defined or database error
}

$menuCombos = array_values(array_filter($allMenuProds, function($p) { 
    return isset($p['is_combo']) && $p['is_combo'] == 1 && $p['show_in_menu'] == 1; 
}));
$menuSingles = array_values(array_filter($allMenuProds, function($p) { 
    return (!isset($p['is_combo']) || $p['is_combo'] == 0) && $p['show_in_menu'] == 1; 
}));

if (empty($menuCombos)) {
    $menuCombos = [
        ['name' => 'Combo Marketing Facebook', 'short_description' => 'Bộ 3 công cụ nuôi nick, seeding, spam đa luồng', 'image_url' => 'image/sanphamfb.webp', 'slug' => 'combo-marketing-facebook', 'price_monthly' => 500000],
        ['name' => 'Combo Phú Quý', 'short_description' => 'Bộ 4 phần mềm đa kênh Facebook + Shopee đỉnh cao', 'image_url' => 'image/sanphamsp.webp', 'slug' => 'combo-phu-quy', 'price_monthly' => 600000]
    ];
}
if (empty($menuSingles)) {
    $menuSingles = [
        ['name' => 'VmaxLogin', 'short_description' => 'Trình duyệt chống quét vân tay antidetect bảo mật cao', 'slug' => 'lushlogin'],
        ['name' => 'V-AutoPost', 'short_description' => 'Phần mềm tự động hóa nuôi nick Facebook đa luồng', 'slug' => 'v-autopost'],
        ['name' => 'V-ShopeeBoost', 'short_description' => 'Tự động đẩy top sản phẩm, tăng tương tác shop', 'slug' => 'v-shopeeboost'],
        ['name' => 'V-ShopeeBoost Pro', 'short_description' => 'Quản trị đa gian hàng, phân tích đối thủ cạnh tranh', 'slug' => 'v-shopeeboost-pro']
    ];
}
?>

<!-- Self-contained Ultra-Modern Navigation CSS -->
<style>
/* Modern Glassmorphic Nav Wrapper */
.vmax-nav-wrapper {
    position: sticky;
    top: 14px;
    z-index: 9999;
    width: 100%;
    max-width: 1260px;
    margin: 0 auto;
    padding: 0 16px;
    box-sizing: border-box;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.vmax-nav-container {
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.95);
    border-radius: 9999px; /* Modern Pill Shape */
    padding: 8px 12px 8px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.08), 
                0 0 0 1px rgba(226, 232, 240, 0.75);
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.vmax-nav-wrapper.scrolled .vmax-nav-container {
    background: rgba(255, 255, 255, 0.96);
    box-shadow: 0 15px 35px -8px rgba(15, 23, 42, 0.12), 
                0 0 0 1px rgba(37, 99, 235, 0.18);
    padding: 6px 10px 6px 18px;
}

/* Brand Logo */
.vmax-nav-logo {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.vmax-nav-logo:hover {
    opacity: 0.92;
    transform: scale(0.99);
}
.vmax-nav-logo img {
    height: 36px;
    width: auto;
    object-fit: contain;
    display: block;
}
.vmax-logo-badge {
    font-size: 10px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 9999px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.12), rgba(124, 58, 237, 0.12));
    color: #2563eb;
    border: 1px solid rgba(37, 99, 235, 0.25);
    letter-spacing: 0.04em;
    display: inline-block;
}

/* Nav Menu Items */
.vmax-nav-menu {
    display: flex;
    align-items: center;
    gap: 4px;
    margin: 0;
    padding: 0;
    list-style: none;
}
.vmax-nav-item {
    position: relative;
}
.vmax-nav-item > a {
    color: #475569;
    font-weight: 600;
    font-size: 13.5px;
    text-decoration: none;
    padding: 8px 14px;
    border-radius: 9999px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.vmax-nav-item > a:hover {
    color: #0f172a;
    background: rgba(15, 23, 42, 0.05);
}
.vmax-nav-item.active > a {
    color: #2563eb;
    background: rgba(37, 99, 235, 0.08);
    font-weight: 700;
}
.vmax-nav-item.active > a:hover {
    background: rgba(37, 99, 235, 0.12);
}

.nav-chevron {
    font-size: 9px;
    transition: transform 0.25s ease;
    opacity: 0.7;
}
.vmax-dropdown-mega:hover .nav-chevron {
    transform: rotate(180deg);
    color: #2563eb;
}

/* Mega Menu Dropdown (Clean, Solid 100% White, No Bleed-Through) */
.vmax-dropdown-mega {
    position: relative;
}
.vmax-mega-menu {
    position: absolute;
    top: calc(100% + 14px);
    left: -85px;
    width: 740px;
    background: #ffffff !important;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    box-shadow: 0 25px 60px -12px rgba(15, 23, 42, 0.22), 
                0 0 0 1px rgba(15, 23, 42, 0.05);
    padding: 0;
    opacity: 0;
    visibility: hidden;
    transform: translateY(8px);
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    z-index: 10000;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    pointer-events: none;
}
/* Invisible hover bridge to avoid flicker when moving mouse */
.vmax-mega-menu::before {
    content: '';
    position: absolute;
    top: -16px;
    left: 0;
    right: 0;
    height: 16px;
}
.vmax-dropdown-mega:hover .vmax-mega-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
}

/* Mega Menu Main Content (2 Columns) */
.vmax-mega-body {
    display: flex;
    background: #ffffff;
}

/* Left Column: Singles (420px) */
.vmax-mega-singles {
    flex: 1.25;
    padding: 20px 18px 18px 22px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.vmax-mega-section-title {
    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    padding: 0 4px;
}
.vmax-mega-pill-tag {
    font-size: 10px;
    font-weight: 700;
    color: #2563eb;
    background: #eff6ff;
    padding: 2px 8px;
    border-radius: 9999px;
}

.vmax-single-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid transparent;
    text-decoration: none;
    background: #ffffff;
    transition: all 0.18s ease;
}
.vmax-single-row:hover {
    background: #f8fafc;
    border-color: #e2e8f0;
    transform: translateX(3px);
}
.vmax-single-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
    transition: transform 0.2s ease;
}
.vmax-single-row:hover .vmax-single-icon-box {
    transform: scale(1.08);
}
.vmax-single-meta {
    flex: 1;
    min-width: 0;
}
.vmax-single-meta h5 {
    margin: 0;
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 6px;
}
.vmax-single-meta p {
    margin: 3px 0 0 0;
    font-size: 11px;
    color: #475569;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.35;
}
.vmax-badge-hot {
    background: #ef4444;
    color: #ffffff;
    padding: 1px 6px;
    border-radius: 9999px;
    font-size: 8.5px;
    font-weight: 800;
    letter-spacing: 0.02em;
}
.vmax-badge-pro {
    background: linear-gradient(135deg, #8b5cf6, #6d28d9);
    color: #ffffff;
    padding: 1px 6px;
    border-radius: 9999px;
    font-size: 8.5px;
    font-weight: 800;
}
.vmax-single-arrow {
    font-size: 11px;
    color: #94a3b8;
    opacity: 0;
    transform: translateX(-4px);
    transition: all 0.18s ease;
}
.vmax-single-row:hover .vmax-single-arrow {
    opacity: 1;
    transform: translateX(0);
    color: #2563eb;
}

/* Right Column: Combos (310px) */
.vmax-mega-combos {
    width: 300px;
    background: #f8fafc;
    border-left: 1px solid #f1f5f9;
    padding: 20px 18px 18px 18px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.vmax-combo-mini-card {
    border-radius: 14px;
    padding: 12px 14px;
    text-decoration: none;
    border: 1px solid #dbeafe;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
}
.vmax-combo-mini-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.12);
    border-color: #93c5fd;
    background: #eff6ff;
}
.vmax-combo-mini-card.alt-green {
    border-color: #d1fae5;
}
.vmax-combo-mini-card.alt-green:hover {
    border-color: #6ee7b7;
    background: #f0fdf4;
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.12);
}
.vmax-combo-mini-info {
    flex: 1;
    min-width: 0;
}
.vmax-combo-mini-tag {
    font-size: 9px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 9999px;
    display: inline-block;
    margin-bottom: 4px;
    letter-spacing: 0.03em;
}
.tag-blue {
    background: #dbeafe;
    color: #1e40af;
}
.tag-green {
    background: #d1fae5;
    color: #065f46;
}
.vmax-combo-mini-info h4 {
    margin: 0;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}
.vmax-combo-mini-info p {
    margin: 3px 0 6px 0;
    font-size: 10.5px;
    color: #475569;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.vmax-combo-mini-price {
    font-size: 11px;
    font-weight: 800;
    color: #2563eb;
}
.vmax-combo-mini-card.alt-green .vmax-combo-mini-price {
    color: #059669;
}
.vmax-combo-mini-img {
    height: 58px;
    width: auto;
    object-fit: contain;
    flex-shrink: 0;
    transition: transform 0.25s ease;
}
.vmax-combo-mini-card:hover .vmax-combo-mini-img {
    transform: scale(1.08) rotate(2deg);
}

/* Full Width Bottom Footer Bar */
.vmax-mega-footer-bar {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 12px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 11.5px;
    color: #475569;
}
.vmax-mega-footer-left {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}
.vmax-mega-footer-right {
    display: flex;
    align-items: center;
    gap: 16px;
}
.vmax-mega-footer-right a {
    color: #475569;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.15s ease;
}
.vmax-mega-footer-right a:hover {
    color: #2563eb;
}
.vmax-mega-footer-right a.vmax-highlight-link {
    color: #2563eb;
    font-weight: 700;
}
.vmax-mega-footer-right a.vmax-highlight-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* Right Actions Group */
.vmax-nav-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Modern Language Segment Switcher */
.vmax-lang-pill {
    display: flex;
    align-items: center;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    padding: 3px;
    gap: 2px;
}
.vmax-lang-opt {
    padding: 4px 9px;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-decoration: none;
    border-radius: 9999px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 4px;
}
.vmax-lang-opt.active {
    background: #ffffff;
    color: #0f172a;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
}
.vmax-lang-opt:hover:not(.active) {
    color: #1e293b;
}

/* Auth Buttons */
.vmax-btn-ghost {
    color: #334155 !important;
    font-weight: 600;
    font-size: 13.5px;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 9999px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
    display: inline-block;
}
.vmax-btn-ghost:hover {
    background: rgba(15, 23, 42, 0.04);
    color: #0f172a !important;
}

.vmax-btn-cta {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff !important;
    font-weight: 700;
    font-size: 13.5px;
    text-decoration: none;
    padding: 9px 20px;
    border-radius: 9999px;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.vmax-btn-cta::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(60deg, transparent, rgba(255, 255, 255, 0.25), transparent);
    transform: rotate(30deg);
    transition: transform 0.6s ease;
}
.vmax-btn-cta:hover {
    transform: translateY(-1.5px);
    box-shadow: 0 8px 22px rgba(37, 99, 235, 0.45);
    color: #ffffff !important;
}
.vmax-btn-cta:hover::after {
    transform: rotate(30deg) translate(50%, 50%);
}

/* Mobile Hamburger Toggle */
.vmax-menu-toggle {
    display: none;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    font-size: 16px;
    color: #1e293b;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.vmax-menu-toggle:hover {
    background: #f1f5f9;
}

/* Mobile Drawer */
.vmax-mobile-drawer {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.5);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    z-index: 10000;
    font-family: 'Inter', sans-serif;
    animation: drawerFadeIn 0.25s ease forwards;
}
@keyframes drawerFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.vmax-drawer-panel {
    position: absolute;
    top: 0;
    right: 0;
    width: 300px;
    height: 100%;
    background: #ffffff;
    padding: 26px;
    box-shadow: -15px 0 40px rgba(15, 23, 42, 0.15);
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    animation: drawerSlide 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes drawerSlide {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}

/* Responsive Breakpoints */
@media (max-width: 991.98px) {
    .vmax-nav-menu { display: none; }
    .vmax-nav-actions { display: none; }
    .vmax-menu-toggle { display: inline-flex; }
    .vmax-nav-wrapper { top: 10px; }
    .vmax-nav-container { padding: 8px 14px 8px 18px; }
}
</style>

<div class="vmax-nav-wrapper" id="vmaxNavWrapper">
    <nav class="vmax-nav-container">
        <!-- Brand Logo -->
        <a href="/" class="vmax-nav-logo">
            <img src="/image/logo-app-vmax-64x.webp" alt="VMAX Logo">
            <span class="vmax-logo-badge">PRO</span>
        </a>
        
        <!-- Desktop Nav Menu -->
        <ul class="vmax-nav-menu">
            <li class="vmax-nav-item <?= $currentPath === '/' ? 'active' : '' ?>">
                <a href="/"><?= __('nav_home') ?></a>
            </li>

            <!-- Products Dropdown -->
            <li class="vmax-nav-item vmax-dropdown-mega <?= $currentPath === '/products' ? 'active' : '' ?>">
                <a href="/products">
                    <span><?= __('nav_products') ?></span>
                    <i class="fa-solid fa-chevron-down nav-chevron"></i>
                </a>
                
                <!-- Mega Dropdown Panel (High-Contrast Solid White 2026 SaaS) -->
                <div class="vmax-mega-menu">
                    <div class="vmax-mega-body">
                        <!-- Left Column: Software Products -->
                        <div class="vmax-mega-singles">
                            <div class="vmax-mega-section-title">
                                <span><?= __('nav_single_title') ?></span>
                                <span class="vmax-mega-pill-tag">Antidetect & Auto</span>
                            </div>
                            
                            <?php foreach ($menuSingles as $sObj): 
                                $nameLower = strtolower($sObj['name']);
                                $iconClass = 'fa-solid fa-cube';
                                $iconColor = '#64748b';
                                $iconBg = '#f1f5f9';
                                $badgeHtml = '';
                                
                                if (strpos($nameLower, 'login') !== false || strpos($nameLower, 'lush') !== false) {
                                    $iconClass = 'fa-brands fa-chrome';
                                    $iconColor = '#2563eb';
                                    $iconBg = '#eff6ff';
                                    $badgeHtml = '<span class="vmax-badge-hot">HOT</span>';
                                } else if (strpos($nameLower, 'autopost') !== false || strpos($nameLower, 'post') !== false) {
                                    $iconClass = 'fa-solid fa-share-nodes';
                                    $iconColor = '#10b981';
                                    $iconBg = '#ecfdf5';
                                    $badgeHtml = '<span class="vmax-badge-hot">BÁN CHẠY</span>';
                                } else if (strpos($nameLower, 'pro') !== false) {
                                    $iconClass = 'fa-solid fa-gem';
                                    $iconColor = '#8b5cf6';
                                    $iconBg = '#f5f3ff';
                                    $badgeHtml = '<span class="vmax-badge-pro">PRO</span>';
                                } else if (strpos($nameLower, 'shopee') !== false) {
                                    $iconClass = 'fa-solid fa-cart-shopping';
                                    $iconColor = '#f97316';
                                    $iconBg = '#fff7ed';
                                }
                            ?>
                                <a href="/products" class="vmax-single-row">
                                    <div class="vmax-single-icon-box" style="background: <?= $iconBg ?>; color: <?= $iconColor ?>;">
                                        <i class="<?= $iconClass ?>"></i>
                                    </div>
                                    <div class="vmax-single-meta">
                                        <h5>
                                            <?= htmlspecialchars($sObj['name']) ?>
                                            <?= $badgeHtml ?>
                                        </h5>
                                        <p><?= htmlspecialchars($sObj['short_description'] ?? '') ?></p>
                                    </div>
                                    <i class="fa-solid fa-arrow-right vmax-single-arrow"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <!-- Right Column: Combos -->
                        <div class="vmax-mega-combos">
                            <div class="vmax-mega-section-title">
                                <span><?= __('nav_combo_title') ?></span>
                                <span class="vmax-mega-pill-tag" style="color: #dc2626; background: #fee2e2;">TIẾT KIỆM 45%</span>
                            </div>
                            
                            <?php 
                            $comboIdx = 0;
                            foreach ($menuCombos as $cObj): 
                                $comboIdx++;
                                $isGreen = ($comboIdx % 2 === 0);
                                $tagClass = $isGreen ? 'tag-green' : 'tag-blue';
                                $tagText = $isGreen ? 'Bộ Toàn Diện' : 'Tiết Kiệm 45%';
                                $cardClass = $isGreen ? 'vmax-combo-mini-card alt-green' : 'vmax-combo-mini-card';
                            ?>
                                <a href="/products" class="<?= $cardClass ?>">
                                    <div class="vmax-combo-mini-info">
                                        <span class="vmax-combo-mini-tag <?= $tagClass ?>"><?= $tagText ?></span>
                                        <h4><?= htmlspecialchars($cObj['name']) ?></h4>
                                        <p><?= htmlspecialchars($cObj['short_description'] ?? '') ?></p>
                                        <div class="vmax-combo-mini-price">
                                            Chỉ từ <?= number_format($cObj['price_monthly'] ?? 500000, 0, ',', '.') ?>đ/th
                                        </div>
                                    </div>
                                    <?php if (!empty($cObj['image_url'])): 
                                        $imgWebP = str_replace('.png', '.webp', $cObj['image_url']);
                                    ?>
                                        <img src="/<?= htmlspecialchars($imgWebP) ?>" alt="<?= htmlspecialchars($cObj['name']) ?>" class="vmax-combo-mini-img">
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Bottom Bar (Spans full width) -->
                    <div class="vmax-mega-footer-bar">
                        <div class="vmax-mega-footer-left">
                            <i class="fa-solid fa-circle-check text-success"></i> 
                            <span>Kích hoạt tự động VietQR trong 10 giây • Hỗ trợ kỹ thuật 24/7</span>
                        </div>
                        <div class="vmax-mega-footer-right">
                            <a href="/pricing">Bảng giá dịch vụ</a>
                            <a href="/products" class="vmax-highlight-link">Xem tất cả phần mềm →</a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="vmax-nav-item <?= $currentPath === '/pricing' ? 'active' : '' ?>">
                <a href="/pricing"><?= __('nav_pricing') ?></a>
            </li>
            <li class="vmax-nav-item <?= $currentPath === '/downloads' ? 'active' : '' ?>">
                <a href="/downloads"><?= __('nav_downloads') ?></a>
            </li>
            <li class="vmax-nav-item <?= $currentPath === '/blog' ? 'active' : '' ?>">
                <a href="/blog"><?= __('nav_blog') ?></a>
            </li>
            <li class="vmax-nav-item <?= $currentPath === '/contact' ? 'active' : '' ?>">
                <a href="/contact"><?= __('nav_contact') ?></a>
            </li>
        </ul>
        
        <!-- Right Actions & Language -->
        <div class="vmax-nav-actions">
            <!-- Segmented Lang Switcher -->
            <div class="vmax-lang-pill">
                <a href="<?= htmlspecialchars($viUrl) ?>" class="vmax-lang-opt <?= APP_LANG === 'vi' ? 'active' : '' ?>">
                    <span>VI</span>
                </a>
                <a href="<?= htmlspecialchars($enUrl) ?>" class="vmax-lang-opt <?= APP_LANG === 'en' ? 'active' : '' ?>">
                    <span>EN</span>
                </a>
            </div>
            
            <!-- Auth Buttons -->
            <?php if (Auth::check()): ?>
                <a href="/dashboard" class="vmax-btn-cta">
                    <i class="fa-solid fa-gauge-high text-xs"></i>
                    <span><?= __('nav_dashboard') ?></span>
                </a>
            <?php else: ?>
                <a href="/login" class="vmax-btn-ghost"><?= __('nav_login') ?></a>
                <a href="/register" class="vmax-btn-cta">
                    <span><?= __('nav_free_trial') ?></span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Hamburger Menu Trigger (Mobile) -->
        <button class="vmax-menu-toggle" onclick="toggleMobileNav()" aria-label="Toggle menu">
            <i class="fa-solid fa-bars"></i>
        </button>
    </nav>
</div>

<!-- Mobile Drawer -->
<div id="vmax-mobile-menu" class="vmax-mobile-drawer">
    <div class="vmax-drawer-panel">
        <div>
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <img src="/image/logo-app-vmax-64x.webp" alt="VMAX Logo" style="height: 32px;">
                <button onclick="toggleMobileNav()" style="background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer;">&times;</button>
            </div>
            
            <!-- Menu Links -->
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 14px;">
                <li><a href="/" class="vmax-btn-ghost w-100 text-start"><?= __('nav_home') ?></a></li>
                <li><a href="/products" class="vmax-btn-ghost w-100 text-start"><?= __('nav_products') ?></a></li>
                <li><a href="/pricing" class="vmax-btn-ghost w-100 text-start"><?= __('nav_pricing') ?></a></li>
                <li><a href="/downloads" class="vmax-btn-ghost w-100 text-start"><?= __('nav_downloads') ?></a></li>
                <li><a href="/blog" class="vmax-btn-ghost w-100 text-start"><?= __('nav_blog') ?></a></li>
                <li><a href="/contact" class="vmax-btn-ghost w-100 text-start"><?= __('nav_contact') ?></a></li>
            </ul>
        </div>
        
        <!-- Bottom Actions -->
        <div class="d-flex flex-column gap-3 pt-3 border-top">
            <div class="vmax-lang-pill align-self-start">
                <a href="<?= htmlspecialchars($viUrl) ?>" class="vmax-lang-opt <?= APP_LANG === 'vi' ? 'active' : '' ?>">VI</a>
                <a href="<?= htmlspecialchars($enUrl) ?>" class="vmax-lang-opt <?= APP_LANG === 'en' ? 'active' : '' ?>">EN</a>
            </div>
            
            <?php if (Auth::check()): ?>
                <a href="/dashboard" class="vmax-btn-cta text-center w-100 justify-content-center"><?= __('nav_dashboard') ?></a>
            <?php else: ?>
                <a href="/login" class="vmax-btn-ghost text-center w-100 border"><?= __('nav_login') ?></a>
                <a href="/register" class="vmax-btn-cta text-center w-100 justify-content-center"><?= __('nav_free_trial') ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Dynamic Scroll Effect on Navbar
    window.addEventListener('scroll', function() {
        const navWrapper = document.getElementById('vmaxNavWrapper');
        if (navWrapper) {
            if (window.scrollY > 20) {
                navWrapper.classList.add('scrolled');
            } else {
                navWrapper.classList.remove('scrolled');
            }
        }
    });

    // Mobile Drawer Toggle
    function toggleMobileNav() {
        const mobileMenu = document.getElementById('vmax-mobile-menu');
        if (mobileMenu) {
            if (mobileMenu.style.display === 'block') {
                mobileMenu.style.display = 'none';
                document.body.style.overflow = '';
            } else {
                mobileMenu.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }
    }
    
    // Close mobile drawer when clicking outside
    const mobileMenuEl = document.getElementById('vmax-mobile-menu');
    if (mobileMenuEl) {
        mobileMenuEl.addEventListener('click', function(e) {
            if (e.target === this) {
                toggleMobileNav();
            }
        });
    }
</script>
