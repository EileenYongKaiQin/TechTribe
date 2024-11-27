
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `wallpost` (
  `postID` varchar(10) NOT NULL,
  `skillCategory` varchar(255) NOT NULL,
  `skillDetails` text NOT NULL,
  `availableTime` text NOT NULL,
  `state` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `jobPreferences` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `userID` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wallpost` (`postID`, `skillCategory`, `skillDetails`, `availableTime`, `state`, `district`, `jobPreferences`, `createdAt`, `userID`) VALUES
('WP0005', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 10:00:09', 'JS001'),
('WP0006', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 10:00:09', 'JS001'),
('WP0007', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 10:00:09', 'JS001'),
('WP0008', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 10:00:09', 'JS001'),
('WP0009', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 10:00:09', 'JS001'),
('WP001', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 01:00:00', 'JS001'),
('WP0010', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 10:00:09', 'JS001'),
('WP0011', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 10:00:09', 'JS001'),
('WP0012', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 10:00:09', 'JS001'),
('WP002', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 09:57:08', 'JS001');

ALTER TABLE `wallpost`
  ADD PRIMARY KEY (`postID`),
  ADD KEY `fk_userID` (`userID`);


ALTER TABLE `wallpost`
  ADD CONSTRAINT `fk_userID` FOREIGN KEY (`userID`) REFERENCES `jobseeker` (`userID`);
COMMIT;

