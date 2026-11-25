<?php
// VMAX Software - Custom Routing Map

return [
    // Web Public Routes
    '/' => 'HomeController@index',
    '/products' => 'ProductController@index',
    '/product/([^/]+)' => 'ProductController@detail',
    '/pricing' => 'HomeController@pricing',
    '/downloads' => 'DownloadController@index',
    '/blog' => 'BlogController@index',
    '/blog/post/([^/]+)' => 'BlogController@post',
    '/lienhe' => 'HomeController@contact',
    '/contact' => 'HomeController@contact',
    '/privacy' => 'HomeController@privacy',
    '/warranty' => 'HomeController@warranty',
    '/terms' => 'HomeController@terms',
    
    // Auth Routes
    '/login' => 'AuthController@login',
    '/login-2fa' => 'AuthController@login2fa',
    '/register' => 'AuthController@register',
    '/welcome' => 'AuthController@welcome',
    '/logout' => 'AuthController@logout',
    '/auth/google' => 'AuthController@googleAuth',
    '/forgot-password' => 'AuthController@forgotPassword',
    '/reset-password' => 'AuthController@resetPassword',
    
    // Client Dashboard Routes
    '/dashboard' => 'DashboardController@index',
    '/licenses' => 'LicenseController@index',
    '/devices' => 'LicenseController@devices',
    '/affiliate' => 'AffiliateController@index',
    '/support' => 'SupportController@index',
    '/settings' => 'DashboardController@settings',
    '/deposit' => 'DashboardController@deposit',
    '/renew' => 'DashboardController@renew',
    '/transactions' => 'DashboardController@transactions',
    
    // Admin Dashboard Routes
    '/admin' => 'AdminController@index',
    '/admin/users' => 'AdminController@users',
    '/admin/licenses' => 'AdminController@licenses',
    '/admin/products' => 'AdminController@products',
    '/admin/payments' => 'AdminController@payments',
    '/admin/tickets' => 'AdminController@tickets',
    '/admin/affiliate' => 'AdminController@affiliate',
    '/admin/settings' => 'AdminController@settings',
    '/admin/files' => 'AdminController@files',
    '/admin/blog' => 'AdminController@blog',
    '/admin/deposits' => 'AdminController@deposits',
    '/admin/devices' => 'AdminController@devices',
    '/admin/setup' => 'AdminController@setup',
    '/admin/setup/languages' => 'AdminController@languages',
    '/admin/setup/builder' => 'AdminController@pageBuilder',
    '/admin/shopee' => 'AdminController@shopeeInteractions',

    // API Routes (SaaS Licensing Server Integrations)
    '/api/auth/login' => 'ApiController@login',
    '/api/auth/register' => 'ApiController@register',
    '/api/license/check' => 'ApiController@checkLicense',
    '/api/device/register' => 'ApiController@registerDevice',
    '/api/device/remove' => 'ApiController@removeDevice',
    '/api/software/check-update' => 'ApiController@checkUpdate',
    '/api/shopee/submit' => 'ApiController@submitShopeeInteraction',
    '/api/shopee/list' => 'ApiController@getShopeeInteractions',
    '/api/shopee/delete' => 'ApiController@deleteShopeeInteraction',
    '/api/shopee/announcements' => 'ApiController@getShopeeAnnouncements',
    '/api/shopee/latest-version' => 'ApiController@getShopeeLatestVersion'
];
