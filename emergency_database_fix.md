# サクラサーバ データベース接続問題の緊急解決手順

## 手順1: コントロールパネルでの設定変更
1. サクラサーバのコントロールパネルにログイン
2. 「データベース」→「MySQL」を選択
3. データベース「wiste-lab_merequest」を選択
4. ユーザー「wiste-lab」の設定を編集
5. 「接続元制限」を「%」に変更（すべてのIPを許可）
6. 設定を保存

## 手順2: .envファイルの更新
サーバー上の.envファイルを以下のように更新：

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=wiste-lab_merequest
DB_USERNAME=wiste-lab
DB_PASSWORD=4tpLQ7NCgAvPwANJJrE8
```

## 手順3: 設定キャッシュのクリア
```bash
# サーバー上で実行
cd /home/wiste-lab/www/merequest
php artisan config:clear
php artisan config:cache
```

## 手順4: 接続テスト
```bash
# MySQL接続テスト
mysql -h localhost -u wiste-lab -p wiste-lab_merequest

# Laravelでの接続テスト
php artisan tinker
DB::connection()->getPdo();
```

## 手順5: アプリケーションの再起動
```bash
# Webサーバーの再起動（必要に応じて）
sudo systemctl restart apache2
# または
sudo systemctl restart nginx
```

## トラブルシューティング

### 問題1: localhostでも接続できない
- 解決方法: コントロールパネルで接続元制限を「%」に設定

### 問題2: パスワードエラー
- 解決方法: コントロールパネルでパスワードを再設定

### 問題3: データベースが存在しない
- 解決方法: コントロールパネルでデータベースを作成

### 問題4: 権限不足
- 解決方法: ユーザーにデータベースへの読み書き権限を付与


