# Inventory Management System - Installation Guide

## Requirements
- Apache Server
- PHP 7.0 or higher
- MySQL Database

## Installation Steps

1. **Database Setup**
   - Create a MySQL database named `inventory_system`
   - Execute the SQL commands in `database_schema.sql` to create tables and sample data

2. **Configuration**
   - Update the database credentials in `includes/db_config.php` if needed:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'inventory_system');
     ```

3. **Web Server Configuration**
   - Place all files in your web server's document root
   - Ensure the web server has read/write permissions for all files

4. **Access the Application**
   - Navigate to your web server's URL in a web browser
   - Register a new account or login with:
     - Username: admin
     - Password: password

## Features
- User registration and authentication
- Dashboard with inventory statistics
- Add, edit, and delete inventory items
- View inventory with search and filtering capabilities
- Responsive design that works on desktop and mobile devices

## File Structure
```
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
├── includes/
│   ├── db_config.php
│   ├── header.php
│   └── footer.php
├── index.php
├── register.php
├── login.php
├── dashboard.php
├── add_item.php
├── view_inventory.php
├── logout.php
└── database_schema.sql
```