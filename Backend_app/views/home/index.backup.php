<?php
$pageTitle = 'VMAX Software - Giải Pháp Antidetect Browser & Tự Động Hóa SaaS Hàng Đầu Việt Nam';
$pageDescription = 'VMAX Software cung cấp hệ sinh thái SaaS Antidetect Browser (VmaxLogin), phần mềm tự động nuôi nick Facebook (V-AutoPost) và tự động tương tác đẩy sản phẩm Shopee (V-ShopeeBoost). Đột phá doanh thu MMO.';
$pageKeywords = 'antidetect browser, gpm login, gemlogin, vmax software, nuôi nick facebook, shopee boost, tự động hóa mmo, proxy manager, phần mềm mmo';
include BASE_PATH . '/views/layout/header.php';
?>

<!-- Structured Schema Data for SEO Optimization -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "VMAX Software",
  "operatingSystem": "Windows, macOS",
  "applicationCategory": "BusinessApplication",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.9",
    "reviewCount": "184"
  },
  "offers": {
    "@type": "Offer",
    "price": "200000",
    "priceCurrency": "VND",
    "priceSpecification": {
      "@type": "UnitPriceSpecification",
      "price": "200000",
      "priceCurrency": "VND",
      "referenceQuantity": {
        "@type": "QuantitativeValue",
        "value": "1",
        "unitCode": "MON"
      }
    }
  }
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "VMAX Technology",
  "url": "<?= SITE_URL ?>",
  "logo": "<?= SITE_URL ?>/image/logo-app-vmax-64x.png",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "0900000000",
    "contactType": "customer service"
  },
  "sameAs": [
    "https://www.facebook.com/vmaxtech",
    "https://github.com/vmaxtech"
  ]
}
</script>

