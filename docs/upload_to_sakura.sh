#!/bin/bash
# upload_to_sakura.sh - さくらサーバーアップロードスクリプト
# Con-Cafe Princess Experience

echo "Con-Cafe Princess Experience - さくらサーバーアップロードスクリプト"
echo "================================================================"

# 設定
SERVER_HOST="purplelion51.sakura.ne.jp"
SERVER_USER="purplelion51"
SERVER_PATH="/home/purplelion51/www/concafeexp"
LOCAL_PATH="."

# アップロードするファイル一覧
FILES=(
    "index.html"
    "deploy.php"
    ".htaccess"
    "assets/css/styles.css"
    "assets/js/script.js"
    "assets/js/sns_strategy.js"
    "api/booking_system.php"
    "api/config.php"
    "api/test_connection.php"
    "data/translations.json"
    "data/database_schema.sql"
    "README.md"
    "FOLDER_STRUCTURE.md"
    "SAKURA_SERVER_SETUP.md"
)

# ディレクトリ作成
echo "1. サーバー上でディレクトリを作成中..."
ssh ${SERVER_USER}@${SERVER_HOST} "mkdir -p ${SERVER_PATH}/assets/css"
ssh ${SERVER_USER}@${SERVER_HOST} "mkdir -p ${SERVER_PATH}/assets/js"
ssh ${SERVER_USER}@${SERVER_HOST} "mkdir -p ${SERVER_PATH}/assets/images"
ssh ${SERVER_USER}@${SERVER_HOST} "mkdir -p ${SERVER_PATH}/api"
ssh ${SERVER_USER}@${SERVER_HOST} "mkdir -p ${SERVER_PATH}/data"
ssh ${SERVER_USER}@${SERVER_HOST} "mkdir -p ${SERVER_PATH}/uploads"
ssh ${SERVER_USER}@${SERVER_HOST} "mkdir -p ${SERVER_PATH}/logs"
ssh ${SERVER_USER}@${SERVER_HOST} "mkdir -p ${SERVER_PATH}/cache"
ssh ${SERVER_USER}@${SERVER_HOST} "mkdir -p ${SERVER_PATH}/tmp"

echo "2. ファイルをアップロード中..."

# ファイルのアップロード
for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        echo "  アップロード中: $file"
        scp "$file" ${SERVER_USER}@${SERVER_HOST}:${SERVER_PATH}/"$file"
    else
        echo "  警告: $file が見つかりません"
    fi
done

echo "3. 権限を設定中..."

# 権限設定
ssh ${SERVER_USER}@${SERVER_HOST} "chmod 755 ${SERVER_PATH}/uploads"
ssh ${SERVER_USER}@${SERVER_HOST} "chmod 755 ${SERVER_PATH}/logs"
ssh ${SERVER_USER}@${SERVER_HOST} "chmod 755 ${SERVER_PATH}/cache"
ssh ${SERVER_USER}@${SERVER_HOST} "chmod 755 ${SERVER_PATH}/tmp"
ssh ${SERVER_USER}@${SERVER_HOST} "chmod 644 ${SERVER_PATH}/*.html"
ssh ${SERVER_USER}@${SERVER_HOST} "chmod 644 ${SERVER_PATH}/*.php"
ssh ${SERVER_USER}@${SERVER_HOST} "chmod 644 ${SERVER_PATH}/assets/css/*.css"
ssh ${SERVER_USER}@${SERVER_HOST} "chmod 644 ${SERVER_PATH}/assets/js/*.js"
ssh ${SERVER_USER}@${SERVER_HOST} "chmod 644 ${SERVER_PATH}/data/*.json"
ssh ${SERVER_USER}@${SERVER_HOST} "chmod 644 ${SERVER_PATH}/data/*.sql"

echo "4. 接続テストを実行中..."

# 接続テスト
echo "  データベース接続テスト:"
curl -s "https://purplelion51.sakura.ne.jp/concafeexp/api/test_connection.php" | head -20

echo ""
echo "================================================================"
echo "アップロード完了！"
echo ""
echo "アクセスURL:"
echo "  メインサイト: https://purplelion51.sakura.ne.jp/concafeexp/"
echo "  接続テスト: https://purplelion51.sakura.ne.jp/concafeexp/api/test_connection.php"
echo "  デプロイメント: https://purplelion51.sakura.ne.jp/concafeexp/deploy.php"
echo ""
echo "次のステップ:"
echo "1. 接続テストでデータベース接続を確認"
echo "2. デプロイメントスクリプトでデータベースを初期化"
echo "3. ホームページで動作確認"
echo "================================================================"
