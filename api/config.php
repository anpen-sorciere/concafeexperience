<?php
// config.php - 本番環境用設定ファイル
// Con-Cafe Princess Experience

// セキュリティヘッダーを設定
if (file_exists(__DIR__ . '/security_headers.php')) {
    require_once __DIR__ . '/security_headers.php';
}

class Config {
    // データベース設定
    const DB_CONFIG = [
        'host' => 'mysql2103.db.sakura.ne.jp',
        'dbname' => 'purplelion51_concafe_exp',
        'username' => 'purplelion51',
        'password' => '-6r_am73',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ];
    
    // アプリケーション設定
    const APP_CONFIG = [
        'name' => 'Con-Cafe Princess Experience',
        'version' => '1.0.0',
        'environment' => 'production', // development, staging, production
        'timezone' => 'Asia/Tokyo',
        'locale' => 'ja',
        'currency' => 'JPY'
    ];
    
    // パス設定
    const PATH_CONFIG = [
        'base_url' => 'https://purplelion51.sakura.ne.jp/concafeexp/',
        'assets_url' => 'https://purplelion51.sakura.ne.jp/concafeexp/assets/',
        'api_url' => 'https://purplelion51.sakura.ne.jp/concafeexp/api/',
        'upload_path' => '/home/purplelion51/www/concafeexp/uploads/',
        'log_path' => '/home/purplelion51/www/concafeexp/logs/',
        'cache_path' => '/home/purplelion51/www/concafeexp/cache/',
        'tmp_path' => '/home/purplelion51/www/concafeexp/tmp/'
    ];
    
    // 営業時間設定
    const BUSINESS_HOURS = [
        'start' => '11:00',
        'end' => '21:00',
        'last_reception' => '19:00',
        'closed_days' => ['Tuesday'], // 火曜日定休
        'timezone' => 'Asia/Tokyo'
    ];
    
    // プラン設定
    const PLANS = [
        'light' => [
            'name_ja' => 'ライトプラン',
            'name_en' => 'Light Plan',
            'name_zh_cn' => '轻量套餐',
            'duration' => 45,
            'price_min' => 7000,
            'price_max' => 10000,
            'description_ja' => 'スタンダードなメイド服の着用、プロカメラマンによる店内フォトブース撮影、記念チェキ1枚',
            'description_en' => 'Standard maid outfit, professional photographer booth shooting, 1 commemorative cheki',
            'description_zh_cn' => '标准女仆装、专业摄影师店内摄影棚拍摄、纪念拍立得1张'
        ],
        'basic' => [
            'name_ja' => 'ベーシックプラン',
            'name_en' => 'Basic Plan',
            'name_zh_cn' => '基础套餐',
            'duration' => 90,
            'price_min' => 15000,
            'price_max' => 20000,
            'description_ja' => '豪華な制服やアクセサリーの選択、プロによるポイントメイクとヘアセット、店内での本格的な撮影、萌え体験を動画で撮影、記念チェキ2枚',
            'description_en' => 'Luxury uniform and accessory selection, professional point makeup and hair styling, professional shooting in-store, moe experience video shooting, 2 commemorative cheki',
            'description_zh_cn' => '豪华制服和配饰选择、专业重点化妆和发型设计、店内专业拍摄、萌系体验视频拍摄、纪念拍立得2张'
        ],
        'premium' => [
            'name_ja' => 'プレミアムプラン',
            'name_en' => 'Premium Plan',
            'name_zh_cn' => '高级套餐',
            'duration' => 120,
            'price_min' => 30000,
            'price_max' => 35000,
            'description_ja' => 'プレミアム限定の特別な衣装、フルメイクとヘアセット、店内＋オタロードでの野外撮影、プロが編集したハイライト動画、記念チェキ3枚、フォトスタンドまたはフォトブック郵送',
            'description_en' => 'Premium limited special outfit, full makeup and hair styling, indoor + outdoor shooting in Otaroad, professional edited highlight video, 3 commemorative cheki, photo stand or photo book delivery',
            'description_zh_cn' => '高级限定特别服装、全套化妆和发型设计、店内+御宅路户外拍摄、专业编辑精彩视频、纪念拍立得3张、照片支架或相册邮寄'
        ]
    ];
    
    // メール設定
    const MAIL_CONFIG = [
        'smtp_host' => 'your_smtp_host', // 実際のSMTPホストに変更
        'smtp_port' => 587,
        'smtp_username' => 'your_smtp_username', // 実際のSMTPユーザー名に変更
        'smtp_password' => 'your_smtp_password', // 実際のSMTPパスワードに変更
        'from_email' => 'info@con-cafe-princess.com',
        'from_name' => 'Con-Cafe Princess Experience',
        'reply_to' => 'info@con-cafe-princess.com'
    ];
    
    // セキュリティ設定
    const SECURITY_CONFIG = [
        'session_lifetime' => 3600, // 1時間
        'max_login_attempts' => 5,
        'lockout_duration' => 900, // 15分
        'password_min_length' => 8,
        'csrf_token_lifetime' => 3600,
        'rate_limit' => [
            'booking' => 5, // 1時間あたり5回まで
            'contact' => 3  // 1時間あたり3回まで
        ]
    ];
    
