# Con-Cafe Princess Experience - Windows環境でのアップロードガイド

## さくらサーバーへのファイルアップロード手順

### 1. FTPクライアントの準備
推奨FTPクライアント：
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

### 3. フォルダ構成の作成

#### 3.1 サーバー側のディレクトリ作成
FTPクライアントで以下のディレクトリを作成：

```
/home/purplelion51/www/concafeexp/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── api/
├── data/
├── uploads/
├── logs/
├── cache/
└── tmp/
```

#### 3.2 ローカル側のフォルダ構成
```
ConCafeExperience/
├── index.html
├── deploy.php
├── .htaccess
├── assets/
│   ├── css/
│   │   └── styles.css
│   └── js/
│       ├── script.js
│       └── sns_strategy.js
├── api/
│   ├── booking_system.php
│   ├── config.php
│   └── test_connection.php
├── data/
│   ├── translations.json
│   └── database_schema.sql
└── README.md
```

### 4. ファイルアップロード手順

#### 4.1 FileZillaでのアップロード
1. FileZillaを起動
2. 接続情報を入力して接続
3. ローカル側: `C:\xampp\htdocs\ConCafeExperience\`
4. リモート側: `/home/purplelion51/www/concafeexp/`
5. 以下のファイルをドラッグ&ドロップでアップロード：

**ルートディレクトリ:**
- `index.html`
- `deploy.php`
- `.htaccess`

**assets/css/:**
- `styles.css`

**assets/js/:**
- `script.js`
- `sns_strategy.js`

**api/:**
- `booking_system.php`
- `config.php`
- `test_connection.php`

**data/:**
- `translations.json`
- `database_schema.sql`

#### 4.2 WinSCPでのアップロード
1. WinSCPを起動
2. 接続情報を入力
3. 左側（ローカル）: `C:\xampp\htdocs\ConCafeExperience\`
4. 右側（リモート）: `/home/purplelion51/www/concafeexp/`
5. ファイルを選択してアップロード

### 5. 権限設定

#### 5.1 ディレクトリ権限
```
uploads/    755
logs/       755
cache/      755
tmp/        755
```

#### 5.2 ファイル権限
```
*.html       644
*.php        644
*.css        644
*.js         644
*.json       644
*.sql        644
```

### 6. 動作確認

#### 6.1 接続テスト
```
https://purplelion51.sakura.ne.jp/concafeexp/api/test_connection.php
```

#### 6.2 デプロイメント
```
https://purplelion51.sakura.ne.jp/concafeexp/deploy.php
```

#### 6.3 メインサイト
```
https://purplelion51.sakura.ne.jp/concafeexp/
```

### 7. トラブルシューティング

#### 7.1 よくある問題

**ファイルが見つからない**
- ファイルパスを確認
- 大文字小文字を確認
- ファイル名の文字化けを確認

**権限エラー**
- ディレクトリの権限を755に設定
- ファイルの権限を644に設定

**データベース接続エラー**
- `config.php`の接続情報を確認
- さくらサーバーのMySQL設定を確認

#### 7.2 ログの確認
```bash
# エラーログの確認
tail -f /home/purplelion51/logs/error.log
```

### 8. セキュリティ設定

#### 8.1 .htaccessの確認
- 機密ファイルの保護設定
- セキュリティヘッダーの設定
- ディレクトリブラウジングの無効化

#### 8.2 ファイル権限の確認
```bash
# 権限の確認
ls -la /home/purplelion51/www/concafeexp/
```

### 9. バックアップ

#### 9.1 ファイルのバックアップ
- FTPクライアントでファイルをダウンロード
- 定期的なバックアップの実施

#### 9.2 データベースのバックアップ
- さくらサーバーのコントロールパネルから
- phpMyAdminからエクスポート

### 10. 完了確認チェックリスト

- [ ] すべてのファイルが正しい場所にアップロードされている
- [ ] ディレクトリの権限が755に設定されている
- [ ] ファイルの権限が644に設定されている
- [ ] .htaccessが正しく設定されている
- [ ] 接続テストが成功している
- [ ] デプロイメントが正常に完了している
- [ ] ホームページが正常に表示されている
- [ ] 予約フォームが正常に動作している

### 11. 今後のメンテナンス

#### 11.1 定期的な確認
- ログファイルの確認
- エラーログの監視
- パフォーマンスの監視

#### 11.2 アップデート
- ファイルの更新時はバックアップを取る
- テスト環境での動作確認
- 本番環境への反映

---

**注意**: アップロード前に必ずバックアップを取ってください。また、本番環境での作業は慎重に行ってください。