<style>
/* Bento Grid Layout - Reponsive Optimized */
.bento-grid-premium {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}
@media (min-width: 768px) {
    .bento-grid-premium {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (min-width: 1024px) {
    .bento-grid-premium {
        grid-template-columns: repeat(3, 1fr);
    }
}
.bento-card-premium {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    padding: 2.25rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.bento-card-premium:hover {
    transform: translateY(-5px);
    border-color: rgba(37, 99, 235, 0.25);
    box-shadow: 0 20px 40px rgba(37, 99, 235, 0.05);
}
.bento-card-premium::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: transparent;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.bento-card-premium.bento-blue::before { background: var(--primary, #2563EB); }
.bento-card-premium.bento-indigo::before { background: var(--secondary, #4F46E5); }
.bento-card-premium.bento-emerald::before { background: #10b981; }
.bento-card-premium.bento-orange::before { background: #f97316; }
.bento-card-premium.bento-red::before { background: #ef4444; }
.bento-card-premium:hover::before { height: 6px; }

.bento-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 1.25rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.bento-card-premium:hover .bento-icon-box {
    transform: scale(1.08) rotate(3deg);
}
.badge-tag-premium {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 500;
    padding: 0.25rem 0.6rem;
    display: inline-block;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.bento-card-premium:hover .badge-tag-premium {
    background: rgba(37, 99, 235, 0.03);
    border-color: rgba(37, 99, 235, 0.15);
    color: var(--primary, #2563EB);
}
@media (max-width: 991.98px) {
    .bento-grid-premium {
        grid-template-columns: 1fr;
    }
}

/* New Sleek Compare Cards Styling */
.compare-card-new {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 28px;
    padding: 2.25rem 1.75rem;
    height: 100%;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.02);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
}
.compare-card-new:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.05);
}

.border-blue-highlight {
    border: 2px solid #2563eb !important;
}

.icon-box-new {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

/* Custom background & text colors since Tailwind is not loaded on Home */
.bg-blue-50 {
    background-color: rgba(37, 99, 235, 0.08) !important;
}
.bg-emerald-50 {
    background-color: rgba(16, 185, 129, 0.08) !important;
}
.bg-purple-50 {
    background-color: rgba(168, 85, 247, 0.08) !important;
}

.text-blue-600 {
    color: #2563eb !important;
}
.text-emerald-600 {
    color: #059669 !important;
}
.text-purple-600 {
    color: #7c3aed !important;
}

.compare-title-new {
    font-size: 1.15rem;
    font-weight: 800;
}

.badge-new {
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.25rem 0.6rem;
    border-radius: 100px;
}

.price-val-new {
    font-size: 2.15rem;
    font-weight: 800;
    letter-spacing: -0.02em;
}

.price-unit-new {
    font-size: 0.8rem;
    color: #94a3b8;
    font-weight: 600;
}

.features-list-new {
    padding-left: 0;
    list-style: none;
}
.features-list-new li {
    font-size: 0.85rem;
    color: #475569;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    line-height: 1.4;
}
.features-list-new li i {
    font-size: 0.95rem;
    color: #10b981 !important;
}

/* Button variants */
.btn-blue-new {
    background-color: #2563eb;
    color: #ffffff !important;
    font-weight: 700;
    border-radius: 18px;
    transition: all 0.25s ease;
    border: none;
    text-align: center;
}
.btn-blue-new:hover {
    background-color: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

.btn-emerald-new {
    background-color: #059669;
    color: #ffffff !important;
    font-weight: 700;
    border-radius: 18px;
    transition: all 0.25s ease;
    border: none;
    text-align: center;
}
.btn-emerald-new:hover {
    background-color: #047857;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
}

.btn-purple-new {
    background-color: #7c3aed;
    color: #ffffff !important;
    font-weight: 700;
    border-radius: 18px;
    transition: all 0.25s ease;
    border: none;
    text-align: center;
}
.btn-purple-new:hover {
    background-color: #6d28d9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
}

.compare-icon-wrapper {
    width: 54px;
    height: 54px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
    font-size: 1.5rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.compare-card-premium:hover .compare-icon-wrapper {
    transform: scale(1.1) rotate(5deg);
}

/* Process Steps Upgrades */
.process-step-premium {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    padding: 2.25rem 1.5rem;
    text-align: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    position: relative;
    z-index: 2;
}
.process-step-premium:hover {
    transform: translateY(-5px);
    border-color: rgba(37, 99, 235, 0.25);
    box-shadow: 0 15px 30px rgba(37, 99, 235, 0.05);
}
.process-step-num-premium {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary, #2563EB), var(--secondary, #4F46E5));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.2rem;
    margin: 0 auto 1.25rem;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.process-step-premium:hover .process-step-num-premium {
    transform: scale(1.1);
    box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
}
.process-step-icon {
    font-size: 1.5rem;
    color: #64748B;
    margin-bottom: 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.process-step-premium:hover .process-step-icon {
    color: var(--primary, #2563EB);
    transform: scale(1.08);
}

/* Testimonial Premium Styles */
.testimonial-card-premium {
    background: white;
    border-radius: 24px;
    border: 1px solid #e2e8f0;
    padding: 2.25rem 2rem;
    height: 100%;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.02);
}
.testimonial-card-premium:hover {
    transform: translateY(-6px);
    border-color: rgba(37, 99, 235, 0.2);
    box-shadow: 0 20px 40px rgba(37, 99, 235, 0.05);
}
.testimonial-quote-icon {
    position: absolute;
    top: 1.5rem;
    right: 2rem;
    font-size: 2.5rem;
    color: rgba(37, 99, 235, 0.06);
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.testimonial-card-premium:hover .testimonial-quote-icon {
    color: rgba(37, 99, 235, 0.12);
    transform: scale(1.1) rotate(-5deg);
}
.testimonial-rating {
    color: #f59e0b;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}
.testimonial-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    color: white;
    box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
}
.testimonial-verify-badge {
    background: rgba(16, 185, 129, 0.08);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.15);
    border-radius: 100px;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.15rem 0.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

/* Showcase Premium Style */
.showcase-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    padding: 1.5rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.02);
}
.showcase-card:hover {
    transform: translateY(-6px);
    border-color: rgba(37, 99, 235, 0.25);
    box-shadow: 0 20px 40px rgba(37, 99, 235, 0.06);
}
.showcase-img-container {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1.5rem 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    height: 300px;
    margin-bottom: 1.25rem;
    position: relative;
    border: 1px solid #f1f5f9;
}
.showcase-img-container img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
    transition: all 0.5s ease;
}
.showcase-card:hover .showcase-img-container img {
    transform: scale(1.05);
}
.showcase-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: rgba(37, 99, 235, 0.9);
    backdrop-filter: blur(8px);
    color: white;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.3rem 0.75rem;
    border-radius: 100px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    z-index: 10;
}
.showcase-zoom-btn {
    position: absolute;
    bottom: 1rem;
    right: 1rem;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0f172a;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
    cursor: pointer;
    z-index: 10;
}
.showcase-card:hover .showcase-zoom-btn {
    opacity: 1;
    transform: translateY(0);
}
.showcase-zoom-btn:hover {
    background: #2563eb;
    color: white;
}

/* Infinite Logo Marquee */
.logo-marquee-container {
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    width: 100%;
    padding: 0.5rem 0;
}
.logo-marquee-container::before,
.logo-marquee-container::after {
    content: '';
    position: absolute;
    top: 0;
    width: 80px;
    height: 100%;
    z-index: 2;
    pointer-events: none;
}
.logo-marquee-container::before {
    left: 0;
    background: linear-gradient(to right, #ffffff, transparent);
}
.logo-marquee-container::after {
    right: 0;
    background: linear-gradient(to left, #ffffff, transparent);
}
.logo-marquee-track {
    display: inline-flex;
    animation: marquee-scroll 20s linear infinite;
    gap: 1.5rem;
}
.logo-marquee-track:hover {
    animation-play-state: paused;
}
@keyframes marquee-scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-33.333%);
    }
}
</style>



    <?php include BASE_PATH . '/views/layout/navbar.php'; ?>

    <!-- Section Hero -->
    <section class="hero-section position-relative overflow-hidden py-5">
        <!-- Background light blur shapes for depth -->
        <div class="position-absolute" style="top: -100px; left: -100px; width: 400px; height: 400px; background: rgba(37, 99, 235, 0.05); filter: blur(100px); border-radius: 50%; z-index: -1;"></div>
        <div class="position-absolute" style="bottom: -50px; right: -50px; width: 500px; height: 500px; background: rgba(79, 70, 229, 0.05); filter: blur(100px); border-radius: 50%; z-index: -1;"></div>

        <div class="container-xl">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="hero-badge-premium">
                        <i class="fa-solid fa-circle-check text-primary me-2"></i> <?= __('hero_badge') ?>
                    </span>
                    <h1 class="mb-3 gradient-text">
                        <?= __('hero_title') ?>
                    </h1>
                    <p class="mb-4 text-muted-color" style="max-width: 520px; font-size: 1.05rem; line-height: 1.8;">
                        <?= __('hero_desc') ?>
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="/register" class="btn btn-custom-primary px-4 py-2.5 d-flex align-items-center gap-2">
                            <?= __('hero_cta_trial') ?> <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                        <a href="#products" class="btn btn-custom-outline px-4 py-2.5">
                            <?= __('hero_cta_ecosystem') ?>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="text-center position-relative">
                        <img src="/image/img-about-mission.webp" alt="VMAX Profile Management" class="img-fluid hero-image-glow" style="max-height: 440px; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Grayscale Logos -->
    <div class="logo-row py-4 overflow-hidden">
        <div class="container-xl">
            <p class="text-center small text-uppercase tracking-wider text-muted mb-4" style="font-size: 0.75rem; font-weight: 700; opacity: 0.8;">Được tin dùng bởi các Agency, Seller và Team MMO</p>
            <div class="logo-marquee-container">
                <div class="logo-marquee-track">
                    <!-- Set 1 -->
                    <span class="client-logo-card"><i class="fa-brands fa-facebook text-primary"></i> Facebook Ads</span>
                    <span class="client-logo-card"><i class="fa-brands fa-google text-danger"></i> Google Marketing</span>
                    <span class="client-logo-card"><i class="fa-brands fa-tiktok text-dark"></i> TikTok Shop</span>
                    <span class="client-logo-card"><i class="fa-solid fa-bag-shopping text-warning"></i> Shopee Boost</span>
                    <span class="client-logo-card"><i class="fa-brands fa-amazon text-warning"></i> Amazon Seller</span>
                    <span class="client-logo-card"><i class="fa-brands fa-ebay text-info"></i> eBay Commerce</span>
                    
                    <!-- Set 2 -->
                    <span class="client-logo-card"><i class="fa-brands fa-facebook text-primary"></i> Facebook Ads</span>
                    <span class="client-logo-card"><i class="fa-brands fa-google text-danger"></i> Google Marketing</span>
                    <span class="client-logo-card"><i class="fa-brands fa-tiktok text-dark"></i> TikTok Shop</span>
                    <span class="client-logo-card"><i class="fa-solid fa-bag-shopping text-warning"></i> Shopee Boost</span>
                    <span class="client-logo-card"><i class="fa-brands fa-amazon text-warning"></i> Amazon Seller</span>
                    <span class="client-logo-card"><i class="fa-brands fa-ebay text-info"></i> eBay Commerce</span>

                    <!-- Set 3 -->
                    <span class="client-logo-card"><i class="fa-brands fa-facebook text-primary"></i> Facebook Ads</span>
                    <span class="client-logo-card"><i class="fa-brands fa-google text-danger"></i> Google Marketing</span>
                    <span class="client-logo-card"><i class="fa-brands fa-tiktok text-dark"></i> TikTok Shop</span>
                    <span class="client-logo-card"><i class="fa-solid fa-bag-shopping text-warning"></i> Shopee Boost</span>
                    <span class="client-logo-card"><i class="fa-brands fa-amazon text-warning"></i> Amazon Seller</span>
                    <span class="client-logo-card"><i class="fa-brands fa-ebay text-info"></i> eBay Commerce</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Stats -->
    <section class="bg-section py-5">
        <div class="container-xl">
            <div class="row row-cols-2 row-cols-md-5 g-4 text-center justify-content-center">
                <div class="col" data-aos="fade-up" data-aos-delay="0">
                    <div class="stat-card-premium">
                        <div class="stat-icon-wrapper">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="h3 fw-bold text-main-color mb-1" style="font-size: 1.8rem; letter-spacing: -0.02em;">10,000+</div>
                        <div class="small text-muted-color fw-semibold" style="font-size: 0.8rem;">Khách Hàng</div>
                    </div>
                </div>
                <div class="col" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card-premium">
                        <div class="stat-icon-wrapper">
                            <i class="fa-solid fa-window-restore"></i>
                        </div>
                        <div class="h3 fw-bold text-main-color mb-1" style="font-size: 1.8rem; letter-spacing: -0.02em;">150,000+</div>
                        <div class="small text-muted-color fw-semibold" style="font-size: 0.8rem;">Profile Active</div>
                    </div>
                </div>
                <div class="col" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card-premium">
                        <div class="stat-icon-wrapper">
                            <i class="fa-solid fa-circle-nodes"></i>
                        </div>
                        <div class="h3 fw-bold text-main-color mb-1" style="font-size: 1.8rem; letter-spacing: -0.02em;">99.9%</div>
                        <div class="small text-muted-color fw-semibold" style="font-size: 0.8rem;">Độ Uptime</div>
                    </div>
                </div>
                <div class="col" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card-premium">
                        <div class="stat-icon-wrapper">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <div class="h3 fw-bold text-main-color mb-1" style="font-size: 1.8rem; letter-spacing: -0.02em;">24/7</div>
                        <div class="small text-muted-color fw-semibold" style="font-size: 0.8rem;">Hỗ Trợ Kỹ Thuật</div>
                    </div>
                </div>
                <div class="col" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card-premium">
                        <div class="stat-icon-wrapper">
                            <i class="fa-solid fa-cubes"></i>
                        </div>
                        <div class="h3 fw-bold text-main-color mb-1" style="font-size: 1.8rem; letter-spacing: -0.02em;">5+</div>
                        <div class="small text-muted-color fw-semibold" style="font-size: 0.8rem;">Sản Phẩm SaaS</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Products -->
    <section class="py-5" id="products">
        <div class="container-xl">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-primary fw-bold uppercase tracking-wider small d-block mb-1"><?= __('prod_badge') ?></span>
                <h2 class="fw-bold text-dark mb-2"><?= __('prod_title') ?></h2>
                <p class="text-muted-color"><?= __('prod_desc') ?></p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <?php
                // Clean white product card configurations
                $productCardConfig = [
                    'lushlogin' => [
                        'logo' => 'fa-brands fa-chrome',
                        'icon_class' => 'icon-lushlogin',
                        'features' => ['Browser Fingerprint', 'Proxy Manager', 'Team Collaboration'],
                    ],
                    'v-autopost' => [
                        'logo' => 'fa-solid fa-share-nodes',
                        'icon_class' => 'icon-v-autopost',
                        'features' => ['Đăng bài đa luồng', 'Tương tác seeding', 'Tự động nuôi tài khoản'],
                    ],
                    'v-shopeeboost' => [
                        'logo' => 'fa-solid fa-cart-shopping',
                        'icon_class' => 'icon-v-shopeeboost',
                        'features' => ['Tự động đẩy sản phẩm', 'Tăng tương tác shop', 'Đồng bộ quản lý đơn'],
                    ],
                    'v-shopeeboost-pro' => [
                        'logo' => 'fa-solid fa-basket-shopping',
                        'icon_class' => 'icon-v-shopeeboost-pro',
                        'features' => ['Tính năng bản Shopee', 'Quản lý đa gian hàng', 'Báo cáo nâng cao'],
                    ]
                ];

                // Ensure Shopee and standard Vmax items are in the array even if they are not active in database yet
                $hasShopee = false;
                $hasShopeePro = false;
                foreach($products as $p) {
                    if (strpos(strtolower($p['slug']), 'shopee') !== false) {
                        if (strpos(strtolower($p['slug']), 'pro') !== false) $hasShopeePro = true;
                        else $hasShopee = true;
                    }
                }

                $mappedProducts = $products;
                if (!$hasShopee) {
                    $mappedProducts[] = [
                        'name' => 'V-ShopeeBoost',
                        'slug' => 'v-shopeeboost',
                        'short_description' => 'Công cụ tối ưu và tự động tăng tương tác, đẩy sản phẩm bán hàng Shopee vượt trội.',
                        'price_monthly' => 350000,
                        'is_combo' => 0
                    ];
                }
                if (!$hasShopeePro) {
                    $mappedProducts[] = [
                        'name' => 'V-ShopeeBoost Pro',
                        'slug' => 'v-shopeeboost-pro',
                        'short_description' => 'Phiên bản cao cấp hỗ trợ quản trị đa gian hàng, phân tích đối thủ cạnh tranh nâng cao.',
                        'price_monthly' => 600000,
                        'is_combo' => 0
                    ];
                }

                foreach ($mappedProducts as $pObj):
                    $slug = strtolower($pObj['slug']);
                    $configKey = 'lushlogin'; // Fallback
                    if (strpos($slug, 'lush') !== false || strpos($slug, 'vmaxlogin') !== false) {
                        $configKey = 'lushlogin';
                    } elseif (strpos($slug, 'autopost') !== false) {
                        $configKey = 'v-autopost';
                    } elseif (strpos($slug, 'pro') !== false) {
                        $configKey = 'v-shopeeboost-pro';
                    } elseif (strpos($slug, 'shopee') !== false) {
                        $configKey = 'v-shopeeboost';
                    } else {
                        $configKey = 'lushlogin';
                    }
                    $cData = $productCardConfig[$configKey];
                ?>
                <div class="col-md-6 col-lg-3" data-aos="fade-up">
                    <div class="product-card">
                        <div>
                            <div class="product-icon-wrapper <?= $cData['icon_class'] ?>">
                                <i class="<?= $cData['logo'] ?>"></i>
                            </div>
                            <h3 class="fw-bold h5 text-dark mb-2"><?= htmlspecialchars($pObj['name']) ?></h3>
                            <p class="text-secondary small mb-3" style="font-size: 0.85rem; line-height: 1.5; height: 58px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                <?= htmlspecialchars($pObj['short_description'] ?: $pObj['description'] ?? 'Hỗ trợ đắc lực hoạt động kinh doanh MMO.') ?>
                            </p>

                            <ul class="list-unstyled mb-4">
                                <?php foreach($cData['features'] as $feat): ?>
                                <li class="small text-secondary mb-2 d-flex align-items-center" style="font-size: 0.85rem;">
                                    <i class="fa-solid fa-check text-success me-2" style="font-size: 0.8rem;"></i>
                                    <?= htmlspecialchars($feat) ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div>
                            <div class="border-top pt-3 d-flex justify-content-between align-items-center mb-3">
                                <span class="small text-muted"><?= __('prod_price_from') ?></span>
                                <span class="text-primary fw-bold" style="font-size: 1.05rem;"><?= number_format($pObj['price_monthly'], 0, ',', '.') ?><?= __('prod_per_month') ?></span>
                            </div>
                            <div class="d-grid">
                                <a href="/products" class="btn btn-custom-primary py-2 font-semibold"><?= __('prod_trial_btn') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

        <!-- Section Pricing Comparison Table -->
    <section class="py-5 position-relative overflow-hidden bg-light/35" id="pricing-comparison">
        <div class="container-xl position-relative" style="z-index: 2;">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-primary fw-bold uppercase tracking-wider small d-block mb-1"><?= __('nav_pricing') ?></span>
                <h2 class="fw-bold text-dark mb-2"><?= __('comp_title') ?></h2>
                <p class="text-secondary max-w-2xl mx-auto"><?= __('comp_desc') ?></p>
            </div>
            
            <div class="row g-4 justify-content-center align-items-stretch" data-aos="fade-up">
                <!-- Starter Plan -->
                <?php
                $title1 = __('comp_starter');
                $badge1 = 'Starter';
                if (preg_match('/^(.*?)\s*\((.*?)\)$/', $title1, $matches)) {
                    $title1 = trim($matches[1]);
                    $badge1 = trim($matches[2]);
                }
                ?>
                <div class="col-lg-3 col-md-6">
                    <div class="compare-card-new">
                        <div>
                            <!-- Card Header -->
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="icon-box-new bg-blue-50 text-blue-600">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <h4 class="fw-bold text-dark mb-0 compare-title-new"><?= htmlspecialchars($title1) ?></h4>
                                    <span class="badge-new bg-blue-50 text-blue-600"><?= htmlspecialchars($badge1) ?></span>
                                </div>
                            </div>
                            <!-- Price -->
                            <div class="mb-4">
                                <span class="price-val-new text-blue-600">200.000đ</span>
                                <span class="price-unit-new">/ <?= __('price_month_label') ?></span>
                            </div>
                            <!-- Features -->
                            <ul class="features-list-new mb-4">
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> <?= __('comp_device_limit') ?>: 1 <?= __('comp_device_unit') ?></li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> <?= __('comp_update') ?>: <?= __('comp_update_free') ?></li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> Hỗ trợ kỹ thuật: Zalo / Ultraview / Hotline</li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> Chống quét vân tay Canvas & WebGL</li>
                            </ul>
                        </div>
                        <!-- Button -->
                        <div class="d-grid mt-auto">
                            <a href="/login" class="btn btn-blue-new py-3">Mua Ngay</a>
                        </div>
                    </div>
                </div>
                
                <!-- Team Plan -->
                <?php
                $title2 = __('comp_team');
                $badge2 = 'Team';
                if (preg_match('/^(.*?)\s*\((.*?)\)$/', $title2, $matches)) {
                    $title2 = trim($matches[1]);
                    $badge2 = trim($matches[2]);
                }
                ?>
                <div class="col-lg-3 col-md-6">
                    <div class="compare-card-new">
                        <div>
                            <!-- Card Header -->
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="icon-box-new bg-emerald-50 text-emerald-600">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <h4 class="fw-bold text-dark mb-0 compare-title-new"><?= htmlspecialchars($title2) ?></h4>
                                    <span class="badge-new bg-emerald-50 text-emerald-600"><?= htmlspecialchars($badge2) ?></span>
                                </div>
                            </div>
                            <!-- Price -->
                            <div class="mb-4">
                                <span class="price-val-new text-emerald-600">500.000đ</span>
                                <span class="price-unit-new">/ 3 <?= __('price_month_label') ?></span>
                            </div>
                            <!-- Features -->
                            <ul class="features-list-new mb-4">
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> <?= __('comp_device_limit') ?>: 2 <?= __('comp_device_unit') ?></li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> <?= __('comp_update') ?>: <?= __('comp_update_free') ?></li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> Hỗ trợ kỹ thuật: Zalo / Ultraview / Hotline</li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> Chống quét vân tay Canvas & WebGL</li>
                            </ul>
                        </div>
                        <!-- Button -->
                        <div class="d-grid mt-auto">
                            <a href="/login" class="btn btn-emerald-new py-3">Mua Ngay</a>
                        </div>
                    </div>
                </div>
                
                <!-- Pro Plan (Recommended) -->
                <?php
                $title3 = __('comp_pro');
                $badge3 = 'Pro';
                if (preg_match('/^(.*?)\s*\((.*?)\)$/', $title3, $matches)) {
                    $title3 = trim($matches[1]);
                    $badge3 = trim($matches[2]);
                }
                ?>
                <div class="col-lg-3 col-md-6">
                    <div class="compare-card-new border-blue-highlight">
                        <!-- Recommended Badge -->
                        <div class="position-absolute start-50 translate-middle-x bg-blue-600 text-white text-xs px-3.5 py-1.5 rounded-full fw-bold d-flex align-items-center gap-1 shadow-sm" style="top: -15px; z-index: 10; white-space: nowrap;">
                            <i class="fa-solid fa-circle-check text-[11px]"></i> Khuyên dùng
                        </div>
                        
                        <!-- Sparkles decoration -->
                        <div class="position-absolute text-amber-400 d-flex gap-1" style="top: 1.5rem; right: 1.5rem; opacity: 0.85;">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c0 6.627-5.373 12-12 12 6.627 0 12 5.373 12 12 0-6.627 5.373-12 12-12-6.627 0-12-5.373-12-12z" />
                            </svg>
                            <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24" class="align-self-end mb-1">
                                <path d="M12 0c0 6.627-5.373 12-12 12 6.627 0 12 5.373 12 12 0-6.627 5.373-12 12-12-6.627 0-12-5.373-12-12z" />
                            </svg>
                        </div>
                        
                        <div>
                            <!-- Card Header -->
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="icon-box-new bg-blue-50 text-blue-600">
                                    <i class="fa-solid fa-crown"></i>
                                </div>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <h4 class="fw-bold text-dark mb-0 compare-title-new"><?= htmlspecialchars($title3) ?></h4>
                                    <span class="badge-new bg-blue-50 text-blue-600"><?= htmlspecialchars($badge3) ?></span>
                                </div>
                            </div>
                            <!-- Price -->
                            <div class="mb-4">
                                <span class="price-val-new text-blue-600">1.800.000đ</span>
                                <span class="price-unit-new">/ <?= __('price_year_label') ?></span>
                            </div>
                            <!-- Features -->
                            <ul class="features-list-new mb-4">
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> <?= __('comp_device_limit') ?>: 5 <?= __('comp_device_unit') ?></li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> <?= __('comp_update') ?>: <?= __('comp_update_free') ?></li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> Hỗ trợ kỹ thuật: <strong class="text-blue-600 fw-bold">Ưu tiên phản hồi nhanh</strong></li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> Chống quét vân tay Canvas & WebGL</li>
                            </ul>
                        </div>
                        <!-- Button -->
                        <div class="d-grid mt-auto">
                            <a href="/login" class="btn btn-blue-new py-3">Mua Ngay</a>
                        </div>
                    </div>
                </div>
                
                <!-- VIP Plan (Lifetime) -->
                <?php
                $title4 = 'Gói Trọn Đời';
                $badge4 = 'VIP';
                ?>
                <div class="col-lg-3 col-md-6">
                    <div class="compare-card-new">
                        <div>
                            <!-- Card Header -->
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="icon-box-new bg-purple-50 text-purple-600">
                                    <i class="fa-solid fa-gem"></i>
                                </div>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <h4 class="fw-bold text-dark mb-0 compare-title-new"><?= htmlspecialchars($title4) ?></h4>
                                    <span class="badge-new bg-purple-50 text-purple-600"><?= htmlspecialchars($badge4) ?></span>
                                </div>
                            </div>
                            <!-- Price -->
                            <div class="mb-4">
                                <span class="price-val-new text-purple-600">3.900.000đ</span>
                                <span class="price-unit-new">/ <?= __('price_lifetime_label') ?></span>
                            </div>
                            <!-- Features -->
                            <ul class="features-list-new mb-4">
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> <?= __('comp_device_limit') ?>: 10 <?= __('comp_device_unit') ?></li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> <?= __('comp_update') ?>: <?= __('comp_update_free') ?></li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> Hỗ trợ kỹ thuật: <strong class="text-purple-600 fw-bold">Đặc quyền 24/7 từ chuyên gia</strong></li>
                                <li><i class="fa-solid fa-circle-check text-emerald-500"></i> Chống quét vân tay Canvas & WebGL</li>
                            </ul>
                        </div>
                        <!-- Button -->
                        <div class="d-grid mt-auto">
                            <a href="/login" class="btn btn-purple-new py-3">Mua Ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Features Bento Grid -->
    <section class="bg-section py-5">
        <div class="container-xl">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-primary fw-bold uppercase tracking-wider small d-block mb-1"><?= __('feat_badge') ?></span>
                <h2 class="fw-bold text-dark mb-2"><?= __('feat_title') ?></h2>
                <p class="text-muted-color"><?= __('feat_desc') ?></p>
            </div>

            <div class="bento-grid-premium">
                <!-- Box 1 -->
                <div class="bento-card-premium bento-item-1 bento-blue" data-aos="fade-up">
                    <div>
                        <div class="bento-icon-box" style="background: rgba(37, 99, 235, 0.08); color: var(--primary);">
                            <i class="fa-solid fa-fingerprint"></i>
                        </div>
                        <h4 class="fw-bold mb-2 text-dark"><?= __('feat_fingerprint_title') ?></h4>
                        <p class="text-secondary small mb-3"><?= __('feat_fingerprint_desc') ?></p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge-tag-premium"><?= __('feat_tag_no_checkpoint', 'Chống checkpoint') ?></span>
                            <span class="badge-tag-premium"><?= __('feat_tag_hide_ip', 'Ẩn IP thật') ?></span>
                            <span class="badge-tag-premium"><?= __('feat_tag_emulation', 'Giả lập thiết bị') ?></span>
                            <span class="badge-tag-premium"><?= __('feat_tag_isolation', 'Độc lập vân tay') ?></span>
                        </div>
                    </div>
                </div>
                <!-- Box 2 -->
                <div class="bento-card-premium bento-item-2 bento-indigo" data-aos="fade-up" data-aos-delay="100">
                    <div>
                        <div class="bento-icon-box" style="background: rgba(79, 70, 229, 0.08); color: var(--secondary);">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <h4 class="fw-bold mb-2 text-dark"><?= __('feat_license_title') ?></h4>
                        <p class="text-secondary small mb-3"><?= __('feat_license_desc') ?></p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge-tag-premium">License Key</span>
                            <span class="badge-tag-premium">Device binding</span>
                        </div>
                    </div>
                </div>
                <!-- Box 3 -->
                <div class="bento-card-premium bento-item-3 bento-emerald" data-aos="fade-up">
                    <div>
                        <div class="bento-icon-box" style="background: rgba(16, 185, 129, 0.08); color: #10b981;">
                            <i class="fa-solid fa-gears"></i>
                        </div>
                        <h4 class="fw-bold mb-2 text-dark"><?= __('feat_auto_title') ?></h4>
                        <p class="text-secondary small mb-3"><?= __('feat_auto_desc') ?></p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge-tag-premium">Puppeteer</span>
                            <span class="badge-tag-premium">Selenium</span>
                        </div>
                    </div>
                </div>
                <!-- Box 4 -->
                <div class="bento-card-premium bento-item-4 bento-blue" data-aos="fade-up" data-aos-delay="100">
                    <div>
                        <div class="bento-icon-box" style="background: rgba(37, 99, 235, 0.08); color: var(--primary);">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <h4 class="fw-bold mb-2 text-dark"><?= __('feat_cloud_title') ?></h4>
                        <p class="text-secondary small mb-3"><?= __('feat_cloud_desc') ?></p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge-tag-premium">Secure Cookies</span>
                            <span class="badge-tag-premium">Proxy Manager</span>
                            <span class="badge-tag-premium">Team collaboration</span>
                        </div>
                    </div>
                </div>
                <!-- Box 5 -->
                <div class="bento-card-premium bento-item-5 bento-orange" data-aos="fade-up">
                    <div>
                        <div class="bento-icon-box" style="background: rgba(249, 115, 22, 0.08); color: #f97316;">
                            <i class="fa-solid fa-code"></i>
                        </div>
                        <h4 class="fw-bold mb-2 text-dark">Tích Hợp API</h4>
                        <p class="text-secondary small mb-3">Hệ thống API RESTful mở, kết nối và điều khiển từ xa linh hoạt.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge-tag-premium">RESTful API</span>
                            <span class="badge-tag-premium">JSON response</span>
                        </div>
                    </div>
                </div>
                <!-- Box 6 -->
                <div class="bento-card-premium bento-item-6 bento-red" data-aos="fade-up" data-aos-delay="100">
                    <div>
                        <div class="bento-icon-box" style="background: rgba(239, 68, 68, 0.08); color: #ef4444;">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h4 class="fw-bold mb-2 text-dark">Bảo Mật Tuyệt Đối</h4>
                        <p class="text-secondary small mb-3">Mã hóa đầu cuối bảo mật tránh thất thoát tài khoản quảng cáo của doanh nghiệp.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge-tag-premium">End-to-End Encryption</span>
                            <span class="badge-tag-premium">AES-256 standard</span>
                            <span class="badge-tag-premium">Anti-leak protection</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Why VMAX (4 Cards Grid) -->
    <section class="py-5">
        <div class="container-xl">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-primary fw-bold uppercase tracking-wider small d-block mb-1">Giá trị cốt lõi</span>
                <h2 class="fw-bold text-dark mb-2">Sự Khác Biệt Từ VMAX</h2>
                <p class="text-muted-color">Những giá trị vượt trội chỉ có tại VMAX Platform.</p>
            </div>

            <div class="row g-4 justify-content-center" data-aos="fade-up">
                <div class="col-md-6 col-lg-3">
                    <div class="compare-card-premium text-center">
                        <div class="compare-icon-wrapper" style="background: rgba(37, 99, 235, 0.08); color: var(--primary);">
                            <i class="fa-solid fa-fingerprint"></i>
                        </div>
                        <h4 class="fw-bold mb-2 h5 text-dark">Browser Fingerprint</h4>
                        <p class="small text-secondary mb-0">Profile vân tay độc lập chống quét liên đới hiệu quả.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="compare-card-premium text-center">
                        <div class="compare-icon-wrapper" style="background: rgba(16, 185, 129, 0.08); color: #10b981;">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h4 class="fw-bold mb-2 h5 text-dark">License SaaS</h4>
                        <p class="small text-secondary mb-0">Kích hoạt và quản lý license key tự động 100%.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="compare-card-premium text-center">
                        <div class="compare-icon-wrapper" style="background: rgba(249, 115, 22, 0.08); color: #f97316;">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h4 class="fw-bold mb-2 h5 text-dark">API Integration</h4>
                        <p class="small text-secondary mb-0">Tích hợp API vận hành tự động đa luồng mượt mà.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="compare-card-premium text-center">
                        <div class="compare-icon-wrapper" style="background: rgba(6, 182, 212, 0.08); color: #06b6d4;">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <h4 class="fw-bold mb-2 h5 text-dark">Hỗ Trợ Tiếng Việt</h4>
                        <p class="small text-secondary mb-0">Hỗ trợ kỹ thuật 24/7 trực tiếp bởi đội ngũ Dev Việt.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Process Steps -->
    <section class="bg-section">
        <div class="container-xl">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-primary fw-bold uppercase tracking-wider small d-block mb-1">Bắt đầu nhanh</span>
                <h2 class="fw-bold text-dark mb-2">Quy Trình Hoạt Động</h2>
                <p class="text-secondary max-w-2xl mx-auto">Dễ dàng kích hoạt bản quyền chỉ trong vòng 5 phút.</p>
            </div>

            <div class="row g-4 process-row">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="process-step-premium">
                        <div class="process-step-num-premium">1</div>
                        <div class="process-step-icon">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <h5 class="fw-bold mb-1 text-dark">Đăng Ký</h5>
                        <p class="text-secondary small px-2">Tạo tài khoản nhanh trên cổng SaaS VMAX.</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="process-step-premium">
                        <div class="process-step-num-premium">2</div>
                        <div class="process-step-icon">
                            <i class="fa-solid fa-cubes"></i>
                        </div>
                        <h5 class="fw-bold mb-1 text-dark">Chọn Sản Phẩm</h5>
                        <p class="text-secondary small px-2">Lựa chọn phần mềm phù hợp với nhu cầu.</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="process-step-premium">
                        <div class="process-step-num-premium">3</div>
                        <div class="process-step-icon">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <h5 class="fw-bold mb-1 text-dark">Thanh Toán</h5>
                        <p class="text-secondary small px-2">Quét mã VietQR tự động kích hoạt tức thì.</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="process-step-premium">
                        <div class="process-step-num-premium">4</div>
                        <div class="process-step-icon">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h5 class="fw-bold mb-1 text-dark">Sử Dụng</h5>
                        <p class="text-secondary small px-2">Tải phần mềm, nhập license và sử dụng.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Dashboard Showcase -->
    <section class="py-5" id="dashboardShowcase">
        <div class="container-xl">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-primary fw-bold uppercase tracking-wider small d-block mb-1">Giao diện trực quan</span>
                <h2 class="fw-bold text-dark mb-2">Ảnh Chụp Thực Tế Giao Diện</h2>
                <p class="text-secondary max-w-2xl mx-auto">Khám phá chi tiết các công cụ hàng đầu hỗ trợ hoạt động MMO chuyên nghiệp.</p>
            </div>

            <div class="row g-4">
                <!-- Product 1 -->
                <div class="col-md-4" data-aos="zoom-in">
                    <div class="showcase-card">
                        <div class="showcase-img-container" onclick="openLightbox('<?= ASSET_PATH ?>/image/logologin.webp')">
                            <span class="showcase-badge" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">BÁN CHẠY</span>
                            <img src="<?= ASSET_PATH ?>/image/logologin.webp" alt="VmaxLogin Antidetect Browser" loading="lazy">
                            <div class="showcase-zoom-btn">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </div>
                        </div>
                        <div class="px-2 mt-2 flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="fw-bold text-dark mb-0 h5">VmaxLogin Antidetect</h4>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 0.7rem; font-weight: 600;">v2.4.0</span>
                            </div>
                            <p class="small text-secondary mb-3" style="line-height: 1.5; min-height: 66px;">
                                Trình duyệt ẩn danh chuyên sâu, hỗ trợ tạo và vận hành hàng ngàn profile với cấu hình vân tay độc lập chống checkpoint chéo.
                            </p>
                            <ul class="list-unstyled mb-4" style="font-size: 0.8rem; color: #475569;">
                                <li class="mb-2.5 d-flex align-items-center"><i class="fa-solid fa-circle-check text-primary me-2.5"></i> Bảo vệ tài khoản khỏi bị Facebook phát hiện và khóa hàng loạt nhờ công nghệ giả lập thiết bị tiên tiến.</li>
                                <li class="mb-2.5 d-flex align-items-center"><i class="fa-solid fa-circle-check text-primary me-2.5"></i> Tích hợp Proxy & Dcom đổi IP tự động</li>
                                <li class="mb-2.5 d-flex align-items-center"><i class="fa-solid fa-circle-check text-primary me-2.5"></i> Hỗ trợ API Automation (Puppeteer, Selenium)</li>
                            </ul>
                        </div>
                        <div class="d-grid mt-3 px-2">
                            <a href="/products" class="btn btn-custom-primary py-2.5 font-semibold d-flex align-items-center justify-content-center gap-2">
                                Trải Nghiệm Ngay <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Product 2 -->
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="showcase-card">
                        <div class="showcase-img-container" onclick="openLightbox('<?= ASSET_PATH ?>/image/logofb.webp')">
                            <span class="showcase-badge" style="background: linear-gradient(135deg, #10b981, #059669);">PHỔ BIẾN</span>
                            <img src="<?= ASSET_PATH ?>/image/logofb.webp" alt="V-AutoPost Facebook Automation" loading="lazy">
                            <div class="showcase-zoom-btn">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </div>
                        </div>
                        <div class="px-2 mt-2 flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="fw-bold text-dark mb-0 h5">V-AutoPost Facebook</h4>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.7rem; font-weight: 600;">v3.1.2</span>
                            </div>
                            <p class="small text-secondary mb-3" style="line-height: 1.5; min-height: 66px;">
                                Hệ thống tự động hóa marketing, đăng bài tự động đa luồng, tương tác Group/Page và nuôi nick an toàn số lượng lớn.
                            </p>
                            <ul class="list-unstyled mb-4" style="font-size: 0.8rem; color: #475569;">
                                <li class="mb-2.5 d-flex align-items-center"><i class="fa-solid fa-circle-check text-success me-2.5"></i> Tự động hóa đăng bài & chia sẻ hàng loạt</li>
                                <li class="mb-2.5 d-flex align-items-center"><i class="fa-solid fa-circle-check text-success me-2.5"></i> Tương tác thả tim, bình luận seeding Group</li>
                                <li class="mb-2.5 d-flex align-items-center"><i class="fa-solid fa-circle-check text-success me-2.5"></i> Lên lịch hoạt động thông minh tự động</li>
                            </ul>
                        </div>
                        <div class="d-grid mt-3 px-2">
                            <a href="/products" class="btn btn-success py-2.5 font-semibold text-white d-flex align-items-center justify-content-center gap-2" style="background: #10b981; border: none; border-radius: var(--border-radius-ui); transition: var(--transition);">
                                Trải Nghiệm Ngay <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Product 3 -->
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="showcase-card">
                        <div class="showcase-img-container" onclick="openLightbox('<?= ASSET_PATH ?>/image/logosp.webp')">
                            <span class="showcase-badge" style="background: linear-gradient(135deg, #f97316, #ea580c);">KHUYÊN DÙNG</span>
                            <img src="<?= ASSET_PATH ?>/image/logosp.webp" alt="V-ShopeeBoost Management" loading="lazy">
                            <div class="showcase-zoom-btn">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </div>
                        </div>
                        <div class="px-2 mt-2 flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="fw-bold text-dark mb-0 h5">V-ShopeeBoost Pro</h4>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1" style="font-size: 0.7rem; font-weight: 600;">v1.8.5</span>
                            </div>
                            <p class="small text-secondary mb-3" style="line-height: 1.5; min-height: 66px;">
                                Công cụ quản trị đa gian hàng Shopee chuyên sâu, tự động đẩy sản phẩm, tăng lượt tương tác và đồng bộ đơn hàng nhanh chóng.
                            </p>
                            <ul class="list-unstyled mb-4" style="font-size: 0.8rem; color: #475569;">
                                <li class="mb-2.5 d-flex align-items-center"><i class="fa-solid fa-circle-check text-warning me-2.5"></i> Tự động đẩy sản phẩm top tìm kiếm</li>
                                <li class="mb-2.5 d-flex align-items-center"><i class="fa-solid fa-circle-check text-warning me-2.5"></i> Sao chép hàng loạt sản phẩm đa gian hàng</li>
                                <li class="mb-2.5 d-flex align-items-center"><i class="fa-solid fa-circle-check text-warning me-2.5"></i> Quản lý tập trung không cần chuyển tab</li>
                            </ul>
                        </div>
                        <div class="d-grid mt-3 px-2">
                            <a href="/products" class="btn btn-warning py-2.5 font-semibold text-white d-flex align-items-center justify-content-center gap-2" style="background: #f97316; border: none; border-radius: var(--border-radius-ui); transition: var(--transition);">
                                Trải Nghiệm Ngay <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body text-end p-0">
                    <button type="button" class="btn-close btn-close-white mb-2" data-bs-dismiss="modal" aria-label="Close"></button>
                    <img id="lightboxImg" src="" alt="Screenshot" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- Section Testimonial -->
    <section class="bg-section">
        <div class="container-xl">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-primary fw-bold uppercase tracking-wider small d-block mb-1"><?= __('testi_badge') ?></span>
                <h2 class="fw-bold text-dark mb-2"><?= __('testi_title') ?></h2>
                <p class="text-secondary max-w-2xl mx-auto"><?= __('testi_desc') ?></p>
            </div>

            <!-- Swiper Carousel -->
            <div class="swiper swiper-testimonials">
                <div class="swiper-wrapper">
                    <!-- Review 1 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card-premium">
                            <i class="fa-solid fa-quote-right testimonial-quote-icon"></i>
                            <div>
                                <div class="testimonial-rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <p class="text-secondary small mb-4" style="line-height: 1.7; min-height: 80px;">
                                    "VMAX Antidetect Browser thực sự là cứu cánh cho đội ngũ của tôi. Chúng tôi chạy hơn 500 tài khoản quảng cáo Facebook & Google ngân sách lớn cực kỳ mượt mà, không hề bị checkpoint chéo nhờ thông số vân tay quá sạch."
                                </p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between border-top pt-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="testimonial-avatar" style="background: linear-gradient(135deg, #2563eb, #4f46e5);">TH</div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Trần Hoàng</h6>
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Founder, Ads Agency • <a href="https://www.facebook.com/groups/mmo-vietnam" target="_blank" class="text-primary hover:underline" style="font-weight: 600;">Verified MMO Partner</a></span>
                                    </div>
                                </div>
                                <span class="testimonial-verify-badge">
                                    <i class="fa-solid fa-circle-check"></i> <?= __('testi_verified') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Review 2 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card-premium">
                            <i class="fa-solid fa-quote-right testimonial-quote-icon"></i>
                            <div>
                                <div class="testimonial-rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <p class="text-secondary small mb-4" style="line-height: 1.7; min-height: 80px;">
                                    "Phần mềm V-AutoPost giúp chúng tôi tự động hóa 90% thời gian chăm sóc fanpage, đăng bài và tương tác seeding các nhóm. Giao diện cực dễ cấu hình, chạy đa luồng rất nhanh và ổn định."
                                </p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between border-top pt-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="testimonial-avatar" style="background: linear-gradient(135deg, #10b981, #059669);">MA</div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Minh Anh</h6>
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">MMO Seller, Shopee & TikTok • <a href="https://www.facebook.com/groups/dropship-vietnam" target="_blank" class="text-primary hover:underline" style="font-weight: 600;">Verified Dropship Seller</a></span>
                                    </div>
                                </div>
                                <span class="testimonial-verify-badge">
                                    <i class="fa-solid fa-circle-check"></i> <?= __('testi_verified') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Review 3 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card-premium">
                            <i class="fa-solid fa-quote-right testimonial-quote-icon"></i>
                            <div>
                                <div class="testimonial-rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <p class="text-secondary small mb-4" style="line-height: 1.7; min-height: 80px;">
                                    "Cực kỳ thích tính năng quản lý thiết bị trên cổng điều khiển SaaS. Reset và xóa thiết bị liên kết cũ chỉ bằng 1-click. Hệ thống kích hoạt key tự động qua VietQR rất nhanh chóng."
                                </p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between border-top pt-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="testimonial-avatar" style="background: linear-gradient(135deg, #f97316, #ea580c);">QD</div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Quốc Duy</h6>
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Affiliate Marketer • <a href="https://www.facebook.com/groups/mmo-vietnam" target="_blank" class="text-primary hover:underline" style="font-weight: 600;">Verified Affiliate Partner</a></span>
                                    </div>
                                </div>
                                <span class="testimonial-verify-badge">
                                    <i class="fa-solid fa-circle-check"></i> <?= __('testi_verified') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Section FAQ -->
    <section class="py-5">
        <div class="container-xl" style="max-width: 768px;">
            <h2 class="fw-bold text-center text-dark mb-4"><?= __('faq_title') ?></h2>
            <div class="accordion" id="accordionFAQ" data-aos="fade-up">
                <!-- FAQ 1 -->
                <div class="accordion-item border-0 mb-2 shadow-sm rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Phần mềm Antidetect Browser là gì?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-secondary small">
                            Đây là trình duyệt cho phép tạo ra hàng loạt profile sạch có cấu hình phần cứng độc lập để đăng nhập nhiều tài khoản trên cùng một thiết bị không lo quét liên đới.
                        </div>
                    </div>
                </div>
                <!-- FAQ 2 -->
                <div class="accordion-item border-0 mb-2 shadow-sm rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Tôi có thể đổi thiết bị kích hoạt bản quyền không?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-secondary small">
                            Có, bạn có thể dễ dàng quản lý danh sách thiết bị và nhấn nút "Reset thiết bị" trực tiếp tại trang Bảng điều khiển của mình.
                        </div>
                    </div>
                </div>
                <!-- FAQ 3 -->
                <div class="accordion-item border-0 mb-2 shadow-sm rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Hệ thống có chính sách hoàn tiền không?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-secondary small">
                            Chúng tôi hoàn tiền trong 24 giờ nếu lỗi phát sinh từ hệ thống phần mềm mà kỹ thuật viên VMAX không thể khắc phục.
                        </div>
                    </div>
                </div>
                <!-- FAQ 4 -->
                <div class="accordion-item border-0 mb-2 shadow-sm rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Phần mềm có hỗ trợ điều khiển API không?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-secondary small">
                            Có, các phần mềm đều hỗ trợ API RESTful và CLI để kết nối tự động với Puppeteer, Playwright hoặc Selenium.
                        </div>
                    </div>
                </div>
                <!-- FAQ 5 -->
                <div class="accordion-item border-0 mb-2 shadow-sm rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Có giới hạn số lượng profile khởi tạo trên VmaxLogin không?
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-secondary small">
                            Số lượng profile phụ thuộc hoàn toàn vào gói license bạn đã mua và đăng ký trên tài khoản của mình.
                        </div>
                    </div>
                </div>
                <!-- FAQ 6 -->
                <div class="accordion-item border-0 mb-2 shadow-sm rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Dữ liệu của tôi có được bảo mật đám mây không?
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-secondary small">
                            Có, mọi cookies, profile và thiết lập proxy đều được mã hóa đầu cuối và đồng bộ hóa đám mây an toàn.
                        </div>
                    </div>
                </div>
                <!-- FAQ 7 -->
                <div class="accordion-item border-0 mb-2 shadow-sm rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            Tôi có cần cấu hình Proxy riêng cho mỗi profile không?
                        </button>
                    </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-secondary small">
                            Có, để đạt hiệu quả độc lập tối ưu, bạn nên gán một proxy sạch (HTTP/SOCKS5) riêng biệt cho từng profile.
                        </div>
                    </div>
                </div>
                <!-- FAQ 8 -->
                <div class="accordion-item border-0 mb-2 shadow-sm rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            Tôi thanh toán bằng hình thức nào?
                        </button>
                    </h2>
                    <div id="collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-secondary small">
                            Cổng SaaS hỗ trợ chuyển khoản quét mã VietQR tự động kích hoạt license key tức thì sau khi giao dịch thành công.
                        </div>
                    </div>
                </div>
                <!-- FAQ 9 -->
                <div class="accordion-item border-0 mb-2 shadow-sm rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            Có gói combo tối ưu chi phí không?
                        </button>
                    </h2>
                    <div id="collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-secondary small">
                            Có, các gói combo được chiết khấu lên đến 45% so với mua riêng lẻ từng công cụ.
                        </div>
                    </div>
                </div>
                <!-- FAQ 10 -->
                <div class="accordion-item border-0 mb-2 shadow-sm rounded-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            VMAX có tự động cập nhật phiên bản mới không?
                        </button>
                    </h2>
                    <div id="collapseTen" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                        <div class="accordion-body text-secondary small">
                            Có, phần mềm tự động kiểm tra và nâng cấp phiên bản mới miễn phí trọn đời khi khởi động.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section CTA -->
    <section class="container-xl px-0 py-4">
        <div class="cta-section text-center py-5" style="border-radius: 20px; background: linear-gradient(135deg, var(--primary), var(--secondary));">
            <div class="container max-w-2xl" data-aos="zoom-in">
                <h2 class="fw-bold mb-3 text-white">Sẵn Sàng Tăng Tốc Công Việc Cùng VMAX?</h2>
                <p class="text-white-50 mb-4 px-3 small">Bảo mật vân tay trình duyệt siêu sạch và tối ưu hiệu suất các chiến dịch.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="/register" class="btn btn-light text-primary fw-bold px-4 py-2.5 border-0">Dùng Thử Miễn Phí</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container-xl">
            <div class="row gy-4">
                <div class="col-lg-3 col-md-6">
                    <a href="/" class="d-flex align-items-center mb-3">
                        <img src="/image/logo-app-vmax45.png" alt="VMAX Logo" style="height: 32px;">
                    </a>
                    <p class="small text-secondary mb-3">Hệ thống SaaS Antidetect Browser và tự động hóa bản quyền tối ưu cho MMO, Agency & Sellers.</p>
                    <p class="small text-secondary mb-0">&copy; <?= date('Y') ?> VMAX Technology. Bảo lưu mọi quyền.</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Sản Phẩm</h5>
                    <ul class="list-unstyled">
                        <li><a href="/products" class="footer-link small">VmaxLogin</a></li>
                        <li><a href="/products" class="footer-link small">V-AutoPost</a></li>
                        <li><a href="/products" class="footer-link small">V-ShopeeBoost</a></li>
                        <li><a href="/products" class="footer-link small">V-ShopeeBoost Pro</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Tài Nguyên</h5>
                    <ul class="list-unstyled">
                        <li><a href="/downloads" class="footer-link small">Tài liệu API</a></li>
                        <li><a href="/downloads" class="footer-link small">Hướng dẫn sử dụng</a></li>
                        <li><a href="/blog" class="footer-link small">Tin tức & Blog</a></li>
                        <li><a href="/privacy" class="footer-link small">Chính sách bảo mật</a></li>
                        <li><a href="/warranty" class="footer-link small">Chính sách bảo hành</a></li>
                        <li><a href="/terms" class="footer-link small">Điều khoản dịch vụ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Liên Hệ</h5>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><i class="fa-solid fa-phone me-2 text-primary"></i> Hotline: 0900.000.000</li>
                        <li class="mb-2"><i class="fa-solid fa-comment-dots me-2 text-primary"></i> Zalo: VMAX Support</li>
                        <li class="mb-2"><i class="fa-brands fa-telegram me-2 text-primary"></i> Telegram: @vmax_support</li>
                        <li class="mb-2"><i class="fa-brands fa-github me-2 text-primary"></i> Github: VMAX Tech</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

<?php include BASE_PATH . '/views/layout/footer.php'; ?>
