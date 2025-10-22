# Con-Cafe Princess Experience - .htaccessなしでの動作ガイド

## .htaccessファイルを削除した理由

さくらサーバーでは、.htaccessファイルが他のプロジェクトに影響を与えることがあります。そのため、.htaccessファイルを削除し、代わりにPHPファイル内でセキュリティ設定を行うように変更しました。

## 新しいセキュリティ設定

### 1. security_headers.php
セキュリティヘッダーとセキュリティ機能を提供する新しいファイルを作成しました：

```php
// api/security_headers.php
- X-Content-Type-Options
- X-Frame-Options
- X-XSS-Protection
- Referrer-Policy
- CORS設定
- レート制限
- CSRF保護
- セッション設定
- セキュリティログ
```

### 2. 各PHPファイルでの自動適用
以下のファイルで自動的にセキュリティヘッダーが適用されます：

- `api/booking_system.php`
- `api/test_connection.php`
- `api/config.php`

## フォルダ構成（更新版）

```
concafeexp/
├── index.html              # メインホームページ
├── deploy.php              # デプロイメントスクリプト
├── assets/                  # 静的ファイル
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   │   ├── script.js
│   │   └── sns_strategy.js
│   └── images/             # 画像ファイル
├── api/                     # API・PHPファイル
│   ├── booking_system.php
│   ├── config.php
│   ├── test_connection.php
│   └── security_headers.php # セキュリティ設定
├── data/                    # データファイル
│   ├── translations.json
│   └── database_schema.sql
├── uploads/                 # アップロードファイル
├── logs/                    # ログファイル
├── cache/                   # キャッシュファイル
├── tmp/                     # 一時ファイル
└── README.md               # プロジェクト説明
```

## セキュリティ機能

### 1. 自動セキュリティヘッダー
```php
// 自動的に設定されるヘッダー
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
```

### 2. レート制限
```php
// 1時間あたり100リクエストまで
// 制限を超えた場合は429エラーを返す
```

### 3. CSRF保護
```php
// CSRFトークンの自動生成と検証
$token = SecurityHeaders::generateCSRFToken();
$isValid = SecurityHeaders::validateCSRFToken($token);
```

### 4. 入力値検証
```php
// SQLインジェクション対策
// XSS対策
// ファイルアップロード検証
```

### 5. セキュリティログ
```php
// セキュリティイベントの自動記録
// /logs/security.log に記録
```

## アップロード手順

### 1. ファイルのアップロード
以下のファイルをアップロードしてください：

**ルートディレクトリ:**
- `index.html`
- `deploy.php`

**assets/css/:**
- `styles.css`

**assets/js/:**
- `script.js`
- `sns_strategy.js`

**api/:**
- `booking_system.php`
- `config.php`
- `test_connection.php`
- `security_headers.php` ← 新規追加

**data/:**
- `translations.json`
- `database_schema.sql`

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
chmod 644 concafeexp/api/*.php
chmod 644 concafeexp/data/*.json
chmod 644 concafeexp/data/*.sql
```

## 動作確認

### 1. 接続テスト
```
https://purplelion51.sakura.ne.jp/concafeexp/api/test_connection.php
```

### 2. デプロイメント
```
https://purplelion51.sakura.ne.jp/concafeexp/deploy.php
```

### 3. メインサイト
```
https://purplelion51.sakura.ne.jp/concafeexp/
```

## セキュリティログの確認

### ログファイルの場所
```
/home/purplelion51/www/concafeexp/logs/security.log
```

### ログの内容例
```
[2024-12-01 10:30:15] IP: 192.168.1.100 | Event: RATE_LIMIT_EXCEEDED | Details: IP: 192.168.1.100 | User-Agent: Mozilla/5.0...
[2024-12-01 10:35:22] IP: 192.168.1.101 | Event: INVALID_INPUT | Details: SQL injection attempt | User-Agent: Mozilla/5.0...
```

## トラブルシューティング

### 1. よくある問題

**セキュリティヘッダーが設定されない**
- `security_headers.php` が正しい場所にあるか確認
- ファイルの権限が644になっているか確認

**レート制限エラー**
- 一時的に制限を緩和する場合は `security_headers.php` の設定を変更

**CSRFエラー**
- セッションが正しく開始されているか確認

### 2. デバッグ方法

**セキュリティヘッダーの確認**
```php
// ブラウザの開発者ツールでNetworkタブを確認
// Response Headersにセキュリティヘッダーが含まれているか確認
```

**ログの確認**
```bash
tail -f /home/purplelion51/www/concafeexp/logs/security.log
```

## 利点

### 1. 他のプロジェクトへの影響なし
- .htaccessファイルがないため、他のプロジェクトに影響しません

### 2. 柔軟な設定
- PHPファイル内で設定を変更できるため、より柔軟です

### 3. 詳細なログ
- セキュリティイベントの詳細なログが記録されます

### 4. 動的な制御
- 実行時にセキュリティ設定を動的に変更できます

## 完了確認チェックリスト

- [ ] .htaccessファイルが削除されている
- [ ] security_headers.phpがアップロードされている
- [ ] すべてのPHPファイルが正しく動作している
- [ ] セキュリティヘッダーが設定されている
- [ ] レート制限が動作している
- [ ] セキュリティログが記録されている
- [ ] ホームページが正常に表示されている
- [ ] 予約フォームが正常に動作している

---

**注意**: .htaccessファイルを削除したことで、Apacheの設定による制限はなくなりますが、PHPファイル内でのセキュリティ設定により、同等以上のセキュリティが確保されています。
