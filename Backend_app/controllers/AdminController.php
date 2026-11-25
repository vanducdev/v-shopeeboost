<?php
// VMAX Software - Admin Dashboard Controller

class AdminController {
    
    public function __construct() {
        // Enforce administrative access control
        if (!Auth::check() || !Auth::isAdmin()) {
            redirect('/login');
        }
    }

    public function index() {
        $db = DB::getInstance();
        
        // Collect platform metrics
        $usersCount = $db->query("SELECT COUNT(*) as cnt FROM users")->fetch()['cnt'];
        $licensesCount = $db->query("SELECT COUNT(*) as cnt FROM licenses")->fetch()['cnt'];
        $ordersCount = $db->query("SELECT COUNT(*) as cnt FROM orders WHERE status = 'completed'")->fetch()['cnt'];
        
        $revenue = $db->query("SELECT SUM(total_amount) as rev FROM orders WHERE status = 'completed'")->fetch()['rev'] ?? 0;
        
        // Fetch recent payments
        $stmt = $db->query(
            "SELECT p.*, u.username FROM payments p 
             JOIN orders o ON p.order_id = o.id 
             JOIN users u ON o.user_id = u.id 
             ORDER BY p.created_at DESC LIMIT 5"
        );
        $recentPayments = $stmt->fetchAll();
        
        view('admin/index', [
            'users_count' => $usersCount,
            'licenses_count' => $licensesCount,
            'orders_count' => $ordersCount,
            'revenue' => $revenue,
            'recent_payments' => $recentPayments
        ]);
    }

    public function users() {
        $userModel = new UserModel();
        
        // Handle User Actions (Ban/Unban/Delete)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $userId = intval($_POST['user_id'] ?? 0);
            $csrf = $_POST['csrf_token'] ?? '';
            
            if (Auth::validateCsrf($csrf)) {
                if ($_POST['action'] === 'ban') {
                    $userModel->updateStatus($userId, 'banned');
                } else if ($_POST['action'] === 'unban') {
                    $userModel->updateStatus($userId, 'active');
                } else if ($_POST['action'] === 'delete') {
                    $userModel->deleteUser($userId);
                }
            }
        }
        
