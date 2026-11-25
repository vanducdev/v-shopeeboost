<?php
// VMAX Software - Product Model

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function findById($id) {
        $stmt = $this->db->query("SELECT * FROM products WHERE id = ?", [$id]);
        return $stmt->fetch();
    }

    public function findBySlug($slug) {
        $stmt = $this->db->query("SELECT * FROM products WHERE slug = ?", [$slug]);
        return $stmt->fetch();
    }

    public function getAllActiveProducts() {
        $stmt = $this->db->query("SELECT * FROM products WHERE status = 'active' ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getAllProducts() {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function createProduct($name, $slug, $shortDesc, $desc, $logoUrl, $imageUrl, $videoUrl, $downloadUrl, $priceM, $priceQ, $priceY, $priceL, $showInMenu = 1, $isCombo = 0) {
        $this->db->query(
            "INSERT INTO products (name, slug, short_description, description, logo_url, image_url, video_url, download_url, price_monthly, price_quarterly, price_yearly, price_lifetime, show_in_menu, is_combo, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$name, $slug, $shortDesc, $desc, $logoUrl, $imageUrl, $videoUrl, $downloadUrl, $priceM, $priceQ, $priceY, $priceL, $showInMenu, $isCombo, time()]
        );
        return $this->db->getConnection()->lastInsertId();
    }

    public function updateProduct($id, $name, $slug, $shortDesc, $desc, $logoUrl, $imageUrl, $videoUrl, $downloadUrl, $priceM, $priceQ, $priceY, $priceL, $status, $showInMenu = 1, $isCombo = 0) {
        $this->db->query(
            "UPDATE products SET name = ?, slug = ?, short_description = ?, description = ?, logo_url = ?, image_url = ?, video_url = ?, download_url = ?, price_monthly = ?, price_quarterly = ?, price_yearly = ?, price_lifetime = ?, status = ?, show_in_menu = ?, is_combo = ? WHERE id = ?",
            [$name, $slug, $shortDesc, $desc, $logoUrl, $imageUrl, $videoUrl, $downloadUrl, $priceM, $priceQ, $priceY, $priceL, $status, $showInMenu, $isCombo, $id]
        );
    }

    public function deleteProduct($id) {
        $this->db->query("DELETE FROM products WHERE id = ?", [$id]);
    }
}
