<?php
// VMAX Software - User Model

class UserModel {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function findById($id) {
        $stmt = $this->db->query("SELECT * FROM users WHERE id = ?", [$id]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        $stmt = $this->db->query("SELECT * FROM users WHERE email = ?", [$email]);
        return $stmt->fetch();
    }

    public function findByUsername($username) {
        $stmt = $this->db->query("SELECT * FROM users WHERE username = ?", [$username]);
        return $stmt->fetch();
    }

    public function findByGoogleId($googleId) {
        $stmt = $this->db->query("SELECT * FROM users WHERE google_id = ?", [$googleId]);
        return $stmt->fetch();
    }

    public function register($username, $email, $password, $referrerId = null) {
        $hashPassword = password_hash($password, PASSWORD_BCRYPT);
        $now = time();
        $this->db->query(
            "INSERT INTO users (username, email, password, referrer_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)",
            [$username, $email, $hashPassword, $referrerId, $now, $now]
        );
        return $this->db->getConnection()->lastInsertId();
    }

    public function updateOtp($id, $code, $expiry) {
        $this->db->query("UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE id = ?", [$code, $expiry, $id]);
    }

    public function update2FA($id, $secret, $enabled) {
        $this->db->query("UPDATE users SET google_2fa_secret = ?, google_2fa_enabled = ? WHERE id = ?", [$secret, $enabled ? 1 : 0, $id]);
    }

    public function updateStatus($id, $status) {
        $this->db->query("UPDATE users SET status = ? WHERE id = ?", [$status, $id]);
    }

    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function deleteUser($id) {
        $this->db->query("DELETE FROM users WHERE id = ?", [$id]);
    }
}
