-- SQLBook: Code
CREATE TABLE jobSeeker (
    userID VARCHAR(10) PRIMARY KEY, -- Matches userID in login table
    fullName VARCHAR(100) NOT NULL, -- Job seeker’s full name
    contactNo VARCHAR(15), -- Contact number
    gender ENUM('Male', 'Female'), -- Gender
    location VARCHAR(255), -- Preferred location
    skills TEXT, -- List of skills
    experience TEXT, -- Work experience details
    languageProficiency VARCHAR(255), -- Languages the job seeker is proficient in
    FOREIGN KEY (userID) REFERENCES login(userID) ON DELETE CASCADE -- Links to login table
);