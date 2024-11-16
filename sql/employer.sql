CREATE TABLE employer (
    userID VARCHAR(10) PRIMARY KEY, -- Matches userID in login table
    contactNo VARCHAR(15), -- Contact number
    fullName VARCHAR(100) NOT NULL, -- Employer's full name
    profilePic TEXT, -- Profile picture (optional)
    companyName VARCHAR(255) NOT NULL, -- Employer's company name
    companyAddress TEXT NOT NULL, -- Address of the company
    accountStatus ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active', -- Account status
    warningHistory INT DEFAULT 0, -- Count of warnings received
    FOREIGN KEY (userID) REFERENCES login(userID) ON DELETE CASCADE -- Links to login table
);

INSERT INTO employer (userID, contactNo, fullName, profilePic, companyName, companyAddress, accountStatus, warningHistory)
VALUES ('EP001', '0123456789', 'Eileen Yong Kai Qin', NULL, 'FlexMatch', '123 Jalan Bukit, KL', 'Active', 0);