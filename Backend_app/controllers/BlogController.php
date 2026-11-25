<?php
// VMAX Software - Blog & SEO Controller

class BlogController {
    
    public function index() {
        $blogModel = new BlogModel();
        $posts = $blogModel->getPublishedPosts();
        
        view('blog/index', [
            'posts' => $posts
        ]);
    }

    public function post($slug) {
        $blogModel = new BlogModel();
        $post = $blogModel->findBySlug($slug);
        
        if (!$post) {
            http_response_code(404);
            die("Bài viết không tồn tại.");
        }
        
        view('blog/post', [
            'post' => $post
        ]);
    }
}
