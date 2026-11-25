<?php
// VMAX Software - Order Model

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function createOrder($userId, $amount) {
        $now = time();
        $this->db->query("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'pending', ?)", [$userId, $amount, $now]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function createPayment($orderId, $method, $amount) {
        $now = time();
        $this->db->query(
            "INSERT INTO payments (order_id, payment_method, amount, status, created_at) VALUES (?, ?, ?, 'pending', ?)",
            [$orderId, $method, $amount, $now]
        );
        return $this->db->getConnection()->lastInsertId();
    }

    public function getOrdersByUser($userId) {
        $stmt = $this->db->query(
            "SELECT o.*, p.payment_method, p.transaction_id, p.status as payment_status FROM orders o
             LEFT JOIN payments p ON p.order_id = o.id
             WHERE o.user_id = ? ORDER BY o.created_at DESC",
            [$userId]
        );
        return $stmt->fetchAll();
    }

    public function completeOrder($orderId, $transactionId = null) {
        $pdo = $this->db->getConnection();
        try {
            $pdo->beginTransaction();
            $this->db->query("UPDATE orders SET status = 'completed' WHERE id = ?", [$orderId]);
            $this->db->query("UPDATE payments SET status = 'completed', transaction_id = ? WHERE order_id = ?", [$transactionId, $orderId]);
            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public function getAllPayments() {
        $stmt = $this->db->query(
            "SELECT p.*, o.user_id, u.username, o.total_amount FROM payments p
             JOIN orders o ON p.order_id = o.id
             JOIN users u ON o.user_id = u.id
             ORDER BY p.created_at DESC"
        );
        return $stmt->fetchAll();
    }
}
