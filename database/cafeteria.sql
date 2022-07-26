-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2022 at 07:19 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cafeteria`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `describtion` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `creationDate` date DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `describtion`, `image`, `creationDate`, `body`, `uid`) VALUES
(20, 'art1', 'art1 desc', '283703667789-team-3-800x800.jpg', '2022-07-26', 'art1 body', 14),
(21, 'art2', 'art2 desc', '138620779171-b.jpg', '2022-07-26', 'art2 body', 14);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Userid` int(11) NOT NULL COMMENT 'id primary key',
  `Fname` varchar(255) NOT NULL COMMENT 'username',
  `Lname` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL COMMENT 'unique email',
  `Password` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `BirthDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Userid`, `Fname`, `Lname`, `Email`, `Password`, `Address`, `BirthDate`) VALUES
(4, 'mohamed', 'fouad', 'mo@gmail.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'mansoura', '1998-02-01'),
(5, 'jnjnjnjn', 'jnjnjnjnjn', 'jnjnjnjn@bhbh.jn', 'e8248cbe79a288ffec75d7300ad2e07172f487f6', 'jnjn', '8585-12-12'),
(6, 'gyggy', 'gygygygy', 'gygygygy@jnjn.derk', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'jnjnjnjn', '2002-10-10'),
(7, 'bhbhbhbh', 'hbbhbhbhbh', 'bhbhbh@jnjn.jnjn', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'jnjnjnjn', '2005-12-12'),
(8, 'jnjui', 'uiuiui', 'uiuiui@ded.mk', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'bhbhb', '2003-01-01'),
(9, 'njnjnjn', 'njnjnjn', 'jnjnjnjn@sw.ui', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'njnj', '1998-02-08'),
(10, 'iiiiii', 'iiiiiiiii', 'iiiiiii@d.mk', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'jnjn', '2003-01-18'),
(11, 'oopooo', 'oppppp', 'opppp@pooo.jnjnjn', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'opopop', '2009-01-25'),
(12, 'ooooooooo', 'ooooooo', 'oooo@o.ooo', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'oooooo', '1998-12-12'),
(13, 'mkmioi', 'opoo', 'pooppoo@aw.ki', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'bhbhb', '2008-12-15'),
(14, 'Ahmed', 'fouad', 'ah@gmail.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'Ahmed maher', '1998-02-15'),
(15, 'momo', 'momomomo', 'momo@mk.mo', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'mmoo', '2002-11-11'),
(16, 'testdate', 'testdate', 'testdate@mk.mk', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'testdate', '2022-07-26'),
(17, 'eslam', 'fouad', 'eslam@gmail.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'mans', '2022-07-25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `articles_ibfk_1` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Userid`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Userid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id primary key', AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`Userid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
