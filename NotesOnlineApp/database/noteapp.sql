-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2024 at 01:09 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `noteapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive2_tbl`
--

CREATE TABLE `archive2_tbl` (
  `archive_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `note_id` int(11) DEFAULT NULL,
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archive_tbl`
--

CREATE TABLE `archive_tbl` (
  `archive_id` int(11) NOT NULL,
  `user_id` int(10) NOT NULL,
  `note_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logintbl`
--

CREATE TABLE `logintbl` (
  `user_id` int(10) NOT NULL,
  `f_name` varchar(50) NOT NULL,
  `l_name` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `l_email` varchar(50) NOT NULL,
  `pass_word` varchar(100) NOT NULL,
  `profile_picture` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logintbl`
--

INSERT INTO `logintbl` (`user_id`, `f_name`, `l_name`, `user_name`, `l_email`, `pass_word`, `profile_picture`) VALUES
(1, 'aeron', 'villafueret', '0', '0', '0', ''),
(2, 'aeron', 'villafueret', '0', '0', '0', ''),
(3, 'Aeron', 'Villafuerte', '0', '0', '0', ''),
(4, 'Darla Kayla', 'Ipon', 'kayla', 'aeronvillafuerte20@gmail.com', 'kay', ''),
(5, 'Darla Kayla', 'Ipon', 'kayla', 'aeronvillafuerte20@gmail.com', 'kay', ''),
(6, 'Aicee', 'Villafuerte', 'pleng', 'aicee@gmail.com', 'indang', ''),
(7, 'Tyron', 'Villafuerte', 'tyron', 'tyron@gmail.com', '$2y$10$jrmfQ.9yog5YPEVW4yjzsuAfowBZDKAEYX62VI5YzC9', ''),
(8, 'Tyron', 'Villafuerte', 'tyron', 'tyron@gmail.com', '$2y$10$jj/0k6rJc1YuTwZ/xvOeFu4Vg8lGSsnOF/EB6D0iFfi', ''),
(9, 'Quin Hailey', 'Villafuerte', 'bandat', 'quin@gmail.com', '$2y$10$9QhIRz4O6GZAP0i8ijHWzuzynqwNw2UVXpeB48sWs7r', ''),
(10, 'Quin Hailey', 'Villafuerte', 'bandat', 'quin@gmail.com', '$2y$10$JPGgbXV8KsOtmJR3BM6vbedHM9UtoppmjWJRD.ko4KL', ''),
(11, 'Kryztel', 'Camello', 'Etil', 'etil@gmail.com', '$2y$10$yF4vsS1A9UJtTiE7cFMXceFza.wQz8dsb9cC3X4CRPq', ''),
(12, 'DARLA', 'MAYUNO', 'darla', 'darla@gmail.com', '$2y$10$PsdwBK30FPAw2kE1DTqNue4HJNZTzZ0Jh95MQ.cg.5z', ''),
(13, 'Jungkook', 'Jeon', 'jk', 'aeronvillafuerte20@gmail.com', '$2y$10$faHwMFlhBf8vLPuJNFzr9uz9JMDp8uP5Tn0Kk6rP3PH', ''),
(14, 'Kryztel', 'Camello', 'etil', 'etil@gmail.com', '$2y$10$iB3KtdDtNR4ehL9ZyrcnXu4i7bmPkWwRMRHd7XozetL', ''),
(15, 'Prima', 'Villafuerte', 'jing', 'jing@gmail.com', '$2y$10$E.Xb28wGPIXQSYEGYWKQEOnZfxA1.sl9M8XWXGhsjxS', ''),
(16, 'Caryll Jean', 'Deiparine', 'caryll', 'erickson@gmail.com', '$2y$10$fED03zSW9.bl2PquwB4VJOOJU15iKe5fVCXjMo.Rnu8', ''),
(17, 'Ariel', 'Abellana', 'ariel', 'aeronvillafuerte20@gmail.com', 'aaa', ''),
(18, 'sss', 'sss', 'sss', 'aeronvillafuerte@gmail.com', '$2y$10$hwqZTZP/yPvYOyv/7THvf.gOaVqTEZSVR94dpxI8U7b', ''),
(19, 'bbb', 'bbb', 'bbb', 'erickson@gmail.com', 'bbb', ''),
(20, 'vvv', 'vvv', 'vvv', 'aeronvillafuerte@gmail.com', '$2y$10$2gcK0v7Xetpf2oNcTyVH/.Akut3eEvuQQ2ydeX0FmDZ', ''),
(21, 'Sweet Venice', 'Casia', 'sweet', 'aeronvillafuertegmail.com', 'xxx', ''),
(22, 'eee', 'eee', 'eee', 'eee@gmail.com', '$2y$10$stmXb6SH/xUqI0n89AabeOtd8N8yaT9q.yT1kwMpok1XYSXpoJRwu', ''),
(23, 'Quin', 'Villafuerte', 'quin', 'quin@gmail.com', 'quin', ''),
(24, 'Aicee', 'Villafuerte', 'aicee', 'quin@gmail.com', '$2y$10$jSsEH4BXyg7jRaK3ZyL8DO6ylYXp4W8dtOYA4jcFKrjxXBetXSQbW', ''),
(25, 'Badidang', 'Villafuerte', 'www', 'quin@gmail.com', '$2y$10$aUZVg.1aWPCzsHRxdizVrOPn06wjp6qG2bJhNMUitOtN33KuoBX62', ''),
(26, 'kkk', 'kkk', 'kkk', 'quin@gmail.com', '$2y$10$q91BW/4PMn8ijGjF5kUyCuysWQVEQbJGL/YiiPOc103/LYVFy21fC', ''),
(27, 'fff', 'fff', 'fff', 'quin@gmail.com', '$2y$10$rgKIsS7a8ZABpmTzB8huauXykfSvoxxD2DcAwiVkG5nu.YCojTimO', ''),
(28, 'claire', 'rellin', 'claring', 'quin@gmail.com', '$2y$10$zO5MLEiRE6afJUXrDMN.M.F9BNKRygVs/6tmSjuaDhPvbQpQdLQuK', ''),
(29, 'claring', 'Villafuerte', 'ring', 'quin@gmail.com', '$2y$10$1dQ4Hzsio73cqSIA.0QCGOlGuzxckiRw6hdGbuX55/.KmcX3xPFYW', ''),
(30, 'buang', 'ka', 'angbu', 'quin@gmail.com', '$2y$10$/J.MzmnPPouJFfH/IUg6v.nq476rQnq0WBt6Vtoqly95nygr2mEsC', ''),
(31, 'bbb', 'bbb', 'bbb', 'bbb@gmail.com', '$2y$10$sppNeBbw2XfgEvrsLye.me3MIibk/g6Wy84g3TFTnKIPejvv/ygeO', ''),
(32, 'aaa', 'aaa', 'aaa', 'ddd@gmail.com', '$2y$10$bY3nIypV6Eqs2V8LsqZU/.LVnTSgnsJwqXeWW2i.NChSVFEH71QMC', ''),
(33, 'kkk', 'kkk', 'kkk', 'aeronvillafuerte20@gmail.com', '$2y$10$4cfvjp4sQRsVGdeS7RMZ9.vzgy2gWIYGmT8RVeQq7b8EkTRZY9qTq', ''),
(34, 'TAEHYUNG', 'KIM', 'V', 'shem@gmail.com', '$2y$10$C2AQ374d/qtOMbIPRWoJRu2lTxPyNvI.kSk65s2bDBlBoUgRM.J7O', 0x75706c6f6164732f494d475f32303231303831355f3038303435382e6a7067);

-- --------------------------------------------------------

--
-- Table structure for table `notes_tbl`
--

CREATE TABLE `notes_tbl` (
  `note_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(50) NOT NULL,
  `created_at` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes_tbl`
--

INSERT INTO `notes_tbl` (`note_id`, `user_id`, `title`, `content`, `created_at`) VALUES
(1, 32, 'sss', 'ssss', '2024-04-02 19:51:44'),
(2, 32, 'ssssss', 'sss', '2024-04-02 19:52:51'),
(3, 32, 'bbb', 'bbb', '2024-04-02 19:54:20'),
(4, 32, 'ddd', 'ddd', '2024-04-02 19:55:20'),
(5, 32, 'sss', 'ssss', '2024-04-02 19:59:39'),
(6, 32, 'sss', 'ssss', '2024-04-02 19:59:59'),
(7, 32, 'badidang', 'buang', '2024-04-02 20:07:03'),
(8, 32, 'darla gwapa', 'hehehehehe', '2024-04-02 20:08:46'),
(9, 32, 'ssss', 'ssss', '2024-04-02 20:21:22'),
(10, 32, 'etil', 'etil', '2024-04-02 20:24:23'),
(11, 32, 'indang', 'villafuerte', '2024-04-02 20:26:55'),
(12, 32, 'aaaa', 'aaaa', '2024-04-02 20:30:02'),
(13, 32, 'aaaa', 'aaaaa', '2024-04-02 20:31:17'),
(14, 32, 'aicee', 'ssss', '2024-04-02 20:31:29'),
(15, 32, 'sss', 'sssssss', '2024-04-02 20:33:47'),
(16, 32, 'etilllllllllllllllllllllllllll', 'camellllllllllllooooooooooooo', '2024-04-02 20:34:05'),
(17, 32, 'SSS', 'SSSS', '2024-04-02 20:46:41'),
(18, 32, 'BBBBBBBBB', 'SS', '2024-04-02 20:49:47'),
(19, 32, 'dd', 'ddd', '2024-04-02 21:03:57'),
(20, 32, 'ssss', 'ssss', '2024-04-02 21:10:23'),
(21, 32, 'ssssssssssssssssssssssss', 'ssssssssssssssssssssssssssss', '2024-04-02 21:14:18'),
(22, 32, 'bati', '', '2024-04-02 21:15:49'),
(23, 32, 'ddddddddddddddddd', '', '2024-04-02 21:16:17'),
(24, 32, 'AAAAAAAAAAAAAAAAAAA', '', '2024-04-02 21:17:32'),
(25, 32, 'DDDDDDDDD', 'DDDDDDDDDDDDDDDDD', '2024-04-02 21:42:59'),
(26, 32, 'SSS', 'SSSS', '2024-04-02 22:22:02'),
(27, 32, 'AAAA', 'AAAAA', '2024-04-02 22:26:43'),
(28, 32, 'etil', '', '2024-04-02 22:38:21'),
(29, 32, 'ssss', 'sss', '2024-04-02 22:57:12'),
(30, 26, 'buang', 'ko', '2024-04-02 23:08:34'),
(31, 26, 'MANGUNDANG', 'ARAT NA', '2024-04-02 23:09:50'),
(32, 34, 'BANGTAN SONYEONDAN', '7', '2024-04-10 18:49:43'),
(33, 34, 'KIM TAEHYUNG', 'TURONG LABLAB', '2024-04-10 18:50:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive2_tbl`
--
ALTER TABLE `archive2_tbl`
  ADD PRIMARY KEY (`archive_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `note_id` (`note_id`);

--
-- Indexes for table `archive_tbl`
--
ALTER TABLE `archive_tbl`
  ADD PRIMARY KEY (`archive_id`);

--
-- Indexes for table `logintbl`
--
ALTER TABLE `logintbl`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `notes_tbl`
--
ALTER TABLE `notes_tbl`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archive2_tbl`
--
ALTER TABLE `archive2_tbl`
  MODIFY `archive_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `archive_tbl`
--
ALTER TABLE `archive_tbl`
  MODIFY `archive_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logintbl`
--
ALTER TABLE `logintbl`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `notes_tbl`
--
ALTER TABLE `notes_tbl`
  MODIFY `note_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `archive2_tbl`
--
ALTER TABLE `archive2_tbl`
  ADD CONSTRAINT `archive2_tbl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `logintbl` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `archive2_tbl_ibfk_2` FOREIGN KEY (`note_id`) REFERENCES `notes_tbl` (`note_id`) ON DELETE CASCADE;

--
-- Constraints for table `notes_tbl`
--
ALTER TABLE `notes_tbl`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `logintbl` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
