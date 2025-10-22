<?php
// debug_simple_deploy.php - 簡易版デプロイメントスクリプト
// Con-Cafe Princess Experience
// 注意: このファイルはデバッグ用です。使用後は削除してください。

echo "Con-Cafe Princess Experience - 簡易版デプロイメント\n";
echo "===============================================\n\n";

// エラー表示を有効にする
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "1. データベース接続テスト\n";
    require_once 'api/config.php';
    $db = Config::getDatabase();
    echo "✅ データベース接続成功\n\n";
    
    echo "2. テーブル作成開始\n";
    
    // 基本的なテーブルを一つずつ作成
    $tables = [
        'plans' => "
            CREATE TABLE IF NOT EXISTS plans (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                price_min INT NOT NULL,
                price_max INT NOT NULL,
                duration_min INT NOT NULL,
                duration_max INT NOT NULL,
                features TEXT,
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'customers' => "
            CREATE TABLE IF NOT EXISTS customers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(255) UNIQUE,
                phone VARCHAR(20),
                nationality VARCHAR(50),
                language VARCHAR(10) DEFAULT 'ja',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'bookings' => "
            CREATE TABLE IF NOT EXISTS bookings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                customer_id INT,
                plan_id INT,
                booking_date DATE NOT NULL,
                booking_time TIME NOT NULL,
                status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
                total_price INT NOT NULL,
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
                FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        "
    ];
    
    foreach ($tables as $tableName => $sql) {
        echo "   テーブル作成中: {$tableName}... ";
        $db->exec($sql);
        echo "✅ 完了\n";
    }
    
    echo "\n3. 初期データの挿入\n";
    
    // プランデータの挿入
    $plans = [
        ['ライトプラン', 'スタンダードなメイド服の着用とプロカメラマンによる店内フォトブース撮影', 7000, 10000, 30, 45, 'メイド服着用,プロ撮影,チェキ1枚'],
        ['ベーシックプラン', '豪華な制服やアクセサリーの選択とプロによるポイントメイクとヘアセット', 15000, 20000, 60, 90, '豪華制服,プロメイク,本格撮影,動画撮影,チェキ2枚'],
        ['プレミアムプラン', 'プレミアム限定の特別な衣装とフルメイクとヘアセット', 30000, 50000, 120, 180, '特別衣装,フルメイク,野外撮影,ハイライト動画,チェキ3枚,フォトブック']
    ];
    
    $stmt = $db->prepare("INSERT INTO plans (name, description, price_min, price_max, duration_min, duration_max, features) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($plans as $plan) {
        $stmt->execute($plan);
        echo "✅ プラン挿入: {$plan[0]}\n";
    }
    
    echo "\n4. 完了確認\n";
    
    // テーブル存在確認
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "作成されたテーブル:\n";
    foreach ($tables as $table) {
        echo "  ✅ {$table}\n";
    }
    
    // プラン数確認
    $stmt = $db->query("SELECT COUNT(*) as count FROM plans");
    $result = $stmt->fetch();
    echo "\nプラン数: {$result['count']} 件\n";
    
    echo "\n===============================================\n";
    echo "✅ 簡易版デプロイメントが正常に完了しました！\n";
    echo "アクセスURL: https://purplelion51.sakura.ne.jp/concafeexp/\n";
    echo "⚠️  このデバッグ用スクリプトは必ず削除してください！\n";
    
} catch (Exception $e) {
    echo "❌ エラーが発生しました: " . $e->getMessage() . "\n";
    echo "エラーファイル: " . $e->getFile() . "\n";
    echo "エラー行: " . $e->getLine() . "\n";
}
?>
