# Laravel アプリケーション デプロイガイド
# サクラサーバ用

## 前提条件
- PHP 8.1以上
- Composer
- Node.js & npm
- MySQL/MariaDB
- FTP/SFTPアクセス

## デプロイ手順

### 1. サーバー準備
```bash
# サーバーにSSH接続
ssh your-username@your-server.com

# 作業ディレクトリに移動
cd /home/your-username/public_html
```

### 2. ファイルアップロード
```bash
# ローカルからサーバーにファイルをアップロード
# FTP/SFTPクライアントまたはrsyncを使用

# rsyncの例
rsync -avz --exclude 'node_modules' --exclude 'vendor' \
  --exclude '.git' --exclude 'storage/logs' \
  /path/to/local/MERequest/ your-username@your-server.com:/home/your-username/public_html/
```

### 3. サーバー側での設定
```bash
# サーバーに接続後
cd /home/your-username/public_html

# 権限設定
chmod +x deploy.sh
./deploy.sh
```

### 4. Webサーバー設定

#### Apache (.htaccess)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Handle Angular and Vue.js routes
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /home/your-username/public_html/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

### 5. データベース設定
```sql
-- MySQLでデータベースとユーザーを作成
CREATE DATABASE your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'your_database_user'@'localhost' IDENTIFIED BY 'your_database_password';
GRANT ALL PRIVILEGES ON your_database_name.* TO 'your_database_user'@'localhost';
FLUSH PRIVILEGES;
```

### 6. 環境変数設定
```bash
# .envファイルを編集
nano .env

# 以下の値を設定
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 7. SSL証明書設定（推奨）
```bash
# Let's Encryptを使用したSSL設定
sudo certbot --apache -d your-domain.com
```

## トラブルシューティング

### よくある問題
1. **権限エラー**: `chmod -R 755 storage bootstrap/cache`
2. **Composerエラー**: `composer install --no-dev --optimize-autoloader`
3. **データベース接続エラー**: `.env`ファイルのDB設定を確認
4. **404エラー**: Webサーバーの設定を確認

### ログ確認
```bash
# Laravelログ
tail -f storage/logs/laravel.log

# Webサーバーログ
tail -f /var/log/apache2/error.log
# または
tail -f /var/log/nginx/error.log
```

## メンテナンス

### 定期実行タスク
```bash
# crontabに追加
* * * * * cd /home/your-username/public_html && php artisan schedule:run >> /dev/null 2>&1
```

### バックアップ
```bash
# データベースバックアップ
mysqldump -u your_database_user -p your_database_name > backup_$(date +%Y%m%d).sql

# ファイルバックアップ
tar -czf files_backup_$(date +%Y%m%d).tar.gz /home/your-username/public_html
```

