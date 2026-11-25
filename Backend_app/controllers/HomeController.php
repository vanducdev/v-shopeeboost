<?php
// VMAX Software - Home Controller

class HomeController {
    
    public function index() {
        $productModel = new ProductModel();
        $blogModel = new BlogModel();
        
        $products = $productModel->getAllActiveProducts();
        $latestPosts = $blogModel->getLatestPosts(3);
        
        view('home/index', [
            'products' => $products,
            'posts' => $latestPosts
        ]);
    }

    public function pricing() {
        $productModel = new ProductModel();
        $products = $productModel->getAllActiveProducts();
        
        view('home/pricing', [
            'products' => $products
        ]);
    }

    public function contact() {
        view('home/contact');
    }

    public function privacy() {
        view('home/privacy');
    }

    public function warranty() {
        view('home/warranty');
    }

    public function terms() {
        view('home/terms');
    }
}
