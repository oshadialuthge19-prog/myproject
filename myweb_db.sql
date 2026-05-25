-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2026 at 08:35 AM
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
-- Database: `myweb_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `gpa_submissions`
--

CREATE TABLE `gpa_submissions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mentor_assignments`
--

CREATE TABLE `mentor_assignments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mentor_assignments`
--

INSERT INTO `mentor_assignments` (`id`, `student_id`, `mentor_id`, `assigned_at`) VALUES
(1, 19, 23, '2026-05-24 20:22:01'),
(2, 22, 21, '2026-05-24 20:37:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `usersId` int(12) NOT NULL,
  `usersName` varchar(20) NOT NULL,
  `usersEmail` varchar(32) NOT NULL,
  `usersPwd` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `mentor_id` varchar(50) NOT NULL,
  `course` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`usersId`, `usersName`, `usersEmail`, `usersPwd`, `role`, `student_id`, `mentor_id`, `course`, `department`) VALUES
(3, 'Admin User', 'admin@gmail.com', '123456', 'mentor', '', '', '', ''),
(4, 'nethmi', 'nethmi@gmail.com', '2468', 'student', '', '', '', ''),
(5, 'ui', 'ui@gmail.com', '0987', 'student', '', '', '', ''),
(6, 'hansi', 'hansi@gmail.com', 'asdf', 'student', '', '', '', ''),
(7, 'osi', 'osi@gmail.com', 'iiii', 'student', '', '', '', ''),
(8, 'Hansamali', 'isurikahansi@gmail.com', 'Legend#1999', 'student', '', '', '', ''),
(9, 'lochimentor', 'lochimentor@gmail.com', '1234', 'student', '', '', '', ''),
(10, 'admin lochi', 'admin@lochi.com', 'uio', 'student', '', '', '', ''),
(11, 'kasun jayawardhana', 'kasun@gmail.com', '123456', 'student', '', '', '', ''),
(12, 'malisha', 'malisha@gmail', '12345', 'student', '', '', '', ''),
(13, 'Oshadi Aluthge', 'oshadi@gmail.com', '$2y$10$n7ddeBU3Twq3XkLhDod1ieanDOoGUT.SwvMNO6ietP0mFi0kxeFEG', 'student', 'st001', '', 'software engineering', ''),
(14, 'hansi', 'hansamali@gmail.com', '$2y$10$y/I7HJz7aYVA5HdF/aAuCeCe6kpybvD4NDo9nZUmu3IR9mp6EPZky', 'mentor', '', 'mt001', '', 'computing'),
(15, 'hansi', 'hansi4@gmail.com', '$2y$10$cggsm5/yUi6Xv4E/HtBcF.JvnwCcClN91sOIZp3NqnbYjMbN2YzNu', 'student', 'st002', '', 'bit', ''),
(16, 'admin', 'admin1@gmail.com', '$2y$10$S0kdQMkf8OzTv8GTej.WHOth9yUxy/rttfBj5gNxwTUs98QGgDoLW', 'student', 'st003', '', 'bit', ''),
(17, 'Hansamali Isurika', 'Hansamali.Chandrasekara@intrepid', '$2y$10$inGkh3lmKf2uwFETCxhnBul9XJsXpxoc6KBpt6RRUAx5oJeRmvkX.', 'student', 'st003', '', 'Business Analysis', ''),
(18, 'Hansamali Isurika', 'Hansamali.Chandrasekara@intrepid', '$2y$10$A99ynzosSN61Bq3CIMigcO6iV6ea1LHLXjvdf8BEpMrbdxCIT5ga2', 'student', 'st005', '', 'Business Analysis', ''),
(19, 'Isurika hansi', 'isurika2@gmail.com', '$2y$10$wnkyvKL9uV045kk4F17Zpu8WY4YjIGfu.ZqUyd/jTEHq1tJdTXH3G', 'student', 'st006', '', 'Accounting', ''),
(20, 'Nisal Aluthge', 'nisaldaluthge@gmail.com', '$2y$10$1qsxjSHcNRUx/QfFGofoS.HNVNu8D8cC0cQMjY4GeZeBykoz3TMGe', 'student', 'st007', '', 'Finance', ''),
(21, 'Pathima Sriyani', 'pathima@gmail.com', '$2y$10$DslR49LXvB8mjmMUYjXX.egCbEdPT9XrA9D.N4mGR4RPO0BfUC1bi', 'mentor', '', 'mt001', '', 'bit'),
(22, 'Nisal devinda', 'nisal6@gmail.com', '$2y$10$97I6E2siw65RnvG6o5Vpr.L0tdN4SmihpGZn7zcHiqkTlcYKSp1Ya', 'student', 'st008', '', 'Cyber Security', ''),
(23, 'Park Jimin', 'jimin1013@gmail.com', '$2y$10$12dzKckBsGxo5EAxjyNeoutaPrqHFJGRbsyWXJocUtwolrbNpZPe2', 'mentor', '', 'mt001', '', 'bit');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gpa_submissions`
--
ALTER TABLE `gpa_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mentor_assignments`
--
ALTER TABLE `mentor_assignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`usersId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gpa_submissions`
--
ALTER TABLE `gpa_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mentor_assignments`
--
ALTER TABLE `mentor_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `usersId` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
