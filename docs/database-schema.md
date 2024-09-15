# Database Schema

## Roles Table

| Column     | Type     | Description                                |
|------------|----------|--------------------------------------------|
| id         | BIGINT   | Primary Key                                |
| name       | STRING   | Name of the role                           |
| guard_name | STRING   | Guard name, default is 'api'               |
| created_at | TIMESTAMP| Timestamp when the record was created      |
| updated_at | TIMESTAMP| Timestamp when the record was last updated |

## Users Table

| Column         | Type     | Description                                |
|----------------|----------|--------------------------------------------|
| id             | BIGINT   | Primary Key                                |
| username       | STRING   | Unique username                            |
| email          | STRING   | Unique email                               |
| password       | STRING   | User's password                            |
| role           | STRING   | Role of the user, default is 'customer'    |
| remember_token | STRING   | Token used to remember the user            |
| created_at     | TIMESTAMP| Timestamp when the record was created      |
| updated_at     | TIMESTAMP| Timestamp when the record was last updated |

## Categories Table

| Column        | Type     | Description                                |
|---------------|----------|--------------------------------------------|
| id            | BIGINT   | Primary Key                                |
| category_name | STRING   | Name of the category                       |
| created_at    | TIMESTAMP| Timestamp when the record was created      |
| updated_at    | TIMESTAMP| Timestamp when the record was last updated |

## Products Table

| Column        | Type     | Description                                |
|---------------|----------|--------------------------------------------|
| id            | BIGINT   | Primary Key                                |
| category_id   | BIGINT   | Foreign Key referencing Categories         |
| seller_id     | BIGINT   | Foreign Key referencing Users (sellers)    |
| product_name  | STRING   | Name of the product                        |
| description   | TEXT     | Description of the product                 |
| price         | DECIMAL  | Price of the product                       |
| image_url     | STRING   | URL of the product image                   |
| is_featured   | BOOLEAN  | Indicates if the product is featured       |
| created_at    | TIMESTAMP| Timestamp when the record was created      |
| updated_at    | TIMESTAMP| Timestamp when the record was last updated |

## Customers Table

| Column        | Type     | Description                                |
|---------------|----------|--------------------------------------------|
| id            | BIGINT   | Primary Key                                |
| user_id       | BIGINT   | Foreign Key referencing Users              |
| address       | STRING   | Customer's address                         |
| phone_number  | STRING   | Customer's phone number                    |
| email         | STRING   | Unique email                               |
| is_guest      | BOOLEAN  | Indicates if the customer is a guest       |
| created_at    | TIMESTAMP| Timestamp when the record was created      |
| updated_at    | TIMESTAMP| Timestamp when the record was last updated |

## Orders Table

| Column           | Type     | Description                                |
|------------------|----------|--------------------------------------------|
| id               | BIGINT   | Primary Key                                |
| customer_id      | BIGINT   | Foreign Key referencing Customers          |
| seller_id        | BIGINT   | Foreign Key referencing Users (sellers)    |
| order_date       | DATETIME | Date and time of the order                 |
| total_amount     | DECIMAL  | Total amount of the order                  |
| is_guest         | BOOLEAN  | Indicates if the order was placed by a guest|
| status           | STRING   | Status of the order                        |
| payment_intent_id| STRING   | Payment intent ID from Stripe              |
| created_at       | TIMESTAMP| Timestamp when the record was created      |
| updated_at       | TIMESTAMP| Timestamp when the record was last updated |

## Order Details Table

| Column        | Type     | Description                                |
|---------------|----------|--------------------------------------------|
| id            | BIGINT   | Primary Key                                |
| order_id      | BIGINT   | Foreign Key referencing Orders             |
| product_id    | BIGINT   | Foreign Key referencing Products           |
| quantity      | INTEGER  | Quantity of the product ordered            |
| price         | DECIMAL  | Price of the product at the time of order  |
| created_at    | TIMESTAMP| Timestamp when the record was created      |
| updated_at    | TIMESTAMP| Timestamp when the record was last updated |

## Payments Table

| Column           | Type     | Description                                |
|------------------|----------|--------------------------------------------|
| id               | BIGINT   | Primary Key                                |
| order_id         | BIGINT   | Foreign Key referencing Orders             |
| seller_id        | BIGINT   | Foreign Key referencing Users (sellers)    |
| payment_intent_id| STRING   | Payment intent ID from Stripe              |
| payment_type     | STRING   | Type of payment                            |
| payment_status   | STRING   | Status of the payment                      |
| amount           | DECIMAL  | Amount paid                                |
| created_at       | TIMESTAMP| Timestamp when the record was created      |
| updated_at       | TIMESTAMP| Timestamp when the record was last updated |

## Shipping Table

