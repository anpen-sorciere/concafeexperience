-- Con-Cafe Princess Experience Database Schema
-- データベース初期化スクリプト

CREATE DATABASE IF NOT EXISTS purplelion51_concafe_exp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE purplelion51_concafe_exp;

-- 予約テーブル
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    plan_type VARCHAR(20) NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    duration INT NOT NULL,
    participants INT NOT NULL,
    message TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    cancellation_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    cancelled_at TIMESTAMP NULL,
    INDEX idx_booking_date (booking_date),
    INDEX idx_status (status),
    INDEX idx_booking_id (booking_id)
);

-- プランテーブル
CREATE TABLE plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plan_code VARCHAR(20) UNIQUE NOT NULL,
    name_ja VARCHAR(100) NOT NULL,
    name_en VARCHAR(100) NOT NULL,
    name_zh_cn VARCHAR(100) NOT NULL,
    duration INT NOT NULL,
    price_min INT NOT NULL,
    price_max INT NOT NULL,
    description_ja TEXT,
    description_en TEXT,
    description_zh_cn TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- プランデータの挿入
INSERT INTO plans (plan_code, name_ja, name_en, name_zh_cn, duration, price_min, price_max, description_ja, description_en, description_zh_cn) VALUES
('light', 'ライトプラン', 'Light Plan', '轻量套餐', 45, 7000, 10000, 
 'スタンダードなメイド服の着用、プロカメラマンによる店内フォトブース撮影、記念チェキ1枚',
 'Standard maid outfit, professional photographer booth shooting, 1 commemorative cheki',
 '标准女仆装、专业摄影师店内摄影棚拍摄、纪念拍立得1张'),

('basic', 'ベーシックプラン', 'Basic Plan', '基础套餐', 90, 15000, 20000,
 '豪華な制服やアクセサリーの選択、プロによるポイントメイクとヘアセット、店内での本格的な撮影、萌え体験を動画で撮影、記念チェキ2枚',
 'Luxury uniform and accessory selection, professional point makeup and hair styling, professional shooting in-store, moe experience video shooting, 2 commemorative cheki',
 '豪华制服和配饰选择、专业重点化妆和发型设计、店内专业拍摄、萌系体验视频拍摄、纪念拍立得2张'),

('premium', 'プレミアムプラン', 'Premium Plan', '高级套餐', 120, 30000, 35000,
 'プレミアム限定の特別な衣装、フルメイクとヘアセット、店内＋オタロードでの野外撮影、プロが編集したハイライト動画、記念チェキ3枚、フォトスタンドまたはフォトブック郵送',
 'Premium limited special outfit, full makeup and hair styling, indoor + outdoor shooting in Otaroad, professional edited highlight video, 3 commemorative cheki, photo stand or photo book delivery',
 '高级限定特别服装、全套化妆和发型设计、店内+御宅路户外拍摄、专业编辑精彩视频、纪念拍立得3张、照片支架或相册邮寄');

-- スタッフテーブル
CREATE TABLE staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(50) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    languages JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 顧客テーブル（リピーター管理用）
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    nationality VARCHAR(50),
    language_preference VARCHAR(10) DEFAULT 'ja',
    total_bookings INT DEFAULT 0,
    last_visit DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
);

-- レビューテーブル
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id VARCHAR(20),
    customer_name VARCHAR(100) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    language VARCHAR(10) DEFAULT 'ja',
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE SET NULL,
    INDEX idx_rating (rating),
    INDEX idx_approved (is_approved)
);

-- 売上テーブル
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id VARCHAR(20) NOT NULL,
    plan_code VARCHAR(20) NOT NULL,
    amount INT NOT NULL,
    payment_method VARCHAR(20),
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    INDEX idx_transaction_date (transaction_date),
    INDEX idx_payment_status (payment_status)
);

-- 在庫管理テーブル（衣装・アクセサリー）
CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    size VARCHAR(20),
    color VARCHAR(50),
    quantity INT DEFAULT 0,
    is_available BOOLEAN DEFAULT TRUE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_available (is_available)
);

-- 衣装・アクセサリーの初期データ
INSERT INTO inventory (item_name, category, size, color, quantity) VALUES
('クラシックメイド服', 'dress', 'S', 'black', 5),
('クラシックメイド服', 'dress', 'M', 'black', 8),
('クラシックメイド服', 'dress', 'L', 'black', 6),
('クラシックメイド服', 'dress', 'XL', 'black', 4),
('フリルメイド服', 'dress', 'S', 'white', 3),
('フリルメイド服', 'dress', 'M', 'white', 5),
('フリルメイド服', 'dress', 'L', 'white', 4),
('プレミアムメイド服', 'dress', 'S', 'pink', 2),
('プレミアムメイド服', 'dress', 'M', 'pink', 3),
('プレミアムメイド服', 'dress', 'L', 'pink', 2),
('エプロン', 'accessory', 'Free', 'white', 20),
('ヘッドドレス', 'accessory', 'Free', 'white', 15),
('ヘッドドレス', 'accessory', 'Free', 'black', 12),
('手袋', 'accessory', 'Free', 'white', 25),
('ストッキング', 'accessory', 'Free', 'white', 30),
('シューズ', 'shoes', 'S', 'black', 8),
('シューズ', 'shoes', 'M', 'black', 10),
('シューズ', 'shoes', 'L', 'black', 8),
('シューズ', 'shoes', 'XL', 'black', 6);

