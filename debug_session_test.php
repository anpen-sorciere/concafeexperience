<?php
// debug_session_test.php - プロジェクト固有セッション設定テスト
// Con-Cafe Princess Experience
// 注意: このファイルはデバッグ用です。使用後は削除してください。

echo "Con-Cafe Princess Experience - プロジェクト固有セッション設定テスト\n";
echo "================================================================\n\n";

// エラー表示を有効にする
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "1. SessionManager クラスの読み込みテスト\n";
try {
    require_once 'api/session_manager.php';
    echo "✅ SessionManager クラスが正常に読み込まれました\n";
    
    if (class_exists('SessionManager')) {
        echo "✅ SessionManager クラスが存在します\n";
    } else {
        echo "❌ SessionManager クラスが存在しません\n";
        exit;
    }
} catch (Exception $e) {
    echo "❌ SessionManager 読み込みエラー: " . $e->getMessage() . "\n";
    exit;
}

echo "\n2. セッション保存ディレクトリの確認\n";
$sessionPath = '/home/purplelion51/www/concafeexp/sessions';
if (is_dir($sessionPath)) {
    echo "✅ セッション保存ディレクトリが存在します\n";
    echo "   パス: {$sessionPath}\n";
    echo "   権限: " . substr(sprintf('%o', fileperms($sessionPath)), -4) . "\n";
} else {
    echo "❌ セッション保存ディレクトリが存在しません\n";
    echo "   作成が必要: {$sessionPath}\n";
    
    // ディレクトリを作成してみる
    if (mkdir($sessionPath, 0755, true)) {
        echo "✅ セッション保存ディレクトリを作成しました\n";
    } else {
        echo "❌ セッション保存ディレクトリの作成に失敗しました\n";
    }
}

echo "\n3. SessionManager の初期化テスト\n";
try {
    SessionManager::init();
    echo "✅ SessionManager の初期化が成功しました\n";
} catch (Exception $e) {
    echo "❌ SessionManager 初期化エラー: " . $e->getMessage() . "\n";
}

echo "\n4. セッション設定の確認\n";
$config = SessionManager::checkConfig();
echo "現在のセッション設定:\n";
foreach ($config as $key => $value) {
    echo "   {$key}: {$value}\n";
}

echo "\n5. セッション情報の取得\n";
$sessionInfo = SessionManager::getInfo();
echo "セッション情報:\n";
foreach ($sessionInfo as $key => $value) {
    if ($key === 'session_data') {
        echo "   {$key}: " . (empty($value) ? '空' : count($value) . ' 項目') . "\n";
    } else {
        echo "   {$key}: {$value}\n";
    }
}

echo "\n6. CSRFトークンのテスト\n";
try {
    $token = SessionManager::generateCSRFToken();
    echo "✅ CSRFトークンの生成成功\n";
    echo "   トークン: " . substr($token, 0, 20) . "...\n";
    
    $isValid = SessionManager::validateCSRFToken($token);
    if ($isValid) {
        echo "✅ CSRFトークンの検証成功\n";
    } else {
        echo "❌ CSRFトークンの検証失敗\n";
    }
} catch (Exception $e) {
    echo "❌ CSRFトークンテストエラー: " . $e->getMessage() . "\n";
}

echo "\n7. セッション値のテスト\n";
try {
    SessionManager::set('test_key', 'test_value');
    $value = SessionManager::get('test_key');
    if ($value === 'test_value') {
        echo "✅ セッション値の設定・取得が正常に動作します\n";
    } else {
        echo "❌ セッション値の設定・取得に問題があります\n";
    }
    
    SessionManager::remove('test_key');
    $removedValue = SessionManager::get('test_key');
    if ($removedValue === null) {
        echo "✅ セッション値の削除が正常に動作します\n";
    } else {
        echo "❌ セッション値の削除に問題があります\n";
    }
} catch (Exception $e) {
    echo "❌ セッション値テストエラー: " . $e->getMessage() . "\n";
}

echo "\n8. セッションクッキーの確認\n";
if (isset($_COOKIE)) {
    echo "現在のクッキー:\n";
    foreach ($_COOKIE as $name => $value) {
        echo "   {$name}: " . substr($value, 0, 20) . "...\n";
    }
} else {
    echo "クッキーが設定されていません\n";
}

echo "\n9. 他のプロジェクトとの分離確認\n";
$defaultSessionPath = '/tmp';
$projectSessionPath = '/home/purplelion51/www/concafeexp/sessions';

$defaultFiles = glob($defaultSessionPath . '/sess_*');
$projectFiles = glob($projectSessionPath . '/sess_*');

echo "デフォルトセッションパス ({$defaultSessionPath}): " . count($defaultFiles) . " ファイル\n";
echo "プロジェクトセッションパス ({$projectSessionPath}): " . count($projectFiles) . " ファイル\n";

if (count($projectFiles) > 0) {
    echo "✅ プロジェクト固有のセッションファイルが作成されています\n";
} else {
    echo "⚠️ プロジェクト固有のセッションファイルがまだ作成されていません\n";
}

echo "\n================================================================\n";
echo "プロジェクト固有セッション設定テスト完了\n";
echo "⚠️  このデバッグ用スクリプトは必ず削除してください！\n";
?>
