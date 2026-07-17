#!/bin/bash

# Laravel アプリケーション デプロイスクリプト
# サクラサーバ用

echo "=== Laravel アプリケーションデプロイ開始 ==="

# 1. 依存関係のインストール（本番環境用）
echo "依存関係をインストール中..."
composer install --no-dev --optimize-autoloader

# 2. フロントエンド資産のビルド
echo "フロントエンド資産をビルド中..."
npm ci
npm run production

# 3. アプリケーションキーの生成（.envファイルが存在しない場合）
if [ ! -f .env ]; then
    echo ".envファイルが見つかりません。.env.productionをコピーします..."
    cp .env.production .env
    php artisan key:generate
fi

# 4. 設定ファイルのキャッシュ
echo "設定ファイルをキャッシュ中..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. データベースマイグレーション
echo "データベースマイグレーションを実行中..."
php artisan migrate --force

# 6. ストレージリンクの作成
echo "ストレージリンクを作成中..."
php artisan storage:link

# 7. 権限設定
echo "権限を設定中..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "=== デプロイ完了 ==="


DB_CONNECTION=mysql
DB_HOST=mysql713.db.sakura.ne.jp
DB_PORT=3306
DB_DATABASE=wiste-lab_merequest
DB_USERNAME=wiste-lab
DB_PASSWORD=gcmtk6EWHCzzPd6FAtkM