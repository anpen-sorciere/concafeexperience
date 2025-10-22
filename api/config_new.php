<?php
// api/config.php - Con-Cafe Princess Experience 設定ファイル
// ブートストラップを使用した設定管理

// ブートストラップを読み込み
require_once __DIR__ . '/../bootstrap.php';

class Config {
    
    // データベース接続の取得
    public static function getDatabase() {
        return Bootstrap::getDatabase();
    }
    
    // 設定値の取得
    public static function get($key = null) {
        return Bootstrap::getConfig($key);
    }
    
    // パスの取得
    public static function getPath($key) {
        return Bootstrap::getPath($key);
    }
    
    // ログの記録
    public static function log($level, $message, $context = []) {
        Bootstrap::log($level, $message, $context);
    }
    
    // キャッシュの操作
    public static function cache($key, $value = null, $ttl = null) {
        return Bootstrap::cache($key, $value, $ttl);
    }
    
    // システム情報の取得
    public static function getSystemInfo() {
        return Bootstrap::getSystemInfo();
    }
}
?>
