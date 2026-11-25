<?php
// VMAX Software - License Verification & Key Generation Helper

class License {
    
    /**
     * Generates a license key with a specific format:
     * VMAX-30D-XXXX-XXXX-XXXX
     * VMAX-LIFE-XXXX-XXXX-XXXX
     */
    public static function generate($daysType) {
        // daysType can be '30D', '90D', '180D', '365D', 'LIFE'
        $rand1 = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
        $rand2 = strtoupper(substr(md5(uniqid(rand(), true)), 4, 4));
        
        // Secure signature to verify tempering
        $payload = "$daysType-$rand1-$rand2";
        $sig = strtoupper(substr(hash_hmac('sha256', $payload, SECRET_KEY), 0, 4));
        
        return "VMAX-{$daysType}-{$rand1}-{$rand2}-{$sig}";
    }

    /**
     * Verifies key format and checks signature validity
     */
    public static function verifySignature($key) {
        $parts = explode('-', $key);
        if (count($parts) !== 5 || $parts[0] !== 'VMAX') {
            return false;
        }
        
        $daysType = $parts[1];
        $rand1 = $parts[2];
        $rand2 = $parts[3];
        $sig = $parts[4];
        
        $payload = "$daysType-$rand1-$rand2";
        $expectedSig = strtoupper(substr(hash_hmac('sha256', $payload, SECRET_KEY), 0, 4));
        
        return hash_equals($expectedSig, $sig);
    }
}
