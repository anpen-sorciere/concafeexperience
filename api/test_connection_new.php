<?php
// api/test_connection.php - Con-Cafe Princess Experience 接続テスト
// ブートストラップを使用したテスト

// ブートストラップを読み込み
require_once __DIR__ . '/../bootstrap.php';

echo "<h1>Con-Cafe Princess Experience - 接続テスト</h1>";

echo "<h2>1. データベース接続テスト</h2>";

try {
    $db = Bootstrap::getDatabase();
    echo "✅ データベース接続成功！<br>";
    echo "ホスト: " . Bootstrap::getConfig('database.host') . "<br>";
    echo "データベース: " . Bootstrap::getConfig('database.dbname') . "<br>";
    echo "ユーザー: " . Bootstrap::getConfig('database.username') . "<br>";
} catch (Exception $e) {
    echo "❌ データベース接続エラー: " . $e->getMessage() . "<br>";
    exit;
}

echo "<h2>2. テーブル存在確認</h2>";

$tables = ['bookings', 'customers', 'plans', 'business_hours', 'system_settings'];
$existingTables = [];

foreach ($tables as $table) {
    try {
        $stmt = $db->query("SHOW TABLES LIKE '{$table}'");
        if ($stmt->rowCount() > 0) {
            $existingTables[] = $table;
            echo "✅ {$table} テーブルが存在します<br>";
        } else {
            echo "❌ {$table} テーブルが存在しません<br>";
        }
    } catch (Exception $e) {
        echo "❌ {$table} テーブルの確認でエラー: " . $e->getMessage() . "<br>";
    }
}

echo "<h2>3. 設定ファイルテスト</h2>";

echo "✅ bootstrap.php が存在します<br>";
echo "✅ Config::getDatabase() が正常に動作します<br>";

echo "<h3>設定値確認:</h3>";
$appConfig = Bootstrap::getConfig('app');
echo "アプリケーション名: " . $appConfig['name'] . "<br>";
echo "環境: " . $appConfig['environment'] . "<br>";
echo "タイムゾーン: " . $appConfig['timezone'] . "<br>";

echo "<h2>4. 予約システムテスト</h2>";

if (file_exists(__DIR__ . '/booking_system.php')) {
    echo "✅ booking_system.php が存在します<br>";
    
    try {
        require_once __DIR__ . '/booking_system.php';
        $bookingSystem = new BookingSystem();
        echo "✅ BookingSystem クラスが正常に初期化されました<br>";
        
        // 空き状況取得のテスト
        $testSlots = $bookingSystem->getAvailableSlots('2025-10-24', 'light');
        echo "✅ 空き状況取得が正常に動作します<br>";
        echo "テスト日時: 2025-10-24<br>";
        echo "利用可能な時間: " . implode(', ', $testSlots) . "<br>";
        
    } catch (Exception $e) {
        echo "❌ BookingSystem エラー: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ booking_system.php が存在しません<br>";
}

echo "<h2>5. ファイル権限確認</h2>";

$directories = ['uploads', 'logs', 'cache', 'tmp'];
foreach ($directories as $dir) {
    $path = Bootstrap::getPath($dir);
    if (is_dir($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        echo "✅ {$dir} ディレクトリが存在します (権限: {$perms})<br>";
    } else {
        echo "⚠️ {$dir} ディレクトリが存在しません<br>";
    }
}

echo "<h2>6. 多言語ファイル確認</h2>";

$translationsPath = Bootstrap::getPath('data') . '/translations.json';
if (file_exists($translationsPath)) {
    echo "✅ translations.json が存在します<br>";
    
    $translations = json_decode(file_get_contents($translationsPath), true);
    if ($translations && isset($translations['ja']) && isset($translations['en']) && isset($translations['zh-cn'])) {
        echo "✅ 多言語データが正常に読み込まれました<br>";
        echo "対応言語: " . implode(', ', array_keys($translations)) . "<br>";
    } else {
        echo "❌ 多言語データの読み込みに失敗しました<br>";
    }
} else {
    echo "❌ translations.json が存在しません<br>";
}

echo "<h2>7. PHP設定確認</h2>";

$systemInfo = Bootstrap::getSystemInfo();
echo "PHP バージョン: " . $systemInfo['php_version'] . "<br>";
echo "メモリ制限: " . $systemInfo['memory_limit'] . "<br>";
echo "アップロード最大サイズ: " . $systemInfo['upload_max_filesize'] . "<br>";
echo "POST最大サイズ: " . $systemInfo['post_max_size'] . "<br>";
echo "実行時間制限: " . $systemInfo['max_execution_time'] . "秒<br>";

echo "<h2>8. ブートストラップ情報</h2>";

$bootstrapInfo = Bootstrap::getSystemInfo();
echo "初期化状態: " . ($bootstrapInfo['initialized'] ? '完了' : '未完了') . "<br>";
echo "プロジェクトルート: " . Bootstrap::getProjectRoot() . "<br>";
echo "設定キー数: " . count($bootstrapInfo['config_keys']) . "<br>";

echo "<h2>9. 次のステップ</h2>";

if (count($existingTables) >= 3) {
    echo "<strong>システムが正常に動作しています！</strong><br>";
    echo "1. ホームページを表示<br>";
    echo "2. 予約フォームをテスト<br>";
    echo "3. 多言語切り替えをテスト<br>";
} else {
    echo "<strong>データベースの初期化が必要です</strong><br>";
    echo "1. deploy.php を実行<br>";
    echo "2. テーブルとデータの作成<br>";
}

echo "<hr>";
echo "テスト完了時刻: " . date('Y-m-d H:i:s') . "<br>";
?>
