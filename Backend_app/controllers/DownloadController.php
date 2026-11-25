<?php
// VMAX Software - Download Center Controller

class DownloadController {
    
    public function index() {
        $db = DB::getInstance();
        $stmt = $db->query(
            "SELECT d.*, p.name as product_name, p.logo_url FROM downloads d 
             JOIN products p ON d.product_id = p.id 
             ORDER BY d.created_at DESC"
        );
        $downloads = $stmt->fetchAll();
        
        view('downloads/index', [
            'downloads' => $downloads
        ]);
    }
}
