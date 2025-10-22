<?php
// session_manager.php - プロジェクト固有セッション管理
// Con-Cafe Princess Experience

class SessionManager {
    
    private static $initialized = false;
    private static $sessionName = 'CONCAFE_SESSION';
    private static $sessionPath = './sessions'; // 相対パスに変更
    
    // セッションの初期化
    public static function init() {
        if (self::$initialized) {
            return;
        }
        
        // セッション保存ディレクトリの作成
        if (!is_dir(self::$sessionPath)) {
            mkdir(self::$sessionPath, 0755, true);
        }
        
        // セッション設定
        ini_set('session.name', self::$sessionName);
        ini_set('session.save_path', self::$sessionPath);
        ini_set('session.cookie_lifetime', 0); // ブラウザ終了まで
        ini_set('session.cookie_path', '/concafeexp/'); // プロジェクト固有パス
        ini_set('session.cookie_domain', 'purplelion51.sakura.ne.jp');
        ini_set('session.cookie_secure', 1); // HTTPS必須
        ini_set('session.cookie_httponly', 1); // JavaScriptアクセス禁止
        ini_set('session.cookie_samesite', 'Strict'); // CSRF対策
        ini_set('session.use_strict_mode', 1); // セッション固定攻撃対策
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.gc_probability', 1);
        ini_set('session.gc_divisor', 100);
        ini_set('session.gc_maxlifetime', 3600); // 1時間
        
        // セッション開始
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        self::$initialized = true;
    }
    
    // セッション値の設定
    public static function set($key, $value) {
        self::init();
        $_SESSION[$key] = $value;
    }
    
    // セッション値の取得
    public static function get($key, $default = null) {
        self::init();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    
    // セッション値の削除
    public static function remove($key) {
        self::init();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    // セッションの破棄
    public static function destroy() {
        self::init();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
    
    // CSRFトークンの生成
    public static function generateCSRFToken() {
        self::init();
        $token = bin2hex(random_bytes(32));
        self::set('csrf_token', $token);
        return $token;
    }
    
    // CSRFトークンの検証
    public static function validateCSRFToken($token) {
        self::init();
        $sessionToken = self::get('csrf_token');
        return $sessionToken && hash_equals($sessionToken, $token);
    }
    
    // セッション情報の取得
    public static function getInfo() {
        self::init();
        return [
            'session_id' => session_id(),
            'session_name' => session_name(),
            'session_path' => session_save_path(),
            'session_status' => session_status(),
            'session_data' => $_SESSION
        ];
    }
    
    // セッションのクリーンアップ
    public static function cleanup() {
        $sessionFiles = glob(self::$sessionPath . '/sess_*');
        $now = time();
        $maxLifetime = ini_get('session.gc_maxlifetime');
        
        foreach ($sessionFiles as $file) {
            if (filemtime($file) < ($now - $maxLifetime)) {
                unlink($file);
            }
        }
    }
    
    // セッション設定の確認
    public static function checkConfig() {
        $config = [
            'session.name' => ini_get('session.name'),
            'session.save_path' => ini_get('session.save_path'),
            'session.cookie_path' => ini_get('session.cookie_path'),
            'session.cookie_domain' => ini_get('session.cookie_domain'),
            'session.cookie_secure' => ini_get('session.cookie_secure'),
            'session.cookie_httponly' => ini_get('session.cookie_httponly'),
            'session.cookie_samesite' => ini_get('session.cookie_samesite'),
            'session.use_strict_mode' => ini_get('session.use_strict_mode')
        ];
        
        return $config;
    }
}
?>
