<?php
// VMAX Software - API Controller

class ApiController {
    
    public function login() {
        $data = Api::getJsonInput();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            return jsonResponse(['status' => false, 'message' => 'Vui lòng nhập Email và Mật khẩu.'], 400);
        }
        
        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] === 'banned') {
                return jsonResponse(['status' => false, 'message' => 'Tài khoản của bạn đã bị khóa.'], 403);
            }
            
            // Generate API token using standard HMAC SHA256 signature
            $tokenPayload = $user['id'] . ':' . time();
            $token = $tokenPayload . ':' . hash_hmac('sha256', $tokenPayload, SECRET_KEY);
            
            return jsonResponse([
                'status' => true, 
                'token' => $token, 
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ]
            ]);
        }
        
        return jsonResponse(['status' => false, 'message' => 'Thông tin đăng nhập không chính xác.'], 401);
    }

    public function register() {
        $data = Api::getJsonInput();
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        
        if (empty($username) || empty($email) || empty($password)) {
            return jsonResponse(['status' => false, 'message' => 'Vui lòng điền đầy đủ thông tin.'], 400);
        }
        
        $userModel = new UserModel();
        if ($userModel->findByEmail($email)) {
            return jsonResponse(['status' => false, 'message' => 'Email đã được sử dụng.'], 400);
        }
        
        $userId = $userModel->register($username, $email, $password);
        if ($userId) {
            return jsonResponse(['status' => true, 'message' => 'Đăng ký tài khoản thành công!']);
        }
        
        return jsonResponse(['status' => false, 'message' => 'Có lỗi xảy ra, đăng ký thất bại.'], 500);
    }

    public function checkLicense() {
        $data = Api::getJsonInput();
        $key = $data['license_key'] ?? '';
        $deviceId = $data['device_id'] ?? '';
        $deviceName = $data['device_name'] ?? 'Unknown PC';
        $softwareName = $data['software_name'] ?? '';
        $version = $data['software_version'] ?? '1.0.0';
        $ip = Api::getIpAddress();
        
        $key = str_replace(' ', '-', trim($key));
        if (empty($key) || empty($deviceId)) {
            Api::logRequest($key, $deviceId, '/api/license/check', 400, $ip);
            return jsonResponse(['status' => false, 'message' => 'Thông tin đầu vào không đầy đủ.'], 400);
        }
        
        $licenseModel = new LicenseModel();
        $lic = $licenseModel->findByKey($key);
        
        if (!$lic) {
            Api::logRequest($key, $deviceId, '/api/license/check', 404, $ip);
            return jsonResponse(['status' => false, 'message' => 'Mã License Key không tồn tại.'], 404);
        }

        // Verify product matches software name
        $productModel = new ProductModel();
        $product = $productModel->findById($lic['product_id']);
        if (!$product || strcasecmp($product['name'], $softwareName) !== 0) {
            Api::logRequest($key, $deviceId, '/api/license/check', 403, $ip);
            return jsonResponse(['status' => false, 'message' => "Mã Key này dành cho phần mềm '" . ($product ? $product['name'] : 'Khác') . "', không dùng được cho '$softwareName'."], 403);
        }
        
        if ($lic['status'] !== 'active') {
            Api::logRequest($key, $deviceId, '/api/license/check', 403, $ip);
            return jsonResponse(['status' => false, 'message' => 'Mã License Key đã bị tạm dừng hoạt động.'], 403);
        }
        
        if ($lic['expires_at'] < time()) {
            Api::logRequest($key, $deviceId, '/api/license/check', 403, $ip);
            return jsonResponse(['status' => false, 'message' => 'Bản quyền đã hết hạn sử dụng.'], 403);
        }
        
        $deviceModel = new DeviceModel();
        $device = $deviceModel->findByLicenseAndDevice($lic['id'], $deviceId);
        
        if (!$device) {
            $currentCount = $deviceModel->countDevicesByLicense($lic['id']);
            if ($currentCount >= $lic['device_limit']) {
                Api::logRequest($key, $deviceId, '/api/license/check', 403, $ip);
                return jsonResponse(['status' => false, 'message' => 'Vượt quá giới hạn số lượng thiết bị cho phép.'], 403);
            }
            // Bind the new device
            $deviceModel->registerDevice($lic['id'], $deviceId, $deviceName, $ip);
        } else {
            // Update device timestamp
            $deviceModel->registerDevice($lic['id'], $deviceId, $deviceName, $ip);
        }
        
        $userModel = new UserModel();
        $owner = $userModel->findById($lic['user_id']);
        
        Api::logRequest($key, $deviceId, '/api/license/check', 200, $ip);
        return jsonResponse([
            'status' => true,
            'expire' => date('Y-m-d H:i:s', $lic['expires_at']),
            'license' => strtoupper($lic['type']),
            'user' => $owner ? $owner['username'] : 'VMAX User',
            'device_limit' => $lic['device_limit']
        ]);
    }

    public function registerDevice() {
        return $this->checkLicense();
    }

    public function removeDevice() {
        $data = Api::getJsonInput();
        $key = $data['license_key'] ?? '';
        $deviceId = $data['device_id'] ?? '';
        
        if (empty($key) || empty($deviceId)) {
            return jsonResponse(['status' => false, 'message' => 'Tham số không hợp lệ.'], 400);
        }
        
        $licenseModel = new LicenseModel();
        $lic = $licenseModel->findByKey($key);
        
        if ($lic) {
            $deviceModel = new DeviceModel();
            $deviceModel->removeDevice($lic['id'], $deviceId);
            return jsonResponse(['status' => true, 'message' => 'Đã hủy liên kết thiết bị thành công.']);
        }
        
        return jsonResponse(['status' => false, 'message' => 'Không tìm thấy thông tin bản quyền.'], 404);
    }

    public function checkUpdate() {
        $data = Api::getJsonInput();
        $softwareName = $data['software_name'] ?? '';
        
        $db = DB::getInstance();
        $stmt = $db->query(
            "SELECT d.* FROM downloads d 
             JOIN products p ON d.product_id = p.id 
             WHERE p.slug = ? ORDER BY d.created_at DESC LIMIT 1",
            [strtolower(str_replace(' ', '-', $softwareName))]
        );
        $update = $stmt->fetch();
        
        if ($update) {
            return jsonResponse([
                'latest_version' => $update['version'],
                'download_url' => $update['download_url'],
                'force_update' => false,
                'changelog' => $update['release_notes']
            ]);
        }
        
        return jsonResponse([
            'latest_version' => '1.0.0',
            'download_url' => SITE_URL . '/downloads',
            'force_update' => false,
            'changelog' => 'Không có cập nhật mới.'
        ]);
    }

    public function submitShopeeInteraction() {
        $data = Api::getJsonInput();
        $licenseKey = $data['license_key'] ?? '';
        $deviceId = $data['device_id'] ?? '';
        $mainShopLink = $data['main_shop_link'] ?? '';
        $subAccounts = $data['sub_accounts'] ?? '';
        $shopName = $data['shop_name'] ?? '';
        $shopAvatar = $data['shop_avatar'] ?? '';
        $followerCount = intval($data['follower_count'] ?? 0);
        $itemCount = intval($data['item_count'] ?? 0);
        $ratingStar = floatval($data['rating_star'] ?? 5.0);

        if (empty($mainShopLink) || empty($subAccounts)) {
            return jsonResponse(['status' => false, 'message' => 'Vui lòng cung cấp link shop chính và danh sách tài khoản phụ.'], 400);
        }

        $mainShopLink = trim($mainShopLink);
        $subAccounts = trim($subAccounts);
        $shopName = trim($shopName);
        $shopAvatar = trim($shopAvatar);

        $db = DB::getInstance();
        $db->query(
            "INSERT INTO shopee_interactions (license_key, device_id, main_shop_link, shop_name, shop_avatar, follower_count, item_count, rating_star, sub_accounts, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$licenseKey ?: null, $deviceId ?: null, $mainShopLink, $shopName ?: null, $shopAvatar ?: null, $followerCount, $itemCount, $ratingStar, $subAccounts, time()]
        );

        return jsonResponse(['status' => true, 'message' => 'Đã gửi tài khoản tương tác lên hệ thống thành công!']);
    }

    public function getShopeeInteractions() {
        $licenseKey = $_GET['license_key'] ?? '';
        $deviceId = $_GET['device_id'] ?? '';

        if (empty($licenseKey) && empty($deviceId)) {
            return jsonResponse(['status' => false, 'message' => 'Thiếu thông tin xác thực.'], 400);
        }

        $db = DB::getInstance();
        $stmt = $db->query(
            "SELECT * FROM shopee_interactions WHERE license_key = ? OR device_id = ? ORDER BY created_at DESC",
            [$licenseKey, $deviceId]
        );
        $list = $stmt->fetchAll();

        return jsonResponse(['status' => true, 'data' => $list]);
    }

    public function deleteShopeeInteraction() {
        $data = Api::getJsonInput();
        $id = intval($data['id'] ?? 0);
        $licenseKey = $data['license_key'] ?? '';
        $deviceId = $data['device_id'] ?? '';

        if (empty($id)) {
            return jsonResponse(['status' => false, 'message' => 'Thiếu ID yêu cầu.'], 400);
        }

        $db = DB::getInstance();
        $db->query(
            "DELETE FROM shopee_interactions WHERE id = ? AND (license_key = ? OR device_id = ?)",
            [$id, $licenseKey, $deviceId]
        );

        return jsonResponse(['status' => true, 'message' => 'Đã xóa cấu hình tương tác thành công!']);
    }

    public function getShopeeAnnouncements() {
        $db = DB::getInstance();
        $stmt = $db->query("SELECT * FROM shopee_announcements ORDER BY created_at DESC LIMIT 10");
        $list = $stmt->fetchAll();
        return jsonResponse(['status' => true, 'data' => $list]);
    }

    public function getShopeeLatestVersion() {
        $db = DB::getInstance();
        $stmt = $db->query("SELECT * FROM shopee_versions ORDER BY created_at DESC LIMIT 1");
        $version = $stmt->fetch();
        return jsonResponse(['status' => true, 'data' => $version ?: null]);
    }
}
