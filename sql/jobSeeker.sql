CREATE TABLE jobseeker (
  userID varchar(10) PRIMARY KEY, -- Matches userID in login table
  fullName VARCHAR(100) NOT NULL, -- Job seeker's full name
  email varchar(100) NOT NULL, -- Email
  contactNo varchar(15) NOT NULL, -- Contact number
  age varchar(3), -- Age
  gender varchar(10), -- Gender
  race varchar(50), -- Race
  location varchar(100), -- Location
  state varchar(50), -- State
  position varchar(100), -- Position
  company varchar(255), -- Company
  workExperience varchar(50), -- Work Experience
  language varchar(255), -- Language
  hardSkill text, -- Hardskill
  softSkill text, -- Softskill
  profilePic varchar(100) DEFAULT NULL, -- ProfilePic
  accountStatus ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active', -- Account status
  warningHistory INT DEFAULT 0, -- Count of warnings received
  FOREIGN KEY (userID) REFERENCES login(userID) ON DELETE CASCADE -- Links to login table
);

INSERT INTO jobSeeker (userID, fullName, email, contactNo, age, gender, race, location, state, 
position, company, workExperience, language, hardSkill, softSkill, profilePic, accountStatus, warningHistory) 
VALUES 
    ('JS001', 'Chua Ern Qi', 'ernqi@graduate.utm.my', '0123456789', '21', 'Female', 'Chinese', 'Kuala Lumpur', 'Selangor', 
    'Worker', 'Company ABC', '2 years experience in IT', 'English, Malay, Mandarin', 'Java, C++, Python', 'Leadership Skill', NULL, 'Active', 0),
    ('JS002', 'Jessie Chang', 'jessie@gmail.com', '012345678901', '21', 'Female', 'Chinese', 'Johor Bahru', 'Johor',
     'Worker', 'Company ABC', '2 years in IT', 'English, Malay, Chinese', 'Javascript, PHP, Microsoft', 'Adaptability, Creativity', NULL, 'Active', 0);
