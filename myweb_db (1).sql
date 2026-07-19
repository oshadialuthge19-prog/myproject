-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2026 at 05:48 PM
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
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `student_id`, `mentor_id`, `appointment_date`, `appointment_time`, `message`, `status`, `created_at`) VALUES
(1, 22, 23, '2026-08-25', '09:30:00', 'disscuss about project', 'Approved', '2026-05-25 12:38:32'),
(2, 24, 21, '2026-05-26', '13:30:00', 'disscuss viva ', 'Approved', '2026-05-26 03:42:47'),
(3, 24, 21, '2026-05-26', '10:12:00', 'kjewhfewjhf kjewfr jnr jr', 'Pending', '2026-05-26 04:42:24'),
(4, 24, 21, '2026-05-26', '10:12:00', 'kjewhfewjhf kjewfr jnr jr', 'Pending', '2026-05-26 04:42:24'),
(5, 25, 23, '2026-08-25', '09:00:00', 'disscuss viva', 'Pending', '2026-05-26 09:18:16'),
(6, 25, 23, '2026-08-25', '09:00:00', 'disscuss viva', 'Rejected', '2026-05-26 09:18:16'),
(7, 27, 28, '2026-07-17', '11:30:00', 'regarding project', 'Approved', '2026-07-16 17:00:24');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `gpa_submissions`
--

INSERT INTO `gpa_submissions` (`id`, `student_id`, `mentor_id`, `semester`, `gpa`, `submitted_at`) VALUES
(3, 22, 21, 'y3s2', 3.51, '2026-05-25 11:07:58'),
(4, 24, 21, 'y3s2', 4.00, '2026-05-26 03:41:30'),
(5, 24, 21, 'y3s2', 4.00, '2026-05-26 04:29:32'),
(6, 24, 23, '1y2s', 4.00, '2026-05-26 08:02:22'),
(7, 25, 21, 'y3s2', 4.00, '2026-05-26 09:06:41'),
(8, 25, 21, 'y3s2', 3.40, '2026-05-26 09:07:56');

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
(2, 22, 21, '2026-05-24 20:37:59'),
(3, 27, 28, '2026-07-14 21:12:37');

-- --------------------------------------------------------

--
-- Table structure for table `mentor_notifications`
--