    // ファイルアップロード設定
    const UPLOAD_CONFIG = [
        'max_file_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif'],
        'upload_path' => '/home/purplelion51/www/concafeexp/uploads/',
        'temp_path' => '/home/purplelion51/www/concafeexp/tmp/'
    ];
    
    // ログ設定
    const LOG_CONFIG = [
        'enabled' => true,
        'level' => 'INFO', // DEBUG, INFO, WARNING, ERROR
        'path' => '/home/purplelion51/www/concafeexp/logs/',
        'max_files' => 30,
        'max_size' => 10 * 1024 * 1024 // 10MB
    ];
    
    // キャッシュ設定
    const CACHE_CONFIG = [
        'enabled' => true,
        'driver' => 'file', // file, redis, memcached
        'path' => '/home/purplelion51/www/concafeexp/cache/',
        'ttl' => 3600 // 1時間
    ];
    
    // API設定
    const API_CONFIG = [
        'rate_limit' => 100, // 1時間あたり100リクエスト
        'timeout' => 30,
        'version' => 'v1'
    ];
    
    // 外部サービス設定
    const EXTERNAL_SERVICES = [
        'google_maps_api_key' => 'your_google_maps_api_key',
        'instagram_api_key' => 'your_instagram_api_key',
        'tiktok_api_key' => 'your_tiktok_api_key',
        'youtube_api_key' => 'your_youtube_api_key'
    ];
    
    // 通知設定
    const NOTIFICATION_CONFIG = [
        'email_notifications' => true,
        'sms_notifications' => false,
        'push_notifications' => false,
        'admin_notifications' => true,
        'admin_email' => 'admin@con-cafe-princess.com'
    ];
    
    // 予約システム設定
    const BOOKING_CONFIG = [
        'max_participants_per_booking' => 6,
        'advance_booking_days' => 30,
        'cancellation_hours' => 24,
        'confirmation_hours' => 2,
        'auto_confirm' => false,
        'require_deposit' => false,
        'deposit_percentage' => 0
    ];
    
    // 決済設定
    const PAYMENT_CONFIG = [
        'enabled' => true,
        'methods' => ['cash', 'credit_card', 'bank_transfer'],
        'currency' => 'JPY',
        'tax_rate' => 0.10, // 10%
        'processing_fee' => 0.03 // 3%
    ];
    
    // 多言語設定
    const LANGUAGE_CONFIG = [
        'default' => 'ja',
        'supported' => ['ja', 'en', 'zh-cn'],
        'fallback' => 'ja',
        'auto_detect' => true
    ];
    
    // デバッグ設定
    const DEBUG_CONFIG = [
        'enabled' => false, // 本番環境ではfalse
        'show_errors' => false,
        'log_queries' => false,
        'profiler' => false
    ];
    
    // データベース接続を取得
    public static function getDatabase() {
        static $db = null;
        
        if ($db === null) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;charset=%s',
                    self::DB_CONFIG['host'],
                    self::DB_CONFIG['dbname'],
                    self::DB_CONFIG['charset']
                );
                
                $db = new PDO(
                    $dsn,
                    self::DB_CONFIG['username'],
                    self::DB_CONFIG['password'],
                    self::DB_CONFIG['options']
                );
                
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                throw new Exception("データベース接続に失敗しました");
            }
        }
        
        return $db;
    }
    
    // 設定値を取得
    public static function get($key, $default = null) {
        $keys = explode('.', $key);
        $value = self::$$keys[0];
        
        for ($i = 1; $i < count($keys); $i++) {
            if (isset($value[$keys[$i]])) {
                $value = $value[$keys[$i]];
            } else {
                return $default;
            }
        }
        
        return $value;
    }
    
    // 環境チェック
    public static function isProduction() {
        return self::APP_CONFIG['environment'] === 'production';
    }
    
    public static function isDevelopment() {
        return self::APP_CONFIG['environment'] === 'development';
    }
    
    public static function isStaging() {
        return self::APP_CONFIG['environment'] === 'staging';
    }
    
    // タイムゾーン設定
    public static function setTimezone() {
        date_default_timezone_set(self::APP_CONFIG['timezone']);
    }
    
    // エラーレポート設定
    public static function setErrorReporting() {
        if (self::isProduction()) {
            error_reporting(0);
            ini_set('display_errors', 0);
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
    }
    
    // 初期化
    public static function init() {
        self::setTimezone();
        self::setErrorReporting();
        
        // セッション設定
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // ログ設定
        if (self::LOG_CONFIG['enabled']) {
            self::setupLogging();
        }
    }
    
    // ログ設定
    private static function setupLogging() {
        $logPath = self::LOG_CONFIG['path'];
        
        if (!is_dir($logPath)) {
            mkdir($logPath, 0755, true);
        }
        
        // ログローテーション
        self::rotateLogs($logPath);
    }
    
    // ログローテーション
    private static function rotateLogs($logPath) {
        $logFile = $logPath . 'app.log';
        
        if (file_exists($logFile) && filesize($logFile) > self::LOG_CONFIG['max_size']) {
            $timestamp = date('Y-m-d_H-i-s');
            rename($logFile, $logPath . "app_{$timestamp}.log");
        }
    }
}

// 初期化実行
Config::init();
?>
