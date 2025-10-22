# Con-Cafe Princess Experience - FTPアップロードガイド

## 完成したローカルフォルダ構成

```
ConCafeExperience/
├── index.html              # メインホームページ
├── deploy.php              # デプロイメントスクリプト
├── business_plan.md         # 事業計画書
├── README.md               # プロジェクト説明
├── assets/                 # 静的ファイル
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   │   ├── script.js
│   │   └── sns_strategy.js
│   └── images/             # 画像ファイル（空）
├── api/                    # API・PHPファイル
│   ├── booking_system.php
│   ├── config.php
│   ├── security_headers.php
│   └── test_connection.php
├── data/                   # データファイル
│   ├── translations.json
│   └── database_schema.sql
├── uploads/                # アップロードファイル（空）
├── logs/                   # ログファイル（空）
├── cache/                  # キャッシュファイル（空）
├── tmp/                    # 一時ファイル（空）
└── (その他のドキュメントファイル)
```

## FTPアップロード手順

### 1. FTPクライアントの準備

#### 推奨FTPクライアント
- **FileZilla** (無料、高機能)
- **WinSCP** (無料、Windows特化)
- **Cyberduck** (無料、Mac/Windows対応)

### 2. FTP接続情報

```
ホスト: purplelion51.sakura.ne.jp
ユーザー名: purplelion51
パスワード: (さくらサーバーのパスワード)
ポート: 21 (FTP) または 22 (SFTP)
```

### 3. アップロード先ディレクトリ

```
/home/purplelion51/www/concafeexp/
```

### 4. アップロードするファイル一覧

**注意**: `docs/` フォルダは開発用ドキュメントのため、サーバーにはアップロードしません。

#### 4.1 ルートディレクトリ
```
ConCafeExperience/
├── index.html              → concafeexp/index.html
└── deploy.php              → concafeexp/deploy.php
```

#### 4.2 静的ファイル
```
ConCafeExperience/assets/css/styles.css
→ concafeexp/assets/css/styles.css

ConCafeExperience/assets/js/script.js
→ concafeexp/assets/js/script.js

ConCafeExperience/assets/js/sns_strategy.js
→ concafeexp/assets/js/sns_strategy.js
```

#### 4.3 APIファイル
```
ConCafeExperience/api/booking_system.php
→ concafeexp/api/booking_system.php

ConCafeExperience/api/config.php
→ concafeexp/api/config.php

ConCafeExperience/api/security_headers.php
→ concafeexp/api/security_headers.php

ConCafeExperience/api/test_connection.php
→ concafeexp/api/test_connection.php
```

#### 4.4 データファイル
```
ConCafeExperience/data/translations.json
→ concafeexp/data/translations.json

ConCafeExperience/data/database_schema.sql
→ concafeexp/data/database_schema.sql
```

### 5. FileZillaでのアップロード手順

#### 5.1 接続設定
1. FileZillaを起動
2. ホスト: `purplelion51.sakura.ne.jp`
3. ユーザー名: `purplelion51`
4. パスワード: (さくらサーバーのパスワード)
5. ポート: `21` (FTP) または `22` (SFTP)
6. 「接続」をクリック

#### 5.2 ディレクトリ移動
1. リモート側で `/home/purplelion51/www/` に移動
2. `concafeexp` フォルダが存在しない場合は作成
3. `concafeexp` フォルダに移動

#### 5.3 ファイルアップロード
1. ローカル側: `C:\xampp\htdocs\ConCafeExperience\`
2. リモート側: `/home/purplelion51/www/concafeexp/`
3. 以下のファイルをドラッグ&ドロップでアップロード：

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
- `security_headers.php`
- `test_connection.php`

**data/:**
- `translations.json`
- `database_schema.sql`

### 6. WinSCPでのアップロード手順

#### 6.1 接続設定
1. WinSCPを起動
2. ホスト名: `purplelion51.sakura.ne.jp`
3. ユーザー名: `purplelion51`
4. パスワード: (さくらサーバーのパスワード)
5. プロトコル: `SFTP` または `FTP`
6. 「ログイン」をクリック

#### 6.2 ファイルアップロード
1. 左側（ローカル）: `C:\xampp\htdocs\ConCafeExperience\`
2. 右側（リモート）: `/home/purplelion51/www/concafeexp/`
3. ファイルを選択してアップロード

### 7. 権限設定

#### 7.1 ディレクトリ権限
```
uploads/    755
logs/       755
cache/      755
tmp/        755
```

#### 7.2 ファイル権限
```
*.html       644
*.php        644
*.css        644
*.js         644
*.json       644
*.sql        644
```

### 8. 動作確認

#### 8.1 接続テスト
```
https://purplelion51.sakura.ne.jp/concafeexp/api/test_connection.php
```

#### 8.2 デプロイメント
```
https://purplelion51.sakura.ne.jp/concafeexp/deploy.php
```

#### 8.3 メインサイト
```
https://purplelion51.sakura.ne.jp/concafeexp/
```

### 9. トラブルシューティング

#### 9.1 よくある問題

**ファイルが見つからない**
- ファイルパスを確認
- 大文字小文字を確認
- ファイル名の文字化けを確認

**権限エラー**
- ディレクトリの権限を755に設定
- ファイルの権限を644に設定

**データベース接続エラー**
- `api/config.php`の接続情報を確認
- さくらサーバーのMySQL設定を確認

#### 9.2 ログの確認
```bash
# エラーログの確認
tail -f /home/purplelion51/logs/error.log
```

### 10. 完了確認チェックリスト

- [ ] すべてのファイルが正しい場所にアップロードされている
- [ ] ディレクトリの権限が755に設定されている
- [ ] ファイルの権限が644に設定されている
- [ ] 接続テストが成功している
- [ ] デプロイメントが正常に完了している
- [ ] ホームページが正常に表示されている
- [ ] 予約フォームが正常に動作している
- [ ] 多言語切り替えが動作している

### 11. アップロード後の確認

#### 11.1 ファイル構造の確認
FTPクライアントで以下の構造になっていることを確認：

```
concafeexp/
├── index.html
├── deploy.php
├── assets/
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   │   ├── script.js
│   │   └── sns_strategy.js
│   └── images/
├── api/
│   ├── booking_system.php
│   ├── config.php
│   ├── security_headers.php
│   └── test_connection.php
├── data/
│   ├── translations.json
│   └── database_schema.sql
├── uploads/
├── logs/
├── cache/
└── tmp/
```

#### 11.2 動作確認の順序
1. **接続テスト**: `test_connection.php`でデータベース接続を確認
2. **デプロイメント**: `deploy.php`でデータベースを初期化
3. **ホームページ**: メインサイトの表示確認
4. **予約フォーム**: フォームの動作確認
5. **多言語**: 言語切り替えの確認

---

**注意**: アップロード前に必ずバックアップを取ってください。また、本番環境での作業は慎重に行ってください。