        $users = $userModel->getAllUsers();
        view('admin/users', ['users' => $users]);
    }

    public function licenses() {
        $licenseModel = new LicenseModel();
        $productModel = new ProductModel();
        $db = DB::getInstance();
        
        $error = '';
        $message = '';
        
        // Handle License Key Generation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $csrf = $_POST['csrf_token'] ?? '';
            
            if (Auth::validateCsrf($csrf)) {
                if ($_POST['action'] === 'create_key') {
                    $productId = intval($_POST['product_id'] ?? 0);
                    $duration = sanitize($_POST['duration'] ?? '30D');
                    $limit = intval($_POST['device_limit'] ?? 1);
                    
                    $days = 30;
                    if ($duration === '90D') $days = 90;
                    else if ($duration === '180D') $days = 180;
                    else if ($duration === '365D') $days = 365;
                    else if ($duration === 'LIFE') $days = 36500;
                    
                    $key = License::generate($duration);
                    
                    // Create license (owner remains null until a user activates it)
                    $licenseModel->createLicense(null, $productId, $key, ($duration === 'LIFE' ? 'lifetime' : 'monthly'), $limit, $days);
                    $message = "Đã tạo thành công Key mới: <strong>$key</strong>";
                } else if ($_POST['action'] === 'suspend') {
                    $licId = intval($_POST['license_id'] ?? 0);
                    $licenseModel->updateStatus($licId, 'suspended');
                } else if ($_POST['action'] === 'activate') {
                    $licId = intval($_POST['license_id'] ?? 0);
                    $licenseModel->updateStatus($licId, 'active');
                } else if ($_POST['action'] === 'delete') {
                    $licId = intval($_POST['license_id'] ?? 0);
                    $licenseModel->deleteLicense($licId);
                }
            }
        }
        
        $licenses = $licenseModel->getAllLicenses();
        $products = $productModel->getAllActiveProducts();
        
        view('admin/licenses', [
            'licenses' => $licenses,
            'products' => $products,
            'error' => $error,
            'message' => $message
        ]);
    }

    public function products() {
        $productModel = new ProductModel();
        $error = '';
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                if ($_POST['action'] === 'create_product') {
                    $name = sanitize($_POST['name'] ?? '');
                    $slug = strtolower(str_replace(' ', '-', $name));
                    $shortDesc = sanitize($_POST['short_description'] ?? '');
                    $desc = sanitize($_POST['description'] ?? '');
                    $priceM = floatval($_POST['price_monthly'] ?? 0);
                    $priceQ = floatval($_POST['price_quarterly'] ?? 0);
                    $priceY = floatval($_POST['price_yearly'] ?? 0);
                    $priceL = floatval($_POST['price_lifetime'] ?? 0);
                    $showInMenu = isset($_POST['show_in_menu']) ? intval($_POST['show_in_menu']) : 0;
                    $isCombo = isset($_POST['is_combo']) ? intval($_POST['is_combo']) : 0;
                    
                    // Handle logo file upload
                    $logoPath = null;
                    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                        if (!is_dir(BASE_PATH . '/assets/images')) {
                            mkdir(BASE_PATH . '/assets/images', 0755, true);
                        }
                        $logoName = time() . '_' . basename($_FILES['logo']['name']);
                        if (move_uploaded_file($_FILES['logo']['tmp_name'], BASE_PATH . '/assets/images/' . $logoName)) {
                            $logoPath = 'assets/images/' . $logoName;
                        }
                    }
                    
                    // Handle banner image file upload
                    $imagePath = null;
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        if (!is_dir(BASE_PATH . '/image')) {
                            mkdir(BASE_PATH . '/image', 0755, true);
                        }
                        $imageName = time() . '_' . basename($_FILES['image']['name']);
                        if (move_uploaded_file($_FILES['image']['tmp_name'], BASE_PATH . '/image/' . $imageName)) {
                            $imagePath = 'image/' . $imageName;
                        }
                    }
                    
                    $productModel->createProduct($name, $slug, $shortDesc, $desc, $logoPath, $imagePath, null, null, $priceM, $priceQ, $priceY, $priceL, $showInMenu, $isCombo);
                    $message = "Thêm phần mềm thành công!";
                } else if ($_POST['action'] === 'update_product') {
                    $id = intval($_POST['product_id'] ?? 0);
                    $name = sanitize($_POST['name'] ?? '');
                    $slug = strtolower(str_replace(' ', '-', $name));
                    $shortDesc = sanitize($_POST['short_description'] ?? '');
                    $desc = $_POST['description'] ?? ''; // Cho phép nhập HTML cho landing page
                    $priceM = floatval($_POST['price_monthly'] ?? 0);
                    $priceQ = floatval($_POST['price_quarterly'] ?? 0);
                    $priceY = floatval($_POST['price_yearly'] ?? 0);
                    $priceL = floatval($_POST['price_lifetime'] ?? 0);
                    $status = sanitize($_POST['status'] ?? 'active');
                    $showInMenu = isset($_POST['show_in_menu']) ? intval($_POST['show_in_menu']) : 0;
                    $isCombo = isset($_POST['is_combo']) ? intval($_POST['is_combo']) : 0;
                    
                    $currentProduct = $productModel->findById($id);
                    $logoPath = $currentProduct['logo_url'] ?? null;
                    $imagePath = $currentProduct['image_url'] ?? null;
                    
                    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                        if (!is_dir(BASE_PATH . '/assets/images')) {
                            mkdir(BASE_PATH . '/assets/images', 0755, true);
                        }
                        $logoName = time() . '_' . basename($_FILES['logo']['name']);
                        if (move_uploaded_file($_FILES['logo']['tmp_name'], BASE_PATH . '/assets/images/' . $logoName)) {
                            $logoPath = 'assets/images/' . $logoName;
                        }
                    }
                    
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        if (!is_dir(BASE_PATH . '/image')) {
                            mkdir(BASE_PATH . '/image', 0755, true);
                        }
                        $imageName = time() . '_' . basename($_FILES['image']['name']);
                        if (move_uploaded_file($_FILES['image']['tmp_name'], BASE_PATH . '/image/' . $imageName)) {
                            $imagePath = 'image/' . $imageName;
                        }
                    }
                    
                    $productModel->updateProduct($id, $name, $slug, $shortDesc, $desc, $logoPath, $imagePath, null, null, $priceM, $priceQ, $priceY, $priceL, $status, $showInMenu, $isCombo);
                    $message = "Cập nhật sản phẩm thành công!";
                } else if ($_POST['action'] === 'delete') {
                    $id = intval($_POST['product_id'] ?? 0);
                    $productModel->deleteProduct($id);
                    $message = "Đã xóa phần mềm thành công.";
                }
            }
        }
        
        $products = $productModel->getAllProducts();
        view('admin/products', [
            'products' => $products,
            'error' => $error,
            'message' => $message
        ]);
    }

    public function payments() {
        $orderModel = new OrderModel();
        
        // Handle payment approvals
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'approve') {
            $csrf = $_POST['csrf_token'] ?? '';
            $orderId = intval($_POST['order_id'] ?? 0);
            
            if (Auth::validateCsrf($csrf)) {
                $orderModel->completeOrder($orderId, 'VMAX_TXN_' . time());
            }
        }
        
        $payments = $orderModel->getAllPayments();
        view('admin/payments', ['payments' => $payments]);
    }

    public function tickets() {
        $ticketModel = new TicketModel();
        $error = '';
        $message = '';
        
        // Handle admin actions (Reply/Close)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                $ticketId = intval($_POST['ticket_id'] ?? 0);
                if ($_POST['action'] === 'reply') {
                    $msg = sanitize($_POST['message'] ?? '');
                    if (!empty($msg)) {
                        $ticketModel->addMessage($ticketId, $_SESSION['user_id'], $msg);
                        $message = 'Gửi câu trả lời thành công!';
                        redirect('/admin/tickets?ticket_id=' . $ticketId);
                    } else {
                        $error = 'Nội dung phản hồi không được để trống.';
                    }
                } else if ($_POST['action'] === 'close_ticket') {
                    $ticketModel->closeTicket($ticketId);
                    $message = 'Đã đóng ticket thành công!';
                    redirect('/admin/tickets?ticket_id=' . $ticketId);
                }
            } else {
                $error = 'Mã xác thực CSRF không hợp lệ.';
            }
        }
        
        $tickets = $ticketModel->getAllTickets();
        
        $activeTicket = null;
        $messages = [];
        $ticketId = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0;
        if ($ticketId > 0) {
            $ticket = $ticketModel->getTicketById($ticketId);
            if ($ticket) {
                $activeTicket = $ticket;
                $messages = $ticketModel->getMessages($ticketId);
            }
        }
        
        view('admin/tickets', [
            'tickets' => $tickets,
            'activeTicket' => $activeTicket,
            'messages' => $messages,
            'error' => $error,
            'message' => $message
        ]);
    }

    public function affiliate() {
        $affModel = new AffiliateModel();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                $action = $_POST['action'];
                if ($action === 'approve_withdraw') {
                    $reqId = intval($_POST['request_id'] ?? 0);
                    $affModel->approveWithdrawRequest($reqId);
                } else if ($action === 'approve_commission') {
                    $logId = intval($_POST['log_id'] ?? 0);
                    $affModel->approveCommission($logId);
                } else if ($action === 'reject_commission') {
                    $logId = intval($_POST['log_id'] ?? 0);
                    $affModel->rejectCommission($logId);
                }
            }
        }
        
        $requests = $affModel->getAllWithdrawRequests();
        $commissions = $affModel->getAllCommissionLogs();
        
        view('admin/affiliate', [
            'requests' => $requests,
            'commissions' => $commissions
        ]);
    }

    public function devices() {
        $deviceModel = new DeviceModel();
        $error = '';
        $message = '';
        
        // Handle device actions (Reset / Delete)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                $deviceId = intval($_POST['device_id'] ?? 0);
                if ($_POST['action'] === 'remove_device') {
                    $deviceModel->removeDeviceById($deviceId);
                    $message = 'Đã xóa liên kết thiết bị thành công!';
                } else if ($_POST['action'] === 'rename_device') {
                    $newName = sanitize($_POST['device_name'] ?? '');
                    if (!empty($newName)) {
                        $deviceModel->renameDevice($deviceId, $newName);
                        $message = 'Đã đổi tên thiết bị thành công!';
                    } else {
                        $error = 'Tên thiết bị không được để trống.';
                    }
                }
            } else {
                $error = 'Mã xác thực CSRF không hợp lệ.';
            }
        }
        
        // Fetch all devices on the system
        $db = DB::getInstance();
        $stmt = $db->query(
            "SELECT d.*, l.license_key, p.name as product_name, u.username as owner_name 
             FROM devices d 
             JOIN licenses l ON d.license_id = l.id 
             JOIN products p ON l.product_id = p.id 
             LEFT JOIN users u ON l.user_id = u.id 
             ORDER BY d.activated_at DESC"
        );
        $devices = $stmt->fetchAll();
        
        view('admin/devices', [
            'devices' => $devices,
            'error' => $error,
            'message' => $message
        ]);
    }

    public function settings() {
        $db = DB::getInstance();
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                foreach ($_POST as $key => $value) {
                    if ($key === 'csrf_token') continue;
                    $db->query("INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?", [$key, $value, $value]);
                }
                $message = 'Cấu hình hệ thống cập nhật thành công!';
            }
        }
        
        $stmt = $db->query("SELECT * FROM settings");
        $settings = [];
        foreach ($stmt->fetchAll() as $row) {
            $settings[$row['key']] = $row['value'];
        }
        
        view('admin/settings', [
            'settings' => $settings,
            'message' => $message
        ]);
    }

    public function files() {
        $uploadDir = BASE_PATH . '/assets/uploads/';
        $message = '';
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_upload'])) {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                $fileError = $_FILES['file_upload']['error'];
                if ($fileError === UPLOAD_ERR_OK) {
                    // Tự động tạo thư mục nếu chưa tồn tại
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    if (!is_writable($uploadDir)) {
                        $error = 'Lỗi: Thư mục tải lên ' . htmlspecialchars($uploadDir) . ' không có quyền ghi (chưa chmod 755 hoặc 777).';
                    } else {
                        $destName = basename($_FILES['file_upload']['name']);
                        if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $uploadDir . $destName)) {
                            $message = 'Tải lên tệp thành công!';
                        } else {
                            $error = 'Không thể di chuyển tệp đã tải. Vui lòng kiểm tra dung lượng ổ cứng hoặc quyền ghi của server.';
                        }
                    }
                } else {
                    $error_msgs = [
                        UPLOAD_ERR_INI_SIZE   => 'Tệp tải lên vượt quá giới hạn kích thước cho phép trong php.ini (upload_max_filesize).',
                        UPLOAD_ERR_FORM_SIZE  => 'Tệp tải lên vượt quá giới hạn kích thước cho phép của form HTML.',
                        UPLOAD_ERR_PARTIAL    => 'Tệp chỉ được tải lên một phần.',
                        UPLOAD_ERR_NO_FILE    => 'Không có tệp nào được tải lên.',
                        UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm trên máy chủ.',
                        UPLOAD_ERR_CANT_WRITE => 'Không thể ghi tệp vào đĩa máy chủ.',
                        UPLOAD_ERR_EXTENSION  => 'Quá trình tải tệp bị dừng bởi một extension PHP.'
                    ];
                    $error = $error_msgs[$fileError] ?? 'Lỗi tải tệp không xác định (Mã lỗi: ' . $fileError . ').';
                }
            } else {
                $error = 'Mã xác thực CSRF không hợp lệ.';
            }
        }

        // ── XỬ LÝ XÓA FILE ──────────────────────────────────────────────────────
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_file') {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                // Chặn path traversal, chỉ lấy basename
                $targetFile = basename($_POST['filename'] ?? '');
                if ($targetFile !== '' && $targetFile !== '.' && $targetFile !== '..') {
                    $fullPath = $uploadDir . $targetFile;
                    if (is_file($fullPath)) {
                        if (unlink($fullPath)) {
                            $message = '🗑️ Đã xóa tệp: ' . htmlspecialchars($targetFile);
                        } else {
                            $error = 'Không thể xóa tệp. Vui lòng kiểm tra quyền ghi của server.';
                        }
                    } else {
                        $error = 'Không tìm thấy tệp cần xóa.';
                    }
                } else {
                    $error = 'Tên tệp không hợp lệ.';
                }
            } else {
                $error = 'Mã xác thực CSRF không hợp lệ.';
            }
        }
        
        // Scan files inside upload dir
        $files = [];
        if (is_dir($uploadDir)) {
            $files = array_diff(scandir($uploadDir), ['.', '..']);
        }
        
        view('admin/files', [
            'files' => $files,
            'message' => $message,
            'error' => $error
        ]);
    }

    public function blog() {
        $blogModel = new BlogModel();
        $error = '';
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                $action = $_POST['action'];
                
                if ($action === 'create' || $action === 'edit') {
                    $title = sanitize($_POST['title'] ?? '');
                    $customSlug = sanitize($_POST['slug'] ?? '');
                    $content = $_POST['content'] ?? ''; // Giữ nguyên mã HTML/Markdown của bài viết
                    $summary = sanitize($_POST['summary'] ?? '');
                    $metaTitle = sanitize($_POST['meta_title'] ?? '');
                    $metaDesc = sanitize($_POST['meta_description'] ?? '');
                    $status = sanitize($_POST['status'] ?? 'draft');
                    
                    // Tạo slug tự động nếu không nhập
                    if (!empty($customSlug)) {
                        $slug = strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '', str_replace(' ', '-', $customSlug)));
                    } else {
                        // Chuyển tiêu đề thành slug cơ bản
                        $slug = strtolower(preg_replace('/[^a-zA-Z0-9\-]/', '', str_replace(' ', '-', $title)));
                    }
                    
                    if (empty($title) || empty($content)) {
                        $error = 'Tiêu đề và nội dung bài viết không được để trống.';
                    } else {
                        // Xử lý upload ảnh đại diện
                        $imageUrl = $_POST['current_image'] ?? '';
                        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                            $blogImgDir = BASE_PATH . '/image/';
                            if (!is_dir($blogImgDir)) {
                                mkdir($blogImgDir, 0755, true);
                            }
                            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                            $imageName = 'blog_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $blogImgDir . $imageName)) {
                                $imageUrl = 'image/' . $imageName;
                            }
                        }
                        
                        if ($action === 'create') {
                            $userId = $_SESSION['user_id'];
                            $blogModel->createPost($userId, $title, $slug, $content, $summary, $imageUrl, $metaTitle, $metaDesc, $status);
                            $message = 'Tạo bài viết mới thành công!';
                        } else {
                            $id = intval($_POST['post_id'] ?? 0);
                            $blogModel->updatePost($id, $title, $slug, $content, $summary, $imageUrl, $metaTitle, $metaDesc, $status);
                            $message = 'Cập nhật bài viết thành công!';
                        }
                    }
                } else if ($action === 'delete') {
                    $id = intval($_POST['post_id'] ?? 0);
                    $blogModel->deletePost($id);
                    $message = 'Đã xóa bài viết thành công.';
                }
            } else {
                $error = 'Mã xác thực CSRF không hợp lệ.';
            }
        }
        
        $posts = $blogModel->getAllPosts();
        view('admin/blog', [
            'posts' => $posts,
            'error' => $error,
            'message' => $message
        ]);
    }

    public function deposits() {
        $db = DB::getInstance();
        $error = '';
        $message = '';

        // Handle approval or rejection
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                $depositId = intval($_POST['deposit_id'] ?? 0);
                $action = $_POST['action'] ?? '';

                // Fetch deposit details
                $stmt = $db->query("SELECT * FROM deposits WHERE id = ?", [$depositId]);
                $deposit = $stmt->fetch();

                if ($deposit) {
                    if ($deposit['status'] === 'pending') {
                        if ($action === 'approve') {
                            $pdo = $db->getConnection();
                            try {
                                $pdo->beginTransaction();
                                // Update deposit status
                                $db->query("UPDATE deposits SET status = 'approved', updated_at = ? WHERE id = ?", [time(), $depositId]);
                                // Increment user balance
                                $db->query("UPDATE users SET balance = balance + ? WHERE id = ?", [$deposit['amount'], $deposit['user_id']]);
                                
                                $pdo->commit();
                                $message = "Đã duyệt thành công " . number_format($deposit['amount'], 0, ',', '.') . "đ cho tài khoản.";
                            } catch (Exception $e) {
                                $pdo->rollBack();
                                $error = "Có lỗi xảy ra khi duyệt giao dịch: " . $e->getMessage();
                            }
                        } else if ($action === 'reject') {
                            $db->query("UPDATE deposits SET status = 'rejected', updated_at = ? WHERE id = ?", [time(), $depositId]);
                            $message = "Đã từ chối giao dịch nạp tiền.";
                        }
                    } else {
                        $error = "Giao dịch này đã được xử lý trước đó.";
                    }
                } else {
                    $error = "Không tìm thấy yêu cầu nạp tiền.";
                }
            } else {
                $error = "Mã xác thực CSRF không hợp lệ.";
            }
        }

        // Fetch all deposits with usernames
        $stmt = $db->query(
            "SELECT d.*, u.username, u.email FROM deposits d 
             JOIN users u ON d.user_id = u.id 
             ORDER BY d.created_at DESC"
        );
        $deposits = $stmt->fetchAll();

        view('admin/deposits', [
            'deposits' => $deposits,
            'error' => $error,
            'message' => $message
        ]);
    }

    public function setup() {
        $db = DB::getInstance();
        $message = '';
        $error = '';

        // Handle POST form submission (configurations / logo / favicon)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                $action = $_POST['action'] ?? 'save_settings';

                if ($action === 'save_settings') {
                    // General and theme configurations
                    $configs = [
                        'site_name' => sanitize($_POST['site_name'] ?? ''),
                        'bank_transfer_info' => $_POST['bank_transfer_info'] ?? '',
                        'site_primary_color' => sanitize($_POST['site_primary_color'] ?? '#2563eb'),
                        'site_secondary_color' => sanitize($_POST['site_secondary_color'] ?? '#4f46e5'),
                    ];

                    foreach ($configs as $key => $value) {
                        $db->query("INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?", [$key, $value, $value]);
                    }

                    // Handle files uploads (logo, favicon, bank qr)
                    $uploadDir = BASE_PATH . '/assets/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    // Upload Logo
                    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION));
                        if (in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'webp'])) {
                            $logoPath = 'assets/uploads/logo_' . time() . '.' . $ext;
                            if (move_uploaded_file($_FILES['site_logo']['tmp_name'], BASE_PATH . '/' . $logoPath)) {
                                $db->query("INSERT INTO settings (`key`, `value`) VALUES ('site_logo', ?) ON DUPLICATE KEY UPDATE `value` = ?", [$logoPath, $logoPath]);
                            }
                        }
                    }

                    // Upload Favicon
                    if (isset($_FILES['site_favicon']) && $_FILES['site_favicon']['error'] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($_FILES['site_favicon']['name'], PATHINFO_EXTENSION));
                        if (in_array($ext, ['png', 'jpg', 'jpeg', 'ico', 'svg'])) {
                            $faviconPath = 'assets/uploads/fav_' . time() . '.' . $ext;
                            if (move_uploaded_file($_FILES['site_favicon']['tmp_name'], BASE_PATH . '/' . $faviconPath)) {
                                $db->query("INSERT INTO settings (`key`, `value`) VALUES ('site_favicon', ?) ON DUPLICATE KEY UPDATE `value` = ?", [$faviconPath, $faviconPath]);
                            }
                        }
                    }

                    // Upload QR Bank Logo
                    if (isset($_FILES['qr_bank']) && $_FILES['qr_bank']['error'] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($_FILES['qr_bank']['name'], PATHINFO_EXTENSION));
                        if (in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'webp'])) {
                            $qrPath = 'assets/uploads/qr_' . time() . '.' . $ext;
                            if (move_uploaded_file($_FILES['qr_bank']['tmp_name'], BASE_PATH . '/' . $qrPath)) {
                                $db->query("INSERT INTO settings (`key`, `value`) VALUES ('qr_bank_path', ?) ON DUPLICATE KEY UPDATE `value` = ?", [$qrPath, $qrPath]);
                            }
                        }
                    }

                    $message = 'Cập nhật cấu hình website thành công!';
                } elseif ($action === 'create_page') {
                    $title = sanitize($_POST['title'] ?? '');
                    $slug = '/' . ltrim(sanitize($_POST['slug'] ?? ''), '/');

                    if (!empty($title) && !empty($slug)) {
                        try {
                            $time = time();
                            $db->getConnection()->prepare("INSERT INTO pages (title, slug, content_html, content_json, is_published, created_at, updated_at) VALUES (?, ?, '', '', 1, ?, ?)")
                               ->execute([$title, $slug, $time, $time]);
                            $newPageId = $db->getConnection()->lastInsertId();
                            redirect('/admin/setup/builder?page_id=' . $newPageId);
                        } catch (Exception $e) {
                            $error = 'Slug này đã tồn tại trên hệ thống.';
                        }
                    } else {
                        $error = 'Vui lòng nhập đầy đủ tiêu đề và đường dẫn (slug) của trang.';
                    }
                } elseif ($action === 'delete_page') {
                    $pageId = intval($_POST['page_id'] ?? 0);
                    $db->getConnection()->prepare("DELETE FROM pages WHERE id = ?")->execute([$pageId]);
                    $message = 'Đã xóa trang tĩnh thành công!';
                }
            } else {
                $error = 'Mã xác thực CSRF không hợp lệ.';
            }
        }

        // Fetch settings
        $stmtSettings = $db->query("SELECT * FROM settings");
        $settings = [];
        foreach ($stmtSettings->fetchAll() as $row) {
            $settings[$row['key']] = $row['value'];
        }

        // Fetch custom pages from database (wrapped in try-catch in case table not created yet)
        $pages = [];
        try {
            $stmtPages = $db->query("SELECT * FROM pages ORDER BY created_at DESC");
            $pages = $stmtPages->fetchAll();
        } catch (Exception $e) {
            // Table pages doesn't exist yet, proceed with empty pages array
        }

        view('admin/setup', [
            'settings' => $settings,
            'pages' => $pages,
            'error' => $error,
            'message' => $message
        ]);
    }

    public function languages() {
        $error = '';
        $message = '';
        $lang = sanitize($_GET['lang_select'] ?? 'vi');
        if (!in_array($lang, ['vi', 'en'])) {
            $lang = 'vi';
        }

        $langFile = BASE_PATH . '/config/lang/' . $lang . '.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                $translations = $_POST['translations'] ?? [];
                
                // Construct PHP translation array code safely
                $phpCode = "<?php\n// VMAX Software - Translation file ($lang)\nreturn [\n";
                foreach ($translations as $key => $value) {
                    // Escape single quotes safely
                    $safeKey = addcslashes($key, "'\\");
                    $safeVal = addcslashes($value, "'\\");
                    $phpCode .= "    '{$safeKey}' => '{$safeVal}',\n";
                }
                $phpCode .= "];\n";

                if (file_put_contents($langFile, $phpCode) !== false) {
                    $message = "Đã lưu tệp ngôn ngữ " . strtoupper($lang) . " thành công!";
                } else {
                    $error = "Không thể ghi đè tệp ngôn ngữ. Vui lòng cấp quyền ghi (chmod 755/777) cho thư mục config/lang/.";
                }
            } else {
                $error = 'Mã xác thực CSRF không hợp lệ.';
            }
        }

        // Load translations
        $translations = [];
        if (file_exists($langFile)) {
            $translations = require $langFile;
        }

        view('admin/setup', [
            'translations' => $translations,
            'lang' => $lang,
            'error' => $error,
            'message' => $message,
            'is_lang_tab' => true
        ]);
    }

    public function pageBuilder() {
        $db = DB::getInstance();
        $pageId = intval($_GET['page_id'] ?? 0);
        $stmt = $db->getConnection()->prepare("SELECT * FROM pages WHERE id = ? LIMIT 1");
        $stmt->execute([$pageId]);
        $page = $stmt->fetch();

        if (!$page) {
            die("Không tìm thấy trang cần chỉnh sửa.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Save GrapeJS design contents
            $json = $_POST['json'] ?? '';
            $html = $_POST['html'] ?? '';
            $css = $_POST['css'] ?? '';
            $isPublished = intval($_POST['is_published'] ?? 1);

            // GrapesJS combines HTML and CSS for rendering
            $fullHtml = $html;
            if (!empty($css)) {
                $fullHtml = "<style>" . $css . "</style>\n" . $html;
            }

            $stmtUpdate = $db->getConnection()->prepare(
                "UPDATE pages SET content_html = ?, content_json = ?, is_published = ?, updated_at = ? WHERE id = ?"
            );
            $stmtUpdate->execute([$fullHtml, $json, $isPublished, time(), $pageId]);

            jsonResponse(['status' => 'success', 'message' => 'Lưu thiết kế trang thành công!']);
        }

        view('admin/builder', [
            'page' => $page
        ]);
    }

    public function shopeeInteractions() {
        $db = DB::getInstance();
        $error = '';
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $csrf = $_POST['csrf_token'] ?? '';
            if (Auth::validateCsrf($csrf)) {
                if ($_POST['action'] === 'delete') {
                    $id = intval($_POST['id'] ?? 0);
                    $db->query("DELETE FROM shopee_interactions WHERE id = ?", [$id]);
                    $message = "Đã xóa yêu cầu tương tác Shopee thành công!";
                } elseif ($_POST['action'] === 'add_announcement') {
                    $title = trim($_POST['title'] ?? '');
                    $content = trim($_POST['content'] ?? '');
                    $imageUrl = trim($_POST['image_url'] ?? '');
                    $actionLink = trim($_POST['action_link'] ?? '');
                    if (!empty($title) && !empty($content)) {
                        $db->query("INSERT INTO shopee_announcements (title, content, image_url, action_link, created_at) VALUES (?, ?, ?, ?, ?)", [$title, $content, $imageUrl ?: null, $actionLink ?: null, time()]);
                        $message = "Đã đăng thông báo mới thành công!";
                    } else {
                        $error = "Tiêu đề và nội dung thông báo không được bỏ trống.";
                    }
                } elseif ($_POST['action'] === 'delete_announcement') {
                    $id = intval($_POST['id'] ?? 0);
                    $db->query("DELETE FROM shopee_announcements WHERE id = ?", [$id]);
                    $message = "Đã xóa thông báo thành công!";
                } elseif ($_POST['action'] === 'add_version') {
                    $version = trim($_POST['version'] ?? '');
                    $downloadUrl = trim($_POST['download_url'] ?? '');
                    $changelog = trim($_POST['changelog'] ?? '');
                    $isMandatory = isset($_POST['is_mandatory']) ? 1 : 0;
                    if (!empty($version) && !empty($downloadUrl)) {
                        $db->query("INSERT INTO shopee_versions (version, download_url, changelog, is_mandatory, created_at) VALUES (?, ?, ?, ?, ?)", [$version, $downloadUrl, $changelog, $isMandatory, time()]);
                        $message = "Đã phát hành phiên bản mới thành công!";
                    } else {
                        $error = "Vui lòng điền đầy đủ số phiên bản và link tải xuống.";
                    }
                } elseif ($_POST['action'] === 'delete_version') {
                    $id = intval($_POST['id'] ?? 0);
                    $db->query("DELETE FROM shopee_versions WHERE id = ?", [$id]);
                    $message = "Đã xóa phiên bản thành công!";
                }
            } else {
                $error = 'Mã xác thực CSRF không hợp lệ.';
            }
        }

        $stmt = $db->query("SELECT * FROM shopee_interactions ORDER BY created_at DESC");
        $interactions = $stmt->fetchAll();

        $stmtAnn = $db->query("SELECT * FROM shopee_announcements ORDER BY created_at DESC");
        $announcements = $stmtAnn->fetchAll();

        $stmtVer = $db->query("SELECT * FROM shopee_versions ORDER BY created_at DESC");
        $versions = $stmtVer->fetchAll();

        view('admin/shopee', [
            'interactions' => $interactions,
            'announcements' => $announcements,
            'versions' => $versions,
            'error' => $error,
            'message' => $message
        ]);
    }
}
