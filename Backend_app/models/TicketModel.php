<?php
// VMAX Software - Support Ticket Model

class TicketModel {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function createTicket($userId, $subject, $priority) {
        $now = time();
        $this->db->query(
            "INSERT INTO tickets (user_id, subject, priority, status, created_at) VALUES (?, ?, ?, 'open', ?)",
            [$userId, $subject, $priority, $now]
        );
        return $this->db->getConnection()->lastInsertId();
    }

    public function addMessage($ticketId, $userId, $message, $attachment = null) {
        $now = time();
        $this->db->query(
            "INSERT INTO ticket_messages (ticket_id, user_id, message, file_attachment, created_at) VALUES (?, ?, ?, ?, ?)",
            [$ticketId, $userId, $message, $attachment, $now]
        );
        // Automatically update ticket status based on sender role
        $stmt = $this->db->query("SELECT role FROM users WHERE id = ?", [$userId]);
        $user = $stmt->fetch();
        $status = ($user['role'] === 'admin') ? 'replied' : 'open';
        $this->db->query("UPDATE tickets SET status = ? WHERE id = ?", [$status, $ticketId]);
    }

    public function getTicketsByUser($userId) {
        $stmt = $this->db->query("SELECT * FROM tickets WHERE user_id = ? ORDER BY created_at DESC", [$userId]);
        return $stmt->fetchAll();
    }

    public function getTicketById($id) {
        $stmt = $this->db->query("SELECT t.*, u.username FROM tickets t JOIN users u ON t.user_id = u.id WHERE t.id = ?", [$id]);
        return $stmt->fetch();
    }

    public function getMessages($ticketId) {
        $stmt = $this->db->query(
            "SELECT m.*, u.username, u.role FROM ticket_messages m 
             JOIN users u ON m.user_id = u.id 
             WHERE m.ticket_id = ? ORDER BY m.created_at ASC", 
            [$ticketId]
        );
        return $stmt->fetchAll();
    }

    public function closeTicket($id) {
        $this->db->query("UPDATE tickets SET status = 'closed' WHERE id = ?", [$id]);
    }

    public function getAllTickets() {
        $stmt = $this->db->query(
            "SELECT t.*, u.username FROM tickets t 
             JOIN users u ON t.user_id = u.id 
             ORDER BY t.created_at DESC"
        );
        return $stmt->fetchAll();
    }
}
