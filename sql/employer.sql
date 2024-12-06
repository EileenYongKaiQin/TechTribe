CREATE TABLE employer (
    userID VARCHAR(10) PRIMARY KEY, -- Matches userID in login table
    fullName VARCHAR(100) NOT NULL, -- Employer's full name
    email varchar(100) NOT NULL, -- Email
    contactNo VARCHAR(15), -- Contact number
    companyName VARCHAR(255) NOT NULL, -- Employer's company name
    companyAddress TEXT NOT NULL, -- Address of the company
    profilePic varchar(100) DEFAULT NULL, -- ProfilePic
    accountStatus ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active', -- Account status
    warningHistory INT DEFAULT 0, -- Count of warnings received
    FOREIGN KEY (userID) REFERENCES login(userID) ON DELETE CASCADE -- Links to login table
);

INSERT INTO employer (userID, fullName, email, contactNo, companyName, companyAddress, profilePic, accountStatus, warningHistory)
VALUES ('EP001', 'Eileen Yong Kai Qin', 'eileen@gmail.com', '0123456789', 'FlexMatch', '123 Jalan Bukit, KL', NULL,'Active', 0),
('EP002', 'Jessie', 'jc@gmail.com', '012345678901', 'Company ABC', 'No 1, Jalan Skudai', NULL,'Active', 0);
