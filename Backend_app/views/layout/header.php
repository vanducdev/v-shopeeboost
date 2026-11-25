<?php
// VMAX Software - Common Header Layout
$pageTitle = $pageTitle ?? 'VMAX Software - Giải Pháp Antidetect Browser & Tự Động Hóa SaaS Hàng Đầu Việt Nam';
$pageDescription = $pageDescription ?? 'VMAX Software cung cấp giải pháp Antidetect Browser, quản lý hàng ngàn tài khoản mạng xã hội, sàn TMĐT an toàn với vân tay trình duyệt độc lập.';
$pageKeywords = $pageKeywords ?? 'antidetect browser, nuôi nick facebook, tự động hóa marketing, gpm login, gemlogin, adspower, gologin, vmax software, bản quyền phần mềm';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
    <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Optimization -->
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($pageKeywords) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= SITE_URL . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?>">

    <!-- Open Graph / Facebook / Zalo -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="VMAX Software Platform">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta property="og:url" content="<?= SITE_URL . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?>">
    <meta property="og:image" content="<?= SITE_URL ?>/image/img-about-mission.webp">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="vi_VN">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="twitter:image" content="<?= SITE_URL ?>/image/img-about-mission.webp">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Global CSS Frameworks & Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS (Support for pages using Tailwind) -->
    <?php if (isset($useTailwind) && $useTailwind): ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                        secondary: '#4F46E5',
                    }
                }
            }
        }
    </script>
    <?php endif; ?>
    
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="<?= ASSET_PATH ?>/css/style.css?v=<?= filemtime(BASE_PATH . '/css/style.css') ?>">

    <!-- SweetAlert2 & Toastify for professional dialogs & alerts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <?php if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/'): ?>
    <!-- Structured Data (JSON-LD Schema) only on Home Page -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "Organization",
          "@id": "<?= SITE_URL ?>/#organization",
          "name": "VMAX Software",
          "url": "<?= SITE_URL ?>/",
          "logo": "<?= SITE_URL ?>/image/logo-app-vmax-64x.png",
          "description": "Giải pháp Antidetect Browser và SaaS quản lý bản quyền phần mềm chuyên nghiệp hàng đầu tại Việt Nam.",
          "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+84-900-000-000",
            "contactType": "customer service"
          }
        },
        {
          "@type": "WebSite",
          "@id": "<?= SITE_URL ?>/#website",
          "url": "<?= SITE_URL ?>/",
          "name": "VMAX Software",
          "description": "Hệ thống Antidetect Browser và Tự động hóa SaaS chuyên nghiệp.",
          "publisher": {
            "@id": "<?= SITE_URL ?>/#organization"
          }
        }
      ]
    }
    </script>
    <?php endif; ?>
</head>
<body class="bg-slate-50 text-slate-900">

<!-- Global Page Loader -->
<div id="global-page-loader" style="position: fixed; inset: 0; background: #ffffff; z-index: 99999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: opacity 0.4s ease, visibility 0.4s ease; opacity: 1; visibility: visible; font-family: 'Inter', sans-serif;">
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
        <!-- Beautiful animated circular track -->
        <div style="width: 72px; height: 72px; border: 4px solid #f1f5f9; border-radius: 50%; position: relative;">
            <div style="position: absolute; inset: -4px; border: 4px solid transparent; border-top-color: #3b82f6; border-right-color: rgba(59, 130, 246, 0.2); border-radius: 50%; animation: loader-spin 1s linear infinite;"></div>
        </div>
        <!-- VMAX Logo in the center -->
        <img src="/image/logo-app-vmax-64x.png" alt="VMAX Logo" style="width: 32px; height: 32px; position: absolute; top: 20px; animation: loader-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;">
        <!-- Loading text -->
        <div style="margin-top: 20px; font-size: 10px; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; animation: loader-pulse 1.5s infinite; text-align: center;">VMAX SOFTWARE</div>
    </div>
</div>

<style>
    @keyframes loader-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes loader-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .6; transform: scale(0.95); }
    }
</style>

<script>
    (function() {
        function hideLoader() {
            var loader = document.getElementById("global-page-loader");
            if (loader) {
                loader.style.opacity = "0";
                loader.style.visibility = "hidden";
                setTimeout(function() {
                    if (loader && loader.parentNode) {
                        loader.parentNode.removeChild(loader);
                    }
                }, 400);
            }
        }
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", hideLoader);
        } else {
            hideLoader();
        }
        window.addEventListener("load", hideLoader);
        setTimeout(hideLoader, 1500);
    })();
</script>
