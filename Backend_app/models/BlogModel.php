<?php
// VMAX Software - SEO Blog Model

class BlogModel {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function findBySlug($slug) {
        $stmt = $this->db->query("SELECT p.*, u.username as author_name FROM posts p JOIN users u ON p.user_id = u.id WHERE p.slug = ?", [$slug]);
        return $stmt->fetch();
    }

    public function getPublishedPosts() {
        $stmt = $this->db->query(
            "SELECT p.*, u.username as author_name FROM posts p 
             JOIN users u ON p.user_id = u.id 
             WHERE p.status = 'published' ORDER BY p.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function getLatestPosts($limit = 3) {
        $stmt = $this->db->query(
            "SELECT p.*, u.username as author_name FROM posts p 
             JOIN users u ON p.user_id = u.id 
             WHERE p.status = 'published' ORDER BY p.created_at DESC LIMIT ?", 
            [$limit]
        );
        return $stmt->fetchAll();
    }

    public function getAllPosts() {
        $stmt = $this->db->query(
            "SELECT p.*, u.username as author_name FROM posts p 
             JOIN users u ON p.user_id = u.id 
             ORDER BY p.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function createPost($userId, $title, $slug, $content, $summary, $imageUrl, $metaTitle, $metaDesc, $status) {
        $this->db->query(
            "INSERT INTO posts (user_id, title, slug, content, summary, image_url, meta_title, meta_description, status, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$userId, $title, $slug, $content, $summary, $imageUrl, $metaTitle, $metaDesc, $status, time()]
        );
        return $this->db->getConnection()->lastInsertId();
    }

    public function updatePost($id, $title, $slug, $content, $summary, $imageUrl, $metaTitle, $metaDesc, $status) {
        $this->db->query(
            "UPDATE posts SET title = ?, slug = ?, content = ?, summary = ?, image_url = ?, meta_title = ?, meta_description = ?, status = ? WHERE id = ?",
            [$title, $slug, $content, $summary, $imageUrl, $metaTitle, $metaDesc, $status, $id]
        );
    }

    public function deletePost($id) {
        $this->db->query("DELETE FROM posts WHERE id = ?", [$id]);
    }
}
