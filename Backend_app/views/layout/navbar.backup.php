<?php
// VMAX Software - Unified Navigation Bar (Self-contained styling)

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

$menuCombos = array_filter($allMenuProds, function($p) { return $p['is_combo'] == 1 && $p['show_in_menu'] == 1; });
$menuSingles = array_filter($allMenuProds, function($p) { return $p['is_combo'] == 0 && $p['show_in_menu'] == 1; });

if (empty($menuCombos)) {
    $menuCombos = [
        ['name' => 'Combo Marketing Facebook', 'short_description' => 'Bộ 3 phần mềm nuôi nick, seeding, spam tin nhắn Facebook', 'image_url' => 'image/sanphamfb.webp', 'slug' => 'combo-marketing-facebook', 'price_monthly' => 500000],
        ['name' => 'Combo Phú Quý', 'short_description' => 'Bộ 4 công cụ marketing đa kênh tự động hàng đầu', 'image_url' => 'image/sanphamsp.webp', 'slug' => 'combo-phu-quy', 'price_monthly' => 600000]
    ];
}
if (empty($menuSingles)) {
    $menuSingles = [
        ['name' => 'VmaxLogin', 'short_description' => 'Trình duyệt chống dò quét vân tay antidetect bảo mật cao', 'logo_url' => '', 'slug' => 'lushlogin'],
        ['name' => 'V-AutoPost', 'short_description' => 'Phần mềm tự động hóa marketing, đăng bài đa luồng', 'logo_url' => '', 'slug' => 'v-autopost'],
        ['name' => 'V-ShopeeBoost', 'short_description' => 'Tự động đẩy sản phẩm, tăng tương tác shop Shopee', 'logo_url' => '', 'slug' => 'v-shopeeboost'],
        ['name' => 'V-ShopeeBoost Pro', 'short_description' => 'Quản trị đa gian hàng, phân tích đối thủ Shopee', 'logo_url' => '', 'slug' => 'v-shopeeboost-pro']
    ];
}
?>

