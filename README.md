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
http://localhost/ … トップ（商品一覧）
http://localhost/register … ユーザー登録
http://localhost/login … ログイン
http://localhost/purchase/shipping … 送付先住所変更画面
http://localhost:8080/ … phpMyAdmin

────────────────────────────
■ 自動テスト（PHPUnit）
────────────────────────────

.env.testing を用意

APP_ENV=testing
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_test
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
CACHE_DRIVER=array    
QUEUE_CONNECTION=sync
MAIL_MAILER=log
初回のみ テスト DB を作成


docker-compose exec mysql mysql -u root -proot \
  -e "CREATE DATABASE IF NOT EXISTS laravel_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

docker-compose exec php bash -c \
  "php artisan migrate:fresh --env=testing --seed && php artisan test"
→ ✅ 22 tests ALL GREEN

────────────────────────────
■ 手動テスト用アカウント（ご自身で登録してください）
────────────────────────────

ブラウザで http://localhost/register を開く

例として下記 2 アカウントを登録

ユーザー名: User1 / メール: general1@gmail.com / パスワード: password

ユーザー名: User2 / メール: general2@gmail.com / パスワード: password
※別の情報でも可。自動テストでは Factory 生成のため必須ではありません。

────────────────────────────
■ 使用技術
────────────────────────────
PHP 8.2.27 / Laravel 8.83.29 / MySQL 8.0.26 / Docker / Fortify / PHPUnit

────────────────────────────
■ ER 図
────────────────────────────

![ER図](https://github.com/user-attachments/assets/59576973-52d0-4a37-a2e7-8356210a40bf)

