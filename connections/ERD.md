erDiagram
    categories {
        int category_id PK
        varchar category_name
        text description
    }
    
    books {
        int book_id PK
        varchar title
        varchar author
        decimal price
        text description
        int category_id FK
        int stock_quantity
        varchar image_url
    }
    
    users {
        int user_id PK
        varchar username
        varchar password
        varchar email
        datetime created_at
        boolean is_admin
    }
    
    orders {
        int order_id PK
        int user_id FK
        datetime order_date
        decimal total_amount
        enum status
    }
    
    order_items {
        int order_item_id PK
        int order_id FK
        int book_id FK
        int quantity
        decimal price
    }

    categories ||--o{ books : contains
    users ||--o{ orders : places
    orders ||--o{ order_items : includes
    books ||--o{ order_items : contains