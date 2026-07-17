# サクラサーバ データベース接続確認手順

## 1. コントロールパネルでの確認
1. サクラサーバのコントロールパネルにログイン
2. 「データベース」→「MySQL」を選択
3. データベース「wiste-lab_merequest」の詳細を確認
4. 以下の情報を確認：
   - ホスト名（例：mysql.wiste-lab.sakura.ne.jp）
   - ポート番号（通常3306）
   - データベース名
   - ユーザー名
   - パスワード

## 2. 接続許可設定の確認
1. 「データベースユーザー」の設定を確認
2. ユーザー「wiste-lab」の接続元制限を確認
3. 以下のいずれかが設定されているか確認：
   - localhost
   - 127.0.0.1
   - %（すべてのIPを許可）
   - サーバーのIPアドレス

## 3. SSH接続でのテスト
```bash
# サーバーにSSH接続
ssh wiste-lab@wiste-lab.sakura.ne.jp

# MySQL接続テスト
mysql -h mysql.wiste-lab.sakura.ne.jp -u wiste-lab -p wiste-lab_merequest

# または localhost でテスト
mysql -h localhost -u wiste-lab -p wiste-lab_merequest
```

## 4. Laravelでの接続テスト
```bash
# サーバー上で実行
cd /home/wiste-lab/www/merequest
php artisan tinker

# Tinker内で実行
DB::connection()->getPdo();
```

## 5. よくある問題と解決方法

### 問題1: ホスト名が間違っている
- 解決方法: コントロールパネルで正確なホスト名を確認

### 問題2: 接続元IPが制限されている
- 解決方法: データベースユーザーの接続元制限を「%」に変更

### 問題3: パスワードが間違っている
- 解決方法: コントロールパネルでパスワードを再設定

### 問題4: データベースが存在しない
- 解決方法: コントロールパネルでデータベースを作成

## 6. 推奨設定
```env
DB_CONNECTION=mysql
DB_HOST=mysql.wiste-lab.sakura.ne.jp
DB_PORT=3306
DB_DATABASE=wiste-lab_merequest
DB_USERNAME=wiste-lab
DB_PASSWORD=4tpLQ7NCgAvPwANJJrE8
```


