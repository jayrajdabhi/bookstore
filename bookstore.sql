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

-- Create genres table to store the different genres
CREATE TABLE IF NOT EXISTS genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    genre_name VARCHAR(255) NOT NULL UNIQUE
);

-- Insert sample genres into genres table
INSERT INTO genres (genre_name) VALUES
('Fiction'),
('Dystopian'),
('Adventure'),
('Fantasy'),
('Science Fiction'),
('Biography'),
('Mystery'),
('Historical');

-- Books table: stores information about the books in the bookstore
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    genre_id INT,  -- This is now a foreign key to the genres table
    publication_year INT,  -- Changed YEAR to INT
    description TEXT,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE SET NULL  -- Foreign key constraint
);

-- Dummy data insertion for books table with genre references
-- INSERT INTO books (name, author, price, image, genre_id, publication_year, description) VALUES
-- ('The Great Gatsby', 'F. Scott Fitzgerald', 10.99, 'img/great_gatsby.jpg', (SELECT id FROM genres WHERE genre_name = 'Fiction'), 1925, 'A classic novel set in the Jazz Age, exploring themes of wealth and society.'),
-- ('1984', 'George Orwell', 8.99, 'img/1984.jpg', (SELECT id FROM genres WHERE genre_name = 'Dystopian'), 1949, 'A dystopian novel that delves into the dangers of totalitarianism and extreme political ideology.'),
-- ('To Kill a Mockingbird', 'Harper Lee', 12.50, 'img/to_kill_a_mockingbird.jpg', (SELECT id FROM genres WHERE genre_name = 'Fiction'), 1960, 'A novel about racial injustice in the Deep South, told through the eyes of a child.'),
-- ('Moby Dick', 'Herman Melville', 15.00, 'img/moby_dick.jpg', (SELECT id FROM genres WHERE genre_name = 'Adventure'), 1851, 'The story of Captain Ahab’s obsessive quest to hunt down the white whale, Moby Dick.');
INSERT INTO `books` (`id`, `name`, `author`, `price`, `image`, `genre_id`, `publication_year`, `description`) VALUES
(12, 'Magic Tree House', 'Mary Pope Osborne', 15.60, '91PLQZpA3HL._SL1500_.jpg', 3, 2000, 'Watch out for that crocodile! Get ready for a wild ride as Jack and Annie find themselves in the Amazon Rainforest on a dangerous journey. From killer ants to vampire bats, they never know what theyll run into next! Soon, theyre hopelessly lost (and a monkey seems to be stalking them. . . whats that about?). Can they find their way through the jungle before it gets dark?\r\n\r\nFor the first time experience this gripping adventure as a graphic novel, bursting with vibrant full-color art that brings the magic to life!'),
(13, 'Secret Agents Jack and Max Stalwart', 'Elizabeth Singer Hunt', 24.94, '91eX6PynadL._SL1500_.jpg', 3, 2017, 'Secret agents Jack and Max Stalwart are sent to the Amazon jungle to investigate the disappearance of an important Global Protection Force scientist. Soon, they discover that the scientists disappearance is no accident. A greedy outlaw is illegally mining for gold and he will stop and nothing to keep his secret safe. Can Jack and Max save themselves and the scientist from almost certain death?'),
(14, 'We Who Wrestle with God', 'Jordan B. Peterson ', 33.50, '71B18dU7xnL._SL1500_.jpg', 6, 2024, 'In We Who Wrestle with God, Dr. Peterson guides us through the ancient, foundational stories of the Western world. In riveting detail, he analyzes the Biblical accounts of rebellion, sacrifice, suffering, and triumph that stabilize, inspire, and unite us culturally and psychologically. Adam and Eve and the eternal fall of mankind; the resentful and ultimately murderous war of Cain and Abel; the cataclysmic flood of Noah; the spectacular collapse of the Tower of Babel; Abraham’s terrible adventure; and the epic of Moses and the Israelites. What could such stories possibly mean? What force wrote and assembled them over the long centuries? How did they bring our spirits and the world together, and point us in the same direction?\r\n\r\nIt is time for us to understand such things, scientifically and spiritually; to become conscious of the structure of our souls and our societies; and to see ourselves and others as if for the first time.'),
(15, 'Darkness - After the Fall', 'Elle Simpson-Edin ', 19.99, '71+Z8DgO8RL._SL1500_.jpg', 2, 2024, 'Embla, second planet from the Sun. A planet where dusk till dawn takes 56 days. City Six, with its 230 million inhabitants, is a city dominated by the Dark mage guilds because light cannot penetrate the depths of a city the height of a mountain. In a city with no laws, a conflict is brewing.\r\n\r\nA battle mage retires to a life of asceticism and research, searching for answers in the Darkness. A young Dark mage comes in to her power. A Light mage team journeys from their stronghold by the sun to spy upon the Dark mages. A young pilot ekes out a living on the fringes of interstellar transport. A Life mage searches for a key to power. Together their lives head towards an inexorable crescendo, as the powers of Dark and Light grapple for control of the system.'),
(16, 'The Lost Bookshop', 'Evie Woods', 18.30, '91Yb6fD29xL._SL1500_.jpg', 1, 1999, 'For too long, Opaline, Martha and Henry have been the side characters in their own lives.\r\n\r\nBut when a vanishing bookshop casts its spell, these three unsuspecting strangers will discover that their own stories are every bit as extraordinary as the ones found in the pages of their beloved books. And by unlocking the secrets of the shelves, they find themselves transported to a world of wonder… where nothing is as it seems.'),
(17, 'Daydream', 'Hannah Grace', 12.80, '71EVPOrf8kL._SL1500_.jpg', 8, 1992, 'When his procrastination lands him in a difficult class with his least favorite professor, Henry Turner knows he’s going to have to work extra hard to survive his junior year of college. And now with his new title of captain for the hockey team—which he didn’t even want—Henry absolutely cannot fail. Enter Halle Jacobs, a fellow junior who finds herself befriended by Henry when he accidentally crashes her book club.\r\n\r\nHalle may not have the romantic pursuits of her favorite fictional leads, but she’s an academic superstar, and as soon as she hears about Henry’s problems with his class reading material, she offers to help. Too bad being a private tutor isn’t exactly ideal given her own studies, job, book club, and the novel she’s trying to write. But new experiences are the key to beating her writer’s block, and Henry’s promising to be the one to give them to her.');

-- User Profile table: stores additional details for each user (first name, last name, address, etc.)
CREATE TABLE IF NOT EXISTS user_profiles (
    profile_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20),
    address TEXT,
    profile_picture VARCHAR(255) NULL,  -- Added profile_picture column
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
-- INSERT INTO user_profiles (user_id, first_name, last_name, phone_number, address, profile_picture, created_at, updated_at)
-- VALUES (1, 'Pravin', 'Velawala', '123-456-7890', '123 Main St, City, Country', 'img/profile_picture.jpg', NOW(), NOW());

