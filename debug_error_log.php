<?php
// debug_error_log.php - エラーログ確認スクリプト
// Con-Cafe Princess Experience
// 注意: このファイルはデバッグ用です。使用後は削除してください。

echo "Con-Cafe Princess Experience - エラーログ確認\n";
echo "============================================\n\n";

// エラー表示を有効にする
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "1. PHP設定確認\n";
echo "PHP バージョン: " . phpversion() . "\n";
echo "エラー表示: " . (ini_get('display_errors') ? 'ON' : 'OFF') . "\n";
echo "ログエラー: " . (ini_get('log_errors') ? 'ON' : 'OFF') . "\n";
echo "エラーログファイル: " . ini_get('error_log') . "\n\n";

echo "2. ファイル存在確認\n";
$files = [
    'deploy.php',
    'api/config.php',
    'data/database_schema.sql',
    'api/security_headers.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ {$file}: 存在\n";
        echo "   権限: " . substr(sprintf('%o', fileperms($file)), -4) . "\n";
    } else {
        echo "❌ {$file}: 存在しません\n";
    }
}

echo "\n3. ディレクトリ存在確認\n";
$dirs = [
    'api',
    'data',
    'uploads',
    'logs',
    'cache',
    'tmp'
];

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ {$dir}: 存在 (権限: " . substr(sprintf('%o', fileperms($dir)), -4) . ")\n";
    } else {
        echo "❌ {$dir}: 存在しません\n";
    }
}

echo "\n4. config.php の読み込みテスト\n";
try {
    if (file_exists('api/config.php')) {
        require_once 'api/config.php';
        echo "✅ config.php の読み込み成功\n";
        
        if (class_exists('Config')) {
            echo "✅ Config クラスが存在します\n";
            
            try {
                $db = Config::getDatabase();
                echo "✅ データベース接続成功\n";
            } catch (Exception $e) {
                echo "❌ データベース接続エラー: " . $e->getMessage() . "\n";
            }
        } else {
            echo "❌ Config クラスが存在しません\n";
        }
    } else {
        echo "❌ config.php が存在しません\n";
    }
} catch (Exception $e) {
    echo "❌ config.php 読み込みエラー: " . $e->getMessage() . "\n";
}

echo "\n5. deploy.php の構文チェック\n";
if (file_exists('deploy.php')) {
    $output = shell_exec('php -l deploy.php 2>&1');
    if (strpos($output, 'No syntax errors') !== false) {
        echo "✅ deploy.php の構文は正常です\n";
    } else {
        echo "❌ deploy.php の構文エラー:\n";
        echo $output . "\n";
    }
} else {
    echo "❌ deploy.php が存在しません\n";
}

echo "\n6. 最近のエラーログ\n";
$errorLogs = [
    '/home/purplelion51/logs/error.log',
    '/var/log/apache2/error.log',
    '/var/log/httpd/error_log',
    'logs/error.log'
];

foreach ($errorLogs as $logFile) {
    if (file_exists($logFile) && is_readable($logFile)) {
        echo "📋 {$logFile} の最後の10行:\n";
        $lines = file($logFile);
        $lastLines = array_slice($lines, -10);
        foreach ($lastLines as $line) {
            echo "   " . trim($line) . "\n";
        }
        break;
    }
}

echo "\n============================================\n";
echo "エラーログ確認完了\n";
echo "⚠️  このデバッグ用スクリプトは必ず削除してください！\n";
?>
