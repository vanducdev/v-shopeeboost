<?php
// VMAX Software - Authentication Controller

class AuthController {
    
    public function login() {
        if (Auth::check()) {
            redirect('/dashboard');
        }

        $error = '';
        $message = $_SESSION['flash_message'] ?? '';
        unset($_SESSION['flash_message']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $csrf = $_POST['csrf_token'] ?? '';

            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else if (empty($email) || empty($password)) {
                $error = 'Vui lòng điền đầy đủ thông tin đăng nhập.';
            } else {
                $userModel = new UserModel();
                $user = $userModel->findByEmail($email);
                if (!$user) {
                    $user = $userModel->findByUsername($email);
                }
                
                if ($user && password_verify($password, $user['password'])) {
                    if ($user['status'] === 'banned') {
                        $error = 'Tài khoản của bạn đã bị khóa bởi quản trị viên.';
                    } else if ($user['google_2fa_enabled'] == 1) {
                        $_SESSION['2fa_pending_user_id'] = $user['id'];
                        redirect('/login-2fa');
                    } else {
                        Auth::login($user['id'], $user['username'], $user['role']);
                        redirect('/dashboard');
                    }
                } else {
                    $error = 'Email hoặc mật khẩu không chính xác.';
                }
            }
        }
        
        view('auth/login', ['error' => $error, 'message' => $message]);
    }

    public function login2fa() {
        if (Auth::check()) {
            redirect('/dashboard');
        }
        if (!isset($_SESSION['2fa_pending_user_id'])) {
            redirect('/login');
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['2fa_code'] ?? '');
            $csrf = $_POST['csrf_token'] ?? '';

            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else if (empty($code)) {
                $error = 'Vui lòng điền mã 2FA gồm 6 chữ số.';
            } else {
                $userModel = new UserModel();
                $user = $userModel->findById($_SESSION['2fa_pending_user_id']);
                
                if ($user && GoogleAuthenticator::verifyCode($user['google_2fa_secret'], $code)) {
                    unset($_SESSION['2fa_pending_user_id']);
                    Auth::login($user['id'], $user['username'], $user['role']);
                    redirect('/dashboard');
                } else {
                    $error = 'Mã xác thực 2FA không chính xác hoặc đã hết hạn.';
                }
            }
        }

