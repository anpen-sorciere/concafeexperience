# Con-Cafe Princess Experience

大阪日本橋オタロードで外国人観光客向けのコンカフェ嬢体験サービス

## 🎯 プロジェクト概要

「Con-Cafe Princess Experience」は、大阪日本橋オタロードに位置する既存のコンカフェを活用し、外国人観光客をメインターゲットとした体験型サービスです。日本のポップカルチャーである「コンカフェ」や「メイド」文化に興味を持つ観光客に対し、単なる見学に留まらず、実際に変身し、プロによる撮影を通して思い出を形に残す、付加価値の高い体験を提供します。

## ✨ 主な特徴

- 🌍 **多言語対応**: 日本語・英語・中国語（簡体・繁体）
- 📱 **レスポンシブデザイン**: モバイルファースト対応
- 🔒 **セキュリティ**: PHPファイル内でのセキュリティ設定
- 📊 **予約システム**: リアルタイム空き状況確認
- 📸 **プロ撮影**: 高品質な写真・動画サービス
- 🎨 **3つのプラン**: ライト・ベーシック・プレミアム

## 🛠️ 技術スタック

- **フロントエンド**: HTML5, CSS3, JavaScript (ES6+)
- **バックエンド**: PHP 7.4+, MySQL 8.0+
- **サーバー**: さくらサーバー
- **セキュリティ**: カスタムセキュリティヘッダー
- **多言語**: JSON ベースの翻訳システム

## 📁 プロジェクト構成

```
ConCafeExperience/
├── index.html              # メインホームページ
├── deploy.php              # デプロイメントスクリプト
├── .gitignore              # Git除外設定
├── assets/                 # 静的ファイル
│   ├── css/styles.css
│   ├── js/script.js
│   ├── js/sns_strategy.js
│   └── images/
├── api/                    # API・PHPファイル
│   ├── booking_system.php
│   ├── config.php
│   ├── security_headers.php
│   └── test_connection.php
├── data/                   # データファイル
│   ├── database_schema.sql
│   └── translations.json
├── docs/                   # ドキュメント
│   └── INDEX.md
├── uploads/                # アップロードファイル
├── logs/                   # ログファイル
├── cache/                  # キャッシュファイル
└── tmp/                    # 一時ファイル
```

## 🚀 クイックスタート

### 1. リポジトリのクローン
```bash
git clone <repository-url>
cd ConCafeExperience
```

### 2. ファイルのアップロード
FTPクライアントでさくらサーバーにアップロード：
- `index.html`
- `deploy.php`
- `assets/` フォルダ
- `api/` フォルダ
- `data/` フォルダ

### 3. データベースの初期化
```
https://purplelion51.sakura.ne.jp/concafeexp/deploy.php
```

### 4. 動作確認
```
https://purplelion51.sakura.ne.jp/concafeexp/
```

## 🔗 アクセスURL

- **メインサイト**: https://purplelion51.sakura.ne.jp/concafeexp/
- **接続テスト**: https://purplelion51.sakura.ne.jp/concafeexp/api/test_connection.php
- **デプロイメント**: https://purplelion51.sakura.ne.jp/concafeexp/deploy.php

## 📋 サービスプラン

### ライトプラン（¥7,000〜）
- スタンダードなメイド服の着用
- プロカメラマンによる店内フォトブース撮影
- 記念チェキ1枚
- 所要時間: 30〜45分

### ベーシックプラン（¥15,000〜）
- 豪華な制服やアクセサリーの選択
- プロによるポイントメイクとヘアセット
- 店内での本格的な撮影（全データ提供）
- 萌え体験を動画で撮影
- 記念チェキ2枚
- 所要時間: 60〜90分

### プレミアムプラン（¥30,000〜）
- プレミアム限定の特別な衣装
- フルメイクとヘアセット
- 店内＋オタロードでの野外撮影
- プロが編集したハイライト動画
- 記念チェキ3枚
- フォトスタンドまたはフォトブック郵送
- 所要時間: 120分〜

## 🔧 開発・運用

### Git ワークフロー
```bash
# 機能開発
git checkout -b feature/new-feature
git add .
git commit -m "Add new feature"
git push origin feature/new-feature

# メインブランチにマージ
git checkout main
git merge feature/new-feature
git push origin main
```

### デプロイメント
1. ファイルをFTPでアップロード
2. `deploy.php`でデータベース初期化
3. 動作確認
4. `deploy.php`を削除（セキュリティ）

## 📚 ドキュメント

詳細なドキュメントは `docs/INDEX.md` を参照してください。

## 🛡️ セキュリティ

- SQLインジェクション対策
- XSS対策
- CSRF保護
- レート制限
- セキュリティログ
- 入力値検証

## 📞 サポート

プロジェクトに関するご質問やサポートが必要な場合は、Issueを作成してください。

## 📄 ライセンス

このプロジェクトは内部使用目的で作成されています。

---

**Con-Cafe Princess Experience** - 大阪日本橋オタロードで特別なコンカフェ嬢体験を