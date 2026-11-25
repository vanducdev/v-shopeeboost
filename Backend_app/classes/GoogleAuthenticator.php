<?php
// VMAX Software - Google Authenticator 2FA Helper (Pure PHP)

class GoogleAuthenticator {
    private static $_base32Lookup = [
        'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7,
        'I' => 8, 'J' => 9, 'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15,
        'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19, 'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23,
        'Y' => 24, 'Z' => 25, '2' => 26, '3' => 27, '4' => 28, '5' => 29, '6' => 30, '7' => 31
    ];

    public static function generateSecret($length = 16) {
        $keys = array_keys(self::$_base32Lookup);
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $keys[random_int(0, 31)];
        }
        return $secret;
    }

    public static function getQRUrl($username, $secret) {
        $label = urlencode("VMAX Software (" . $username . ")");
        $otpauth = "otpauth://totp/{$label}?secret={$secret}&issuer=VMAX+Software";
        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($otpauth);
    }

    public static function verifyCode($secret, $code, $discrepancy = 1) {
        $currentTimeSlice = floor(time() / 30);
        
        if (strlen($code) != 6) {
            return false;
        }

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $calculatedCode = self::getCode($secret, $currentTimeSlice + $i);
            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    private static function getCode($secret, $timeSlice) {
        $secretKey = self::base32Decode($secret);

        // Pack time into binary string
        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        // Hash it with HMAC-SHA1
        $hmac = hash_hmac('sha1', $time, $secretKey, true);
        // Use last nibble of result as index/offset
        $offset = ord(substr($hmac, -1)) & 0x0F;
        // grab 4 bytes of the hmac starting from offset
        $hashpart = substr($hmac, $offset, 4);

        // Unpack binary value
        $value = unpack('N', $hashpart);
        $value = $value[1];
        // Only 32 bits
        $value = $value & 0x7FFFFFFF;

        $modulo = pow(10, 6);
        return str_pad($value % $modulo, 6, '0', STR_PAD_LEFT);
    }

    private static function base32Decode($secret) {
        if (empty($secret)) return '';
        
        $secret = strtoupper($secret);
        $secret = str_replace('=', '', $secret);
        
        $buf = '';
        $val = 0;
        $vLen = 0;
        
        $len = strlen($secret);
        for ($i = 0; $i < $len; $i++) {
            $c = $secret[$i];
            if (!isset(self::$_base32Lookup[$c])) {
                continue;
            }
            $val = ($val << 5) | self::$_base32Lookup[$c];
            $vLen += 5;
            if ($vLen >= 8) {
                $buf .= chr(($val >> ($vLen - 8)) & 0xFF);
                $vLen -= 8;
            }
        }
        return $buf;
    }
}
