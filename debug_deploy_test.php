<?php
// debug_deploy_test.php - deploy.php の実行時エラーテスト
// Con-Cafe Princess Experience
// 注意: このファイルはデバッグ用です。使用後は削除してください。

echo "Con-Cafe Princess Experience - deploy.php 実行時エラーテスト\n";
echo "========================================================\n\n";

// エラー表示を有効にする
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "1. deploy.php の内容確認\n";
if (file_exists('deploy.php')) {
    $content = file_get_contents('deploy.php');
    echo "ファイルサイズ: " . strlen($content) . " bytes\n";
    echo "最初の100文字: " . substr($content, 0, 100) . "...\n\n";
} else {
    echo "❌ deploy.php が存在しません\n";
    exit;
}

echo "2. 必要なファイルの詳細確認\n";
$requiredFiles = [
    'api/config.php',
    'data/database_schema.sql',
    'api/security_headers.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ {$file}: 存在\n";
        echo "   サイズ: " . filesize($file) . " bytes\n";
        echo "   権限: " . substr(sprintf('%o', fileperms($file)), -4) . "\n";
        echo "   読み取り可能: " . (is_readable($file) ? 'YES' : 'NO') . "\n";
    } else {
        echo "❌ {$file}: 存在しません\n";
    }
}

echo "\n3. config.php の詳細テスト\n";
try {
    require_once 'api/config.php';
    echo "✅ config.php 読み込み成功\n";
    
    if (class_exists('Config')) {
        echo "✅ Config クラス存在\n";
        
        // データベース接続テスト
        try {
            $db = Config::getDatabase();
            echo "✅ データベース接続成功\n";
            
            // データベース名の確認
            $stmt = $db->query("SELECT DATABASE() as db_name");
            $result = $stmt->fetch();
            echo "   現在のデータベース: " . $result['db_name'] . "\n";
            
        } catch (Exception $e) {
            echo "❌ データベース接続エラー: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ Config クラスが存在しません\n";
    }
} catch (Exception $e) {
    echo "❌ config.php 読み込みエラー: " . $e->getMessage() . "\n";
}

echo "\n4. database_schema.sql の内容確認\n";
if (file_exists('data/database_schema.sql')) {
    $sqlContent = file_get_contents('data/database_schema.sql');
    echo "✅ SQLファイル読み込み成功\n";
    echo "   サイズ: " . strlen($sqlContent) . " bytes\n";
    echo "   最初の200文字:\n";
    echo "   " . substr($sqlContent, 0, 200) . "...\n\n";
    
    // SQLの構文チェック（簡単なもの）
    if (strpos($sqlContent, 'CREATE TABLE') !== false) {
        echo "✅ CREATE TABLE 文が含まれています\n";
    } else {
        echo "⚠️ CREATE TABLE 文が見つかりません\n";
    }
} else {
    echo "❌ database_schema.sql が存在しません\n";
}

echo "\n5. deploy.php の段階的実行テスト\n";
try {
    // deploy.php の最初の部分だけをテスト
    echo "DeploymentManager クラスのテスト...\n";
    
    // クラス定義の確認
    if (class_exists('DeploymentManager')) {
        echo "✅ DeploymentManager クラスが存在します\n";
    } else {
        echo "❌ DeploymentManager クラスが存在しません\n";
    }
    
} catch (Exception $e) {
    echo "❌ 段階的実行テストエラー: " . $e->getMessage() . "\n";
}

echo "\n6. メモリと実行時間の確認\n";
echo "メモリ使用量: " . memory_get_usage(true) / 1024 / 1024 . " MB\n";
echo "メモリ制限: " . ini_get('memory_limit') . "\n";
echo "実行時間制限: " . ini_get('max_execution_time') . " 秒\n";

echo "\n7. セッションとクッキーの確認\n";
echo "セッション状態: " . (session_status() === PHP_SESSION_ACTIVE ? 'ACTIVE' : 'INACTIVE') . "\n";
echo "クッキー: " . (isset($_COOKIE) ? count($_COOKIE) . ' 個' : 'なし') . "\n";

echo "\n========================================================\n";
echo "deploy.php 実行時エラーテスト完了\n";
echo "⚠️  このデバッグ用スクリプトは必ず削除してください！\n";
?>
