<?php
// VMAX Software - License Model

class LicenseModel {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function findByKey($key) {
        $stmt = $this->db->query("SELECT * FROM licenses WHERE license_key = ?", [$key]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->query("SELECT * FROM licenses WHERE id = ?", [$id]);
        return $stmt->fetch();
    }

    public function getLicensesByUser($userId) {
        $stmt = $this->db->query(
            "SELECT l.*, p.name as product_name, p.logo_url FROM licenses l 
             JOIN products p ON l.product_id = p.id 
             WHERE l.user_id = ? ORDER BY l.created_at DESC", 
            [$userId]
        );
        return $stmt->fetchAll();
    }

    public function getActiveLicensesByUserAndProduct($userId, $productId) {
        $stmt = $this->db->query(
            "SELECT * FROM licenses WHERE user_id = ? AND product_id = ? AND status = 'active' AND expires_at > ?", 
            [$userId, $productId, time()]
        );
        return $stmt->fetchAll();
    }

    public function createLicense($userId, $productId, $key, $type, $deviceLimit, $durationDays) {
        $now = time();
        $expires = ($type === 'lifetime') ? $now + (100 * 365 * 24 * 60 * 60) : $now + ($durationDays * 24 * 60 * 60);
        
        $this->db->query(
            "INSERT INTO licenses (user_id, product_id, license_key, type, status, device_limit, created_at, expires_at) 
             VALUES (?, ?, ?, ?, 'active', ?, ?, ?)",
            [$userId, $productId, $key, $type, $deviceLimit, $now, $expires]
        );
        return $this->db->getConnection()->lastInsertId();
    }

    public function updateStatus($id, $status) {
        $this->db->query("UPDATE licenses SET status = ? WHERE id = ?", [$status, $id]);
    }

    public function extendLicense($id, $days) {
        $license = $this->findById($id);
        if (!$license) return false;
        
        $currentExpiry = max($license['expires_at'], time());
        $newExpiry = $currentExpiry + ($days * 24 * 60 * 60);
        
        $this->db->query("UPDATE licenses SET expires_at = ? WHERE id = ?", [$newExpiry, $id]);
        return $newExpiry;
    }

    public function getAllLicenses() {
        $stmt = $this->db->query(
            "SELECT l.*, u.username, p.name as product_name FROM licenses l 
             LEFT JOIN users u ON l.user_id = u.id 
             JOIN products p ON l.product_id = p.id 
             ORDER BY l.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function deleteLicense($id) {
        $this->db->query("DELETE FROM licenses WHERE id = ?", [$id]);
    }
}
