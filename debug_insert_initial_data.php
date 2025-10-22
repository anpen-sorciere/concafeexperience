<?php
// debug_insert_initial_data.php - 初期データ挿入スクリプト
// Con-Cafe Princess Experience
// 注意: このファイルはデバッグ用です。使用後は削除してください。

echo "Con-Cafe Princess Experience - 初期データ挿入\n";
echo "==========================================\n\n";

// エラー表示を有効にする
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "1. データベース接続\n";
    require_once 'api/config.php';
    $db = Config::getDatabase();
    echo "✅ データベース接続成功\n\n";
    
    echo "2. 現在のデータ確認\n";
    
    // プランデータの確認
    $stmt = $db->query("SELECT COUNT(*) as count FROM plans");
    $result = $stmt->fetch();
    echo "プラン数: {$result['count']} 件\n";
    
    // 顧客データの確認
    $stmt = $db->query("SELECT COUNT(*) as count FROM customers");
    $result = $stmt->fetch();
    echo "顧客数: {$result['count']} 件\n";
    
    // 予約データの確認
    $stmt = $db->query("SELECT COUNT(*) as count FROM bookings");
    $result = $stmt->fetch();
    echo "予約数: {$result['count']} 件\n\n";
    
    echo "3. プランデータの挿入\n";
    
    // プランデータの準備
    $plans = [
        [
            'name' => 'ライトプラン',
            'description' => 'スタンダードなメイド服の着用とプロカメラマンによる店内フォトブース撮影',
            'price_min' => 7000,
            'price_max' => 10000,
            'duration_min' => 30,
            'duration_max' => 45,
            'features' => 'メイド服着用,プロ撮影,チェキ1枚',
            'is_active' => 1
        ],
        [
            'name' => 'ベーシックプラン',
            'description' => '豪華な制服やアクセサリーの選択とプロによるポイントメイクとヘアセット',
            'price_min' => 15000,
            'price_max' => 20000,
            'duration_min' => 60,
            'duration_max' => 90,
            'features' => '豪華制服,プロメイク,本格撮影,動画撮影,チェキ2枚',
            'is_active' => 1
        ],
        [
            'name' => 'プレミアムプラン',
            'description' => 'プレミアム限定の特別な衣装とフルメイクとヘアセット',
            'price_min' => 30000,
            'price_max' => 50000,
            'duration_min' => 120,
            'duration_max' => 180,
            'features' => '特別衣装,フルメイク,野外撮影,ハイライト動画,チェキ3枚,フォトブック',
            'is_active' => 1
        ]
    ];
    
    // 既存のプランデータをクリア
    $db->exec("DELETE FROM plans");
    echo "✅ 既存のプランデータをクリアしました\n";
    
    // プランデータの挿入
    $stmt = $db->prepare("
        INSERT INTO plans (name, description, price_min, price_max, duration_min, duration_max, features, is_active) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($plans as $plan) {
        $stmt->execute([
            $plan['name'],
            $plan['description'],
            $plan['price_min'],
            $plan['price_max'],
            $plan['duration_min'],
            $plan['duration_max'],
            $plan['features'],
            $plan['is_active']
        ]);
        echo "✅ プラン挿入: {$plan['name']} (¥{$plan['price_min']}〜¥{$plan['price_max']})\n";
    }
    
    echo "\n4. 営業時間設定の挿入\n";
    
    // 営業時間テーブルが存在しない場合は作成
    $createBusinessHoursTable = "
        CREATE TABLE IF NOT EXISTS business_hours (
            id INT AUTO_INCREMENT PRIMARY KEY,
            day_of_week VARCHAR(10) NOT NULL,
            is_open BOOLEAN DEFAULT TRUE,
            open_time TIME,
            close_time TIME,
            last_reception_time TIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $db->exec($createBusinessHoursTable);
    echo "✅ 営業時間テーブルを作成/確認しました\n";
    
    // 営業時間データの準備
    $businessHours = [
        ['day' => 'Monday', 'is_open' => 1, 'open_time' => '11:00', 'close_time' => '21:00', 'last_reception' => '19:00'],
        ['day' => 'Tuesday', 'is_open' => 0, 'open_time' => null, 'close_time' => null, 'last_reception' => null], // 定休日
        ['day' => 'Wednesday', 'is_open' => 1, 'open_time' => '11:00', 'close_time' => '21:00', 'last_reception' => '19:00'],
        ['day' => 'Thursday', 'is_open' => 1, 'open_time' => '11:00', 'close_time' => '21:00', 'last_reception' => '19:00'],
        ['day' => 'Friday', 'is_open' => 1, 'open_time' => '11:00', 'close_time' => '21:00', 'last_reception' => '19:00'],
        ['day' => 'Saturday', 'is_open' => 1, 'open_time' => '10:00', 'close_time' => '22:00', 'last_reception' => '20:00'],
        ['day' => 'Sunday', 'is_open' => 1, 'open_time' => '10:00', 'close_time' => '22:00', 'last_reception' => '20:00']
    ];
    
    // 既存の営業時間データをクリア
    $db->exec("DELETE FROM business_hours");
    echo "✅ 既存の営業時間データをクリアしました\n";
    
    // 営業時間データの挿入
    $stmt = $db->prepare("
        INSERT INTO business_hours (day_of_week, is_open, open_time, close_time, last_reception_time) 
        VALUES (?, ?, ?, ?, ?)
    ");
    
    foreach ($businessHours as $hours) {
        $stmt->execute([
            $hours['day'],
            $hours['is_open'],
            $hours['open_time'],
            $hours['close_time'],
            $hours['last_reception']
        ]);
        $status = $hours['is_open'] ? "営業" : "定休日";
        echo "✅ 営業時間挿入: {$hours['day']} - {$status}\n";
    }
    
    echo "\n5. システム設定の挿入\n";
    
    // システム設定テーブルが存在しない場合は作成
    $createSystemSettingsTable = "
        CREATE TABLE IF NOT EXISTS system_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    $db->exec($createSystemSettingsTable);
    echo "✅ システム設定テーブルを作成/確認しました\n";
    
    // システム設定データの準備
    $systemSettings = [
        ['key' => 'max_guests_per_slot', 'value' => '4', 'description' => '1回の予約で受け入れ可能な最大人数'],
        ['key' => 'advance_booking_days', 'value' => '30', 'description' => '事前予約可能日数'],
        ['key' => 'cancellation_hours', 'value' => '24', 'description' => 'キャンセル可能時間（時間前）'],
        ['key' => 'business_name', 'value' => 'Con-Cafe Princess Experience', 'description' => '事業名'],
        ['key' => 'business_address', 'value' => '大阪府大阪市浪速区日本橋', 'description' => '事業所住所'],
        ['key' => 'contact_email', 'value' => 'info@concafe-princess.com', 'description' => 'お問い合わせメールアドレス'],
        ['key' => 'contact_phone', 'value' => '06-1234-5678', 'description' => 'お問い合わせ電話番号']
    ];
    
    // 既存のシステム設定データをクリア
    $db->exec("DELETE FROM system_settings");
    echo "✅ 既存のシステム設定データをクリアしました\n";
    
    // システム設定データの挿入
    $stmt = $db->prepare("
        INSERT INTO system_settings (setting_key, setting_value, description) 
        VALUES (?, ?, ?)
    ");
    
    foreach ($systemSettings as $setting) {
        $stmt->execute([
            $setting['key'],
            $setting['value'],
            $setting['description']
        ]);
        echo "✅ システム設定挿入: {$setting['key']} = {$setting['value']}\n";
    }
    
    echo "\n6. データ挿入後の確認\n";
    
    // プランデータの確認
    $stmt = $db->query("SELECT COUNT(*) as count FROM plans");
    $result = $stmt->fetch();
    echo "プラン数: {$result['count']} 件\n";
    
    // 営業時間データの確認
    $stmt = $db->query("SELECT COUNT(*) as count FROM business_hours");
    $result = $stmt->fetch();
    echo "営業時間設定数: {$result['count']} 件\n";
    
    // システム設定データの確認
    $stmt = $db->query("SELECT COUNT(*) as count FROM system_settings");
    $result = $stmt->fetch();
    echo "システム設定数: {$result['count']} 件\n";
    
    echo "\n7. プラン詳細の表示\n";
    $stmt = $db->query("SELECT * FROM plans ORDER BY price_min");
    $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($plans as $plan) {
        echo "  - {$plan['name']}: ¥{$plan['price_min']}〜¥{$plan['price_max']} ({$plan['duration_min']}〜{$plan['duration_max']}分)\n";
    }
    
    echo "\n==========================================\n";
    echo "✅ 初期データの挿入が完了しました！\n";
    echo "⚠️  このデバッグ用スクリプトは必ず削除してください！\n";
    
} catch (Exception $e) {
    echo "❌ エラーが発生しました: " . $e->getMessage() . "\n";
    echo "エラーファイル: " . $e->getFile() . "\n";
    echo "エラー行: " . $e->getLine() . "\n";
}
?>
