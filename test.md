```mermaid

erDiagram
    USERS {
        bigint id PK
        string name
        string email
        string postal_code
        string address
        string building
        string profile_image
        string avatar_url
        datetime created_at
        datetime updated_at
    }

    PRODUCTS {
        bigint id PK
        bigint user_id FK
        string name
        string description
        int price
        bool is_sold
        bigint buyer_id FK
        datetime purchased_at
        string category
        string condition
        string image
        bool is_recommended
        datetime created_at
        datetime updated_at
    }

    COMMENTS {
        bigint id PK
        bigint user_id FK
        bigint product_id FK
        string body
        datetime created_at
        datetime updated_at
    }

    LIKES {
        bigint id PK
        bigint user_id FK
        bigint product_id FK
        datetime created_at
        datetime updated_at
    }

    ADDRESSES {
        bigint id PK
        bigint user_id FK
        string postal_code
        string address
        string building
        datetime created_at
        datetime updated_at
    }

    PURCHASES {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        datetime created_at
        datetime updated_at
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
