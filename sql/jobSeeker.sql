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
  language text, -- Language with proficiency (stores as "Language|Proficiency")
  hardSkill text, -- Hardskill
  softSkill text, -- Softskill
  profilePic varchar(100) DEFAULT NULL, -- ProfilePic
  accountStatus ENUM(
      'Active', 
      'Inactive', 
      'Suspended-Temporary-6M', 
      'Suspended-Temporary-2Y', 
      'Suspended-Temporary-5Y', 
      'Suspended-Permanently'
  ) DEFAULT 'Active', -- Account status
  warningHistory INT DEFAULT 0, -- Count of warnings received
  suspensionEndDate DATETIME NULL, -- Suspension Date (End)
  FOREIGN KEY (userID) REFERENCES login(userID) ON DELETE CASCADE -- Links to login table
);

INSERT INTO jobSeeker (userID, fullName, email, contactNo, age, gender, race, location, state, 
position, company, workExperience, language, hardSkill, softSkill, profilePic, accountStatus, warningHistory, suspensionEndDate) 
VALUES 
    ('JS001', 'Chua Ern Qi', 'ernqi@graduate.utm.my', '+601234567778', '21', 'Female', 'Chinese', 'Kuala Lumpur', 'Selangor', 
    'Worker', 'Company ABC', '2 years experience in IT', 'English|8, Malay|8, Mandarin|9', 'Java, C++, Python', 'Leadership Skill', NULL, 'Active', 0, NULL),
    
    ('JS002', 'Jessie Chang', 'jessie@gmail.com', '+601234556666', '21', 'Female', 'Chinese', 'Johor Bahru', 'Johor',
     'Worker', 'Company ABC', '2 years in IT', 'English|6, Malay|7, Chinese|8', 'Javascript, PHP, Microsoft', 'Adaptability, Creativity', NULL, 'Active', 0, NULL),
     
    ('JS003', 'Bernice Lim', 'bernice@gmail.com', '+601234556996', '21', 'Female', 'Chinese', 'Johor Bahru', 'Johor', 
    'Worker', 'Company ABCD ', '2 years in Data Engineering', 'English|9, Malay|9, Chinese|10', 'C++, PHP, Java', 'Communication, Leadership', NULL, 'Active', 1, NULL),
    
    ('JS004', 'Kek Jesslyn', 'kek@gmail.com', '+601234557766', '21', 'Female', 'Chinese', 'Kota Syahbandar', 'Malacca', 
    'Worker', 'Company XYZ', '2 years in Data Engineering', 'English|7, Malay|8, Chinese|9', 'C++, Java, PHP', 'Communication, Leadership', NULL, 'Active', 2, NULL);