| Column                | Type     | Description                                |
|-----------------------|----------|--------------------------------------------|
| id                    | BIGINT   | Primary Key                                |
| order_id              | BIGINT   | Foreign Key referencing Orders             |
| seller_id             | BIGINT   | Foreign Key referencing Users (sellers)    |
| shipping_type         | STRING   | Type of shipping                           |
| shipping_cost         | DECIMAL  | Cost of shipping                           |
| shipping_status       | STRING   | Status of the shipping                     |
| tracking_number       | STRING   | Tracking number for the shipment           |
| shipping_address      | STRING   | Shipping address                           |
| shipping_carrier      | STRING   | Shipping carrier                           |
| recipient_name        | STRING   | Name of the recipient                      |
| estimated_delivery_date| TIMESTAMP| Estimated delivery date                    |
| additional_notes      | TEXT     | Additional notes                           |
| created_at            | TIMESTAMP| Timestamp when the record was created      |
| updated_at            | TIMESTAMP| Timestamp when the record was last updated |

## Reviews Table

| Column        | Type     | Description                                |
|---------------|----------|--------------------------------------------|
| id            | BIGINT   | Primary Key                                |
| product_id    | BIGINT   | Foreign Key referencing Products           |
| customer_id   | BIGINT   | Foreign Key referencing Customers          |
| review_text   | TEXT     | Review text                                |
| rating        | INTEGER  | Rating given by the customer               |
| review_date   | TIMESTAMP| Date and time of the review                |
| created_at    | TIMESTAMP| Timestamp when the record was created      |
| updated_at    | TIMESTAMP| Timestamp when the record was last updated |

## Inventory Table

| Column        | Type     | Description                                |
|---------------|----------|--------------------------------------------|
| id            | BIGINT   | Primary Key                                |
| seller_id     | BIGINT   | Foreign Key referencing Users (sellers)    |
| product_id    | BIGINT   | Foreign Key referencing Products           |
| location      | STRING   | Location of the inventory                  |
| quantity      | INTEGER  | Quantity of the product in stock           |
| created_at    | TIMESTAMP| Timestamp when the record was created      |
| updated_at    | TIMESTAMP| Timestamp when the record was last updated |

## Inventory Movements Table

| Column        | Type     | Description                                |
|---------------|----------|--------------------------------------------|
| id            | BIGINT   | Primary Key                                |
| inventory_id  | BIGINT   | Foreign Key referencing Inventory          |
| movement_type | STRING   | Type of movement (e.g., incoming, outgoing)|
| quantity      | INTEGER  | Quantity moved                             |
| movement_date | TIMESTAMP| Date and time of the movement              |
| created_at    | TIMESTAMP| Timestamp when the record was created      |
| updated_at    | TIMESTAMP| Timestamp when the record was last updated |

## Settings Table

| Column     | Type     | Description                                |
|------------|----------|--------------------------------------------|
| id         | BIGINT   | Primary Key                                |
| key        | STRING   | Unique key for the setting                 |
| value      | TEXT     | Value of the setting                       |
| created_at | TIMESTAMP| Timestamp when the record was created      |
| updated_at | TIMESTAMP| Timestamp when the record was last updated |

## Carts Table

| Column     | Type     | Description                                |
|------------|----------|--------------------------------------------|
| id         | BIGINT   | Primary Key                                |
| user_id    | BIGINT   | Foreign Key referencing Users              |
| created_at | TIMESTAMP| Timestamp when the record was created      |
| updated_at | TIMESTAMP| Timestamp when the record was last updated |

## Cart Items Table

| Column     | Type     | Description                                |
|------------|----------|--------------------------------------------|
| id         | BIGINT   | Primary Key                                |
| cart_id    | BIGINT   | Foreign Key referencing Carts              |
| product_id | BIGINT   | Foreign Key referencing Products           |
| quantity   | INTEGER  | Quantity of the product in the cart        |
| created_at | TIMESTAMP| Timestamp when the record was created      |
| updated_at | TIMESTAMP| Timestamp when the record was last updated |

## Password Reset Tokens Table

| Column     | Type     | Description                                |
|------------|----------|--------------------------------------------|
| email      | STRING   | Indexed email address                      |
| token      | STRING   | Password reset token                       |
| created_at | TIMESTAMP| Timestamp when the record was created      |

## Personal Access Tokens Table

| Column        | Type     | Description                                |
|---------------|----------|--------------------------------------------|
| id            | BIGINT   | Primary Key                                |
| tokenable_type| STRING   | Type of the tokenable model                |
| tokenable_id  | BIGINT   | ID of the tokenable model                  |
| name          | STRING   | Name of the token                          |
| token         | STRING   | The access token (unique)                  |
| abilities     | TEXT     | Token abilities                            |
| last_used_at  | TIMESTAMP| Timestamp when the token was last used     |
| expires_at    | TIMESTAMP| Expiration timestamp                       |
| created_at    | TIMESTAMP| Timestamp when the record was created      |
| updated_at    | TIMESTAMP| Timestamp when the record was last updated |

