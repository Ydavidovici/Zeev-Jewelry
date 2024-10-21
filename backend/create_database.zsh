#!/bin/zsh

# === Configuration ===

# Database name to create
DB_NAME="zeev-jewelry"

# Prompt for MySQL username
read -p "Enter MySQL username [root]: " MYSQL_USER
MYSQL_USER=${MYSQL_USER:-root}  # Default to 'root' if no input

# Prompt for MySQL password securely
read -s -p "Enter MySQL password for user '$MYSQL_USER': " MYSQL_PASSWORD
echo

# Optional: Specify MySQL host and port
read -p "Enter MySQL host [localhost]: " MYSQL_HOST
MYSQL_HOST=${MYSQL_HOST:-localhost}

read -p "Enter MySQL port [3306]: " MYSQL_PORT
MYSQL_PORT=${MYSQL_PORT:-3306}

# === Function to Create Database ===

create_database() {
    # Attempt to create the database
    mysql -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -h "$MYSQL_HOST" -P "$MYSQL_PORT" -e "CREATE DATABASE \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

    # Check if the command was successful
    if [ $? -eq 0 ]; then
        echo "✅ Database '$DB_NAME' created successfully."
    else
        # Check if the database already exists
        echo "⚠️ Failed to create database '$DB_NAME'. It might already exist or your credentials might be incorrect."
        # Optionally, you can check if the database exists
        EXISTS=$(mysql -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -h "$MYSQL_HOST" -P "$MYSQL_PORT" -e "SHOW DATABASES LIKE '$DB_NAME';" | grep "$DB_NAME" > /dev/null; echo $?)
        if [ $EXISTS -eq 0 ]; then
            echo "ℹ️ The database '$DB_NAME' already exists."
        else
            echo "ℹ️ Please check your MySQL credentials and ensure the MySQL server is running."
        fi
    fi
}

# === Execute the Function ===

create_database
