FleaMarketApp – README（コピーしてそのまま貼り付けてください）

────────────────────────────
■ セットアップ手順
────────────────────────────
git clone git@github.com:haru268/fleamarketapp01.git
cd fleamarketapp01
docker-compose up -d

docker-compose exec php bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
exit

────────────────────────────
■ 主要 URL 一覧
────────────────────────────
http://localhost/                     … トップ（商品一覧）
http://localhost/register             … ユーザー登録
http://localhost/login                … ログイン
http://localhost/purchase/shipping    … 送付先住所変更画面
http://localhost:8080/                … phpMyAdmin

────────────────────────────
■ 自動テスト（PHPUnit）
────────────────────────────
① .env.testing を用意  
   APP_ENV=testing  
   DB_DATABASE=laravel_test  などを設定  
② 初回のみテスト DB を作成  
   docker-compose exec mysql mysql -u root -proot \
     -e "CREATE DATABASE IF NOT EXISTS laravel_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
③ 実行  
   docker-compose exec php bash -c "php artisan migrate:fresh --env=testing --seed && php artisan test"
   → ✅ 22 tests 完全合格

────────────────────────────
■ 手動テスト用アカウント（任意）
────────────────────────────
1. http://localhost/register で下記 2 ユーザーを登録
   ├─ ユーザー名: User1 / メール: general1@gmail.com / パスワード: password
   └─ ユーザー名: User2 / メール: general2@gmail.com / パスワード: password
※ 自動テストでは Factory 生成のため必須ではありません。

────────────────────────────
■ 使用技術
────────────────────────────
PHP 8.2.27 / Laravel 8.83.29 / MySQL 8.0.26 / Docker / Fortify / PHPUnit

────────────────────────────
■ ER 図
────────────────────────────
![ER図](https://github.com/user-attachments/assets/59576973-52d0-4a37-a2e7-8356210a40bf)

