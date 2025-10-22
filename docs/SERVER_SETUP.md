# Con-Cafe Princess Experience - サーバー設定ガイド

## 既存サーバー環境でのセットアップ手順

### 1. 前提条件
- PHP 7.4以上
- MySQL 8.0以上
- Apache/Nginx
- 既存のサーバー環境でDBが利用可能

### 2. ファイルのアップロード
```bash
# サーバーのWebルートディレクトリにファイルをアップロード
# 例: /var/www/html/con-cafe-princess/
```

### 3. データベース設定

#### 3.1 データベースの作成
```sql
-- サーバーのMySQLに接続
mysql -u root -p

-- データベースを作成
CREATE DATABASE con_cafe_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ユーザーを作成（既存のユーザーを使用する場合はスキップ）
CREATE USER 'con_cafe_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON con_cafe_booking.* TO 'con_cafe_user'@'localhost';
FLUSH PRIVILEGES;
```

#### 3.2 設定ファイルの更新
`config.php` の以下の部分を実際のサーバー環境に合わせて変更：

```php
const DB_CONFIG = [
    'host' => 'localhost', // サーバーのDBホスト
    'dbname' => 'con_cafe_booking',
    'username' => 'your_actual_db_username', // 実際のDBユーザー名
    'password' => 'your_actual_db_password', // 実際のDBパスワード
    'charset' => 'utf8mb4',
    // ...
];
```

### 4. 自動デプロイメントの実行

#### 4.1 コマンドラインから実行
```bash
# サーバーにSSH接続
ssh your-server

# プロジェクトディレクトリに移動
cd /var/www/html/con-cafe-princess/

# デプロイメントスクリプトを実行
php deploy.php
```

#### 4.2 ブラウザから実行
```
http://your-domain.com/deploy.php
```

### 5. 手動セットアップ（オプション）

#### 5.1 データベーススキーマの実行
```bash
# SQLファイルを直接実行
mysql -u your_username -p con_cafe_booking < database_schema.sql
```

#### 5.2 ディレクトリの作成
```bash
mkdir -p uploads logs cache tmp
chmod 755 uploads logs cache tmp
```

### 6. 設定の確認

#### 6.1 データベース接続テスト
```php
<?php
require_once 'config.php';
try {
    $db = Config::getDatabase();
    echo "データベース接続成功！";
} catch (Exception $e) {
    echo "データベース接続失敗: " . $e->getMessage();
}
?>
```

#### 6.2 ファイル権限の確認
```bash
ls -la
# 以下のディレクトリが755権限であることを確認
# drwxr-xr-x uploads
# drwxr-xr-x logs
# drwxr-xr-x cache
# drwxr-xr-x tmp
```

### 7. 本番環境の設定

#### 7.1 セキュリティ設定
```php
// config.php で本番環境に設定
const APP_CONFIG = [
    'environment' => 'production', // 本番環境
    // ...
];

const DEBUG_CONFIG = [
    'enabled' => false, // デバッグ無効
    'show_errors' => false, // エラー表示無効
    // ...
];
```

#### 7.2 メール設定
```php
// config.php で実際のSMTP設定
const MAIL_CONFIG = [
    'smtp_host' => 'your-smtp-server.com',
    'smtp_port' => 587,
    'smtp_username' => 'your-smtp-username',
    'smtp_password' => 'your-smtp-password',
    // ...
];
```

### 8. 動作確認

#### 8.1 ホームページの表示
```
http://your-domain.com/
```

#### 8.2 予約システムのテスト
1. ホームページの「お問い合わせ・予約」セクションにアクセス
2. テスト用の予約フォームを送信
3. データベースに予約が正しく保存されることを確認

#### 8.3 多言語機能のテスト
1. ナビゲーションバーの言語ボタンをクリック
2. 日本語、英語、中国語の切り替えが正常に動作することを確認

### 9. トラブルシューティング

#### 9.1 よくある問題と解決方法

**データベース接続エラー**
```bash
# エラーログを確認
tail -f /var/log/apache2/error.log
# または
tail -f /var/log/nginx/error.log
```

**ファイル権限エラー**
```bash
# 権限を修正
chmod -R 755 /var/www/html/con-cafe-princess/
chown -R www-data:www-data /var/www/html/con-cafe-princess/
```

**PHPエラー**
```bash
# PHPエラーログを確認
tail -f /var/log/php/error.log
```

#### 9.2 ログファイルの確認
```bash
# アプリケーションログ
tail -f logs/app.log

# エラーログ
tail -f logs/error.log
```

### 10. パフォーマンス最適化

#### 10.1 データベース最適化
```sql
-- インデックスの確認
SHOW INDEX FROM bookings;

-- クエリの最適化
EXPLAIN SELECT * FROM bookings WHERE booking_date = '2024-12-01';
```

#### 10.2 キャッシュの設定
```php
// config.php でキャッシュを有効化
const CACHE_CONFIG = [
    'enabled' => true,
    'driver' => 'file',
    'path' => '/cache/',
    'ttl' => 3600
];
```

### 11. バックアップ設定

#### 11.1 データベースバックアップ
```bash
# 日次バックアップスクリプト
#!/bin/bash
mysqldump -u your_username -p con_cafe_booking > backup_$(date +%Y%m%d).sql
```

#### 11.2 ファイルバックアップ
```bash
# ファイルのバックアップ
tar -czf backup_$(date +%Y%m%d).tar.gz /var/www/html/con-cafe-princess/
```

### 12. 監視設定

#### 12.1 システム監視
```bash
# ディスク使用量の監視
df -h

# メモリ使用量の監視
free -h

# プロセス監視
ps aux | grep php
```

#### 12.2 アプリケーション監視
```php
// ヘルスチェックエンドポイント
<?php
require_once 'config.php';
try {
    $db = Config::getDatabase();
    $db->query("SELECT 1");
    echo json_encode(['status' => 'healthy', 'timestamp' => date('Y-m-d H:i:s')]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'unhealthy', 'error' => $e->getMessage()]);
}
?>
```

### 13. セキュリティ強化

#### 13.1 .htaccess設定
```apache
# セキュリティヘッダー
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"

# 機密ファイルの保護
<Files "config.php">
    Order allow,deny
    Deny from all
</Files>

<Files "deploy.php">
    Order allow,deny
    Deny from all
</Files>
```

#### 13.2 SSL証明書の設定
```bash
# Let's Encryptを使用したSSL設定
certbot --apache -d your-domain.com
```

### 14. 完了確認

デプロイメントが完了したら、以下の項目を確認してください：

- [ ] ホームページが正常に表示される
- [ ] 多言語切り替えが動作する
- [ ] 予約フォームが正常に動作する
- [ ] データベースに予約が保存される
- [ ] メール通知が送信される
- [ ] 管理画面にアクセスできる
- [ ] ログファイルが正常に作成される
- [ ] エラーログにエラーが記録されていない

---

**注意**: 本番環境では必ずセキュリティ設定を確認し、適切なバックアップと監視を設定してください。
