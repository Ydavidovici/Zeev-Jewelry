-- Create the database
CREATE DATABASE IF NOT EXISTS Zeev_Jewelry;
USE Zeev_Jewelry;

-- Drop tables if they exist to avoid errors when running the script multiple times
DROP TABLE IF EXISTS InventoryMovements;
DROP TABLE IF EXISTS Inventory;
DROP TABLE IF EXISTS Reviews;
DROP TABLE IF EXISTS Shipping;
DROP TABLE IF EXISTS Payments;
DROP TABLE IF EXISTS OrderDetails;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS Customers;
DROP TABLE IF EXISTS Products;
DROP TABLE IF EXISTS Categories;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Roles;

-- Roles table
CREATE TABLE Roles (
                       role_id INT AUTO_INCREMENT PRIMARY KEY,
                       role_name VARCHAR(255) NOT NULL
);

-- Users table
CREATE TABLE Users (
                       user_id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(255) UNIQUE NOT NULL,
                       password VARCHAR(255) NOT NULL,
                       role_id INT,
                       FOREIGN KEY (role_id) REFERENCES Roles(role_id)
);

-- Categories table
CREATE TABLE Categories (
                            category_id INT AUTO_INCREMENT PRIMARY KEY,
                            category_name VARCHAR(255) NOT NULL
);

-- Products table
CREATE TABLE Products (
                          product_id INT AUTO_INCREMENT PRIMARY KEY,
                          product_name VARCHAR(255) NOT NULL,
                          description TEXT,
                          price DECIMAL(10, 2) NOT NULL,
                          category_id INT,
                          image_url VARCHAR(255),
                          FOREIGN KEY (category_id) REFERENCES Categories(category_id)
);

-- Customers table
CREATE TABLE Customers (
                           customer_id INT AUTO_INCREMENT PRIMARY KEY,
                           user_id INT,
                           address TEXT NOT NULL,
                           phone_number VARCHAR(15),
                           email VARCHAR(255) UNIQUE NOT NULL,
                           is_guest BOOLEAN DEFAULT FALSE,
                           FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Orders table
CREATE TABLE Orders (
                        order_id INT AUTO_INCREMENT PRIMARY KEY,
                        customer_id INT,
                        order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                        total_amount DECIMAL(10, 2) NOT NULL,
                        is_guest BOOLEAN DEFAULT FALSE,
                        status ENUM('pending', 'completed', 'shipped', 'canceled') DEFAULT 'pending',
                        FOREIGN KEY (customer_id) REFERENCES Customers(customer_id)
);

-- Order Details table
CREATE TABLE OrderDetails (
                              order_details_id INT AUTO_INCREMENT PRIMARY KEY,
                              order_id INT,
                              product_id INT,
                              quantity INT,
                              price DECIMAL(10, 2) NOT NULL,
                              FOREIGN KEY (order_id) REFERENCES Orders(order_id),
                              FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

-- Payments table
CREATE TABLE Payments (
                          payment_id INT AUTO_INCREMENT PRIMARY KEY,
                          order_id INT,
                          payment_type VARCHAR(255) NOT NULL,
                          payment_status ENUM('processed', 'failed', 'pending') DEFAULT 'pending',
                          FOREIGN KEY (order_id) REFERENCES Orders(order_id)
);

-- Shipping table
CREATE TABLE Shipping (
                          shipping_id INT AUTO_INCREMENT PRIMARY KEY,
                          order_id INT,
                          shipping_type VARCHAR(255),
                          shipping_cost DECIMAL(10, 2),
                          shipping_status ENUM('shipped', 'pending', 'delivered') DEFAULT 'pending',
                          FOREIGN KEY (order_id) REFERENCES Orders(order_id)
);

-- Reviews table
CREATE TABLE Reviews (
                         review_id INT AUTO_INCREMENT PRIMARY KEY,
                         product_id INT,
                         customer_id INT,
                         review_text TEXT,
                         rating INT CHECK (rating >= 1 AND rating <= 5),
                         review_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                         FOREIGN KEY (product_id) REFERENCES Products(product_id),
                         FOREIGN KEY (customer_id) REFERENCES Customers(customer_id)
);

-- Inventory table
CREATE TABLE Inventory (
                           inventory_id INT AUTO_INCREMENT PRIMARY KEY,
                           product_id INT,
                           quantity INT,
                           location VARCHAR(255),
                           FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

-- Inventory movements table
CREATE TABLE InventoryMovements (
                                    movement_id INT AUTO_INCREMENT PRIMARY KEY,
                                    inventory_id INT,
                                    type ENUM('addition', 'subtraction'),
                                    quantity_change INT,
                                    movement_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                                    FOREIGN KEY (inventory_id) REFERENCES Inventory(inventory_id)
);

-- Dropping existing indexes if they exist
DROP INDEX IF EXISTS idx_order_date ON Orders;
DROP INDEX IF EXISTS idx_order_status ON Orders;
DROP INDEX IF EXISTS idx_product_category ON Products;
DROP INDEX IF EXISTS idx_user_role ON Users;

-- Creating indexes for optimization
CREATE INDEX idx_order_date ON Orders(order_date);
CREATE INDEX idx_order_status ON Orders(status);
CREATE INDEX idx_product_category ON Products(category_id);
CREATE INDEX idx_user_role ON Users(role_id);