<!-- Self-contained Navigation CSS -->
<style>
    .vmax-nav-wrapper {
        position: sticky;
        top: 16px;
        z-index: 9999;
        width: 100%;
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 16px;
        box-sizing: border-box;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    .vmax-nav-container {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 20px;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        transition: all 0.3s ease;
    }
    .vmax-nav-container:hover {
        box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.08);
        border-color: rgba(226, 232, 240, 1);
    }
    .vmax-nav-logo img {
        height: 38px;
        width: auto;
        object-fit: contain;
        display: block;
    }
    .vmax-nav-menu {
        display: flex;
        align-items: center;
        gap: 28px;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .vmax-nav-item > a {
        color: #475569;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: color 0.2s ease;
        padding: 8px 0;
        display: flex;
        align-items: center;
        gap: 6px;
        position: relative;
    }
    .vmax-nav-item > a:hover {
        color: #2563eb;
    }
    .vmax-nav-item > a::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: #2563eb;
        transition: width 0.2s ease;
    }
    .vmax-nav-item:hover > a::after,
    .vmax-nav-item.active > a::after {
        width: 100%;
    }
    .vmax-nav-item.active > a {
        color: #2563eb;
    }
    
    /* Mega Menu Dropdown */
    .vmax-dropdown-mega {
        position: relative;
    }
    .vmax-mega-menu {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(10px);
        width: 900px;
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.1);
        padding: 24px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 10000;
        display: flex;
        gap: 24px;
    }
    .vmax-dropdown-mega:hover .vmax-mega-menu {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(4px);
    }
    
    .vmax-mega-combos {
        width: 380px;
        border-right: 1px solid #f1f5f9;
        padding-right: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .vmax-mega-section-title {
        font-size: 11px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .vmax-mega-badge-deal {
        background: #fff1f2;
        color: #f43f5e;
        padding: 2px 8px;
        border-radius: 100px;
        font-size: 9px;
        font-weight: 700;
    }
    .vmax-combo-card {
        border-radius: 16px;
        padding: 14px 16px;
        color: #ffffff;
        text-decoration: none;
        height: 115px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
        position: relative;
        overflow: hidden;
    }
    .vmax-combo-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15);
    }
    .vmax-combo-info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        flex: 1;
        max-width: 60%;
        z-index: 2;
    }
    .vmax-combo-card h4 {
        margin: 0;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.3px;
    }
    .vmax-combo-card p {
        margin: 4px 0 0 0;
        font-size: 10px;
        opacity: 0.9;
        line-height: 1.4;
    }
    .vmax-combo-card .vmax-combo-save {
        background: rgba(255, 255, 255, 0.25);
        padding: 2px 8px;
        border-radius: 100px;
        font-size: 9px;
        font-weight: 700;
        align-self: flex-start;
    }
    .vmax-combo-img {
        height: 85px;
        width: auto;
        object-fit: contain;
        opacity: 0.95;
        transition: transform 0.3s ease;
        z-index: 1;
        margin-left: 10px;
    }
    .vmax-combo-card:hover .vmax-combo-img {
        transform: scale(1.06);
    }
    
    .vmax-mega-singles {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .vmax-singles-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    .vmax-single-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 16px;
        border: 1px solid transparent;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .vmax-single-item:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }
    .vmax-single-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(79, 70, 229, 0.1));
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 14px;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .vmax-single-item:hover .vmax-single-icon {
        background: #2563eb;
        color: #ffffff;
    }
    .vmax-single-item:hover .vmax-single-icon i {
        color: #ffffff !important;
    }
    .vmax-single-info {
        min-width: 0;
    }
    .vmax-single-info h5 {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .vmax-single-info p {
        margin: 2px 0 0 0;
        font-size: 10px;
        color: #94a3b8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .vmax-badge-hot {
        background: #2563eb;
        color: #ffffff;
        padding: 1px 4px;
        border-radius: 4px;
        font-size: 7px;
        font-weight: 700;
    }
    
    .vmax-mega-footer {
        grid-column: span 2;
        border-top: 1px solid #f1f5f9;
        margin-top: 16px;
        padding-top: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 11px;
        color: #64748b;
        width: 100%;
    }
    .vmax-mega-footer a {
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        margin-left: 16px;
        transition: color 0.15s ease;
    }
    .vmax-mega-footer a:hover {
        color: #2563eb;
    }
    
    /* Action & Lang Group */
    .vmax-nav-actions {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    /* Lang Switcher Styles */
    .vmax-lang-switcher {
        display: flex;
        align-items: center;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 3px;
        background: #f8fafc;
    }
    .vmax-lang-btn {
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .vmax-lang-btn.active {
        background: #ffffff;
        color: #1e293b;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }
    
    /* Buttons */
    .vmax-btn-login {
        color: #475569;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        padding: 8px 16px;
        transition: color 0.2s ease;
    }
    .vmax-btn-login:hover {
        color: #2563eb;
    }
    .vmax-btn-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        padding: 10px 22px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.2);
        transition: all 0.3s ease;
        display: inline-block;
    }
    .vmax-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        color: #ffffff;
    }
    
    /* Responsive Mobile Menu Trigger */
    .vmax-menu-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 20px;
        color: #1e293b;
        cursor: pointer;
    }
    
    /* Responsive Media Queries */
    @media (max-width: 991.98px) {
        .vmax-nav-menu {
            display: none;
        }
        .vmax-nav-actions {
            display: none;
        }
        .vmax-menu-toggle {
            display: block;
        }
        .vmax-nav-wrapper {
            top: 8px;
        }
        .vmax-nav-container {
            padding: 8px 16px;
            border-radius: 14px;
        }
    }
</style>