CREATE TABLE `mentor_notifications` (
  `id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mentor_notifications`
--

INSERT INTO `mentor_notifications` (`id`, `mentor_id`, `message`, `is_read`, `created_at`) VALUES
(1, 21, 'A student submitted a GPA report.', 0, '2026-05-26 04:29:32'),
(2, 21, 'A new appointment has been booked.', 0, '2026-05-26 04:42:24'),
(3, 23, 'A student submitted a GPA report.', 0, '2026-05-26 08:02:22'),
(4, 21, 'A student submitted a GPA report.', 0, '2026-05-26 09:06:41'),
(5, 21, 'A student submitted a GPA report.', 0, '2026-05-26 09:07:56'),
(6, 23, 'A new appointment has been booked.', 0, '2026-05-26 09:18:16');

-- --------------------------------------------------------

--
-- Table structure for table `mentor_profiles`
--

CREATE TABLE `mentor_profiles` (
  `id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `university` varchar(100) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `student_id`, `message`, `is_read`, `created_at`) VALUES
(1, 22, 'Your appointment has been APPROVED.', 0, '2026-05-26 02:51:30'),
(2, 22, 'Your appointment has been REJECTED.', 0, '2026-05-26 02:51:33'),
(3, 22, 'Your appointment has been APPROVED.', 0, '2026-05-26 02:51:35'),
(4, 22, 'Your appointment has been REJECTED.', 0, '2026-05-26 02:51:36'),
(5, 22, 'Your appointment has been APPROVED.', 0, '2026-05-26 02:51:51'),
(6, 22, 'Your appointment has been REJECTED.', 0, '2026-05-26 02:51:53'),
(7, 24, 'Your appointment has been APPROVED.', 0, '2026-05-26 03:44:39'),
(8, 22, 'Your appointment has been APPROVED.', 0, '2026-05-26 09:14:46'),
(9, 22, 'Your appointment has been REJECTED.', 0, '2026-05-26 09:14:48'),
(10, 22, 'Your appointment has been APPROVED.', 0, '2026-06-02 12:22:36'),
(11, 25, 'Your appointment has been APPROVED.', 0, '2026-06-02 12:22:43'),
(12, 25, 'Your appointment has been REJECTED.', 0, '2026-06-02 12:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `student_profiles`
--

CREATE TABLE `student_profiles` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `degree` varchar(100) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `academic_year` varchar(50) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_profiles`
--

INSERT INTO `student_profiles` (`id`, `student_id`, `full_name`, `email`, `degree`, `semester`, `academic_year`, `contact_no`, `address`, `bio`, `created_at`, `profile_picture`) VALUES
(1, 24, 'Sanduni silva', 'sanduni@gmail.com', 'se', 'y3s2', '3', '0750240861', '133/g,\r\ndjfnkerjfnejrf,\r\nerfnerjkgnjrf.', 'kfmerjkfnjerfn kjefnrjekfne ejfnrf jnkr rjr ejfrrj jrtjj4 ', '2026-05-25 17:31:44', 'Uploads/1779739102_me.png'),
(2, 27, 'Sanduni silva', 'Hansamali.Chandrasekara@intrepidtravel.com', 'Bachelor of Information Technology', 'Semester 4', '3', '0750240867', 'mhgjgh jhg jhuhui', ',mnjhygy ibgygt6rde5wsw l,okkoi jiouhi hby', '2026-07-04 10:18:26', '');

-- --------------------------------------------------------

--
-- Table structure for table `system_notifications`
--

CREATE TABLE `system_notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `related_id` int(11) DEFAULT NULL,
  `notification_link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_notifications`
--

INSERT INTO `system_notifications` (`id`, `user_id`, `type`, `message`, `related_id`, `notification_link`, `is_read`, `created_at`) VALUES
(1, 28, 'appointment', 'A student has booked a new appointment.', 7, 'mentor_appointment.php', 0, '2026-07-16 17:00:24'),
(2, 27, 'appointment', 'Your appointment request has been approved.', 7, 'book_appointment.php', 0, '2026-07-16 17:01:53');

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
(23, 'Park Jimin', 'jimin1013@gmail.com', '$2y$10$12dzKckBsGxo5EAxjyNeoutaPrqHFJGRbsyWXJocUtwolrbNpZPe2', 'mentor', '', 'mt001', '', 'bit'),
(24, 'sanduni', 'sanduni@gmail.com', '$2y$10$etmXY3Vqq9ElUk0/EnPAIe2PsFv9JqlpaVriS1fVr4lg3SPd007T2', 'student', 'st009', '', 'Graphic Design', ''),
(25, 'maduri', 'maduri@gmail.com', '$2y$10$xhUI3wuEQx23NKNST3GyD.MA9XNUOwWOtHfJFxhxLrL3czV6lbaoy', 'student', 'st009', '', 'Cyber Security', ''),
(26, 'rangani atapattu', 'rangani@gmail.com', '$2y$10$Rz57eu9jK5diNiuTei67p.r2L2cwTzUt3x18J65FwCDGrPq/QO8Tu', 'mentor', '', 'mt0011', '', 'bit'),
(27, 'abs', 'abs@gmail.com', '$2y$10$Psf4klsdczo85rk3BtKWnexDgiknSuBAVOsYzvdJPm6gNZfScg6Rq', 'student', 'st0021', '', 'Graphic Design', ''),
(28, 'test1', 'test@gmail.com', '$2y$10$RmQCI9dru4GgZqY5lCeTReGt5.IZE7Jn6zIZfWacQGvPqla4tiuIe', 'mentor', '', 'mt001', '', 'computing');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `mentor_notifications`
--
ALTER TABLE `mentor_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mentor_profiles`
--
ALTER TABLE `mentor_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mentor_id` (`mentor_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_notifications`
--
ALTER TABLE `system_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`usersId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gpa_submissions`
--
ALTER TABLE `gpa_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mentor_assignments`
--
ALTER TABLE `mentor_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mentor_notifications`
--
ALTER TABLE `mentor_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mentor_profiles`
--
ALTER TABLE `mentor_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `student_profiles`
--
ALTER TABLE `student_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `system_notifications`
--
ALTER TABLE `system_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `usersId` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`usersId`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`usersId`) ON DELETE CASCADE;

--
-- Constraints for table `system_notifications`
--
ALTER TABLE `system_notifications`
  ADD CONSTRAINT `system_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`usersId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
