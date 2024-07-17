# Zeev-Jewelry E-Commerce Application

## Overview
Zeev-Jewelry is a robust e-commerce application built with Laravel. This application allows users to browse and purchase products, manage orders, and handle inventory, customers, and shipping. It also includes roles and permissions management to ensure secure and efficient handling of tasks.

## Features
- **User Management**: Admins can manage users, including assigning roles and permissions.
- **Product Management**: Admins and sellers can add, update, and delete products.
- **Order Management**: Users can place orders, and admins/sellers can manage order statuses.
- **Inventory Management**: Admins and sellers can manage inventory and inventory movements.
- **Customer Management**: Manage customer information and order history.
- **Shipping Management**: Handle shipping details and tracking.
- **Reviews**: Customers can leave reviews for products.
- **Roles and Permissions**: Admin, seller, and customer roles with specific permissions.

## Requirements
- PHP 8.0+
- Composer
- Node.js (optional for frontend assets)
- SQLite (for development)
- MySQL/PostgreSQL (for production)

## Installation

### Clone the repository:
```bash
git clone https://github.com/Ydavidovici/Zeev-Jewelry.git
cd Zeev-Jewelry
```

### Install dependencies:
```bash
composer install
```
### Create a copy of your .env file:
```bash
cp ..env.example ..env
```
### Generate an application key:
```bash
php artisan key:generate
```
### Configure your database:
Update the .env file with your database information. For development, you can use SQLite:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/project/database/database.sqlite
```
### Run migrations and seeders:
```bash
php artisan migrate
php artisan db:seed
```
### Set up storage link:
```bash
php artisan storage:link
```

## Usage
### Start the local development server:
```bash
php artisan serve
```
### Access the application:
Open your browser and go to http://localhost:8000.

## Testing
### Run the tests to ensure everything is working correctly:
```bash
php artisan test
```
## Deployment
- For production, make sure to set up your .env file with the appropriate settings and use a production-ready database like MySQL or PostgreSQL. Additionally, ensure proper web server configuration and security measures are in place.

## Contributing
Contributions are welcome! Please fork the repository and create a pull request with your changes.

## License
This project is open-source and available under the MIT License.
