<?php
// VMAX Software - Auth Class Helper

class Auth {
    
    public static function check() {
        return isset($_SESSION['user_id']);
    }

    public static function user() {
        if (!self::check()) return null;
        $userModel = new UserModel();
        return $userModel->findById($_SESSION['user_id']);
    }

    public static function isAdmin() {
        $user = self::user();
        return ($user && $user['role'] === 'admin');
    }

    public static function login($userId, $username, $role) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        self::generateCsrfToken();
    }

    public static function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        unset($_SESSION['csrf_token']);
        session_destroy();
    }

    public static function generateCsrfToken() {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }

    public static function getCsrfToken() {
        return $_SESSION['csrf_token'] ?? self::generateCsrfToken();
    }

    public static function validateCsrf($token) {
        return (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token));
    }

    public static function verifyGoogleToken($idToken) {
        // Verification helper for Google OAuth2 tokens (GSI backend validation)
        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($idToken);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        if ($data && isset($data['email']) && $data['aud'] === GOOGLE_CLIENT_ID) {
            return $data;
        }
        return false;
    }
}
