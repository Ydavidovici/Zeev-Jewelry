# API Routes

## Authentication

- **POST** `/login` - Login a user.
- **POST** `/logout` - Logout a user. (Requires authentication)
- **POST** `/register` - Register a new user.

## Password Management

- **POST** `/password/email` - Send a password reset link.
- **POST** `/password/reset` - Reset a user's password.
- **POST** `/password/change` - Change the authenticated user's password. (Requires authentication)

## Public Routes

- **GET** `/` - View the home page.

## Products

- **GET** `/products` - List all products.
- **GET** `/products/{product}` - View a specific product.

## Cart (Requires authentication)

- **GET** `/cart` - View the current user's cart.
- **POST** `/cart` - Add a product to the cart.
- **PUT** `/cart/{product}` - Update a product in the cart.
- **DELETE** `/cart/{product}` - Remove a product from the cart.

## Categories, Customers, Inventories, etc. (Requires authentication)

- **GET/POST/PUT/DELETE** `/categories` - Manage categories.
- **GET/POST/PUT/DELETE** `/customers` - Manage customers.
- **GET/POST/PUT/DELETE** `/inventories` - Manage inventories.
- ...

## Orders (Requires authentication)

- **GET** `/orders` - List all orders.
- **POST** `/orders` - Create a new order.
- **GET** `/orders/{order}` - View a specific order.
- **PUT** `/orders/{order}` - Update a specific order.
- **DELETE** `/orders/{order}` - Delete a specific order.

## Admin Routes (Requires authentication)

- **GET** `/admin` - Admin dashboard.
- **GET/POST/PUT/DELETE** `/admin/users` - Manage users.
- **GET/POST/PUT/DELETE** `/admin/roles` - Manage roles.
- **GET/POST/PUT/DELETE** `/admin/permissions` - Manage permissions.
- **GET/POST** `/admin/settings` - View and update settings.

## Seller Routes (Requires authentication)

- **GET** `/seller` - Seller dashboard.
- **GET/POST/PUT/DELETE** `/seller/products` - Manage products.
- **GET/POST/PUT/DELETE** `/seller/inventory` - Manage inventory.
- **GET/POST/PUT/DELETE** `/seller/shipping` - Manage shipping.
- **GET** `/seller/reports` - View reports.

## Stripe Webhook

- **POST** `/stripe/webhook` - Handle Stripe webhook events.

## Checkout (Requires authentication)

- **GET** `/checkout` - View the checkout page.
- **POST** `/checkout` - Complete the checkout process.
- **GET** `/checkout/success` - Checkout success page.
- **GET** `/checkout/failure` - Checkout failure page.