-- 営業時間設定テーブル
CREATE TABLE business_hours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    day_of_week VARCHAR(10) NOT NULL,
    is_open BOOLEAN DEFAULT TRUE,
    open_time TIME,
    close_time TIME,
    last_reception_time TIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_day (day_of_week)
);

-- 営業時間の初期データ
INSERT INTO business_hours (day_of_week, is_open, open_time, close_time, last_reception_time) VALUES
('Monday', TRUE, '11:00:00', '21:00:00', '19:00:00'),
('Tuesday', FALSE, NULL, NULL, NULL),
('Wednesday', TRUE, '11:00:00', '21:00:00', '19:00:00'),
('Thursday', TRUE, '11:00:00', '21:00:00', '19:00:00'),
('Friday', TRUE, '11:00:00', '21:00:00', '19:00:00'),
('Saturday', TRUE, '11:00:00', '21:00:00', '19:00:00'),
('Sunday', TRUE, '11:00:00', '21:00:00', '19:00:00');

-- システム設定テーブル
CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- システム設定の初期データ
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('max_participants_per_booking', '6', '1回の予約で受け入れ可能な最大人数'),
('advance_booking_days', '30', '何日前まで予約可能か'),
('cancellation_hours', '24', '何時間前までキャンセル可能か'),
('email_notifications', 'true', 'メール通知の有効/無効'),
('sms_notifications', 'false', 'SMS通知の有効/無効'),
('default_language', 'ja', 'デフォルト言語'),
('currency', 'JPY', '通貨'),
('timezone', 'Asia/Tokyo', 'タイムゾーン');

-- ビュー: 予約統計
CREATE VIEW booking_statistics AS
SELECT 
    DATE(booking_date) as date,
    plan_type,
    COUNT(*) as total_bookings,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_bookings,
    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings,
    AVG(participants) as avg_participants,
    SUM(participants) as total_participants
FROM bookings
GROUP BY DATE(booking_date), plan_type;

-- ビュー: 月次売上
CREATE VIEW monthly_sales AS
SELECT 
    YEAR(transaction_date) as year,
    MONTH(transaction_date) as month,
    plan_code,
    COUNT(*) as total_transactions,
    SUM(amount) as total_revenue,
    AVG(amount) as avg_transaction_value
FROM sales
WHERE payment_status = 'paid'
GROUP BY YEAR(transaction_date), MONTH(transaction_date), plan_code;

-- ストアドプロシージャ: 予約可能時間の取得
DELIMITER //
CREATE PROCEDURE GetAvailableSlots(
    IN p_date DATE,
    IN p_plan_code VARCHAR(20),
    IN p_duration INT
)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE slot_time TIME;
    DECLARE slot_start TIME DEFAULT '11:00:00';
    DECLARE slot_end TIME DEFAULT '19:00:00';
    DECLARE slot_interval INT DEFAULT 30;
    
    -- 一時テーブルを作成
    CREATE TEMPORARY TABLE temp_slots (slot_time TIME);
    
    -- 時間スロットを生成
    SET slot_time = slot_start;
    WHILE slot_time <= slot_end DO
        INSERT INTO temp_slots VALUES (slot_time);
        SET slot_time = ADDTIME(slot_time, CONCAT('00:', slot_interval, ':00'));
    END WHILE;
    
    -- 利用可能なスロットを返す
    SELECT ts.slot_time
    FROM temp_slots ts
    WHERE NOT EXISTS (
        SELECT 1 FROM bookings b
        WHERE b.booking_date = p_date
        AND b.status != 'cancelled'
        AND (
            (ts.slot_time < ADDTIME(b.start_time, CONCAT('00:', b.duration, ':00')))
            AND (ADDTIME(ts.slot_time, CONCAT('00:', p_duration, ':00')) > b.start_time)
        )
    );
    
    DROP TEMPORARY TABLE temp_slots;
END //
DELIMITER ;

-- トリガー: 予約作成時の自動処理
DELIMITER //
CREATE TRIGGER after_booking_insert
AFTER INSERT ON bookings
FOR EACH ROW
BEGIN
    -- 顧客情報を更新または作成
    INSERT INTO customers (name, email, phone, total_bookings, last_visit)
    VALUES (NEW.name, NEW.email, NEW.phone, 1, NEW.booking_date)
    ON DUPLICATE KEY UPDATE
        total_bookings = total_bookings + 1,
        last_visit = NEW.booking_date,
        updated_at = CURRENT_TIMESTAMP;
    
    -- 売上レコードを作成
    INSERT INTO sales (booking_id, plan_code, amount, payment_status)
    SELECT 
        NEW.booking_id,
        NEW.plan_type,
        CASE NEW.plan_type
            WHEN 'light' THEN 8500
            WHEN 'basic' THEN 17500
            WHEN 'premium' THEN 32500
            ELSE 0
        END,
        'pending';
END //
DELIMITER ;

-- インデックスの最適化
CREATE INDEX idx_bookings_date_time ON bookings(booking_date, start_time);
CREATE INDEX idx_bookings_email ON bookings(email);
CREATE INDEX idx_sales_booking_id ON sales(booking_id);
CREATE INDEX idx_reviews_booking_id ON reviews(booking_id);

-- パフォーマンス向上のための設定
SET GLOBAL innodb_buffer_pool_size = 128M;
SET GLOBAL query_cache_size = 32M;
SET GLOBAL query_cache_type = 1;
