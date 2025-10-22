# Con-Cafe Princess Experience - 開発ルール

## 📋 開発ルール

### **デバッグ用ファイルの命名規則**

#### **プリフィックス: `debug_`**
デバッグ用に一時的に作成したPHPファイルは、必ず`debug_`プリフィックスを付けてください。

#### **命名例**
```
debug_set_permissions.php    # 権限設定スクリプト
debug_test_connection.php    # 接続テストスクリプト
debug_database_check.php     # データベース確認スクリプト
debug_file_upload.php         # ファイルアップロードテスト
debug_email_test.php          # メール送信テスト
debug_log_viewer.php          # ログ表示スクリプト
```

### **目的**
1. **識別の容易さ**: デバッグ用ファイルが一目で分かる
2. **一括削除**: `debug_*`で検索して一括削除可能
3. **セキュリティ**: 本番環境にデバッグファイルが残らない
4. **保守性**: 開発用ファイルと本番用ファイルの区別

## 🚨 重要な注意事項

### **本番環境での取り扱い**
- `debug_`プリフィックスのファイルは**必ず削除**してください
- セキュリティリスクとなる可能性があります
- 一時的な使用のみに限定してください

### **削除方法**

#### **WinSCPでの一括削除**
1. WinSCPでリモート側のファイル一覧を表示
2. 検索機能で`debug_`を検索
3. 該当ファイルをすべて選択
4. 右クリック → 「削除」

#### **コマンドラインでの一括削除**
```bash
# デバッグ用ファイルを一括削除
rm debug_*.php
```

#### **PHPスクリプトでの一括削除**
```php
// debug_cleanup.php - デバッグ用ファイル一括削除
$files = glob('debug_*.php');
foreach ($files as $file) {
    if (unlink($file)) {
        echo "削除完了: {$file}\n";
    } else {
        echo "削除失敗: {$file}\n";
    }
}
```

## 📁 ファイル分類

### **本番用ファイル**
```
index.html              # メインホームページ
deploy.php              # デプロイメントスクリプト
api/booking_system.php  # 予約システム
api/config.php          # 設定ファイル
api/security_headers.php # セキュリティヘッダー
api/test_connection.php # 接続テスト（本番用）
```

### **デバッグ用ファイル**
```
debug_set_permissions.php    # 権限設定スクリプト
debug_database_check.php     # データベース確認
debug_file_upload.php        # ファイルアップロードテスト
debug_email_test.php         # メール送信テスト
debug_log_viewer.php         # ログ表示
```

### **開発用ファイル（Git管理）**
```
docs/                   # ドキュメント
.gitignore             # Git除外設定
README.md              # プロジェクト説明
```

## 🔧 開発フロー

### **1. デバッグファイルの作成**
```bash
# デバッグ用ファイルを作成
touch debug_new_feature.php
```

### **2. 開発・テスト**
```php
<?php
// debug_new_feature.php - 新機能のデバッグ
// 注意: このファイルはデバッグ用です。使用後は削除してください。

echo "新機能のデバッグテスト\n";
// デバッグコードを記述
?>
```

### **3. テスト実行**
```
https://purplelion51.sakura.ne.jp/concafeexp/debug_new_feature.php
```

### **4. デバッグファイルの削除**
```bash
# 使用後は必ず削除
rm debug_new_feature.php
```

## 📋 チェックリスト

### **開発時**
- [ ] デバッグ用ファイルに`debug_`プリフィックスを付けた
- [ ] ファイル内にデバッグ用であることを明記した
- [ ] 使用後は削除することを明記した

### **本番デプロイ時**
- [ ] `debug_*.php`ファイルが存在しないことを確認
- [ ] デバッグ用ファイルを一括削除
- [ ] セキュリティチェックを実行

### **定期メンテナンス**
- [ ] `debug_*.php`ファイルの存在確認
- [ ] 不要なデバッグファイルの削除
- [ ] ログファイルの確認

## 🛡️ セキュリティ考慮事項

### **デバッグファイルのリスク**
- 機密情報の露出
- システム情報の漏洩
- 不正アクセスの入口
- パフォーマンスの低下

### **対策**
- 使用後は必ず削除
- 本番環境では作成禁止
- 定期的な監査
- アクセスログの監視

## 📚 参考資料

- **セキュリティガイド**: `docs/NO_HTACCESS_GUIDE.md`
- **デプロイメントガイド**: `docs/FTP_UPLOAD_GUIDE.md`
- **Git管理ガイド**: `docs/GIT_GUIDE.md`

---

**重要**: この開発ルールは、プロジェクトのセキュリティと保守性を確保するために必ず遵守してください。
