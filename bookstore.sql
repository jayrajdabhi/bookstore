CREATE DATABASE bookstore;

USE bookstore;


-- User database
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact Us Database --
CREATE TABLE contact_us (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(255),
    message TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Books Database Table
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    genre VARCHAR(100),
    publication_year INT,  -- Change YEAR to INT
    description TEXT
);


-- Dummy Data
-- INSERT INTO books (name, author, price, image, genre, publication_year, description) VALUES
-- ('The Great Gatsby', 'F. Scott Fitzgerald', 10.99, 'img/great_gatsby.jpg', 'Fiction', 1925, 'A classic novel set in the Jazz Age, exploring themes of wealth and society.'),
-- ('1984', 'George Orwell', 8.99, 'img/1984.jpg', 'Dystopian', 1949, 'A dystopian novel that delves into the dangers of totalitarianism and extreme political ideology.'),
-- ('To Kill a Mockingbird', 'Harper Lee', 12.50, 'img/to_kill_a_mockingbird.jpg', 'Fiction', 1960, 'A novel about racial injustice in the Deep South, told through the eyes of a child.'),
-- ('Moby Dick', 'Herman Melville', 15.00, 'img/moby_dick.jpg', 'Adventure', 1851, 'The story of Captain Ahab’s obsessive quest to hunt down the white whale, Moby Dick.');  -- 1851 is fine for INT

-- User Profile table
CREATE TABLE user_profiles (
    profile_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
