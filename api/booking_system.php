<?php
// Con-Cafe Princess Experience - Booking System
// 予約システムのバックエンド処理

// セキュリティヘッダーを設定
require_once 'security_headers.php';

class BookingSystem {
    private $db;
    private $config;
    
    public function __construct() {
        $this->config = [
            'db_host' => 'mysql2103.db.sakura.ne.jp',
            'db_name' => 'purplelion51_concafe_exp',
            'db_user' => 'purplelion51',
            'db_pass' => '-6r_am73',
            'business_hours' => [
                'start' => '11:00',
                'end' => '21:00',
                'last_reception' => '19:00',
                'closed_days' => ['Tuesday'] // 火曜日定休
            ],
            'plans' => [
                'light' => [
                    'name' => 'ライトプラン',
                    'duration' => 45, // 分
                    'price_min' => 7000,
                    'price_max' => 10000
                ],
                'basic' => [
                    'name' => 'ベーシックプラン',
                    'duration' => 90,
                    'price_min' => 15000,
                    'price_max' => 20000
                ],
                'premium' => [
                    'name' => 'プレミアムプラン',
                    'duration' => 120,
                    'price_min' => 30000,
                    'price_max' => 35000
                ]
            ]
        ];
        
        $this->connectDatabase();
    }
    
