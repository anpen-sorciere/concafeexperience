<?php
// bootstrap.php - Con-Cafe Princess Experience ブートストラップ
// システムの初期化と設定を一元管理

class Bootstrap {
    
    private static $initialized = false;
    private static $config = [];
    private static $paths = [];
    
    // システムの初期化
    public static function init() {
        if (self::$initialized) {
            return;
        }
        
        // エラー表示設定
        self::setupErrorHandling();
        
        // パスの自動検出
        self::detectPaths();
        
        // 設定の読み込み
        self::loadConfig();
        
        // ディレクトリの確認・作成
        self::ensureDirectories();
        
        // セッションの初期化
        self::initSession();
        
        // セキュリティヘッダーの設定
        self::setSecurityHeaders();
        
        self::$initialized = true;
    }
    
    // エラーハンドリングの設定
    private static function setupErrorHandling() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        ini_set('error_log', self::getLogPath() . '/error.log');
    }
    
    // パスの自動検出
    private static function detectPaths() {
        // スクリプトの実行パスを取得
        $scriptPath = dirname($_SERVER['SCRIPT_FILENAME']);
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        
        // プロジェクトルートの検出
        $projectRoot = $scriptPath;
        if (strpos($scriptPath, '/api/') !== false) {
            $projectRoot = dirname($scriptPath);
        }
        
        self::$paths = [
            'project_root' => $projectRoot,
            'script_path' => $scriptPath,
            'document_root' => $documentRoot,
            'uploads' => $projectRoot . '/uploads',
            'logs' => $projectRoot . '/logs',
            'cache' => $projectRoot . '/cache',
            'tmp' => $projectRoot . '/tmp',
            'data' => $projectRoot . '/data',
            'assets' => $projectRoot . '/assets',
            'api' => $projectRoot . '/api'
        ];
    }
    
    // 設定の読み込み
    private static function loadConfig() {
        // データベース設定
        self::$config['database'] = [
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
        self::$config['app'] = [
            'name' => 'Con-Cafe Princess Experience',
            'version' => '1.0.0',
            'environment' => 'production',
            'timezone' => 'Asia/Tokyo',
            'locale' => 'ja',
            'currency' => 'JPY'
        ];
        
        // 営業時間設定
        self::$config['business_hours'] = [
            'start' => '11:00',
            'end' => '21:00',
            'last_reception' => '19:00',
            'closed_days' => ['Tuesday'],
            'timezone' => 'Asia/Tokyo'
        ];
        
        // プラン設定
        self::$config['plans'] = [
            'light' => [
                'name_ja' => 'ライトプラン',
                'name_en' => 'Light Plan',
                'name_zh_cn' => '轻量套餐',
                'duration_min' => 45,
                'duration_max' => 45,
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
                'duration_min' => 90,
                'duration_max' => 90,
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
                'duration_min' => 120,
                'duration_max' => 120,
                'price_min' => 30000,
                'price_max' => 35000,
                'description_ja' => 'プレミアム限定の特別な衣装、フルメイクとヘアセット、店内＋オタロードでの野外撮影、プロが編集したハイライト動画、記念チェキ3枚、フォトスタンドまたはフォトブック郵送',
                'description_en' => 'Premium limited special outfit, full makeup and hair styling, indoor + outdoor shooting in Otaroad, professional edited highlight video, 3 commemorative cheki, photo stand or photo book delivery',
                'description_zh_cn' => '高级限定特别服装、全套化妆和发型设计、店内+御宅路户外拍摄、专业编辑精彩视频、纪念拍立得3张、照片支架或相册邮寄'
            ]
        ];
        
        // セキュリティ設定
        self::$config['security'] = [
            'rate_limit' => [
                'booking' => 5,
                'contact' => 3
            ],
            'csrf_protection' => true,
            'session_timeout' => 3600
        ];
        
        // ファイルアップロード設定
        self::$config['upload'] = [
            'max_file_size' => 5 * 1024 * 1024, // 5MB
            'allowed_types' => ['jpg', 'jpeg', 'png', 'gif'],
            'upload_path' => self::$paths['uploads'],
            'temp_path' => self::$paths['tmp']
        ];
        
        // ログ設定
        self::$config['log'] = [
            'enabled' => true,
            'level' => 'INFO',
            'path' => self::$paths['logs'],
            'max_files' => 30,
            'max_size' => 10 * 1024 * 1024 // 10MB
        ];
        
        // キャッシュ設定
        self::$config['cache'] = [
            'enabled' => true,
            'driver' => 'file',
            'path' => self::$paths['cache'],
            'ttl' => 3600 // 1時間
        ];
    }
    
    // ディレクトリの確認・作成
    private static function ensureDirectories() {
        $requiredDirs = ['uploads', 'logs', 'cache', 'tmp', 'data'];
        
        foreach ($requiredDirs as $dir) {
            $path = self::$paths[$dir];
            if (!is_dir($path)) {
                if (!mkdir($path, 0755, true)) {
                    throw new Exception("ディレクトリの作成に失敗しました: {$path}");
                }
            }
            
            // 権限の確認
            if (!is_writable($path)) {
                chmod($path, 0755);
            }
        }
    }
    
    // セッションの初期化
    private static function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // セッション保存パスの設定
            $sessionPath = self::$paths['tmp'] . '/sessions';
            if (!is_dir($sessionPath)) {
                mkdir($sessionPath, 0755, true);
            }
            ini_set('session.save_path', $sessionPath);
            
            // セッション名の設定
            session_name('CONCAFE_SESSION');
            
            // セッションクッキーの設定
            $cookieParams = session_get_cookie_params();
            session_set_cookie_params(
                $cookieParams['lifetime'],
                '/concafeexp/',
                'purplelion51.sakura.ne.jp',
                true, // secure
                true  // httponly
            );
            
            // セキュリティ設定
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', 1);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', 'Strict');
            
            session_start();
        }
    }
    
    // セキュリティヘッダーの設定
    private static function setSecurityHeaders() {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // CORS設定（API用）
        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
            header('Access-Control-Allow-Origin: https://purplelion51.sakura.ne.jp');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            header('Access-Control-Allow-Credentials: true');
        }
    }
    
    // データベース接続の取得
    public static function getDatabase() {
        $config = self::$config['database'];
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
        try {
            $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception("データベース接続エラー: " . $e->getMessage());
        }
    }
    
    // 設定値の取得
    public static function getConfig($key = null) {
        if ($key === null) {
            return self::$config;
        }
        
        $keys = explode('.', $key);
        $value = self::$config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return null;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    // パスの取得
    public static function getPath($key) {
        return self::$paths[$key] ?? null;
    }
    
    // ログパスの取得
    public static function getLogPath() {
        return self::$paths['logs'];
    }
    
    // アップロードパスの取得
    public static function getUploadPath() {
        return self::$paths['uploads'];
    }
    
    // キャッシュパスの取得
    public static function getCachePath() {
        return self::$paths['cache'];
    }
    
    // 一時ファイルパスの取得
    public static function getTmpPath() {
        return self::$paths['tmp'];
    }
    
    // プロジェクトルートパスの取得
    public static function getProjectRoot() {
        return self::$paths['project_root'];
    }
    
    // システム情報の取得
    public static function getSystemInfo() {
        return [
            'initialized' => self::$initialized,
            'paths' => self::$paths,
            'config_keys' => array_keys(self::$config),
            'php_version' => PHP_VERSION,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size')
        ];
    }
    
    // ログの記録
    public static function log($level, $message, $context = []) {
        if (!self::$config['log']['enabled']) {
            return;
        }
        
        $logPath = self::$paths['logs'];
        $logFile = $logPath . '/' . date('Y-m-d') . '.log';
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    // キャッシュの操作
    public static function cache($key, $value = null, $ttl = null) {
        if (!self::$config['cache']['enabled']) {
            return $value;
        }
        
        $cachePath = self::$paths['cache'];
        $cacheFile = $cachePath . '/' . md5($key) . '.cache';
        
        if ($value === null) {
            // 取得
            if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < ($ttl ?? self::$config['cache']['ttl'])) {
                return unserialize(file_get_contents($cacheFile));
            }
            return null;
        } else {
            // 保存
            file_put_contents($cacheFile, serialize($value), LOCK_EX);
            return $value;
        }
    }
}

// 自動初期化
Bootstrap::init();
?>