<div class="vmax-nav-wrapper">
    <nav class="vmax-nav-container">
        <!-- Logo -->
        <a href="/" class="vmax-nav-logo">
            <img src="/image/logo-app-vmax45.webp" alt="VMAX Logo">
        </a>
        
        <!-- Desktop Nav Menu -->
        <ul class="vmax-nav-menu">
            <li class="vmax-nav-item <?= $currentPath === '/' ? 'active' : '' ?>">
                <a href="/"><?= __('nav_home') ?></a>
            </li>
            <li class="vmax-nav-item vmax-dropdown-mega <?= $currentPath === '/products' ? 'active' : '' ?>">
                <a href="/products">
                    <?= __('nav_products') ?>
                    <i class="fa-solid fa-chevron-down" style="font-size: 9px; transition: transform 0.2s;"></i>
                </a>
                
                <!-- Mega Dropdown Panel -->
                <div class="vmax-mega-menu">
                    <!-- Combos -->
                    <div class="vmax-mega-combos">
                        <div class="vmax-mega-section-title">
                            <span><?= __('nav_combo_title') ?></span>
                            <span class="vmax-mega-badge-deal"><?= __('nav_super_deal') ?></span>
                        </div>
                        <?php 
                        $comboIdx = 0;
                        foreach ($menuCombos as $cObj): 
                            $comboIdx++;
                            $bgGrad = $comboIdx % 2 === 1 
                                ? 'linear-gradient(135deg, #2563eb, #4f46e5)' 
                                : 'linear-gradient(135deg, #10b981, #0d9488)';
                        ?>
                            <a href="/products" class="vmax-combo-card" style="background: <?= $bgGrad ?>; justify-content: center; align-items: center; padding: 10px;">
                                <?php if (!empty($cObj['image_url'])): 
                                    $imgWebP = str_replace('.png', '.webp', $cObj['image_url']);
                                ?>
                                    <img src="/<?= htmlspecialchars($imgWebP) ?>" alt="<?= htmlspecialchars($cObj['name']) ?>" class="vmax-combo-img" style="height: 95px; width: 100%; object-fit: contain; margin-left: 0; opacity: 1;">
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Singles -->
                    <div class="vmax-mega-singles">
                        <div class="vmax-mega-section-title">
                            <span><?= __('nav_single_title') ?></span>
                            <span style="color: #2563eb; font-weight: 700;"><?= __('nav_new_update') ?></span>
                        </div>
                        <div class="vmax-singles-grid">
                            <?php foreach ($menuSingles as $sObj): 
                                $isHot = in_array(strtolower($sObj['name']), ['vmaxlogin', 'lushlogin', 'v-autopost']);
                                $iconClass = 'fa-cube text-slate-500';
                                $nameLower = strtolower($sObj['name']);
                                if (strpos($nameLower, 'login') !== false || strpos($nameLower, 'lush') !== false) {
                                    $iconClass = 'fa-fingerprint text-blue-600';
                                } else if (strpos($nameLower, 'autopost') !== false || strpos($nameLower, 'post') !== false) {
                                    $iconClass = 'fa-paper-plane text-indigo-600';
                                } else if (strpos($nameLower, 'shopee') !== false) {
                                    $iconClass = 'fa-rocket text-orange-500';
                                } else if (strpos($nameLower, 'care') !== false) {
                                    $iconClass = 'fa-heart-pulse text-rose-500';
                                }
                            ?>
                                <a href="/products" class="vmax-single-item">
                                    <div class="vmax-single-icon" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(79, 70, 229, 0.05)); border: 1px solid rgba(226, 232, 240, 0.5);">
                                        <i class="fa-solid <?= $iconClass ?> text-base"></i>
                                    </div>
                                    <div class="vmax-single-info">
                                        <h5>
                                            <?= htmlspecialchars($sObj['name']) ?>
                                            <?php if ($isHot): ?>
                                                <span class="vmax-badge-hot"><?= __('nav_hot') ?></span>
                                            <?php endif; ?>
                                        </h5>
                                        <p><?= htmlspecialchars($sObj['short_description'] ?? '') ?></p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Footer Band inside Mega Menu -->
                        <div class="vmax-mega-footer">
                            <span>
                                <i class="fa-solid fa-shield-halved text-success" style="margin-right: 4px;"></i> 
                                <?= __('nav_security_text') ?>
                            </span>
                            <div>
                                <a href="/pricing"><?= __('nav_pricing_detail') ?> →</a>
                                <a href="/products" style="font-weight: 700;"><?= __('nav_all_products') ?> →</a>
                            </div>
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
        
        <!-- Actions & Language Switcher -->
        <div class="vmax-nav-actions">
            <!-- Language Switcher -->
            <div class="vmax-lang-switcher">
                <a href="<?= htmlspecialchars($viUrl) ?>" class="vmax-lang-btn <?= APP_LANG === 'vi' ? 'active' : '' ?>">VI</a>
                <a href="<?= htmlspecialchars($enUrl) ?>" class="vmax-lang-btn <?= APP_LANG === 'en' ? 'active' : '' ?>">EN</a>
            </div>
            
            <!-- Auth Links -->
            <?php if (Auth::check()): ?>
                <a href="/dashboard" class="vmax-btn-primary"><?= __('nav_dashboard') ?></a>
            <?php else: ?>
                <a href="/login" class="vmax-btn-login"><?= __('nav_login') ?></a>
                <a href="/register" class="vmax-btn-primary"><?= __('nav_free_trial') ?></a>
            <?php endif; ?>
        </div>
        
        <!-- Hamburger Menu Trigger (Mobile) -->
        <button class="vmax-menu-toggle" onclick="toggleMobileNav()" aria-label="Toggle menu">
            <i class="fa-solid fa-bars"></i>
        </button>
    </nav>