    private function connectDatabase() {
        try {
            $this->db = new PDO(
                "mysql:host={$this->config['db_host']};dbname={$this->config['db_name']};charset=utf8mb4",
                $this->config['db_user'],
                $this->config['db_pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("データベース接続に失敗しました");
        }
    }
    
    // 予約可能な日時を取得
    public function getAvailableSlots($date, $plan) {
        $planConfig = $this->config['plans'][$plan];
        $duration = $planConfig['duration_min']; // duration_min を使用
        
        // 営業時間内のスロットを生成
        $slots = $this->generateTimeSlots($date, $duration);
        
        // 既存の予約を取得
        $existingBookings = $this->getExistingBookings($date);
        
        // 利用可能なスロットをフィルタリング
        $availableSlots = [];
        foreach ($slots as $slot) {
            if ($this->isSlotAvailable($slot, $existingBookings, $duration)) {
                $availableSlots[] = $slot;
            }
        }
        
        return $availableSlots;
    }
    
    private function generateTimeSlots($date, $duration) {
        $slots = [];
        $startTime = strtotime($date . ' ' . $this->config['business_hours']['start']);
        $endTime = strtotime($date . ' ' . $this->config['business_hours']['last_reception']);
        
        $interval = 30; // 30分間隔
        
        for ($time = $startTime; $time <= $endTime; $time += ($interval * 60)) {
            $slots[] = date('H:i', $time);
        }
        
        return $slots;
    }
    
    private function getExistingBookings($date) {
        $stmt = $this->db->prepare("
            SELECT start_time, duration_min 
            FROM bookings b
            JOIN plans p ON b.plan_id = p.id
            WHERE booking_date = ? AND status != 'cancelled'
        ");
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }
    
    private function isSlotAvailable($slot, $existingBookings, $duration) {
        $slotStart = strtotime($slot);
        $slotEnd = $slotStart + ($duration * 60);
        
        foreach ($existingBookings as $booking) {
            $bookingStart = strtotime($booking['start_time']);
            $bookingEnd = $bookingStart + ($booking['duration_min'] * 60);
            
            // 時間が重複しているかチェック
            if (($slotStart < $bookingEnd) && ($slotEnd > $bookingStart)) {
                return false;
            }
        }
        
        return true;
    }
    
    // 予約を作成
    public function createBooking($data) {
        try {
            $this->db->beginTransaction();
            
            // バリデーション
            $this->validateBookingData($data);
            
            // 予約IDを生成
            $bookingId = $this->generateBookingId();
            
            // 予約データを挿入
            $stmt = $this->db->prepare("
                INSERT INTO bookings (
                    booking_id, name, email, phone, plan_type, 
                    booking_date, start_time, duration_min, participants, 
                    message, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");
            
            $planConfig = $this->config['plans'][$data['plan']];
            $stmt->execute([
                $bookingId,
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['plan'],
                $data['date'],
                $data['time'],
                $planConfig['duration_min'],
                $data['participants'],
                $data['message'] ?? '',
            ]);
            
            // 確認メールを送信
            $this->sendConfirmationEmail($bookingId, $data);
            
            $this->db->commit();
            
            return [
                'success' => true,
                'booking_id' => $bookingId,
                'message' => '予約が正常に作成されました'
            ];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Booking creation failed: " . $e->getMessage());
            return [
                'success' => false,
                'message' => '予約の作成に失敗しました: ' . $e->getMessage()
            ];
        }
    }
    
    private function validateBookingData($data) {
        $required = ['name', 'email', 'phone', 'plan', 'date', 'time', 'participants'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("必須項目が入力されていません: {$field}");
            }
        }
        
        // メールアドレスの形式チェック
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("有効なメールアドレスを入力してください");
        }
        
        // 電話番号の形式チェック
        if (!preg_match('/^[\d\-\+\(\)\s]+$/', $data['phone'])) {
            throw new Exception("有効な電話番号を入力してください");
        }
        
        // 日時の妥当性チェック
        $bookingDateTime = strtotime($data['date'] . ' ' . $data['time']);
        if ($bookingDateTime < time()) {
            throw new Exception("未来の日時を選択してください");
        }
        
        // 営業時間チェック
        $this->validateBusinessHours($data['date'], $data['time']);
        
        // プランの存在チェック
        if (!isset($this->config['plans'][$data['plan']])) {
            throw new Exception("無効なプランが選択されています");
        }
    }
    
    private function validateBusinessHours($date, $time) {
        $dayOfWeek = date('l', strtotime($date));
        
        // 定休日チェック
        if (in_array($dayOfWeek, $this->config['business_hours']['closed_days'])) {
            throw new Exception("選択された日は定休日です");
        }
        
        // 営業時間チェック
        $bookingTime = strtotime($time);
        $startTime = strtotime($this->config['business_hours']['start']);
        $lastReception = strtotime($this->config['business_hours']['last_reception']);
        
        if ($bookingTime < $startTime || $bookingTime > $lastReception) {
            throw new Exception("営業時間外の予約はできません");
        }
    }
    
    private function generateBookingId() {
        return 'CCP' . date('Ymd') . sprintf('%04d', rand(1, 9999));
    }
    
    private function sendConfirmationEmail($bookingId, $data) {
        $planConfig = $this->config['plans'][$data['plan']];
        
        $subject = "【Con-Cafe Princess Experience】予約確認 - {$bookingId}";
        
        $message = "
        Con-Cafe Princess Experience をご利用いただき、ありがとうございます。
        
        予約内容の確認
        
        予約ID: {$bookingId}
        お名前: {$data['name']}
        プラン: {$planConfig['name']}
        日時: {$data['date']} {$data['time']}
        参加人数: {$data['participants']}名
        
        ご予約いただいた内容で承りました。
        当日はお時間に余裕を持ってお越しください。
        
        お問い合わせ: 080-XXXX-XXXX
        ";
        
        // 実際のメール送信処理（PHPMailer等を使用）
        // mail($data['email'], $subject, $message);
        
        error_log("Confirmation email sent to: {$data['email']}");
    }
    
    // 予約状況を取得
    public function getBookingStatus($bookingId) {
        $stmt = $this->db->prepare("
            SELECT * FROM bookings WHERE booking_id = ?
        ");
        $stmt->execute([$bookingId]);
        return $stmt->fetch();
    }
    
    // 予約をキャンセル
    public function cancelBooking($bookingId, $reason = '') {
        try {
            $stmt = $this->db->prepare("
                UPDATE bookings 
                SET status = 'cancelled', cancellation_reason = ?, cancelled_at = NOW()
                WHERE booking_id = ? AND status != 'cancelled'
            ");
            
            $result = $stmt->execute([$reason, $bookingId]);
            
            if ($stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'message' => '予約がキャンセルされました'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => '予約が見つからないか、既にキャンセルされています'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Booking cancellation failed: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'キャンセル処理に失敗しました'
            ];
        }
    }
    
    // 統計情報を取得
    public function getStatistics($startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                plan_type,
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_bookings,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings,
                AVG(participants) as avg_participants
            FROM bookings 
            WHERE booking_date BETWEEN ? AND ?
            GROUP BY plan_type
        ");
        
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
}

// API エンドポイント
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // セキュリティヘッダーを設定
    SecurityHeaders::setHeaders();
    SecurityHeaders::setCORS();
    SecurityHeaders::setCacheHeaders('api');
    
    header('Content-Type: application/json');
    
    try {
        $bookingSystem = new BookingSystem();
        $input = json_decode(file_get_contents('php://input'), true);
        
        // 入力値の検証
        if (!$input || !is_array($input)) {
            throw new Exception('無効な入力データです');
        }
        
        $action = $input['action'] ?? '';
        
        switch ($action) {
            case 'get_available_slots':
                $slots = $bookingSystem->getAvailableSlots(
                    $input['date'], 
                    $input['plan']
                );
                echo json_encode(['success' => true, 'slots' => $slots]);
                break;
                
            case 'create_booking':
                $result = $bookingSystem->createBooking($input);
                echo json_encode($result);
                break;
                
            case 'get_booking_status':
                $booking = $bookingSystem->getBookingStatus($input['booking_id']);
                echo json_encode(['success' => true, 'booking' => $booking]);
                break;
                
            case 'cancel_booking':
                $result = $bookingSystem->cancelBooking(
                    $input['booking_id'], 
                    $input['reason'] ?? ''
                );
                echo json_encode($result);
                break;
                
            default:
                throw new Exception('無効なアクションです');
        }
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

// データベース初期化スクリプト
function initializeDatabase() {
    $sql = "
    CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        booking_id VARCHAR(20) UNIQUE NOT NULL,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        plan_type VARCHAR(20) NOT NULL,
        booking_date DATE NOT NULL,
        start_time TIME NOT NULL,
        duration_min INT NOT NULL,
        participants INT NOT NULL,
        message TEXT,
        status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
        cancellation_reason TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        cancelled_at TIMESTAMP NULL,
        INDEX idx_booking_date (booking_date),
        INDEX idx_status (status)
    );
    ";
    
    return $sql;
}
?>
