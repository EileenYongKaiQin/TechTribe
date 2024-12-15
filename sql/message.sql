-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2024 at 04:50 PM
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
-- Database: `flexmatch_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `userID` varchar(10) NOT NULL,
  `messageContents` text NOT NULL,
  `senderRole` enum('employer','job_seeker') NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `jobSeekerID` varchar(10) DEFAULT NULL,
  `employerID` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `userID`, `messageContents`, `senderRole`, `timestamp`, `jobSeekerID`, `employerID`) VALUES
(54, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 19:27:59', 'JS002', NULL),
(56, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 19:29:36', 'JS001', NULL),
(57, 'EP001', 'asaas', 'employer', '2024-12-13 19:29:42', 'JS001', NULL),
(58, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 19:29:43', 'JS001', NULL),
(59, 'EP001', 'joahao', 'employer', '2024-12-13 19:51:59', 'JS001', NULL),
(60, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 19:52:00', 'JS001', NULL),
(61, 'EP001', 'jisasa', 'employer', '2024-12-13 19:52:09', 'JS001', NULL),
(62, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 19:52:10', 'JS001', NULL),
(63, 'EP001', 'jiahao', 'employer', '2024-12-13 19:52:12', 'JS001', NULL),
(64, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 19:52:13', 'JS001', NULL),
(65, 'EP001', 'jiahao', 'employer', '2024-12-13 19:52:41', 'JS001', NULL),
(66, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 19:52:42', 'JS001', NULL),
(68, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 19:52:53', 'JS001', NULL),
(70, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 19:56:35', 'JS001', NULL),
(71, 'EP001', 'ssssss', 'employer', '2024-12-13 19:59:24', 'JS001', NULL),
(72, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 19:59:25', 'JS001', NULL),
(73, 'EP001', 'ji', 'employer', '2024-12-13 20:15:44', 'JS001', NULL),
(74, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:15:45', 'JS001', NULL),
(75, 'EP001', 'jibai', 'employer', '2024-12-13 20:15:51', 'JS001', NULL),
(76, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:15:52', 'JS001', NULL),
(78, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:20:01', 'JS001', NULL),
(79, 'EP001', 'asa', 'employer', '2024-12-13 20:41:15', 'JS001', NULL),
(80, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:41:16', 'JS001', NULL),
(81, 'EP001', 'sa', 'employer', '2024-12-13 20:42:00', 'JS001', NULL),
(82, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:42:01', 'JS001', NULL),
(83, 'EP001', 'sasa', 'employer', '2024-12-13 20:42:44', 'JS001', NULL),
(84, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:42:45', 'JS001', NULL),
(85, 'EP001', 'sa', 'employer', '2024-12-13 20:42:49', 'JS001', NULL),
(86, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:42:50', 'JS001', NULL),
(87, 'EP001', 'sa', 'employer', '2024-12-13 20:43:19', 'JS001', NULL),
(88, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:43:20', 'JS001', NULL),
(89, 'EP001', 'sa', 'employer', '2024-12-13 20:43:22', 'JS001', NULL),
(90, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:43:23', 'JS001', NULL),
(91, 'EP001', 'saaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'employer', '2024-12-13 20:44:47', 'JS001', NULL),
(92, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:44:48', 'JS001', NULL),
(93, 'EP001', 'sa', 'employer', '2024-12-13 20:52:07', 'JS001', NULL),
(94, 'EP001', 'sa', 'employer', '2024-12-13 20:52:14', 'JS001', NULL),
(95, 'EP001', 'sa', 'employer', '2024-12-13 20:52:46', 'JS001', NULL),
(96, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:52:47', 'JS001', NULL),
(97, 'EP001', 'sa', 'employer', '2024-12-13 20:52:54', 'JS001', NULL),
(98, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:52:55', 'JS001', NULL),
(99, 'EP001', 'sss', 'employer', '2024-12-13 20:56:29', 'JS001', NULL),
(100, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:56:30', 'JS001', NULL),
(102, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:56:33', 'JS001', NULL),
(104, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:56:36', 'JS001', NULL),
(106, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:56:40', 'JS001', NULL),
(108, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:57:22', 'JS001', NULL),
(110, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:58:29', 'JS001', NULL),
(112, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:59:55', 'JS001', NULL),
(114, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 20:59:59', 'JS001', NULL),
(116, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:03:07', 'JS001', NULL),
(118, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:03:23', 'JS001', NULL),
(120, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:03:28', 'JS001', NULL),
(122, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:05:16', 'JS001', NULL),
(124, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:15:31', 'JS001', NULL),
(126, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:17:54', 'JS001', NULL),
(128, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:18:08', 'JS001', NULL),
(130, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:18:38', 'JS001', NULL),
(132, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:19:37', 'JS001', NULL),
(133, 'EP001', 'as', 'employer', '2024-12-13 21:21:51', 'JS001', NULL),
(134, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:21:52', 'JS001', NULL),
(135, 'EP001', 'sa', 'employer', '2024-12-13 21:21:54', 'JS001', NULL),
(136, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:21:55', 'JS001', NULL),
(137, 'EP001', 'sa', 'employer', '2024-12-13 21:22:08', 'JS001', NULL),
(138, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:22:09', 'JS001', NULL),
(139, 'EP001', 'sa', 'employer', '2024-12-13 21:22:12', 'JS001', NULL),
(140, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:22:13', 'JS001', NULL),
(141, 'EP001', 'sasa', 'employer', '2024-12-13 21:22:16', 'JS001', NULL),
(142, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:22:17', 'JS001', NULL),
(143, 'EP001', 'sasa', 'employer', '2024-12-13 21:22:51', 'JS001', NULL),
(144, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:22:52', 'JS001', NULL),
(145, 'EP001', 'ji', 'employer', '2024-12-13 21:22:55', 'JS001', NULL),
(146, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:22:56', 'JS001', NULL),
(147, 'EP001', 'jibai', 'employer', '2024-12-13 21:23:00', 'JS001', NULL),
(148, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:23:01', 'JS001', NULL),
(149, 'EP001', 'asa', 'employer', '2024-12-13 21:23:14', 'JS001', NULL),
(150, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:23:15', 'JS001', NULL),
(151, 'EP001', 'sa', 'employer', '2024-12-13 21:23:17', 'JS001', NULL),
(152, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:23:18', 'JS001', NULL),
(153, 'EP001', 'sas', 'employer', '2024-12-13 21:23:20', 'JS001', NULL),
(154, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:23:21', 'JS001', NULL),
(155, 'EP001', 'sa', 'employer', '2024-12-13 21:23:23', 'JS001', NULL),
(156, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:23:24', 'JS001', NULL),
(157, 'EP001', 'ssssss', 'employer', '2024-12-13 21:23:26', 'JS001', NULL),
(158, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:23:28', 'JS001', NULL),
(159, 'EP001', 'ssssssssss;;;;', 'employer', '2024-12-13 21:23:30', 'JS001', NULL),
(160, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:23:31', 'JS001', NULL),
(162, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:23:41', 'JS001', NULL),
(164, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:31:18', 'JS001', NULL),
(166, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:38:19', 'JS001', NULL),
(167, 'EP001', 'asasasa', 'employer', '2024-12-13 21:39:24', 'JS001', NULL),
(168, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:39:25', 'JS001', NULL),
(169, 'EP001', 'sa', 'employer', '2024-12-13 21:39:52', 'JS001', NULL),
(170, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:39:53', 'JS001', NULL),
(171, 'EP001', 'jisa', 'employer', '2024-12-13 21:39:55', 'JS001', NULL),
(172, 'EP001', 'nsassssssssssssssssss', 'employer', '2024-12-13 21:39:56', 'JS001', NULL),
(173, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:39:56', 'JS001', NULL),
(175, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:40:00', 'JS001', NULL),
(177, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:45:10', 'JS001', NULL),
(178, 'EP001', 'asassssasaaa', 'employer', '2024-12-13 21:45:51', 'JS001', NULL),
(179, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:45:52', 'JS001', NULL),
(180, 'EP001', 's (edited)', 'employer', '2024-12-13 21:51:23', 'JS001', NULL),
(181, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:51:24', 'JS001', NULL),
(183, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:51:42', 'JS001', NULL),
(184, 'EP001', '<i>${editMessageInput.value.trim()} (edited)</i>', 'employer', '2024-12-13 21:54:44', 'JS001', NULL),
(185, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 21:54:45', 'JS001', NULL),
(186, 'EP001', '<i>ssssssssssssssssssss (edited)</i>', 'employer', '2024-12-13 22:02:25', 'JS001', NULL),
(187, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 22:02:26', 'JS001', NULL),
(189, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:30:14', 'JS002', NULL),
(190, 'EP001', 'sasssssss (edited)', 'employer', '2024-12-13 23:30:16', 'JS002', NULL),
(191, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:30:17', 'JS002', NULL),
(192, 'EP001', 's', 'employer', '2024-12-13 23:40:12', 'JS001', NULL),
(193, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:40:13', 'JS001', NULL),
(194, 'EP001', 'sss [ed]  [Edited]', 'employer', '2024-12-13 23:40:30', 'JS001', NULL),
(195, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:40:31', 'JS001', NULL),
(196, 'EP001', 's <i>(edited)</i>', 'employer', '2024-12-13 23:41:11', 'JS001', NULL),
(197, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:41:12', 'JS001', NULL),
(198, 'EP001', 's [ed] [Edited]', 'employer', '2024-12-13 23:48:48', 'JS001', NULL),
(199, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:48:49', 'JS001', NULL),
(200, 'EP001', 'sssss  [Edited]', 'employer', '2024-12-13 23:50:54', 'JS001', NULL),
(201, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:50:55', 'JS001', NULL),
(202, 'EP001', 'sssss (Edited)', 'employer', '2024-12-13 23:51:32', 'JS001', NULL),
(203, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:51:33', 'JS001', NULL),
(204, 'EP001', 'ss (Edited)', 'employer', '2024-12-13 23:52:05', 'JS001', NULL),
(205, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:52:06', 'JS001', NULL),
(206, 'EP001', 's [ed]  [Edited]', 'employer', '2024-12-13 23:52:51', 'JS001', NULL),
(207, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:52:52', 'JS001', NULL),
(208, 'EP001', 'sssssssss (edited)', 'employer', '2024-12-13 23:54:29', 'JS001', NULL),
(209, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:54:30', 'JS001', NULL),
(210, 'EP001', 'ssss (edited)', 'employer', '2024-12-13 23:56:00', 'JS001', NULL),
(211, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-13 23:56:01', 'JS001', NULL),
(212, 'EP001', 's', 'employer', '2024-12-14 21:28:20', 'JS002', NULL),
(213, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-14 21:28:21', 'JS002', NULL),
(214, 'EP001', 's', 'employer', '2024-12-14 21:28:24', 'JS001', NULL),
(215, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-14 21:28:25', 'JS001', NULL),
(216, 'EP001', 'ssa', 'employer', '2024-12-14 21:47:13', NULL, NULL),
(217, 'EP001', 's', 'employer', '2024-12-14 21:48:13', NULL, NULL),
(218, 'EP001', 'CREATE TABLE `message` (   `id` int(11) NOT NULL,   `userID` varchar(10) NOT NULL,   `messageContents` text NOT NULL,   `senderRole` enum(\'employer\',\'job_seeker\') NOT NULL,   `timestamp` datetime DEFAULT current_timestamp(),   `userID2` varchar(10) DEFAULT NULL )', 'employer', '2024-12-14 21:59:32', NULL, NULL),
(219, 'EP001', 'sa', 'employer', '2024-12-14 22:09:11', NULL, NULL),
(220, 'EP001', 'sa', 'employer', '2024-12-14 22:13:51', NULL, NULL),
(221, 'EP001', 's', 'employer', '2024-12-14 22:14:33', NULL, NULL),
(222, 'EP001', 'kk', 'employer', '2024-12-14 22:22:37', NULL, NULL),
(223, 'EP001', 'sa', 'employer', '2024-12-14 22:35:45', NULL, NULL),
(224, 'EP001', 'sasa', 'employer', '2024-12-14 22:36:53', NULL, NULL),
(225, 'JS001', 'jujsashaisasa', 'employer', '2024-12-14 23:24:05', 'JS001', NULL),
(226, 'JS001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-14 23:24:06', 'JS001', NULL),
(227, 'JS001', 'saaaa', 'employer', '2024-12-14 23:25:38', 'JS002', NULL),
(228, 'JS001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-14 23:25:39', 'JS002', NULL),
(229, 'EP001', 'jessia', 'employer', '2024-12-14 23:32:19', 'JS002', NULL),
(230, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-14 23:32:21', 'JS002', NULL),
(231, 'EP001', 'enwi', 'employer', '2024-12-14 23:32:25', 'JS001', NULL),
(232, 'EP001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-14 23:32:26', 'JS001', NULL),
(233, 'JS001', 'sasas', 'employer', '2024-12-14 23:42:10', 'null', NULL),
(234, 'JS001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-14 23:42:11', 'null', NULL),
(235, 'JS001', 's', 'employer', '2024-12-14 23:42:22', 'JS001', NULL),
(236, 'JS001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-14 23:42:23', 'JS001', NULL),
(237, 'JS001', 'j', 'employer', '2024-12-14 23:43:00', 'JS001', NULL),
(238, 'JS001', 'Thank you for your message. I will get back to you soon!', 'job_seeker', '2024-12-14 23:43:01', 'JS001', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
