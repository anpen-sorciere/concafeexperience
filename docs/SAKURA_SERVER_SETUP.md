# Con-Cafe Princess Experience - さくらサーバー設定ガイド

## さくらサーバー環境でのセットアップ手順

### 1. 前提条件
- さくらサーバーのレンタルサーバー
- MySQL データベース: `purplelion51_concafe_exp`
- PHP 7.4以上（さくらサーバー標準）

### 2. データベース接続情報
```
ホスト: mysql2103.db.sakura.ne.jp
データベース名: purplelion51_concafe_exp
ユーザー名: purplelion51
パスワード: -6r_am73
```

### 3. ファイルのアップロード

#### 3.1 FTP/SFTPでファイルをアップロード
```bash
# さくらサーバーのWebルートディレクトリにアップロード
# 通常は /home/purplelion51/www/ または /home/purplelion51/public_html/
```

#### 3.2 アップロードするファイル
```
index.html
styles.css
script.js
booking_system.php
config.php
deploy.php
database_schema.sql
translations.json
sns_strategy.js
business_plan.md
README.md
SERVER_SETUP.md
```

### 4. データベースの初期化

#### 4.1 さくらサーバーのphpMyAdminを使用
1. さくらサーバーのコントロールパネルにログイン
2. phpMyAdminにアクセス
3. `purplelion51_concafe_exp` データベースを選択
4. `database_schema.sql` の内容をインポート

#### 4.2 コマンドラインから実行（SSH接続可能な場合）
```bash
# SSH接続
ssh purplelion51@your-server.sakura.ne.jp

# MySQL接続
mysql -h mysql2103.db.sakura.ne.jp -u purplelion51 -p purplelion51_concafe_exp

# SQLファイルを実行
source database_schema.sql;
```

### 5. 自動デプロイメントの実行

#### 5.1 ブラウザから実行
```
http://your-domain.com/deploy.php
```

#### 5.2 コマンドラインから実行（SSH接続可能な場合）
```bash
cd /home/purplelion51/www/
php deploy.php
```

### 6. さくらサーバー特有の設定

#### 6.1 .htaccess設定
```apache
# さくらサーバー用の設定
RewriteEngine On

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

<Files "database_schema.sql">
    Order allow,deny
    Deny from all
</Files>

# PHP設定
php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value memory_limit 128M
php_value max_execution_time 300
```

#### 6.2 ディレクトリ権限の設定
```bash
# 必要なディレクトリを作成
mkdir -p uploads logs cache tmp

# 権限を設定
chmod 755 uploads logs cache tmp
chmod 644 *.php *.html *.css *.js *.json *.md *.sql
```

### 7. 動作確認

#### 7.1 データベース接続テスト
```php
<?php
// test_db.php
require_once 'config.php';
try {
    $db = Config::getDatabase();
    echo "データベース接続成功！<br>";
    
    // テーブルの存在確認
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "テーブル一覧: " . implode(', ', $tables);
    
} catch (Exception $e) {
    echo "データベース接続失敗: " . $e->getMessage();
}
?>
```

#### 7.2 ホームページの表示確認
```
http://your-domain.com/
```

#### 7.3 予約システムのテスト
1. ホームページの「お問い合わせ・予約」セクションにアクセス
2. テスト用の予約フォームを送信
3. データベースに予約が正しく保存されることを確認

### 8. さくらサーバー特有の注意点

#### 8.1 ファイルアップロード制限
- さくらサーバーではファイルアップロードに制限があります
- `php.ini` の設定を確認してください
- 必要に応じて `.htaccess` で設定を調整

#### 8.2 メール送信設定
```php
// config.php でさくらサーバーのメール設定
const MAIL_CONFIG = [
    'smtp_host' => 'mail.sakura.ne.jp', // さくらサーバーのSMTP
    'smtp_port' => 587,
    'smtp_username' => 'purplelion51@sakura.ne.jp', // さくらサーバーのメールアドレス
    'smtp_password' => 'your_email_password',
    'from_email' => 'purplelion51@sakura.ne.jp',
    'from_name' => 'Con-Cafe Princess Experience',
    'reply_to' => 'purplelion51@sakura.ne.jp'
];
```

#### 8.3 ログファイルの場所
```php
// さくらサーバーではログファイルの場所に注意
const LOG_CONFIG = [
    'enabled' => true,
    'level' => 'INFO',
    'path' => '/home/purplelion51/logs/', // さくらサーバーのログディレクトリ
    'max_files' => 30,
    'max_size' => 10 * 1024 * 1024
];
```

### 9. トラブルシューティング

#### 9.1 よくある問題と解決方法

**データベース接続エラー**
```php
// 接続情報を再確認
$host = "mysql2103.db.sakura.ne.jp";
$user = "purplelion51";
$password = "-6r_am73";
$dbname = "purplelion51_concafe_exp";
```

**ファイル権限エラー**
```bash
# 権限を修正
chmod 755 uploads logs cache tmp
chmod 644 *.php *.html *.css *.js
```

**PHPエラー**
```bash
# エラーログを確認
tail -f /home/purplelion51/logs/error.log
```

#### 9.2 さくらサーバーのサポート
- さくらサーバーのサポートセンターに問い合わせ
- コントロールパネルでサーバー状態を確認
- ログファイルでエラーを確認

### 10. セキュリティ設定

#### 10.1 さくらサーバー用のセキュリティ設定
```php
// config.php で本番環境に設定
const APP_CONFIG = [
    'environment' => 'production',
    'timezone' => 'Asia/Tokyo',
    'locale' => 'ja'
];

const DEBUG_CONFIG = [
    'enabled' => false, // 本番環境では無効
    'show_errors' => false,
    'log_queries' => false
];
```

#### 10.2 SSL証明書の設定
- さくらサーバーのコントロールパネルでSSL証明書を設定
- Let's Encryptの無料SSL証明書を使用可能

### 11. バックアップ設定

#### 11.1 データベースバックアップ
```bash
# さくらサーバーのコントロールパネルから
# データベースのバックアップ機能を使用
```

#### 11.2 ファイルバックアップ
```bash
# FTP/SFTPでファイルをダウンロード
# またはさくらサーバーのバックアップ機能を使用
```

### 12. パフォーマンス最適化

#### 12.1 さくらサーバーでの最適化
```php
// キャッシュの設定
const CACHE_CONFIG = [
    'enabled' => true,
    'driver' => 'file',
    'path' => '/home/purplelion51/cache/',
    'ttl' => 3600
];
```

#### 12.2 データベース最適化
```sql
-- インデックスの確認
SHOW INDEX FROM bookings;

-- テーブルの最適化
OPTIMIZE TABLE bookings;
```

### 13. 完了確認チェックリスト

- [ ] ファイルが正しくアップロードされている
- [ ] データベースにテーブルが作成されている
- [ ] ホームページが正常に表示される
- [ ] 多言語切り替えが動作する
- [ ] 予約フォームが正常に動作する
- [ ] データベースに予約が保存される
- [ ] メール通知が送信される
- [ ] エラーログにエラーが記録されていない
- [ ] SSL証明書が設定されている
- [ ] バックアップが設定されている

### 14. アクセスURL

デプロイメント完了後のアクセスURL：
```
メインサイト: http://your-domain.com/
予約システム: http://your-domain.com/#contact
管理画面: http://your-domain.com/admin/ (今後実装予定)
```

---

**注意**: さくらサーバーでは共有サーバー環境のため、リソース制限があります。大量のアクセスがある場合は、専用サーバーへの移行を検討してください。
