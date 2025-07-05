環境構築
Docokerビルド
・git clone git@github.com:haru268/fleamarketapp01.git
・docker-compose up -d

Laravel環境構築
・docker-compose exec php bash
・composer install
・cp .env.example .env
・php artisan key:generate
・php artisan migrate
・php artisan db:seed

開発環境
・お問い合わせ画面：http://localhost/
・ユーザー登録：http://localhost/register
・phpMyAdmin：http://localhost:8080/

・自動テスト実行
・.env.testing を用意
APP_ENV=testing
APP_KEY=base64:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX=
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_test
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

CACHE_DRIVER=array    # テストはメモリ上
QUEUE_CONNECTION=sync
MAIL_MAILER=log

※ 初回のみ テスト DB を作成
docker-compose exec mysql mysql -u root -proot \
  -e "CREATE DATABASE IF NOT EXISTS laravel_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

・テストコマンド
docker-compose exec php bash
php artisan migrate:fresh --env=testing --seed   # クリーン状態
php artisan test                                 # ✅ 22 件 PASS


使用技術(実行環境)
・PHP　8.2.27
・Laravel 8.83.29
・Docker
・MySQL　8.0.26
・Fortify
・PHPUnit
・Fortify

ER図
![ER図](https://github.com/user-attachments/assets/59576973-52d0-4a37-a2e7-8356210a40bf)
