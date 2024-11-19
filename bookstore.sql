-- Create the `bookstore` database
CREATE DATABASE IF NOT EXISTS bookstore;

USE bookstore;

-- User table: stores user data (login credentials, role)
CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact Us table: stores contact form submissions from users
CREATE TABLE IF NOT EXISTS contact_us (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(255),
    message TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Books table: stores information about the books in the bookstore
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    publication_year INT,  -- Changed YEAR to INT
    description TEXT
);

-- Dummy data insertion for books table (optional)
INSERT INTO books (name, author, price, image, genre, publication_year, description) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 10.99, 'img/great_gatsby.jpg', 'Fiction', 1925, 'A classic novel set in the Jazz Age, exploring themes of wealth and society.'),
('1984', 'George Orwell', 8.99, 'img/1984.jpg', 'Dystopian', 1949, 'A dystopian novel that delves into the dangers of totalitarianism and extreme political ideology.'),
('To Kill a Mockingbird', 'Harper Lee', 12.50, 'img/to_kill_a_mockingbird.jpg', 'Fiction', 1960, 'A novel about racial injustice in the Deep South, told through the eyes of a child.'),
('Moby Dick', 'Herman Melville', 15.00, 'img/moby_dick.jpg', 'Adventure', 1851, 'The story of Captain Ahab’s obsessive quest to hunt down the white whale, Moby Dick.');

-- User Profile table: stores additional details for each user (first name, last name, address, etc.)
CREATE TABLE IF NOT EXISTS user_profiles (
    profile_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Foreign key constraint: Links to the users table
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert initial dummy data into the `users` table
INSERT INTO users (username, password, email, role, created_at)
VALUES
    ('prv', '$2y$10$zswLWFTjQmjENq9OGryW..egb1VXTe.RHYYi5PePCXQ2pjOABGgtS', 'prvvelawala@gmail.com', 'customer', '2024-10-29 21:11:01');

-- You can insert a user profile for the user above once the user is created
-- INSERT INTO user_profiles (user_id, first_name, last_name, phone_number, address, created_at, updated_at)
-- VALUES (1, 'Pravin', 'Velawala', '123-456-7890', '123 Main St, City, Country', NOW(), NOW());

