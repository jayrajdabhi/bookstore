-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 11:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `publication_year` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `name`, `author`, `price`, `image`, `genre_id`, `publication_year`, `description`) VALUES
(12, 'Magic Tree House', 'Mary Pope Osborne', 15.60, '91PLQZpA3HL._SL1500_.jpg', 3, 2000, 'Watch out for that crocodile! Get ready for a wild ride as Jack and Annie find themselves in the Amazon Rainforest on a dangerous journey. From killer ants to vampire bats, they never know what theyll run into next! Soon, theyre hopelessly lost (and a monkey seems to be stalking them. . . whats that about?). Can they find their way through the jungle before it gets dark?\r\n\r\nFor the first time experience this gripping adventure as a graphic novel, bursting with vibrant full-color art that brings the magic to life!'),
(13, 'Secret Agents Jack and Max Stalwart', 'Elizabeth Singer Hunt', 24.94, '91eX6PynadL._SL1500_.jpg', 3, 2017, 'Secret agents Jack and Max Stalwart are sent to the Amazon jungle to investigate the disappearance of an important Global Protection Force scientist. Soon, they discover that the scientists disappearance is no accident. A greedy outlaw is illegally mining for gold and he will stop and nothing to keep his secret safe. Can Jack and Max save themselves and the scientist from almost certain death?'),
(14, 'We Who Wrestle with God', 'Jordan B. Peterson ', 33.50, '71B18dU7xnL._SL1500_.jpg', 6, 2024, 'In We Who Wrestle with God, Dr. Peterson guides us through the ancient, foundational stories of the Western world. In riveting detail, he analyzes the Biblical accounts of rebellion, sacrifice, suffering, and triumph that stabilize, inspire, and unite us culturally and psychologically. Adam and Eve and the eternal fall of mankind; the resentful and ultimately murderous war of Cain and Abel; the cataclysmic flood of Noah; the spectacular collapse of the Tower of Babel; Abraham’s terrible adventure; and the epic of Moses and the Israelites. What could such stories possibly mean? What force wrote and assembled them over the long centuries? How did they bring our spirits and the world together, and point us in the same direction?\r\n\r\nIt is time for us to understand such things, scientifically and spiritually; to become conscious of the structure of our souls and our societies; and to see ourselves and others as if for the first time.'),
(15, 'Darkness - After the Fall', 'Elle Simpson-Edin ', 19.99, '71+Z8DgO8RL._SL1500_.jpg', 2, 2024, 'Embla, second planet from the Sun. A planet where dusk till dawn takes 56 days. City Six, with its 230 million inhabitants, is a city dominated by the Dark mage guilds because light cannot penetrate the depths of a city the height of a mountain. In a city with no laws, a conflict is brewing.\r\n\r\nA battle mage retires to a life of asceticism and research, searching for answers in the Darkness. A young Dark mage comes in to her power. A Light mage team journeys from their stronghold by the sun to spy upon the Dark mages. A young pilot ekes out a living on the fringes of interstellar transport. A Life mage searches for a key to power. Together their lives head towards an inexorable crescendo, as the powers of Dark and Light grapple for control of the system.'),
(16, 'The Lost Bookshop', 'Evie Woods', 18.30, '91Yb6fD29xL._SL1500_.jpg', 1, 1998, 'For too long, Opaline, Martha and Henry have been the side characters in their own lives.\r\n\r\nBut when a vanishing bookshop casts its spell, these three unsuspecting strangers will discover that their own stories are every bit as extraordinary as the ones found in the pages of their beloved books. And by unlocking the secrets of the shelves, they find themselves transported to a world of wonder… where nothing is as it seems.');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `feedback_text` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `feedback_text`, `submitted_at`) VALUES
(1, 1, 'Thank you for books', '2024-11-19 22:19:37');

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `genre_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `genre_name`) VALUES
(3, 'Adventure'),
(6, 'Biography'),
(2, 'Dystopian'),
(4, 'Fantasy'),
(1, 'Fiction'),
(8, 'Historical'),
(7, 'Mystery'),
(5, 'Science Fiction');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `full_name`, `address`, `city`, `zip_code`, `payment_method`, `total_amount`, `created_at`) VALUES
(1, 1, 'Jayraj', '150 Darling st', 'Brantford', 'N3T6A7', 'Credit/Debit Card', 30.00, '2024-11-19 20:16:53'),
(2, 1, 'Jayraj', '150 Darling Street', 'Brantford', 'N3T6A7', 'PayPal', 30.00, '2024-11-19 20:27:34'),
(3, 1, 'Jayraj', '21 Diana Avenue', 'Brantford', 'N3T6A7', 'PayPal', 30.00, '2024-11-19 21:42:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'prv', '$2y$10$zswLWFTjQmjENq9OGryW..egb1VXTe.RHYYi5PePCXQ2pjOABGgtS', 'prvvelawala@gmail.com', 'customer', '2024-10-30 01:11:01'),
(2, 'jayrajdabhi', '$2y$10$qYbN7GN1DnIlU9KA1C01nOKZiF.SD1mEvQEBiqXSt4WaYxirbGuxG', 'dabhi.jayraj200498@gmail.com', 'admin', '2024-11-19 19:46:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`profile_id`, `user_id`, `first_name`, `last_name`, `phone_number`, `address`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 2, 'Jayraj', 'Dabhi', '2269780954', 'jayraj dabhi', 'uploads/thumb-1920-136006g1.png', '2024-11-19 20:55:03', '2024-11-19 20:55:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `genre_name` (`genre_name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
