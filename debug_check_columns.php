<?php
// debug_check_columns.php - カラム存在確認スクリプト
// Con-Cafe Princess Experience
// 注意: このファイルはデバッグ用です。使用後は削除してください。

echo "Con-Cafe Princess Experience - カラム存在確認\n";
echo "==========================================\n\n";

// エラー表示を有効にする
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "1. データベース接続\n";
    require_once 'api/config.php';
    $db = Config::getDatabase();
    echo "✅ データベース接続成功\n\n";
    
    echo "2. bookings テーブルの現在の構造\n";
    $stmt = $db->query("DESCRIBE bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "カラム一覧:\n";
    foreach ($columns as $column) {
        echo "  - {$column['Field']} ({$column['Type']})\n";
    }
    
    echo "\n3. 必要なカラムの存在確認\n";
    $requiredColumns = ['start_time', 'end_time'];
    $existingColumns = array_column($columns, 'Field');
    
    foreach ($requiredColumns as $column) {
        if (in_array($column, $existingColumns)) {
            echo "✅ {$column}: 存在します\n";
        } else {
            echo "❌ {$column}: 存在しません\n";
        }
    }
    
    echo "\n4. カラム追加の試行\n";
    $alterQueries = [
        "ALTER TABLE bookings ADD COLUMN start_time TIME",
        "ALTER TABLE bookings ADD COLUMN end_time TIME"
    ];
    
    foreach ($alterQueries as $query) {
        try {
            $db->exec($query);
            echo "✅ カラム追加成功: " . substr($query, 0, 50) . "...\n";
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                echo "✅ カラムは既に存在: " . substr($query, 0, 50) . "...\n";
            } else {
                echo "❌ カラム追加失敗: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\n5. 修正後のテーブル構造\n";
    $stmt = $db->query("DESCRIBE bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "カラム一覧:\n";
    foreach ($columns as $column) {
        echo "  - {$column['Field']} ({$column['Type']})\n";
    }
    
    echo "\n6. BookingSystem のテスト\n";
    try {
        require_once 'api/booking_system.php';
        $bookingSystem = new BookingSystem();
        echo "✅ BookingSystem の初期化成功\n";
        
        // 簡単なテスト
        $testSlots = $bookingSystem->getAvailableSlots('2024-12-01', 'light');
        echo "✅ getAvailableSlots メソッドのテスト成功\n";
        
    } catch (Exception $e) {
        echo "❌ BookingSystem エラー: " . $e->getMessage() . "\n";
    }
    
    echo "\n==========================================\n";
    echo "カラム存在確認完了\n";
    echo "⚠️  このデバッグ用スクリプトは必ず削除してください！\n";
    
} catch (Exception $e) {
    echo "❌ エラーが発生しました: " . $e->getMessage() . "\n";
    echo "エラーファイル: " . $e->getFile() . "\n";
    echo "エラー行: " . $e->getLine() . "\n";
}
?>
