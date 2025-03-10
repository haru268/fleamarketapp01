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

使用技術(実行環境)
・PHP　8.2.27
・Laravel 8.83.29
・Docker
・MySQL　8.0.26
・Fortify
・PHPUnit


erDiagram
    USERS {
        bigint id PK
        string name
        string email UNIQUE
        string postal_code NULL
        string address NULL
        string building NULL
        string profile_image NULL
        string avatar_url NULL
        timestamps
    }
    PRODUCTS {
        bigint id PK
        bigint user_id FK "出品者"
        string name
        text description NULL
        integer price NULL
        boolean is_sold DEFAULT false
        bigint buyer_id FK "購入者" NULL
        timestamp purchased_at NULL
        string category NULL
        string condition NULL
        string image NULL
        boolean is_recommended DEFAULT false
        timestamps
    }
    COMMENTS {
        bigint id PK
        bigint user_id FK
        bigint product_id FK
        text body
        timestamps
    }
    LIKES {
        bigint id PK
        bigint user_id FK
        bigint product_id FK
        timestamps
    }
    ADDRESSES {
        bigint id PK
        bigint user_id FK
        string postal_code NULL
        string address NULL
        string building NULL
        timestamps
    }
    PURCHASES {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        timestamps
    }
    
    USERS ||--o{ PRODUCTS : "出品する"
    USERS ||--o{ COMMENTS : "投稿する"
    USERS ||--o{ LIKES : "いいねする"
    USERS ||--o{ ADDRESSES : "所有する"
    USERS ||--o{ PURCHASES : "購入する"
    
    PRODUCTS ||--o{ COMMENTS : "持つ"
    PRODUCTS ||--o{ LIKES : "持つ"
    PRODUCTS ||--|| USERS : "出品者"
    PRODUCTS ||--|| USERS : "購入者"
    
    COMMENTS }|..|{ USERS : "書いた"
    LIKES }|..|{ USERS : "付けた"