        view('auth/login_2fa', ['error' => $error]);
    }

    public function register() {
        if (isset($_GET['ref'])) {
            $_SESSION['referrer_code'] = sanitize($_GET['ref']);
        }
        
        if (Auth::check()) {
            redirect('/dashboard');
        }

        $error = '';
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitize($_POST['username'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');
            $referrerCode = sanitize($_POST['referrer_code'] ?? '');
            $csrf = $_POST['csrf_token'] ?? '';

            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else if (empty($username) || empty($email) || empty($password)) {
                $error = 'Vui lòng điền đầy đủ tất cả các trường dữ liệu.';
            } else if ($password !== $confirmPassword) {
                $error = 'Mật khẩu xác nhận không khớp.';
            } else {
                $userModel = new UserModel();
                if ($userModel->findByEmail($email)) {
                    $error = 'Địa chỉ Email này đã được sử dụng.';
                } else if ($userModel->findByUsername($username)) {
                    $error = 'Tên đăng nhập này đã được sử dụng.';
                } else {
                    $referrerId = null;
                    if (!empty($referrerCode)) {
                        $affModel = new AffiliateModel();
                        $refProfile = $affModel->findByReferralCode($referrerCode);
                        if ($refProfile) {
                            $referrerId = $refProfile['user_id'];
                        }
                    }
                    
                    $userId = $userModel->register($username, $email, $password, $referrerId);
                    if ($userId) {
                        Auth::login($userId, $username, 'user');
                        redirect('/welcome');
                    } else {
                        $error = 'Đăng ký tài khoản thất bại, vui lòng thử lại sau.';
                    }
                }
            }
        }

        view('auth/register', ['error' => $error, 'message' => $message]);
    }

    public function logout() {
        Auth::logout();
        redirect('/login');
    }

    public function googleAuth() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            ?>
            <!DOCTYPE html>
            <html lang="vi">
            <head>
                <link rel="icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
                <link rel="shortcut icon" type="image/x-icon" href="/image/logo-app-vmax-64x.ico">
                <link rel="icon" type="image/png" href="/image/logo-app-vmax-64x.png">
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>V-ShopeeBoost Pro - Đồng bộ liên kết</title>
                <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
                <style>
                    :root {
                        --bg: #07080e;
                        --panel: rgba(15, 23, 42, 0.45);
                        --border: rgba(99, 102, 241, 0.15);
                        --primary: #6366f1;
                        --primary-hover: #4f46e5;
                        --text: #f8fafc;
                        --text-muted: #94a3b8;
                        --green: #10b981;
                    }
                    
                    * { box-sizing: border-box; margin: 0; padding: 0; }
                    
                    body {
                        font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
                        background-color: var(--bg);
                        background-image: 
                            radial-gradient(circle at 20% 20%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                            radial-gradient(circle at 80% 80%, rgba(168, 85, 247, 0.06) 0%, transparent 40%);
                        color: var(--text);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        min-height: 100vh;
                        overflow: hidden;
                        padding: 20px;
                    }

                    .success-card {
                        background: var(--panel);
                        backdrop-filter: blur(20px);
                        -webkit-backdrop-filter: blur(20px);
                        border: 1px solid var(--border);
                        border-radius: 24px;
                        width: 100%;
                        max-width: 480px;
                        padding: 40px 30px;
                        text-align: center;
                        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.05);
                        animation: cardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                        position: relative;
                    }

                    @keyframes cardIn {
                        from { opacity: 0; transform: translateY(20px) scale(0.97); }
                        to { opacity: 1; transform: translateY(0) scale(1); }
                    }

                    .icon-wrapper {
                        position: relative;
                        width: 80px;
                        height: 80px;
                        margin: 0 auto 24px auto;
                    }

                    .icon-ring {
                        position: absolute;
                        inset: 0;
                        border-radius: 50%;
                        background: rgba(16, 185, 129, 0.1);
                        animation: pulseRing 2s cubic-bezier(0.215, 0.610, 0.355, 1) infinite;
                    }

                    @keyframes pulseRing {
                        0% { transform: scale(0.85); opacity: 0.8; }
                        50% { transform: scale(1.15); opacity: 0; }
                        100% { transform: scale(1.15); opacity: 0; }
                    }

                    .icon-circle {
                        position: absolute;
                        inset: 10px;
                        border-radius: 50%;
                        background: var(--green);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
                    }

                    .check-icon {
                        width: 28px;
                        height: 28px;
                        stroke-dasharray: 48;
                        stroke-dashoffset: 48;
                        animation: drawCheck 0.5s ease-out 0.3s forwards;
                    }

                    @keyframes drawCheck {
                        to { stroke-dashoffset: 0; }
                    }

                    h2 {
                        font-size: 22px;
                        font-weight: 800;
                        margin-bottom: 12px;
                        background: linear-gradient(135deg, #fff 30%, #a78bfa 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                    }

                    .desc {
                        font-size: 14px;
                        color: var(--text-muted);
                        line-height: 1.6;
                        margin-bottom: 8px;
                        font-weight: 500;
                    }

                    .sub-desc {
                        font-size: 12px;
                        color: var(--text-muted);
                        line-height: 1.5;
                        margin-bottom: 24px;
                        opacity: 0.85;
                    }

                    .btn {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        padding: 12px 28px;
                        background: var(--primary);
                        color: white;
                        text-decoration: none;
                        border-radius: 12px;
                        font-weight: 700;
                        font-size: 13.5px;
                        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
                        transition: all 0.2s;
                        border: none;
                        cursor: pointer;
                        width: 100%;
                    }

                    .btn:hover {
                        background: var(--primary-hover);
                        transform: translateY(-1.5px);
                        box-shadow: 0 10px 28px rgba(99, 102, 241, 0.45);
                    }

                    .btn:active {
                        transform: translateY(0);
                    }
                </style>
            </head>
            <body>
                <div class="success-card">
                    <div class="icon-wrapper">
                        <div class="icon-ring"></div>
                        <div class="icon-circle">
                            <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                    </div>
                    <h2 id="status-title">Đăng nhập thành công!</h2>
                    <p id="status-desc" class="desc">Đang tự động chuyển hướng và kết nối lại với ứng dụng V-ShopeeBoost...</p>
                    <p id="status-sub" class="sub-desc">Nếu ứng dụng không tự động mở lên, bạn hãy nhấn vào nút phía dưới:</p>
                    <a id="auth-btn" class="btn" href="#">Mở V-ShopeeBoost Pro</a>
                </div>

                <script>
                    const hash = window.location.hash || window.location.search;
                    if (hash) {
                        const deepLinkUrl = "vshopeeboost://auth" + hash;
                        document.getElementById('auth-btn').href = deepLinkUrl;
                        
                        const params = new URLSearchParams(hash.replace('#', '?'));
                        const accessToken = params.get('access_token');
                        if (accessToken) {
                            let successLogged = false;
                            const handleSuccess = (appName) => {
                                if (successLogged) return;
                                successLogged = true;
                                document.getElementById('status-title').textContent = "Đăng nhập thành công!";
                                document.getElementById('status-desc').textContent = "Đã đồng bộ kết nối với ứng dụng " + appName + " thành công.";
                                document.getElementById('status-sub').textContent = "Trình duyệt sẽ tự động đóng sau giây lát...";
                                document.getElementById('auth-btn').style.display = 'none';
                                setTimeout(() => { window.close(); }, 2000);
                            };

                            // Try V-ShopeeBoost (Port 34297)
                            fetch('http://127.0.0.1:34297/auth-callback?access_token=' + encodeURIComponent(accessToken))
                                .then(r => r.json())
                                .then(data => {
                                    if (data.ok || data.success) handleSuccess("V-ShopeeBoost Pro");
                                })
                                .catch(err => console.log("Port 34297 offline"));

                            // Try V-AutoPost / VmaxCare (Port 5000)
                            fetch('http://127.0.0.1:5000/api/auth/google/callback_token?access_token=' + encodeURIComponent(accessToken))
                                .then(r => r.json())
                                .then(data => {
                                    if (data.ok || data.success) handleSuccess("V-AutoPost Professional");
                                })
                                .catch(err => console.log("Port 5000 offline"));

                            // Fallback redirect protocol after 3 seconds if neither port responds
                            setTimeout(() => {
                                if (!successLogged) {
                                    window.location.href = deepLinkUrl;
                                    setTimeout(() => { window.close(); }, 3000);
                                }
                            }, 3000);
                        } else {
                            window.location.href = deepLinkUrl;
                            setTimeout(() => { window.close(); }, 5000);
                        }
                    } else {
                        document.getElementById('status-title').textContent = "Lỗi xác thực OAuth!";
                        document.getElementById('status-desc').textContent = "Không tìm thấy token đăng nhập.";
                        document.getElementById('status-sub').style.display = 'none';
                        document.getElementById('auth-btn').style.display = 'none';
                    }
                </script>
            </body>
            </html>
            <?php
            exit;
        }

        $idToken = $_POST['credential'] ?? '';
        if (empty($idToken)) {
            redirect('/login');
        }

        $googleUser = Auth::verifyGoogleToken($idToken);
        if ($googleUser) {
            $email = sanitize($googleUser['email']);
            $googleId = sanitize($googleUser['sub']);
            $name = sanitize($googleUser['name'] ?? '');
            $picture = sanitize($googleUser['picture'] ?? '');

            $userModel = new UserModel();
            $user = $userModel->findByGoogleId($googleId);
            
            if (!$user) {
                $user = $userModel->findByEmail($email);
                if ($user) {
                    $db = DB::getInstance()->getConnection();
                    $db->prepare("UPDATE users SET google_id = ?, avatar_url = ? WHERE id = ?")->execute([$googleId, $picture, $user['id']]);
                }
            } else {
                $db = DB::getInstance()->getConnection();
                $db->prepare("UPDATE users SET avatar_url = ? WHERE id = ?")->execute([$picture, $user['id']]);
            }

            if (!$user) {
                if (!empty($name)) {
                    $username = preg_replace('/[^\w\s]/u', '', $name);
                    $username = str_replace(' ', '_', trim($username));
                } else {
                    $username = strstr($email, '@', true);
                }
                
                if (empty($username)) {
                    $username = 'vmax_user';
                }
                
                $originalUsername = $username;
                while ($userModel->findByUsername($username)) {
                    $username = $originalUsername . '_' . rand(100, 999);
                }
                
                $password = bin2hex(random_bytes(16));
                
                $referrerId = null;
                if (!empty($_SESSION['referrer_code'])) {
                    $affModel = new AffiliateModel();
                    $refProfile = $affModel->findByReferralCode($_SESSION['referrer_code']);
                    if ($refProfile) {
                        $referrerId = $refProfile['user_id'];
                    }
                }

                $userId = $userModel->register($username, $email, $password, $referrerId);
                
                $db = DB::getInstance()->getConnection();
                $db->prepare("UPDATE users SET google_id = ?, avatar_url = ? WHERE id = ?")->execute([$googleId, $picture, $userId]);
                
                $user = $userModel->findById($userId);
            }

            if ($user) {
                if ($user['status'] === 'banned') {
                    view('auth/login', ['error' => 'Tài khoản của bạn đã bị khóa bởi quản trị viên.']);
                } else {
                    Auth::login($user['id'], $user['username'], $user['role']);
                    redirect('/dashboard');
                }
            }
        } else {
            view('auth/login', ['error' => 'Xác thực tài khoản Google thất bại.']);
        }
    }

    public function welcome() {
        if (!Auth::check()) {
            redirect('/login');
        }
        view('auth/welcome');
    }

    public function forgotPassword() {
        if (Auth::check()) {
            redirect('/dashboard');
        }

        $error = '';
        $message = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email'] ?? '');
            $csrf = $_POST['csrf_token'] ?? '';
            
            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else if (empty($email)) {
                $error = 'Vui lòng nhập địa chỉ email của bạn.';
            } else {
                $userModel = new UserModel();
                $user = $userModel->findByEmail($email);
                if (!$user) {
                    $user = $userModel->findByUsername($email);
                }
                
                if ($user) {
                    // Generate a 6-digit OTP code
                    $otp = strval(rand(100000, 999999));
                    $expiry = time() + 900; // 15 minutes validity
                    
                    $userModel->updateOtp($user['id'], $otp, $expiry);
                    
                    // Compose email body
                    $subject = "Mã xác thực khôi phục mật khẩu - VMAX Software";
                    $body = "
                    <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px;'>
                        <h2 style='color: #2563eb; text-align: center;'>Khôi Phục Mật Khẩu</h2>
                        <p>Xin chào <strong>{$user['username']}</strong>,</p>
                        <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng sử dụng mã OTP dưới đây để hoàn tất việc thiết lập mật khẩu mới:</p>
                        <div style='background-color: #f1f5f9; padding: 15px; text-align: center; border-radius: 8px; font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #1e293b; margin: 20px 0;'>
                            {$otp}
                        </div>
                        <p style='color: #64748b; font-size: 12px; text-align: center;'>Mã này có hiệu lực trong vòng 15 phút. Nếu không phải bạn yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.</p>
                    </div>";
                    
                    // Attempt sending email
                    if (Mailer::send($email, $subject, $body)) {
                        $_SESSION['reset_email'] = $email;
                        $_SESSION['flash_message'] = 'Mã xác thực OTP đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.';
                        redirect('/reset-password');
                    } else {
                        $error = 'Không thể gửi email OTP. Vui lòng kiểm tra lại cấu hình SMTP.';
                    }
                } else {
                    $error = 'Không tìm thấy tài khoản nào liên kết với địa chỉ email này.';
                }
            }
        }

        view('auth/forgot_password', ['error' => $error, 'message' => $message]);
    }

    public function resetPassword() {
        if (Auth::check()) {
            redirect('/dashboard');
        }

        $error = '';
        $message = $_SESSION['flash_message'] ?? '';
        unset($_SESSION['flash_message']);
        $email = $_SESSION['reset_email'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $otp = trim($_POST['otp'] ?? '');
            $newPassword = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');
            $csrf = $_POST['csrf_token'] ?? '';

            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else if (empty($email)) {
                $error = 'Phiên làm việc đã hết hạn. Vui lòng yêu cầu lại OTP.';
            } else if (empty($otp) || empty($newPassword) || empty($confirmPassword)) {
                $error = 'Vui lòng điền đầy đủ tất cả các thông tin.';
            } else if ($newPassword !== $confirmPassword) {
                $error = 'Mật khẩu xác nhận không khớp.';
            } else {
                $userModel = new UserModel();
                $user = $userModel->findByEmail($email);
                if (!$user) {
                    $user = $userModel->findByUsername($email);
                }

                if ($user) {
                    if ($user['otp_code'] === $otp && $user['otp_expires_at'] >= time()) {
                        // Reset OTP and set new password
                        $db = DB::getInstance()->getConnection();
                        $hashPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                        $db->prepare("UPDATE users SET password = ?, otp_code = NULL, otp_expires_at = NULL WHERE id = ?")
                           ->execute([$hashPassword, $user['id']]);
                        
                        unset($_SESSION['reset_email']);
                        $_SESSION['flash_message'] = 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập lại.';
                        redirect('/login');
                    } else {
                        $error = 'Mã OTP không hợp lệ hoặc đã hết hạn sử dụng.';
                    }
                } else {
                    $error = 'Không tìm thấy thông tin tài khoản.';
                }
            }
        }

        view('auth/reset_password', ['email' => $email, 'error' => $error, 'message' => $message]);
    }
}
