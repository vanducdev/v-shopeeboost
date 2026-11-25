<?php
// VMAX Software - Affiliate System Controller

class AffiliateController {
    
    public function index() {
        if (!Auth::check()) {
            redirect('/login');
        }
        
        $user = Auth::user();
        $affModel = new AffiliateModel();
        
        $error = '';
        $message = '';
        
        // Handle payout withdraw request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'withdraw') {
            $csrf = $_POST['csrf_token'] ?? '';
            $bankName = sanitize($_POST['bank_name'] ?? '');
            $accNumber = sanitize($_POST['account_number'] ?? '');
            $accName = sanitize($_POST['account_name'] ?? '');
            $amount = floatval($_POST['amount'] ?? 0);
            
            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else if (empty($bankName) || empty($accNumber) || empty($accName) || $amount <= 0) {
                $error = 'Vui lòng nhập đầy đủ thông tin rút tiền chính xác.';
            } else {
                $success = $affModel->createWithdrawRequest($user['id'], $bankName, $accNumber, $accName, $amount);
                if ($success) {
                    $message = 'Yêu cầu rút tiền đã được tạo thành công và đang chờ duyệt.';
                } else {
                    $error = 'Số dư tài khoản không đủ để thực hiện giao dịch này.';
                }
            }
        }
        
        $profile = $affModel->getAffiliateProfile($user['id']);
        $logs = $affModel->getAffiliateLogsByUser($user['id']);
        $withdrawals = $affModel->getWithdrawRequestsByUser($user['id']);
        $leaderboard = $affModel->getLeaderboard();
        
        view('dashboard/affiliate', [
            'profile' => $profile,
            'logs' => $logs,
            'withdrawals' => $withdrawals,
            'leaderboard' => $leaderboard,
            'error' => $error,
            'message' => $message
        ]);
    }
}
