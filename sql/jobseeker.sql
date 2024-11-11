CREATE TABLE `jobseeker` (
  `Name` varchar(255) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `ContactNo` varchar(13) NOT NULL,
  `Location` varchar(100) NOT NULL,
  `StudyField` varchar(255) NOT NULL,
  `GraduateYear` year(4) NOT NULL,
  `Institution` varchar(255) NOT NULL,
  `Position` varchar(255) NOT NULL,
  `Company` varchar(255) NOT NULL,
  `ExperienceYear` int(4) NOT NULL,
  `HardSkill1` varchar(255) DEFAULT NULL,
  `HardSkill2` varchar(255) NOT NULL,
  `SoftSkill1` varchar(255) DEFAULT NULL,
  `SoftSkill2` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `jobseeker` (`Name`, `Email`, `ContactNo`, `Location`, `StudyField`, `GraduateYear`, `Institution`, `Position`, `Company`, `ExperienceYear`, `HardSkill1`, `HardSkill2`, `SoftSkill1`, `SoftSkill2`) VALUES
('Ali', 'ali@graduate.utm.my', '012345678910', 'Skudai,Johor', 'Bachlor of Computer Science ', '2024', 'University of  Teknologi Malaysia', 'Manager', 'Small  Company', '3', 'Using Microsoft tools like Excel and Docs', 'Good in communication skills', 'Language proficiency Skills ', 'Leadership Skills'),
('John', 'john@graduate.utm.my', '01435567892', 'Kulai,Johor', 'Bachelor of Software Engineering', '2024', 'University of Teknologi Malaysia', 'Manager', 'Company YYY', '4', 'Coding Skills', 'Design Skills', 'Leadership Skills', 'Communication Skills'),
('Siti', 'siti@graduate.utm.my', '012345678923', 'Skudai,Johor', 'Bachelor of Graphic Design', '2024', 'University of Teknologi Malaysia', 'Employer', 'Company XYZ', '4', 'Photoshop Skills', 'Adobe skills', 'Language proficiency Skills ', 'Communication Skills');

ALTER TABLE `jobseeker`
  ADD PRIMARY KEY (`Email`);
COMMIT;