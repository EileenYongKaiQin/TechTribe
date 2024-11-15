CREATE TABLE `jobSeeker` (
  `JobSeekerID` varchar(10) NOT NULL PRIMARY KEY,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `ContactNo` varchar(255) NOT NULL,
  `City` varchar(255) NOT NULL,
  `State` varchar(255) NOT NULL,
  `Hobby` varchar(255) DEFAULT NULL,
  `StudyField` varchar(255) DEFAULT NULL,
  `YearGraduate` year(4) DEFAULT NULL,
  `Institution` varchar(255) DEFAULT NULL,
  `Position` varchar(255) DEFAULT NULL,
  `YearExperience` int(3) DEFAULT NULL,
  `Company` varchar(255) DEFAULT NULL,
  `HardSkill` varchar(255) DEFAULT NULL,
  `SoftSkill` varchar(255) DEFAULT NULL,
  `ProfilePic` varchar(255) DEFAULT NULL,
  `Resume` varchar(255) DEFAULT NULL
)