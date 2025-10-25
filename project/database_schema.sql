-- Database: inventory_system

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create inventory_items table
CREATE TABLE inventory_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    description TEXT,
    quantity INT NOT NULL DEFAULT 0,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample data (optional)
INSERT INTO users (username, email, password) VALUES 
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('user1', 'user1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

INSERT INTO inventory_items (user_id, item_name, description, quantity, price, category) VALUES 
(1, 'Laptop', 'Gaming Laptop with RTX 3080', 10, 1299.99, 'Electronics'),
(1, 'Mouse', 'Wireless Optical Mouse', 50, 29.99, 'Electronics'),
(1, 'Keyboard', 'Mechanical Gaming Keyboard', 25, 89.99, 'Electronics'),
(2, 'Monitor', '27 inch 4K Monitor', 15, 399.99, 'Electronics');