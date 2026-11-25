<?php
// VMAX Software - Customer Dashboard Controller

class DashboardController {
    
    public function index() {
        if (!Auth::check()) {
            redirect('/login');
        }

        $user = Auth::user();
        
        $licenseModel = new LicenseModel();
        $orderModel = new OrderModel();
        $affiliateModel = new AffiliateModel();
        
        // Fetch licenses history
        $licenses = $licenseModel->getLicensesByUser($user['id']);
        
        // Fetch billing invoices
        $orders = $orderModel->getOrdersByUser($user['id']);
        
        // Fetch affiliate profile
        $affiliate = $affiliateModel->getAffiliateProfile($user['id']);
        
        // Fetch latest system notifications
        $db = DB::getInstance();
        $stmt = $db->query("SELECT * FROM notifications WHERE status = 'active' ORDER BY created_at DESC LIMIT 5");
        $notifications = $stmt->fetchAll();
        
        view('dashboard/index', [
            'user' => $user,
            'licenses' => $licenses,
            'orders' => $orders,
            'affiliate' => $affiliate,
            'notifications' => $notifications
        ]);
    }

    public function settings() {
        if (!Auth::check()) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                jsonResponse(['success' => false, 'message' => 'Vui lòng đăng nhập lại.'], 401);
            }
            redirect('/login');
        }

        $user = Auth::user();
        $error = '';
        $message = '';
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || (isset($_GET['ajax']) && $_GET['ajax'] == 1);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf = $_POST['csrf_token'] ?? '';
            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else {
                $action = $_POST['action'] ?? 'update_profile';
                $db = DB::getInstance();

                if ($action === 'enable_2fa') {
                    $code = trim($_POST['2fa_code'] ?? '');
                    $secret = $_SESSION['temp_2fa_secret'] ?? '';
                    
                    if (empty($code)) {
                        $error = 'Vui lòng điền mã 2FA gồm 6 chữ số.';
                    } else if (empty($secret)) {
                        $error = 'Phiên làm việc kích hoạt 2FA đã hết hạn. Vui lòng tải lại trang.';
                    } else if (!GoogleAuthenticator::verifyCode($secret, $code)) {
                        $error = 'Mã xác thực 2FA không chính xác hoặc đã hết hạn.';
                    } else {
                        $db->query("UPDATE users SET google_2fa_secret = ?, google_2fa_enabled = 1 WHERE id = ?", [$secret, $user['id']]);
                        unset($_SESSION['temp_2fa_secret']);
                        $message = 'Đã kích hoạt bảo mật 2FA thành công!';
                        $user = Auth::user();
                    }
                } else if ($action === 'disable_2fa') {
                    $code = trim($_POST['2fa_code'] ?? '');
                    $secret = $user['google_2fa_secret'] ?? '';
                    
                    if (empty($code)) {
                        $error = 'Vui lòng điền mã 2FA gồm 6 chữ số.';
                    } else if (empty($secret)) {
                        $error = 'Tài khoản của bạn chưa kích hoạt bảo mật 2FA.';
                    } else if (!GoogleAuthenticator::verifyCode($secret, $code)) {
                        $error = 'Mã xác thực 2FA không chính xác hoặc đã hết hạn.';
                    } else {
                        $db->query("UPDATE users SET google_2fa_secret = NULL, google_2fa_enabled = 0 WHERE id = ?", [$user['id']]);
                        $message = 'Đã tắt bảo mật 2FA thành công!';
                        $user = Auth::user();
                    }
                } else {
                    // Default: update profile details and password
                    $username = sanitize($_POST['username'] ?? '');
                    $email = sanitize($_POST['email'] ?? '');
                    $newPassword = trim($_POST['new_password'] ?? '');
                    $confirmPassword = trim($_POST['confirm_password'] ?? '');

                    if (empty($username) || empty($email)) {
                        $error = 'Tên đăng nhập và Email không được để trống.';
                    } else if (!empty($newPassword) && $newPassword !== $confirmPassword) {
                        $error = 'Mật khẩu xác nhận không khớp.';
                    } else {
                        // Check duplicate username or email
                        $stmt = $db->query("SELECT id FROM users WHERE (email = ? OR username = ?) AND id != ?", [$email, $username, $user['id']]);
                        if ($stmt->fetch()) {
                            $error = 'Tên đăng nhập hoặc Email đã được sử dụng bởi tài khoản khác.';
                        } else {
                            // Handle custom avatar upload
                            $avatarPath = $user['avatar_url'];
                            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                                $uploadDir = BASE_PATH . '/assets/uploads/avatars/';
                                if (!is_dir($uploadDir)) {
                                    mkdir($uploadDir, 0755, true);
                                }
                                
                                $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                                if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                                    $avatarName = time() . '_' . $user['id'] . '.' . $ext;
                                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $avatarName)) {
                                        $avatarPath = '/assets/uploads/avatars/' . $avatarName;
                                    }
                                }
                            }

                            $db->query("UPDATE users SET username = ?, email = ?, avatar_url = ? WHERE id = ?", [$username, $email, $avatarPath, $user['id']]);
                            
                            if (!empty($newPassword)) {
                                $hashPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                                $db->query("UPDATE users SET password = ? WHERE id = ?", [$hashPassword, $user['id']]);
                            }
                            
                            $_SESSION['username'] = $username;
                            $user = Auth::user();
                            $message = 'Cập nhật thông tin tài khoản thành công!';
                        }
                    }
                }
            }

            if ($isAjax) {
                if (!empty($error)) {
                    jsonResponse(['success' => false, 'message' => $error], 400);
                } else {
                    jsonResponse(['success' => true, 'message' => $message, 'user' => [
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'avatar_url' => $user['avatar_url'],
                        'google_2fa_enabled' => $user['google_2fa_enabled']
                    ]]);
                }
            }
        }

        // Generate 2FA components if not enabled
        $tempSecret = '';
        $qrUrl = '';
        if (!$user['google_2fa_enabled']) {
            if (!isset($_SESSION['temp_2fa_secret'])) {
                $_SESSION['temp_2fa_secret'] = GoogleAuthenticator::generateSecret();
            }
            $tempSecret = $_SESSION['temp_2fa_secret'];
            $qrUrl = GoogleAuthenticator::getQRUrl($user['username'], $tempSecret);
        }

        view('dashboard/settings', [
            'user' => $user,
            'tempSecret' => $tempSecret,
            'qrUrl' => $qrUrl,
            'error' => $error,
            'message' => $message
        ]);
    }

    public function deposit() {
        if (!Auth::check()) {
            redirect('/login');
        }

        $user = Auth::user();
        $db = DB::getInstance();

        // AJAX Status polling check
        if (isset($_GET['check_status']) && $_GET['check_status'] == 1) {
            $depositId = intval($_GET['deposit_id'] ?? 0);
            $stmt = $db->query("SELECT status, amount FROM deposits WHERE id = ? AND user_id = ?", [$depositId, $user['id']]);
            $dep = $stmt->fetch();
            if ($dep) {
                jsonResponse(['success' => true, 'status' => $dep['status'], 'amount' => floatval($dep['amount'])]);
            } else {
                jsonResponse(['success' => false, 'message' => 'Không tìm thấy giao dịch.'], 404);
            }
        }
        
        $error = '';
        $message = '';
        $pendingDeposit = null;

        // Check if there is an active pending deposit created within the last 15 minutes
        $stmt = $db->query(
            "SELECT * FROM deposits WHERE user_id = ? AND status = 'pending' AND created_at > ? ORDER BY created_at DESC LIMIT 1",
            [$user['id'], time() - 900]
        );
        $pendingDeposit = $stmt->fetch();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf = $_POST['csrf_token'] ?? '';
            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else {
                $action = $_POST['action'] ?? '';
                if ($action === 'create_qr') {
                    $amount = floatval($_POST['amount'] ?? 0);
                    if ($amount < 10000) {
                        $error = 'Số tiền nạp tối thiểu là 10.000đ.';
                    } else {
                        // Generate a unique reference code
                        $referenceCode = 'VMD' . $user['id'] . strtoupper(substr(md5(uniqid()), 0, 4));
                        
                        // Insert pending deposit request
                        $db->query(
                            "INSERT INTO deposits (user_id, amount, reference_code, status, created_at, updated_at) VALUES (?, ?, ?, 'pending', ?, ?)",
                            [$user['id'], $amount, $referenceCode, time(), time()]
                        );
                        
                        // Fetch the newly created deposit
                        $stmt = $db->query("SELECT * FROM deposits WHERE user_id = ? AND reference_code = ?", [$user['id'], $referenceCode]);
                        $pendingDeposit = $stmt->fetch();
                        $message = 'Tạo yêu cầu nạp tiền QR Bank thành công!';
                    }
                } else if ($action === 'cancel_deposit') {
                    $depositId = intval($_POST['deposit_id'] ?? 0);
                    $db->query("UPDATE deposits SET status = 'rejected', updated_at = ? WHERE id = ? AND user_id = ?", [time(), $depositId, $user['id']]);
                    $pendingDeposit = null;
                    $message = 'Đã hủy yêu cầu nạp tiền.';
                }
            }
        }

        view('dashboard/deposit', [
            'user' => $user,
            'pendingDeposit' => $pendingDeposit,
            'error' => $error,
            'message' => $message
        ]);
    }

    public function renew() {
        if (!Auth::check()) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                jsonResponse(['success' => false, 'message' => 'Vui lòng đăng nhập lại.'], 401);
            }
            redirect('/login');
        }

        $user = Auth::user();
        $licenseModel = new LicenseModel();
        $db = DB::getInstance();
        
        $error = '';
        $message = '';
        $generatedKey = '';
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || (isset($_GET['ajax']) && $_GET['ajax'] == 1);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf = $_POST['csrf_token'] ?? '';
            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else {
                $action = $_POST['action'] ?? 'renew';
                
                if ($action === 'buy') {
                    $productId = intval($_POST['product_id'] ?? 0);
                    $package = sanitize($_POST['package'] ?? '');

                    // Fetch product pricing
                    $stmt = $db->query("SELECT * FROM products WHERE id = ?", [$productId]);
                    $product = $stmt->fetch();

                    if (!$product) {
                        $error = 'Sản phẩm không tồn tại trên hệ thống.';
                    } else {
                        $price = 0;
                        $days = 0;
                        $typeKey = '';

                        switch ($package) {
                            case 'monthly':
                                $price = $product['price_monthly'];
                                $days = 30;
                                $typeKey = '30D';
                                break;
                            case 'quarterly':
                                $price = $product['price_quarterly'];
                                $days = 90;
                                $typeKey = '90D';
                                break;
                            case 'yearly':
                                $price = $product['price_yearly'];
                                $days = 365;
                                $typeKey = '365D';
                                break;
                            case 'lifetime':
                                $price = $product['price_lifetime'];
                                $days = 36500;
                                $typeKey = 'LIFE';
                                break;
                            default:
                                $error = 'Gói thời hạn không hợp lệ.';
                        }

                        if (empty($error)) {
                            // Check user balance
                            if ($user['balance'] < $price) {
                                $error = 'Số dư tài khoản không đủ để mua sản phẩm này. Vui lòng nạp thêm tiền.';
                            } else {
                                // Deduct balance
                                $db->query("UPDATE users SET balance = balance - ? WHERE id = ?", [$price, $user['id']]);
                                
                                // Generate a new key immediately
                                $newKey = License::generate($typeKey);
                                
                                // Create license
                                $licenseModel->createLicense($user['id'], $productId, $newKey, $package, 1, $days);
                                
                                // Create order and payment record as completed
                                $orderModel = new OrderModel();
                                $orderId = $orderModel->createOrder($user['id'], $price);
                                $orderModel->createPayment($orderId, 'Banking', $price);
                                $orderModel->completeOrder($orderId, 'BUY_' . $newKey . '_' . time());

                                // Log affiliate commission if referrer exists
                                if (!empty($user['referrer_id'])) {
                                    $affModel = new AffiliateModel();
                                    $refProfile = $affModel->getAffiliateProfile($user['referrer_id']);
                                    if ($refProfile) {
                                        $commission = $price * ($refProfile['commission_rate'] / 100);
                                        if ($commission > 0) {
                                            $affModel->logCommission($user['referrer_id'], $user['id'], $orderId, $commission);
                                        }
                                    }
                                }

                                // Log activity
                                $db->query(
                                    "INSERT INTO activity_logs (user_id, action, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?)",
                                    [$user['id'], 'Mua mới key ' . $product['name'] . ' gói ' . $package . ' (' . number_format($price, 0, ',', '.') . 'đ)', $_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? '', time()]
                                );

                                // Reload user for updated balance
                                $user = Auth::user();
                                $generatedKey = $newKey;
                                $message = 'Mua khóa bản quyền mới thành công!';
                            }
                        }
                    }
                } else {
                    // Renew existing license
                    $licenseId = intval($_POST['license_id'] ?? 0);
                    $package = sanitize($_POST['package'] ?? '');

                    // Verify license ownership
                    $lic = $licenseModel->findById($licenseId);
                    if (!$lic || $lic['user_id'] != $user['id']) {
                        $error = 'Không tìm thấy giấy phép bản quyền hợp lệ.';
                    } else {
                        // Fetch product pricing
                        $stmt = $db->query("SELECT * FROM products WHERE id = ?", [$lic['product_id']]);
                        $product = $stmt->fetch();

                        if (!$product) {
                            $error = 'Sản phẩm liên kết không tồn tại.';
                        } else {
                            $price = 0;
                            $days = 0;
                            $type = '';

                            switch ($package) {
                                case 'monthly':
                                    $price = $product['price_monthly'];
                                    $days = 30;
                                    $type = 'monthly';
                                    break;
                                case 'quarterly':
                                    $price = $product['price_quarterly'];
                                    $days = 90;
                                    $type = 'quarterly';
                                    break;
                                case 'yearly':
                                    $price = $product['price_yearly'];
                                    $days = 365;
                                    $type = 'yearly';
                                    break;
                                case 'lifetime':
                                    $price = $product['price_lifetime'];
                                    $days = 36500;
                                    $type = 'lifetime';
                                    break;
                                default:
                                    $error = 'Gói gia hạn không hợp lệ.';
                            }

                            if (empty($error)) {
                                // Check user balance
                                if ($user['balance'] < $price) {
                                    $error = 'Số dư tài khoản không đủ để thực hiện gia hạn. Vui lòng nạp thêm tiền.';
                                } else {
                                    // Deduct balance
                                    $db->query("UPDATE users SET balance = balance - ? WHERE id = ?", [$price, $user['id']]);
                                    
                                    // Extend license
                                    $newExpiry = $licenseModel->extendLicense($licenseId, $days);
                                    
                                    // Update license type if renewed to lifetime or another type
                                    $db->query("UPDATE licenses SET type = ?, status = 'active' WHERE id = ?", [$type, $licenseId]);

                                    // Create order and payment record as completed
                                    $orderModel = new OrderModel();
                                    $orderId = $orderModel->createOrder($user['id'], $price);
                                    $orderModel->createPayment($orderId, 'Banking', $price);
                                    $orderModel->completeOrder($orderId, 'RENEW_' . $lic['license_key'] . '_' . time());

                                    // Log affiliate commission if referrer exists
                                    if (!empty($user['referrer_id'])) {
                                        $affModel = new AffiliateModel();
                                        $refProfile = $affModel->getAffiliateProfile($user['referrer_id']);
                                        if ($refProfile) {
                                            $commission = $price * ($refProfile['commission_rate'] / 100);
                                            if ($commission > 0) {
                                                $affModel->logCommission($user['referrer_id'], $user['id'], $orderId, $commission);
                                            }
                                        }
                                    }

                                    // Log activity
                                    $db->query(
                                        "INSERT INTO activity_logs (user_id, action, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?)",
                                        [$user['id'], 'Gia hạn key ' . $lic['license_key'] . ' gói ' . $type . ' (' . number_format($price, 0, ',', '.') . 'đ)', $_SERVER['REMOTE_ADDR'] ?? '', $_SERVER['HTTP_USER_AGENT'] ?? '', time()]
                                    );

                                    // Reload user for updated balance
                                    $user = Auth::user();
                                    $generatedKey = $lic['license_key'];
                                    $message = 'Gia hạn giấy phép bản quyền thành công!';
                                }
                            }
                        }
                    }
                }
            }

            if ($isAjax) {
                if (!empty($error)) {
                    jsonResponse(['success' => false, 'message' => $error], 400);
                } else {
                    jsonResponse(['success' => true, 'message' => $message, 'key' => $generatedKey, 'balance' => floatval($user['balance'])]);
                }
            }
        }

        $licenses = $licenseModel->getLicensesByUser($user['id']);

        view('dashboard/renew', [
            'user' => $user,
            'licenses' => $licenses,
            'error' => $error,
            'message' => $message
        ]);
    }

    public function transactions() {
        if (!Auth::check()) {
            redirect('/login');
        }

        $user = Auth::user();
        $db = DB::getInstance();

        // Fetch deposits
        $stmt = $db->query("SELECT * FROM deposits WHERE user_id = ? ORDER BY created_at DESC", [$user['id']]);
        $deposits = $stmt->fetchAll();

        // Fetch completed license orders
        $orderModel = new OrderModel();
        $orders = $orderModel->getOrdersByUser($user['id']);

        view('dashboard/transactions', [
            'user' => $user,
            'deposits' => $deposits,
            'orders' => $orders
        ]);
    }
}
