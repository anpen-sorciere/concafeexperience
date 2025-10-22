# Con-Cafe Princess Experience - ドキュメント一覧

## 📚 プロジェクトドキュメント

### 🚀 セットアップ・デプロイメント
- **[FTP_UPLOAD_GUIDE.md](FTP_UPLOAD_GUIDE.md)** - FTPアップロード手順
- **[SAKURA_SERVER_SETUP.md](SAKURA_SERVER_SETUP.md)** - さくらサーバー専用セットアップガイド
- **[SERVER_SETUP.md](SERVER_SETUP.md)** - 一般的なサーバーセットアップガイド
- **[WINDOWS_UPLOAD_GUIDE.md](WINDOWS_UPLOAD_GUIDE.md)** - Windows環境でのアップロード手順

### 🏗️ プロジェクト構成
- **[FOLDER_STRUCTURE.md](FOLDER_STRUCTURE.md)** - フォルダ構成ガイド
- **[NO_HTACCESS_GUIDE.md](NO_HTACCESS_GUIDE.md)** - .htaccessなしでの動作ガイド

### 📋 プロジェクト概要
- **[README.md](README.md)** - プロジェクト説明書
- **[business_plan.md](business_plan.md)** - 詳細事業計画書

### 🛠️ 開発・運用ツール
- **[upload_to_sakura.sh](upload_to_sakura.sh)** - Linux/Mac用アップロードスクリプト

## 🎯 プロジェクト概要

**Con-Cafe Princess Experience** は、大阪日本橋オタロードに位置する既存のコンカフェを活用し、外国人観光客をメインターゲットとした体験型サービスです。

### 主な特徴
- 🌍 **多言語対応**: 日本語・英語・中国語
- 📱 **レスポンシブデザイン**: モバイルファースト
- 🔒 **セキュリティ**: PHPファイル内でのセキュリティ設定
- 📊 **予約システム**: リアルタイム空き状況確認
- 📸 **プロ撮影**: 高品質な写真・動画サービス

### 技術スタック
- **フロントエンド**: HTML5, CSS3, JavaScript (ES6+)
- **バックエンド**: PHP 7.4+, MySQL 8.0+
- **サーバー**: さくらサーバー
- **セキュリティ**: カスタムセキュリティヘッダー

## 🚀 クイックスタート

### 1. ファイルのアップロード
```
FTPクライアントで以下のファイルをアップロード:
- index.html
- deploy.php
- assets/ (フォルダごと)
- api/ (フォルダごと)
- data/ (フォルダごと)
```

### 2. データベースの初期化
```
https://purplelion51.sakura.ne.jp/concafeexp/deploy.php
```

### 3. 動作確認
```
https://purplelion51.sakura.ne.jp/concafeexp/
```

## 📁 フォルダ構成

```
concafeexp/
├── index.html              # メインホームページ
├── deploy.php              # デプロイメントスクリプト
├── assets/                 # 静的ファイル
│   ├── css/styles.css
│   ├── js/script.js
│   └── js/sns_strategy.js
├── api/                    # API・PHPファイル
│   ├── booking_system.php
│   ├── config.php
│   ├── security_headers.php
│   └── test_connection.php
├── data/                   # データファイル
│   ├── translations.json
│   └── database_schema.sql
├── uploads/                # アップロードファイル
├── logs/                   # ログファイル
├── cache/                  # キャッシュファイル
└── tmp/                    # 一時ファイル
```

## 🔗 アクセスURL

- **メインサイト**: https://purplelion51.sakura.ne.jp/concafeexp/
- **接続テスト**: https://purplelion51.sakura.ne.jp/concafeexp/api/test_connection.php
- **デプロイメント**: https://purplelion51.sakura.ne.jp/concafeexp/deploy.php

## 📞 サポート

プロジェクトに関するご質問やサポートが必要な場合は、以下のドキュメントを参照してください：

1. **セットアップで困った場合**: [FTP_UPLOAD_GUIDE.md](FTP_UPLOAD_GUIDE.md)
2. **サーバー設定で困った場合**: [SAKURA_SERVER_SETUP.md](SAKURA_SERVER_SETUP.md)
3. **フォルダ構成で困った場合**: [FOLDER_STRUCTURE.md](FOLDER_STRUCTURE.md)
4. **セキュリティで困った場合**: [NO_HTACCESS_GUIDE.md](NO_HTACCESS_GUIDE.md)

---

**Con-Cafe Princess Experience** - 大阪日本橋オタロードで特別なコンカフェ嬢体験を
