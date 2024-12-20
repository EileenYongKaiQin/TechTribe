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
('WP0001', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 01:00:00', 'JS001'),
('WP0002', 'Customer Service', 'Provide customer support through phone and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"09:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"],\"Saturday\":[\"10:00\",\"14:00\"],\"Sunday\":[\"00:00\",\"00:00\"]}', 'Sarawak', 'Kuching', 'Full-time', '2024-11-26 09:57:08', 'JS002'),
('WP0003', 'Graphic Design', 'Create logos, branding, and promotional materials', '{\"Monday\":[\"10:00\",\"16:00\"],\"Wednesday\":[\"10:00\",\"16:00\"],\"Friday\":[\"10:00\",\"16:00\"]}', 'Selangor', 'Petaling Jaya', 'Part-time', '2024-11-26 11:00:00', 'JS001'),
('WP0004', 'Data Entry', 'Input data into systems accurately and efficiently', '{\"Monday\":[\"08:00\",\"12:00\"],\"Tuesday\":[\"08:00\",\"12:00\"],\"Wednesday\":[\"08:00\",\"12:00\"]}', 'Kuala Lumpur', 'Cheras', 'Remote', '2024-11-26 11:15:00', 'JS002'),
('WP0005', 'Writing', 'Write articles, blogs, and marketing copy', '{\"Monday\":[\"09:00\",\"17:00\"],\"Friday\":[\"10:00\",\"14:00\"]}', 'Penang', 'George Town', 'Freelance', '2024-11-26 12:00:00', 'JS001'),
('WP0006', 'Web Development', 'Develop responsive websites using HTML, CSS, and JavaScript', '{\"Monday\":[\"13:00\",\"17:00\"],\"Thursday\":[\"09:00\",\"13:00\"]}', 'Johor', 'Johor Bahru', 'Contract', '2024-11-26 12:30:00', 'JS002'),
('WP0007', 'Tutoring', 'Provide academic tutoring for Mathematics and Science', '{\"Tuesday\":[\"14:00\",\"18:00\"],\"Saturday\":[\"10:00\",\"12:00\"]}', 'Selangor', 'Shah Alam', 'Freelance', '2024-11-26 13:00:00', 'JS001'),
('WP0008', 'Photography', 'Event photography and editing', '{\"Saturday\":[\"08:00\",\"16:00\"],\"Sunday\":[\"09:00\",\"15:00\"]}', 'Sabah', 'Kota Kinabalu', 'Project-based', '2024-11-26 14:00:00', 'JS002'),
('WP0009', 'Customer Service', 'Provide support via chat and email', '{\"Monday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"]}', 'Perak', 'Ipoh', 'Full-time', '2024-11-26 15:00:00', 'JS001'),
('WP0010', 'Accounting', 'Assist with bookkeeping and financial reports', '{\"Tuesday\":[\"10:00\",\"14:00\"],\"Thursday\":[\"10:00\",\"14:00\"]}', 'Kuala Lumpur', 'Bukit Bintang', 'Part-time', '2024-11-26 15:30:00', 'JS002'),
('WP0011', 'Customer Service', 'Handle customer inquiries and complaints', '{\"Monday\":[\"08:00\",\"12:00\"],\"Wednesday\":[\"08:00\",\"12:00\"]}', 'Sarawak', 'Sibu', 'Full-time', '2024-11-26 16:00:00', 'JS001'),
('WP0012', 'Content Writing', 'Develop SEO-optimized articles', '{\"Tuesday\":[\"11:00\",\"15:00\"],\"Friday\":[\"11:00\",\"15:00\"]}', 'Selangor', 'Subang Jaya', 'Freelance', '2024-11-26 16:30:00', 'JS002'),
('WP0013', 'Marketing', 'Social media marketing and content strategy', '{\"Monday\":[\"09:00\",\"13:00\"],\"Thursday\":[\"09:00\",\"13:00\"]}', 'Penang', 'Bayan Lepas', 'Remote', '2024-11-26 17:00:00', 'JS001'),
('WP0014', 'Programming', 'Develop mobile apps using Flutter', '{\"Wednesday\":[\"10:00\",\"16:00\"],\"Friday\":[\"10:00\",\"16:00\"]}', 'Johor', 'Muar', 'Contract', '2024-11-26 17:30:00', 'JS002'),
('WP0015', 'Event Planning', 'Organize and coordinate events', '{\"Saturday\":[\"09:00\",\"17:00\"],\"Sunday\":[\"09:00\",\"17:00\"]}', 'Selangor', 'Kajang', 'Freelance', '2024-11-26 18:00:00', 'JS001'),
('WP0016', 'Translation', 'Translate documents between English and Malay', '{\"Monday\":[\"14:00\",\"18:00\"],\"Thursday\":[\"14:00\",\"18:00\"]}', 'Kedah', 'Alor Setar', 'Project-based', '2024-11-26 18:30:00', 'JS002'),
('WP0017', 'Data Analysis', 'Analyze datasets and create visual reports', '{\"Monday\":[\"09:00\",\"17:00\"],\"Friday\":[\"09:00\",\"17:00\"]}', 'Negeri Sembilan', 'Seremban', 'Part-time', '2024-11-26 19:00:00', 'JS001'),
('WP0018', 'Teaching', 'Conduct English language classes for kids', '{\"Tuesday\":[\"10:00\",\"12:00\"],\"Thursday\":[\"10:00\",\"12:00\"]}', 'Kelantan', 'Kota Bharu', 'Freelance', '2024-11-26 19:30:00', 'JS002'),
('WP0019', 'Graphic Design', 'Design infographics and social media posts', '{\"Wednesday\":[\"10:00\",\"14:00\"],\"Saturday\":[\"10:00\",\"14:00\"]}', 'Malacca', 'Ayer Keroh', 'Project-based', '2024-11-26 20:00:00', 'JS001'),
('WP0020', 'Photography', 'Portrait and product photography', '{\"Friday\":[\"08:00\",\"12:00\"],\"Saturday\":[\"08:00\",\"12:00\"]}', 'Pahang', 'Kuantan', 'Freelance', '2024-11-26 20:30:00', 'JS002'),
('WP0021', 'Customer Service', 'Support via phone and chat Support via phone and chat Support via phone and chat Support via phone ', '{\"Monday\":[\"09:00\",\"17:00\"],\"Tuesday\":[\"09:00\",\"17:00\"],\"Wednesday\":[\"\",\"\"],\"Thursday\":[\"\",\"\"],\"Friday\":[\"\",\"\"],\"Saturday\":[\"\",\"\"],\"Sunday\":[\"\",\"\"]}', 'Sarawak', 'Miri', 'Full-time', '2024-11-26 21:00:00', 'JS001');


ALTER TABLE `wallpost`
  ADD PRIMARY KEY (`postID`),
  ADD KEY `userID` (`userID`);
COMMIT;