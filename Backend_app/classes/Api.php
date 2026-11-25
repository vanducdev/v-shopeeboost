<?php
// VMAX Software - API Helper Class

class Api {
    
    public static function getJsonInput() {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        return $data ?: [];
    }

    public static function logRequest($licenseKey, $deviceId, $path, $responseCode, $ipAddress) {
        $db = DB::getInstance();
        $db->query(
            "INSERT INTO api_logs (license_key, device_id, request_path, response_code, ip_address, created_at) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [$licenseKey, $deviceId, $path, $responseCode, $ipAddress, time()]
        );
    }

    public static function getIpAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }
    }
}
