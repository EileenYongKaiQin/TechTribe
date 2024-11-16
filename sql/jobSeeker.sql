CREATE TABLE jobSeeker (
    userID VARCHAR(10) PRIMARY KEY, -- Matches userID in login table
    contactNo VARCHAR(15), -- Contact number
    fullName VARCHAR(100) NOT NULL, -- Job seeker's full name
    profilePic TEXT, -- Profile picture (optional)
    gender ENUM('Male', 'Female'), -- Gender
    race VARCHAR(50), -- Race
    accountStatus ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active', -- Account status
    location VARCHAR(255), -- Preferred location
    state VARCHAR(50), -- State
    district VARCHAR(50), -- District
    skill TEXT, -- Skills
    workExperience TEXT, -- Work experience
    warningHistory INT DEFAULT 0, -- Count of warnings received
    language VARCHAR(255), -- Languages known
    FOREIGN KEY (userID) REFERENCES login(userID) ON DELETE CASCADE -- Links to login table
);

INSERT INTO jobSeeker (userID, contactNo, fullName, profilePic, gender, race, accountStatus, location, state, district, skill, workExperience, warningHistory, language) 
VALUES (
    'JS001', '0123456789', 'Oh Kai Xuan', NULL, 'Male', 'Cina', 'Active', 
    'Kuala Lumpur', 'Selangor', 'District 1', 'Java, C++, Python', 
    '2 years experience in IT', 0, 'English, Malay, Mandarin'
);