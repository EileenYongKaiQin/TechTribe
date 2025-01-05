CREATE TABLE employer (
    userID VARCHAR(10) PRIMARY KEY, -- Matches userID in login table
    fullName VARCHAR(100) NOT NULL, -- Employer's full name
    email varchar(100) NOT NULL, -- Email
    contactNo VARCHAR(15), -- Contact number
    companyName VARCHAR(255) NOT NULL, -- Employer's company name
    jobRole varchar(100), -- Position
    companyAddress TEXT NOT NULL, -- Address of the company
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
    suspensionEndDate  DATETIME NULL, -- Suspension Date (End)
    FOREIGN KEY (userID) REFERENCES login(userID) ON DELETE CASCADE -- Links to login table
);

INSERT INTO employer (userID, fullName, email, contactNo, companyName, jobRole, companyAddress, profilePic, accountStatus, warningHistory, suspensionEndDate)
VALUES ('EP001', 'Eileen Yong Kai Qin', 'eileen@gmail.com', '0123456789', 'FlexMatch', 'Developer', '123 Jalan Bukit, KL', NULL,'Active', 0, NULL),
('EP002', 'Jessie', 'jc@gmail.com', '+601234567890', 'Company ABC', 'Developer', 'No 1, Jalan Skudai', NULL,'Active', 0, NULL),
('EP003', 'Alice', 'alice@gmail.com', '+601987654321', 'Company ASD', 'Technician', 'No.1, Taman Universiti, Skudai 81300, Johor', NULL, 'Active', 3, NULL),
('EP004', 'Nabilah', 'nabilah@gmail.com', '+60177000023', 'Company ASD', 'Manager', 'Jalan 1, Taman 1, Johor Bahru', NULL, 'Active', 1, NULL);