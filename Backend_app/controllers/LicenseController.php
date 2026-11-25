<?php
// VMAX Software - License & Device Controller

class LicenseController {
    
    public function index() {
        if (!Auth::check()) {
            redirect('/login');
        }
        
        $user = Auth::user();
        $licenseModel = new LicenseModel();
        $productModel = new ProductModel();
        
        $error = '';
        $message = '';
        
        // Handle license key activation
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'activate') {
            $key = sanitize($_POST['license_key'] ?? '');
            $csrf = $_POST['csrf_token'] ?? '';
            
            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else if (empty($key)) {
                $error = 'Vui lòng điền mã License Key bản quyền.';
            } else {
                $lic = $licenseModel->findByKey($key);
                if (!$lic) {
                    $error = 'Mã License Key này không tồn tại trên hệ thống.';
                } else if ($lic['user_id'] != 0 && $lic['user_id'] != $user['id']) {
                    $error = 'Mã License Key này đã thuộc quyền sở hữu của tài khoản khác.';
                } else if ($lic['status'] === 'suspended') {
                    $error = 'Mã Key này đã bị tạm ngừng hoạt động.';
                } else {
                    // Update license owner if it was newly generated and unassigned
                    if ($lic['user_id'] == 0) {
                        $db = DB::getInstance();
                        $db->query("UPDATE licenses SET user_id = ? WHERE id = ?", [$user['id'], $lic['id']]);
                    }
                    $message = 'Kích hoạt giấy phép bản quyền thành công!';
                }
            }
        }
        
        $licenses = $licenseModel->getLicensesByUser($user['id']);
        
        view('dashboard/licenses', [
            'licenses' => $licenses,
            'error' => $error,
            'message' => $message
        ]);
    }

    public function devices() {
        if (!Auth::check()) {
            redirect('/login');
        }
        
        $user = Auth::user();
        $deviceModel = new DeviceModel();
        $licenseModel = new LicenseModel();
        
        $error = '';
        $message = '';
        
        // Handle device reset action
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $csrf = $_POST['csrf_token'] ?? '';
            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else {
                if ($_POST['action'] === 'reset_license') {
                    $licId = intval($_POST['license_id'] ?? 0);
                    $lic = $licenseModel->findById($licId);
                    if ($lic && $lic['user_id'] == $user['id']) {
                        $deviceModel->resetLicenseDevices($licId);
                        $message = 'Thiết bị đã được khởi động lại thành công cho giấy phép.';
                    }
                } else if ($_POST['action'] === 'remove_device') {
                    $devId = intval($_POST['device_id'] ?? 0);
                    // Verify ownership before deleting
                    $db = DB::getInstance();
                    $stmt = $db->query(
                        "SELECT d.* FROM devices d 
                         JOIN licenses l ON d.license_id = l.id 
                         WHERE d.id = ? AND l.user_id = ?",
                        [$devId, $user['id']]
                    );
                    $dev = $stmt->fetch();
                    if ($dev) {
                        $deviceModel->removeDeviceById($devId);
                        $message = 'Xóa liên kết thiết bị thành công.';
                    }
                }
            }
        }
        
        // Fetch all user devices via join
        $db = DB::getInstance();
        $stmt = $db->query(
            "SELECT d.*, l.license_key, p.name as product_name FROM devices d 
             JOIN licenses l ON d.license_id = l.id 
             JOIN products p ON l.product_id = p.id 
             WHERE l.user_id = ? ORDER BY d.activated_at DESC",
            [$user['id']]
        );
        $devices = $stmt->fetchAll();
        
        view('dashboard/devices', [
            'devices' => $devices,
            'error' => $error,
            'message' => $message
        ]);
    }
}
