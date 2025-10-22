<?php
// set_permissions.php - 権限設定スクリプト
// Con-Cafe Princess Experience

echo "Con-Cafe Princess Experience - 権限設定スクリプト\n";
echo "================================================\n\n";

// 設定
$basePath = '/home/purplelion51/www/concafeexp/';
$directories = [
    'uploads',
    'logs', 
    'cache',
    'tmp',
    'assets',
    'assets/css',
    'assets/js',
    'assets/images',
    'api',
    'data'
];

$files = [
    'index.html',
    'deploy.php',
    'assets/css/styles.css',
    'assets/js/script.js',
    'assets/js/sns_strategy.js',
    'api/booking_system.php',
    'api/config.php',
    'api/security_headers.php',
    'api/test_connection.php',
    'data/database_schema.sql',
    'data/translations.json'
];

echo "1. ディレクトリの権限を設定中...\n";
foreach ($directories as $dir) {
    $fullPath = $basePath . $dir;
    if (is_dir($fullPath)) {
        if (chmod($fullPath, 0755)) {
            echo "✓ {$dir}: 755\n";
        } else {
            echo "✗ {$dir}: 権限設定失敗\n";
        }
    } else {
        echo "⚠ {$dir}: ディレクトリが存在しません\n";
    }
}

echo "\n2. ファイルの権限を設定中...\n";
foreach ($files as $file) {
    $fullPath = $basePath . $file;
    if (file_exists($fullPath)) {
        if (chmod($fullPath, 0644)) {
            echo "✓ {$file}: 644\n";
        } else {
            echo "✗ {$file}: 権限設定失敗\n";
        }
    } else {
        echo "⚠ {$file}: ファイルが存在しません\n";
    }
}

echo "\n3. 権限の確認...\n";
echo "ディレクトリ:\n";
foreach ($directories as $dir) {
    $fullPath = $basePath . $dir;
    if (is_dir($fullPath)) {
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        echo "  {$dir}: {$perms}\n";
    }
}

echo "\nファイル:\n";
foreach ($files as $file) {
    $fullPath = $basePath . $file;
    if (file_exists($fullPath)) {
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        echo "  {$file}: {$perms}\n";
    }
}

echo "\n================================================\n";
echo "権限設定が完了しました！\n";
echo "このスクリプトは削除することを推奨します。\n";
?>
