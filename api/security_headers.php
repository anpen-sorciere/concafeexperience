<?php
// security_headers.php - セキュリティヘッダー設定
// Con-Cafe Princess Experience

class SecurityHeaders {
    
    // セキュリティヘッダーを設定
    public static function setHeaders() {
        // X-Content-Type-Options
        header('X-Content-Type-Options: nosniff');
        
        // X-Frame-Options
        header('X-Frame-Options: DENY');
        
        // X-XSS-Protection
        header('X-XSS-Protection: 1; mode=block');
        
        // Referrer-Policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Content-Security-Policy（必要に応じて）
        // header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' https://cdnjs.cloudflare.com; style-src \'self\' \'unsafe-inline\' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src \'self\' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src \'self\' data: https:;');
    }
    
    // CORS設定
    public static function setCORS() {
        header('Access-Control-Allow-Origin: https://purplelion51.sakura.ne.jp');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Max-Age: 86400');
    }
    
    // キャッシュ制御
    public static function setCacheHeaders($type = 'no-cache') {
        switch ($type) {
            case 'no-cache':
                header('Cache-Control: no-cache, no-store, must-revalidate');
                header('Pragma: no-cache');
                header('Expires: 0');
                break;
            case 'static':
                header('Cache-Control: public, max-age=2592000'); // 30日
                break;
            case 'api':
                header('Cache-Control: no-cache, must-revalidate');
                break;
        }
    }
    
    // ファイルタイプの検証
    public static function validateFileType($filename, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']) {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, $allowedTypes);
    }
    
    // ファイルサイズの検証
    public static function validateFileSize($file, $maxSize = 5242880) { // 5MB
        return $file['size'] <= $maxSize;
    }
    
    // 入力値のサニタイズ
    public static function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    // SQLインジェクション対策
    public static function validateSQLInput($input) {
        // 危険な文字列をチェック
        $dangerous = ['DROP', 'DELETE', 'INSERT', 'UPDATE', 'SELECT', 'UNION', 'SCRIPT'];
        $upperInput = strtoupper($input);
        
        foreach ($dangerous as $danger) {
            if (strpos($upperInput, $danger) !== false) {
                return false;
            }
        }
        
        return true;
    }
    
    // レート制限チェック
    public static function checkRateLimit($ip, $action = 'general', $limit = 100, $window = 3600) {
        $cacheFile = sys_get_temp_dir() . '/rate_limit_' . md5($ip . $action) . '.json';
        
        if (file_exists($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true);
            
            if ($data['timestamp'] > (time() - $window)) {
                if ($data['count'] >= $limit) {
                    return false; // 制限に達している
                }
                $data['count']++;
            } else {
                $data = ['count' => 1, 'timestamp' => time()];
            }
        } else {
            $data = ['count' => 1, 'timestamp' => time()];
        }
        
        file_put_contents($cacheFile, json_encode($data));
        return true;
    }
    
    // CSRFトークンの生成
    public static function generateCSRFToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }
    
    // CSRFトークンの検証
    public static function validateCSRFToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    // ログイン試行の制限
    public static function checkLoginAttempts($ip, $maxAttempts = 5, $lockoutTime = 900) {
        $attemptFile = sys_get_temp_dir() . '/login_attempts_' . md5($ip) . '.json';
        
        if (file_exists($attemptFile)) {
            $data = json_decode(file_get_contents($attemptFile), true);
            
            if ($data['timestamp'] > (time() - $lockoutTime)) {
                if ($data['attempts'] >= $maxAttempts) {
                    return false; // ロックアウト中
                }
            } else {
                $data = ['attempts' => 0, 'timestamp' => time()];
            }
        } else {
            $data = ['attempts' => 0, 'timestamp' => time()];
        }
        
        $data['attempts']++;
        file_put_contents($attemptFile, json_encode($data));
        
        return $data['attempts'] < $maxAttempts;
    }
    
    // セッション設定
    public static function configureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // セッション設定
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', 1);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', 'Strict');
            
            session_start();
        }
    }
    
    // エラーログの記録
    public static function logSecurityEvent($event, $details = '') {
        $logFile = '/home/purplelion51/www/concafeexp/logs/security.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        $logEntry = "[{$timestamp}] IP: {$ip} | Event: {$event} | Details: {$details} | User-Agent: {$userAgent}\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    // 初期化
    public static function init() {
        self::setHeaders();
        self::configureSession();
        
        // レート制限チェック
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if (!self::checkRateLimit($ip)) {
            http_response_code(429);
            self::logSecurityEvent('RATE_LIMIT_EXCEEDED', "IP: {$ip}");
            die('Too Many Requests');
        }
    }
}

// 自動初期化
SecurityHeaders::init();
?>
