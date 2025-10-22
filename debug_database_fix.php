<?php
// debug_database_fix.php - データベース構造修正スクリプト
// Con-Cafe Princess Experience
// 注意: このファイルはデバッグ用です。使用後は削除してください。

echo "Con-Cafe Princess Experience - データベース構造修正\n";
echo "===============================================\n\n";

// エラー表示を有効にする
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "1. データベース接続\n";
    require_once 'api/config.php';
    $db = Config::getDatabase();
    echo "✅ データベース接続成功\n\n";
    
    echo "2. 現在のテーブル構造確認\n";
    $tables = ['bookings', 'customers', 'plans'];
    
    foreach ($tables as $table) {
        echo "テーブル: {$table}\n";
        try {
            $stmt = $db->query("DESCRIBE {$table}");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "  カラム一覧:\n";
            foreach ($columns as $column) {
                echo "    - {$column['Field']} ({$column['Type']})\n";
            }
        } catch (Exception $e) {
            echo "  ❌ エラー: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
    
    echo "3. bookings テーブルの修正\n";
    
    // bookings テーブルに start_time カラムを追加
    $alterQueries = [
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS start_time TIME",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS end_time TIME",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'pending'",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
        "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
    ];
    
    foreach ($alterQueries as $query) {
        try {
            $db->exec($query);
            echo "✅ クエリ実行成功: " . substr($query, 0, 50) . "...\n";
        } catch (Exception $e) {
            echo "⚠️ クエリ実行警告: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n4. 修正後のテーブル構造確認\n";
    try {
        $stmt = $db->query("DESCRIBE bookings");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "bookings テーブルのカラム:\n";
        foreach ($columns as $column) {
            echo "  - {$column['Field']} ({$column['Type']})\n";
        }
    } catch (Exception $e) {
        echo "❌ エラー: " . $e->getMessage() . "\n";
    }
    
    echo "\n5. BookingSystem のテスト\n";
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
    
    echo "\n6. プランデータの確認\n";
    try {
        $stmt = $db->query("SELECT * FROM plans");
        $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "プラン数: " . count($plans) . " 件\n";
        foreach ($plans as $plan) {
            echo "  - {$plan['name']}: ¥{$plan['price_min']}〜¥{$plan['price_max']}\n";
        }
    } catch (Exception $e) {
        echo "❌ プランデータ取得エラー: " . $e->getMessage() . "\n";
    }
    
    echo "\n===============================================\n";
    echo "✅ データベース構造修正が完了しました！\n";
    echo "⚠️  このデバッグ用スクリプトは必ず削除してください！\n";
    
} catch (Exception $e) {
    echo "❌ エラーが発生しました: " . $e->getMessage() . "\n";
    echo "エラーファイル: " . $e->getFile() . "\n";
    echo "エラー行: " . $e->getLine() . "\n";
}
?>
