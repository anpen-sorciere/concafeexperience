<?php
// debug_session_path_test.php - セッション保存パスのテスト
// Con-Cafe Princess Experience
// 注意: このファイルはデバッグ用です。使用後は削除してください。

echo "Con-Cafe Princess Experience - セッション保存パステスト\n";
echo "====================================================\n\n";

// エラー表示を有効にする
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "1. 利用可能なパスの確認\n";

$possiblePaths = [
    '/home/purplelion51/www/concafeexp/sessions',
    '/home/purplelion51/www/concafeexp/tmp',
    '/home/purplelion51/www/concafeexp/cache',
    '/tmp/concafeexp_sessions',
    '/var/tmp/concafeexp_sessions',
    sys_get_temp_dir() . '/concafeexp_sessions'
];

foreach ($possiblePaths as $path) {
    echo "パス: {$path}\n";
    
    if (is_dir($path)) {
        echo "  ✅ ディレクトリが存在します\n";
        echo "  権限: " . substr(sprintf('%o', fileperms($path)), -4) . "\n";
        echo "  書き込み可能: " . (is_writable($path) ? 'YES' : 'NO') . "\n";
    } else {
        echo "  ❌ ディレクトリが存在しません\n";
        
        // ディレクトリを作成してみる
        $parentDir = dirname($path);
        if (is_dir($parentDir) && is_writable($parentDir)) {
            echo "  親ディレクトリが書き込み可能です\n";
            if (mkdir($path, 0755, true)) {
                echo "  ✅ ディレクトリを作成しました\n";
                echo "  権限: " . substr(sprintf('%o', fileperms($path)), -4) . "\n";
            } else {
                echo "  ❌ ディレクトリの作成に失敗しました\n";
            }
        } else {
            echo "  親ディレクトリが書き込み不可能です\n";
        }
    }
    echo "\n";
}

echo "2. 現在の作業ディレクトリの確認\n";
echo "現在のディレクトリ: " . getcwd() . "\n";
echo "スクリプトのディレクトリ: " . __DIR__ . "\n";

echo "\n3. 相対パスでのテスト\n";
$relativePaths = [
    'sessions',
    'tmp/sessions',
    'cache/sessions',
    './sessions'
];

foreach ($relativePaths as $path) {
    echo "相対パス: {$path}\n";
    
    if (is_dir($path)) {
        echo "  ✅ ディレクトリが存在します\n";
        echo "  権限: " . substr(sprintf('%o', fileperms($path)), -4) . "\n";
        echo "  書き込み可能: " . (is_writable($path) ? 'YES' : 'NO') . "\n";
    } else {
        echo "  ❌ ディレクトリが存在しません\n";
        
        if (mkdir($path, 0755, true)) {
            echo "  ✅ ディレクトリを作成しました\n";
        } else {
            echo "  ❌ ディレクトリの作成に失敗しました\n";
        }
    }
    echo "\n";
}

echo "4. 推奨設定\n";
echo "最も安全で確実なパス:\n";
echo "  - 相対パス: ./sessions\n";
echo "  - 絶対パス: " . __DIR__ . "/sessions\n";

echo "\n====================================================\n";
echo "セッション保存パステスト完了\n";
echo "⚠️  このデバッグ用スクリプトは必ず削除してください！\n";
?>
