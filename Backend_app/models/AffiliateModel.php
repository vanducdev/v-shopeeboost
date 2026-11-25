<?php
// VMAX Software - Affiliate System Model

class AffiliateModel {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function getAffiliateProfile($userId) {
        $stmt = $this->db->query("SELECT * FROM affiliate_users WHERE user_id = ?", [$userId]);
        $profile = $stmt->fetch();
        
        if (!$profile) {
            // Generate affiliate profile on the fly if not exists
            $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            $now = time();
            $this->db->query(
                "INSERT INTO affiliate_users (user_id, referral_code, balance, commission_rate, created_at) VALUES (?, ?, 0.00, 10.00, ?)",
                [$userId, $code, $now]
            );
            $stmt = $this->db->query("SELECT * FROM affiliate_users WHERE user_id = ?", [$userId]);
            $profile = $stmt->fetch();
        }
        return $profile;
    }

    public function findByReferralCode($code) {
        $stmt = $this->db->query("SELECT * FROM affiliate_users WHERE referral_code = ?", [$code]);
        return $stmt->fetch();
    }

    public function logCommission($referrerId, $buyerId, $orderId, $amount) {
        $now = time();
        // Insert commission log
        $this->db->query(
            "INSERT INTO affiliate_logs (referrer_id, buyer_id, order_id, commission_amount, status, created_at) VALUES (?, ?, ?, ?, 'pending', ?)",
            [$referrerId, $buyerId, $orderId, $amount, $now]
        );
    }

    public function approveCommission($logId) {
        $pdo = $this->db->getConnection();
        try {
            $pdo->beginTransaction();
            // Get log details
            $stmt = $this->db->query("SELECT * FROM affiliate_logs WHERE id = ? AND status = 'pending'", [$logId]);
            $log = $stmt->fetch();
            if ($log) {
                // Update log status
                $this->db->query("UPDATE affiliate_logs SET status = 'approved' WHERE id = ?", [$logId]);
                // Update affiliate user balance
                $this->db->query("UPDATE affiliate_users SET balance = balance + ? WHERE user_id = ?", [$log['commission_amount'], $log['referrer_id']]);
            }
            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public function createWithdrawRequest($userId, $bankName, $accNumber, $accName, $amount) {
        $profile = $this->getAffiliateProfile($userId);
        if ($profile['balance'] < $amount) {
            return false; // Insufficient balance
        }
        
        $pdo = $this->db->getConnection();
        try {
            $pdo->beginTransaction();
            // Deduct from balance
            $this->db->query("UPDATE affiliate_users SET balance = balance - ? WHERE user_id = ?", [$amount, $userId]);
            // Create withdraw request
            $this->db->query(
                "INSERT INTO withdraw_requests (user_id, bank_name, account_number, account_name, amount, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', ?)",
                [$userId, $bankName, $accNumber, $accName, $amount, time()]
            );
            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public function getWithdrawRequestsByUser($userId) {
        $stmt = $this->db->query("SELECT * FROM withdraw_requests WHERE user_id = ? ORDER BY created_at DESC", [$userId]);
        return $stmt->fetchAll();
    }

    public function getAffiliateLogsByUser($userId) {
        $stmt = $this->db->query(
            "SELECT l.*, u.username as buyer_name FROM affiliate_logs l 
             JOIN users u ON l.buyer_id = u.id 
             WHERE l.referrer_id = ? ORDER BY l.created_at DESC", 
            [$userId]
        );
        return $stmt->fetchAll();
    }

    public function getLeaderboard() {
        $stmt = $this->db->query(
            "SELECT a.balance, u.username FROM affiliate_users a 
             JOIN users u ON a.user_id = u.id 
             ORDER BY a.balance DESC LIMIT 10"
        );
        return $stmt->fetchAll();
    }

    public function getAllWithdrawRequests() {
        $stmt = $this->db->query(
            "SELECT w.*, u.username FROM withdraw_requests w 
             JOIN users u ON w.user_id = u.id 
             ORDER BY w.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function approveWithdrawRequest($id) {
        $this->db->query("UPDATE withdraw_requests SET status = 'approved' WHERE id = ?", [$id]);
    }

    public function rejectCommission($id) {
        return $this->db->query("UPDATE affiliate_logs SET status = 'rejected' WHERE id = ?", [$id]);
    }

    public function getAllCommissionLogs() {
        $stmt = $this->db->query(
            "SELECT l.*, u.username as referrer_name, b.username as buyer_name, o.total_amount 
             FROM affiliate_logs l 
             JOIN users u ON l.referrer_id = u.id 
             JOIN users b ON l.buyer_id = b.id 
             JOIN orders o ON l.order_id = o.id 
             ORDER BY l.created_at DESC"
        );
        return $stmt->fetchAll();
    }
}

