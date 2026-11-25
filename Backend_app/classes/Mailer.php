<?php
// VMAX Software - Pure PHP SMTP Mailer Client

class Mailer {
    
    public static function send($to, $subject, $body) {
        $db = DB::getInstance();
        $stmt = $db->query("SELECT * FROM settings");
        $settings = [];
        foreach ($stmt->fetchAll() as $row) {
            $settings[$row['key']] = $row['value'];
        }

        $host = $settings['smtp_host'] ?? '';
        $port = intval($settings['smtp_port'] ?? 465);
        $user = $settings['smtp_user'] ?? '';
        $pass = $settings['smtp_pass'] ?? '';
        $from = $settings['smtp_from'] ?? $user;

        if (empty($host) || empty($user) || empty($pass)) {
            // Fallback to PHP native mail() if SMTP settings are missing
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: <$from>\r\n";
            return @mail($to, $subject, $body, $headers);
        }

        // Determine protocol
        $encryption = ($port === 465) ? 'ssl://' : '';
        $socketHost = $encryption . $host;

        $socket = @fsockopen($socketHost, $port, $errno, $errstr, 15);
        if (!$socket) {
            return false;
        }

        $getResponse = function($socket) {
            $data = '';
            while ($str = fgets($socket, 515)) {
                $data .= $str;
                if (substr($str, 3, 1) === ' ') {
                    break;
                }
            }
            return $data;
        };

        $sendCommand = function($socket, $cmd) use ($getResponse) {
            fputs($socket, $cmd . "\r\n");
            return $getResponse($socket);
        };

        // Welcome response
        $getResponse($socket);

        // EHLO
        $sendCommand($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));

        // Authenticate
        $sendCommand($socket, "AUTH LOGIN");
        $sendCommand($socket, base64_encode($user));
        $sendCommand($socket, base64_encode($pass));

        // Mail transaction
        $sendCommand($socket, "MAIL FROM: <$from>");
        $sendCommand($socket, "RCPT TO: <$to>");

        // Send Data
        $sendCommand($socket, "DATA");

        // Construct headers & body
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "To: <$to>\r\n";
        $headers .= "From: " . ($settings['site_name'] ?? 'VMAX Software') . " <$from>\r\n";
        $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
        $headers .= "Date: " . date('r') . "\r\n";
        $headers .= "Message-ID: <" . time() . "-" . uniqid() . "@" . ($_SERVER['SERVER_NAME'] ?? 'localhost') . ">\r\n";
        $headers .= "\r\n";

        // SMTP Data ending with <CRLF>.<CRLF>
        $emailContent = $headers . $body . "\r\n.";
        $response = $sendCommand($socket, $emailContent);

        // Quit
        $sendCommand($socket, "QUIT");
        fclose($socket);

        return (strpos($response, '250') === 0 || strpos($response, '250') !== false);
    }
}