</div>

<!-- Simple Mobile Menu Panel -->
<div id="vmax-mobile-menu" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 9998; font-family: 'Inter', sans-serif;">
    <div style="position: absolute; top: 0; right: 0; width: 280px; height: 100%; background: #ffffff; padding: 24px; box-shadow: -10px 0 30px rgba(0,0,0,0.1); box-sizing: border-box; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <!-- Close Button -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                <img src="/image/logo-app-vmax45.webp" alt="VMAX Logo" style="height: 32px;">
                <button onclick="toggleMobileNav()" style="background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer;">&times;</button>
            </div>
            
            <!-- Menu Links -->
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 20px;">
                <li><a href="/" style="color: #1e293b; text-decoration: none; font-weight: 600; font-size: 15px; display: block;"><?= __('nav_home') ?></a></li>
                <li><a href="/products" style="color: #1e293b; text-decoration: none; font-weight: 600; font-size: 15px; display: block;"><?= __('nav_products') ?></a></li>
                <li><a href="/pricing" style="color: #1e293b; text-decoration: none; font-weight: 600; font-size: 15px; display: block;"><?= __('nav_pricing') ?></a></li>
                <li><a href="/downloads" style="color: #1e293b; text-decoration: none; font-weight: 600; font-size: 15px; display: block;"><?= __('nav_downloads') ?></a></li>
                <li><a href="/blog" style="color: #1e293b; text-decoration: none; font-weight: 600; font-size: 15px; display: block;"><?= __('nav_blog') ?></a></li>
                <li><a href="/contact" style="color: #1e293b; text-decoration: none; font-weight: 600; font-size: 15px; display: block;"><?= __('nav_contact') ?></a></li>
            </ul>
        </div>
        
        <!-- Mobile Actions -->
        <div style="display: flex; flex-direction: column; gap: 16px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
            <!-- Language Switcher -->
            <div class="vmax-lang-switcher" style="align-self: flex-start;">
                <a href="<?= htmlspecialchars($viUrl) ?>" class="vmax-lang-btn <?= APP_LANG === 'vi' ? 'active' : '' ?>">VI</a>
                <a href="<?= htmlspecialchars($enUrl) ?>" class="vmax-lang-btn <?= APP_LANG === 'en' ? 'active' : '' ?>">EN</a>
            </div>
            
            <?php if (Auth::check()): ?>
                <a href="/dashboard" class="vmax-btn-primary" style="text-align: center; width: 100%; box-sizing: border-box;"><?= __('nav_dashboard') ?></a>
            <?php else: ?>
                <a href="/login" class="vmax-btn-login" style="text-align: center; display: block; font-size: 15px;"><?= __('nav_login') ?></a>
                <a href="/register" class="vmax-btn-primary" style="text-align: center; width: 100%; box-sizing: border-box;"><?= __('nav_free_trial') ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function toggleMobileNav() {
        const mobileMenu = document.getElementById('vmax-mobile-menu');
        if (mobileMenu) {
            if (mobileMenu.style.display === 'none') {
                mobileMenu.style.display = 'block';
                document.body.style.overflow = 'hidden';
            } else {
                mobileMenu.style.display = 'none';
                document.body.style.overflow = '';
            }
        }
    }
    
    // Close mobile menu on clicking outside the content panel
    document.getElementById('vmax-mobile-menu').addEventListener('click', function(e) {
        if (e.target === this) {
            toggleMobileNav();
        }
    });
</script>
