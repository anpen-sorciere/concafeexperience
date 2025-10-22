# Git バージョン管理ガイド

## 🎉 Git リポジトリの初期化完了

Con-Cafe Princess Experience プロジェクトがGitリポジトリとして初期化されました！

## 📁 現在の構成

```
ConCafeExperience/
├── .git/                   # Git リポジトリ
├── .gitignore              # Git除外設定
├── README.md               # プロジェクト説明
├── index.html              # メインホームページ
├── deploy.php              # デプロイメントスクリプト
├── assets/                 # 静的ファイル
├── api/                    # API・PHPファイル
├── data/                   # データファイル
├── docs/                   # ドキュメント（開発用）
├── uploads/                # アップロードファイル
├── logs/                   # ログファイル
├── cache/                  # キャッシュファイル
└── tmp/                    # 一時ファイル
```

## 🔧 .gitignore の設定

以下のファイル・フォルダが除外されています：

### 除外されるファイル
- `logs/*.log` - ログファイル
- `cache/*` - キャッシュファイル
- `tmp/*` - 一時ファイル
- `uploads/*` - アップロードファイル
- `docs/` - 開発用ドキュメント
- `deploy.php` - デプロイメントスクリプト
- `*.sql` - データベースファイル（スキーマ以外）

### 含まれるファイル
- `data/database_schema.sql` - データベーススキーマ
- `api/config.php` - 設定ファイル（テンプレート）
- すべてのソースコードファイル

## 🚀 GitHub リポジトリとの連携

### 1. GitHub でリポジトリを作成

1. GitHub にログイン
2. 「New repository」をクリック
3. リポジトリ名: `con-cafe-princess-experience`
4. 説明: `大阪日本橋オタロードで外国人観光客向けのコンカフェ嬢体験サービス`
5. Public または Private を選択
6. 「Create repository」をクリック

### 2. ローカルリポジトリとGitHubを連携

```bash
# GitHub リポジトリをリモートとして追加
git remote add origin https://github.com/your-username/con-cafe-princess-experience.git

# メインブランチを main に変更（推奨）
git branch -M main

# GitHub にプッシュ
git push -u origin main
```

### 3. 今後の開発フロー

#### 機能開発
```bash
# 新しい機能ブランチを作成
git checkout -b feature/new-feature

# 変更をコミット
git add .
git commit -m "Add new feature: description"

# GitHub にプッシュ
git push origin feature/new-feature
```

#### メインブランチへのマージ
```bash
# メインブランチに切り替え
git checkout main

# 機能ブランチをマージ
git merge feature/new-feature

# GitHub にプッシュ
git push origin main
```

## 📋 推奨ブランチ戦略

### ブランチ構成
- `main` - 本番環境用（安定版）
- `develop` - 開発環境用
- `feature/*` - 機能開発用
- `hotfix/*` - 緊急修正用

### ブランチ作成例
```bash
# 機能開発
git checkout -b feature/booking-system-improvement
git checkout -b feature/multilingual-support

# 緊急修正
git checkout -b hotfix/security-patch
```

## 🔄 デプロイメント戦略

### 1. 開発環境
```bash
# develop ブランチで開発
git checkout develop
git pull origin develop
# 開発作業
git add .
git commit -m "Development update"
git push origin develop
```

### 2. 本番環境
```bash
# main ブランチを本番環境にデプロイ
git checkout main
git pull origin main
# FTP でアップロード
# deploy.php でセットアップ
```

## 📝 コミットメッセージの規約

### フォーマット
```
<type>(<scope>): <description>

<body>

<footer>
```

### タイプ
- `feat`: 新機能
- `fix`: バグ修正
- `docs`: ドキュメント更新
- `style`: コードスタイル変更
- `refactor`: リファクタリング
- `test`: テスト追加・修正
- `chore`: その他の変更

### 例
```bash
git commit -m "feat(booking): add real-time availability check"
git commit -m "fix(security): prevent SQL injection in booking form"
git commit -m "docs(readme): update installation instructions"
```

## 🛡️ セキュリティ考慮事項

### 機密情報の管理
- データベース接続情報は環境変数で管理
- 本番環境の設定ファイルは別途管理
- API キーは環境変数に保存

### ブランチ保護
- `main` ブランチは直接プッシュ禁止
- Pull Request 必須
- コードレビュー必須

## 📊 リリース管理

### タグ付け
```bash
# バージョンタグを作成
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

### リリースノート
- GitHub の Releases 機能を使用
- 変更内容を詳細に記載
- 既知の問題を明記

## 🔍 トラブルシューティング

### よくある問題

#### 1. プッシュエラー
```bash
# リモートリポジトリの状態を確認
git fetch origin
git status

# 強制プッシュ（注意して使用）
git push --force-with-lease origin main
```

#### 2. マージコンフリクト
```bash
# コンフリクトを解決後
git add .
git commit -m "Resolve merge conflicts"
git push origin main
```

#### 3. ファイルの除外設定
```bash
# .gitignore に追加
echo "filename" >> .gitignore
git add .gitignore
git commit -m "Update .gitignore"
```

## 📚 参考資料

- [Git公式ドキュメント](https://git-scm.com/doc)
- [GitHub公式ガイド](https://guides.github.com/)
- [Conventional Commits](https://www.conventionalcommits.org/)

---

**注意**: 本番環境にデプロイする際は、`deploy.php` を削除することを忘れずに！
