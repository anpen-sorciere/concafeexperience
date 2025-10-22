# Con-Cafe Princess Experience - フォルダ構成ガイド

## さくらサーバーでの推奨フォルダ構成

### 基本URL
```
https://purplelion51.sakura.ne.jp/concafeexp/
```

### 推奨フォルダ構成
```
concafeexp/
├── index.html              # メインホームページ
├── assets/                  # 静的ファイル
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   │   ├── script.js
│   │   └── sns_strategy.js
│   ├── images/             # 画像ファイル
│   └── fonts/              # フォントファイル
├── api/                     # API・PHPファイル
│   ├── booking_system.php
│   ├── config.php
│   ├── test_connection.php
│   └── security_headers.php
├── admin/                   # 管理画面（将来実装）
│   ├── index.php
│   └── login.php
├── data/                    # データファイル
│   ├── translations.json
│   └── database_schema.sql
├── uploads/                 # アップロードファイル
├── logs/                    # ログファイル
├── cache/                   # キャッシュファイル
├── tmp/                     # 一時ファイル
├── deploy.php               # デプロイメントスクリプト
└── README.md               # プロジェクト説明
```

## ファイル配置手順

### 1. メインファイル
```
concafeexp/
├── index.html              # ルートに配置
└── deploy.php              # ルートに配置
```

### 2. 静的ファイル
```
concafeexp/assets/
├── css/
│   └── styles.css
├── js/
│   ├── script.js
│   └── sns_strategy.js
├── images/
│   ├── hero-bg.jpg
│   ├── gallery/
│   └── icons/
└── fonts/
    └── (フォントファイル)
```

### 3. API・PHPファイル
```
concafeexp/api/
├── booking_system.php
├── config.php
├── test_connection.php
├── mail_handler.php        # メール送信処理
└── admin/                  # 管理画面
    ├── index.php
    ├── login.php
    └── dashboard.php
```

### 4. データファイル
```
concafeexp/data/
├── translations.json
├── database_schema.sql
├── plans.json              # プラン設定
└── settings.json           # システム設定
```

### 5. システムディレクトリ
```
concafeexp/
├── uploads/                 # ユーザーアップロード
├── logs/                    # システムログ
├── cache/                   # キャッシュ
└── tmp/                     # 一時ファイル
```

## パス設定の更新

### index.html の更新
```html
<!-- CSS -->
<link rel="stylesheet" href="assets/css/styles.css">

<!-- JavaScript -->
<script src="assets/js/script.js"></script>
<script src="assets/js/sns_strategy.js"></script>

<!-- 画像 -->
<img src="assets/images/hero-bg.jpg" alt="Hero Background">
```

### config.php の更新
```php
// パス設定
const PATH_CONFIG = [
    'base_url' => 'https://purplelion51.sakura.ne.jp/concafeexp/',
    'assets_url' => 'https://purplelion51.sakura.ne.jp/concafeexp/assets/',
    'api_url' => 'https://purplelion51.sakura.ne.jp/concafeexp/api/',
    'upload_path' => '/home/purplelion51/www/concafeexp/uploads/',
    'log_path' => '/home/purplelion51/www/concafeexp/logs/',
    'cache_path' => '/home/purplelion51/www/concafeexp/cache/',
    'tmp_path' => '/home/purplelion51/www/concafeexp/tmp/'
];
```

### booking_system.php の更新
```php
// API エンドポイントの設定
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    // CORS設定（必要に応じて）
    header('Access-Control-Allow-Origin: https://purplelion51.sakura.ne.jp');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    
    // 処理...
}
```

## .htaccess 設定

### ルートディレクトリの .htaccess
```apache
RewriteEngine On

# セキュリティヘッダー
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"

# 機密ファイルの保護
<Files "deploy.php">
    Order allow,deny
    Deny from all
</Files>

<Files "*.sql">
    Order allow,deny
    Deny from all
</Files>

# API ルーティング
RewriteRule ^api/(.*)$ api/$1 [L]

# 管理画面ルーティング
RewriteRule ^admin/(.*)$ admin/$1 [L]

# 静的ファイルのキャッシュ設定
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
</IfModule>

# PHP設定
php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value memory_limit 128M
php_value max_execution_time 300
```

## アップロード手順

### 1. FTP/SFTPでアップロード
```bash
# メインファイル
concafeexp/index.html
concafeexp/deploy.php
concafeexp/.htaccess

# 静的ファイル
concafeexp/assets/css/styles.css
concafeexp/assets/js/script.js
concafeexp/assets/js/sns_strategy.js

# APIファイル
concafeexp/api/booking_system.php
concafeexp/api/config.php
concafeexp/api/test_connection.php

# データファイル
concafeexp/data/translations.json
concafeexp/data/database_schema.sql
```

### 2. ディレクトリの作成
```bash
mkdir -p concafeexp/assets/css
mkdir -p concafeexp/assets/js
mkdir -p concafeexp/assets/images
mkdir -p concafeexp/api
mkdir -p concafeexp/data
mkdir -p concafeexp/uploads
mkdir -p concafeexp/logs
mkdir -p concafeexp/cache
mkdir -p concafeexp/tmp
```

### 3. 権限の設定
```bash
chmod 755 concafeexp/uploads
chmod 755 concafeexp/logs
chmod 755 concafeexp/cache
chmod 755 concafeexp/tmp
chmod 644 concafeexp/*.html
chmod 644 concafeexp/*.php
chmod 644 concafeexp/assets/css/*.css
chmod 644 concafeexp/assets/js/*.js
```

## アクセスURL

### メインサイト
```
https://purplelion51.sakura.ne.jp/concafeexp/
```

### API エンドポイント
```
https://purplelion51.sakura.ne.jp/concafeexp/api/booking_system.php
```

### 接続テスト
```
https://purplelion51.sakura.ne.jp/concafeexp/api/test_connection.php
```

### デプロイメント
```
https://purplelion51.sakura.ne.jp/concafeexp/deploy.php
```

## セキュリティ設定

### 機密ファイルの保護
```apache
# config.php の保護
<Files "config.php">
    Order allow,deny
    Deny from all
</Files>

# データベースファイルの保護
<Files "*.sql">
    Order allow,deny
    Deny from all
</Files>

# ログファイルの保護
<Files "*.log">
    Order allow,deny
    Deny from all
</Files>
```

## パフォーマンス最適化

### 静的ファイルの圧縮
```apache
# Gzip圧縮
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

### キャッシュ設定
```apache
# ブラウザキャッシュ
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
</IfModule>
```

## 完了確認チェックリスト

- [ ] フォルダ構成が正しく作成されている
- [ ] ファイルが適切な場所に配置されている
- [ ] 権限が正しく設定されている
- [ ] .htaccess が正しく設定されている
- [ ] メインサイトが表示される
- [ ] API が正常に動作する
- [ ] 静的ファイルが正しく読み込まれる
- [ ] セキュリティ設定が有効になっている

---

**注意**: この構成により、保守性とセキュリティが向上し、将来的な機能拡張も容易になります。
