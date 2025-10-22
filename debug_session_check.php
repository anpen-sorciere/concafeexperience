<?php
// debug_session_check.php - セッション設定確認スクリプト
// Con-Cafe Princess Experience
// 注意: このファイルはデバッグ用です。使用後は削除してください。

echo "Con-Cafe Princess Experience - セッション設定確認\n";
echo "==============================================\n\n";

// エラー表示を有効にする
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "1. 現在のセッション設定\n";
echo "セッション状態: " . session_status() . " (" . (session_status() === PHP_SESSION_ACTIVE ? 'ACTIVE' : 'INACTIVE') . ")\n";
echo "セッションID: " . (session_id() ?: '未設定') . "\n";
echo "セッション名: " . session_name() . "\n";
echo "セッション保存パス: " . session_save_path() . "\n";
echo "セッション有効期限: " . ini_get('session.gc_maxlifetime') . " 秒\n\n";

echo "2. セッション設定の詳細\n";
$sessionSettings = [
    'session.cookie_lifetime',
    'session.cookie_path',
    'session.cookie_domain',
    'session.cookie_secure',
    'session.cookie_httponly',
    'session.cookie_samesite',
    'session.use_strict_mode',
    'session.use_cookies',
    'session.use_only_cookies',
    'session.gc_probability',
    'session.gc_divisor',
    'session.gc_maxlifetime'
];

foreach ($sessionSettings as $setting) {
    echo "   {$setting}: " . ini_get($setting) . "\n";
}

echo "\n3. セッションクッキーの確認\n";
if (isset($_COOKIE)) {
    echo "クッキー数: " . count($_COOKIE) . "\n";
    foreach ($_COOKIE as $name => $value) {
        if (strpos($name, 'PHPSESSID') !== false || strpos($name, 'session') !== false) {
            echo "   セッション関連クッキー: {$name} = " . substr($value, 0, 20) . "...\n";
        }
    }
} else {
    echo "クッキーが設定されていません\n";
}

echo "\n4. セッションデータの確認\n";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "セッションデータ:\n";
    if (empty($_SESSION)) {
        echo "   セッションデータは空です\n";
    } else {
        foreach ($_SESSION as $key => $value) {
            echo "   {$key}: " . (is_string($value) ? substr($value, 0, 50) : gettype($value)) . "\n";
        }
    }
} else {
    echo "セッションが開始されていません\n";
}

echo "\n5. プロジェクト固有セッション設定のテスト\n";
try {
    // プロジェクト固有のセッション設定
    $projectSessionName = 'CONCAFE_SESSION';
    $projectSessionPath = '/home/purplelion51/www/concafeexp/sessions';
    
    echo "推奨セッション名: {$projectSessionName}\n";
    echo "推奨セッション保存パス: {$projectSessionPath}\n";
    
    // セッション保存ディレクトリの確認
    if (is_dir($projectSessionPath)) {
        echo "✅ セッション保存ディレクトリが存在します\n";
        echo "   権限: " . substr(sprintf('%o', fileperms($projectSessionPath)), -4) . "\n";
    } else {
        echo "⚠️ セッション保存ディレクトリが存在しません\n";
        echo "   作成が必要: {$projectSessionPath}\n";
    }
    
} catch (Exception $e) {
    echo "❌ セッション設定テストエラー: " . $e->getMessage() . "\n";
}

echo "\n6. 他のプロジェクトとの競合チェック\n";
$commonSessionPaths = [
    '/tmp',
    '/var/lib/php/sessions',
    '/var/tmp',
    sys_get_temp_dir()
];

echo "一般的なセッション保存パス:\n";
foreach ($commonSessionPaths as $path) {
    if (is_dir($path)) {
        echo "   {$path}: 存在 (権限: " . substr(sprintf('%o', fileperms($path)), -4) . ")\n";
        
        // セッションファイルの確認
        $sessionFiles = glob($path . '/sess_*');
        if ($sessionFiles) {
            echo "     セッションファイル数: " . count($sessionFiles) . "\n";
        }
    } else {
        echo "   {$path}: 存在しません\n";
    }
}

echo "\n==============================================\n";
echo "セッション設定確認完了\n";
echo "⚠️  このデバッグ用スクリプトは必ず削除してください！\n";
?>
