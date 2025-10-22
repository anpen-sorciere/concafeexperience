<?php
// test_connection.php - さくらサーバー接続テスト
// Con-Cafe Princess Experience

// セキュリティヘッダーを設定
require_once 'security_headers.php';

// エラー表示を有効にする（テスト用）
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Con-Cafe Princess Experience - 接続テスト</h1>";

// データベース接続情報
$host = "mysql2103.db.sakura.ne.jp";
$user = "purplelion51";
$password = "-6r_am73";
$dbname = "purplelion51_concafe_exp";

echo "<h2>1. データベース接続テスト</h2>";

try {
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "✅ データベース接続成功！<br>";
    echo "ホスト: {$host}<br>";
    echo "データベース: {$dbname}<br>";
    echo "ユーザー: {$user}<br>";
    
} catch (PDOException $e) {
    echo "❌ データベース接続失敗: " . $e->getMessage() . "<br>";
    exit;
}

echo "<h2>2. テーブル存在確認</h2>";

try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "⚠️ テーブルが存在しません。データベーススキーマを実行してください。<br>";
        echo "<a href='deploy.php'>デプロイメントスクリプトを実行</a><br>";
    } else {
        echo "✅ 以下のテーブルが存在します:<br>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>{$table}</li>";
        }
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    echo "❌ テーブル確認エラー: " . $e->getMessage() . "<br>";
}

echo "<h2>3. 設定ファイルテスト</h2>";

if (file_exists('config.php')) {
    echo "✅ config.php が存在します<br>";
    
    try {
        require_once 'config.php';
        $db = Config::getDatabase();
        echo "✅ Config::getDatabase() が正常に動作します<br>";
        
        // 設定値の確認
        echo "<h3>設定値確認:</h3>";
        echo "アプリケーション名: " . Config::APP_CONFIG['name'] . "<br>";
        echo "環境: " . Config::APP_CONFIG['environment'] . "<br>";
        echo "タイムゾーン: " . Config::APP_CONFIG['timezone'] . "<br>";
        
    } catch (Exception $e) {
        echo "❌ Config::getDatabase() エラー: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ config.php が存在しません<br>";
}

echo "<h2>4. 予約システムテスト</h2>";

if (file_exists('booking_system.php')) {
    echo "✅ booking_system.php が存在します<br>";
    
    try {
        require_once 'booking_system.php';
        $bookingSystem = new BookingSystem();
        echo "✅ BookingSystem クラスが正常に初期化されました<br>";
        
        // 空き状況の取得テスト
        $testDate = date('Y-m-d', strtotime('+1 day'));
        $slots = $bookingSystem->getAvailableSlots($testDate, 'light');
        
        if (is_array($slots)) {
            echo "✅ 空き状況取得が正常に動作します<br>";
            echo "テスト日時: {$testDate}<br>";
            echo "利用可能な時間: " . implode(', ', $slots) . "<br>";
        } else {
            echo "⚠️ 空き状況取得でエラーが発生しました<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ BookingSystem エラー: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ booking_system.php が存在しません<br>";
}

echo "<h2>5. ファイル権限確認</h2>";

$directories = ['../uploads', '../logs', '../cache', '../tmp'];
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        echo "✅ {$dir} ディレクトリが存在します (権限: {$perms})<br>";
    } else {
        echo "⚠️ {$dir} ディレクトリが存在しません<br>";
    }
}

echo "<h2>6. 多言語ファイル確認</h2>";

if (file_exists('../data/translations.json')) {
    echo "✅ translations.json が存在します<br>";
    
    $translations = json_decode(file_get_contents('../data/translations.json'), true);
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

echo "PHP バージョン: " . phpversion() . "<br>";
echo "メモリ制限: " . ini_get('memory_limit') . "<br>";
echo "アップロード最大サイズ: " . ini_get('upload_max_filesize') . "<br>";
echo "POST最大サイズ: " . ini_get('post_max_size') . "<br>";
echo "実行時間制限: " . ini_get('max_execution_time') . "秒<br>";

echo "<h2>8. 次のステップ</h2>";

if (empty($tables)) {
    echo "<p><strong>データベーススキーマを実行してください:</strong></p>";
    echo "<ol>";
    echo "<li><a href='deploy.php'>デプロイメントスクリプトを実行</a></li>";
    echo "<li>または phpMyAdmin で database_schema.sql をインポート</li>";
    echo "</ol>";
} else {
    echo "<p><strong>システムが正常に動作しています！</strong></p>";
    echo "<ol>";
    echo "<li><a href='index.html'>ホームページを表示</a></li>";
    echo "<li>予約フォームをテスト</li>";
    echo "<li>多言語切り替えをテスト</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p><small>テスト完了時刻: " . date('Y-m-d H:i:s') . "</small></p>";
?>
