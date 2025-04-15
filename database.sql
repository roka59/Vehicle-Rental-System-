-- Create tables
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE books (
    book_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    category_id INT,
    stock_quantity INT NOT NULL,
    image_url VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    book_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (book_id) REFERENCES books(book_id)
);

-- Insert initial data
-- Categories
INSERT INTO categories (category_name, description) VALUES
('Fiction', 'Explore imaginative worlds through novels, short stories, and literary fiction'),
('Non-Fiction', 'Discover real-world knowledge through biographies, history, and educational books'),
('Children''s Books', 'Engaging stories and educational content for young readers');

-- Users (admin and regular users)
-- Admin user (password: admin123)
INSERT INTO users (username, password, email, is_admin) VALUES
('admin', 'admin123', 'admin@booknook.com', TRUE);

-- Regular users (passwords: user1, user2)
INSERT INTO users (username, password, email, is_admin) VALUES
('user1', 'user1', 'user1@example.com', FALSE),
('user2', 'user2', 'user2@example.com', FALSE);

-- Sample books
INSERT INTO books (title, author, price, description, category_id, stock_quantity, image_url) VALUES
-- Fiction Books
('The Midnight Library', 'Matt Haig', 24.99, 'Between life and death there is a library. When Nora finds herself in the Midnight Library, she has a chance to make things right.', 1, 50, 'images/products/b1_1.jpg'),
('The Silent Patient', 'Alex Michaelides', 22.99, 'A woman shoots her husband five times and then never speaks another word.', 1, 45, 'images/products/b1_2.jpg'),
('Project Hail Mary', 'Andy Weir', 26.99, 'A lone astronaut must save humanity from a catastrophic extinction event.', 1, 40, 'images/products/b1_3.jpg'),
('The Seven Husbands of Evelyn Hugo', 'Taylor Jenkins Reid', 23.99, 'An aging Hollywood starlet reveals the story of her glamorous life.', 1, 35, 'images/products/b1_4.jpg'),

-- Non-Fiction Books
('Atomic Habits', 'James Clear', 27.99, 'Tiny Changes, Remarkable Results: An Easy & Proven Way to Build Good Habits & Break Bad Ones.', 2, 60, 'images/products/b2_1.jpg'),
('Sapiens', 'Yuval Noah Harari', 29.99, 'A Brief History of Humankind: From ancient civilizations to modern society.', 2, 55, 'images/products/b2_2.jpg'),
('The Body', 'Bill Bryson', 25.99, 'A Guide for Occupants: An exploration of the human body.', 2, 45, 'images/products/b2_3.jpg'),
('Think Again', 'Adam Grant', 24.99, 'The Power of Knowing What You Don''t Know.', 2, 50, 'images/products/b2_4.jpg'),

-- Children's Books
('The Gruffalo', 'Julia Donaldson', 14.99, 'A mouse takes a walk through the deep dark wood.', 3, 70, 'images/products/b3_1.jpg'),
('Where the Wild Things Are', 'Maurice Sendak', 16.99, 'The night Max wore his wolf suit and made mischief of one kind and another.', 3, 65, 'images/products/b3_2.jpg'),
('The Very Hungry Caterpillar', 'Eric Carle', 15.99, 'Follow the caterpillar''s journey through various foods until it becomes a butterfly.', 3, 75, 'images/products/b3_3.jpg'),
('Wonder', 'R.J. Palacio', 17.99, 'A story about a boy with facial differences who enters fifth grade.', 3, 60, 'images/products/b3_4.jpg');