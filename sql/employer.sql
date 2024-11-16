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

INSERT INTO employer (employer_id, name, email, password, company_name, phone_number)
VALUES 
    ('E001', 'Chua Ern Qi', 'chua@example.com', MD5('chua123'), 'FlexMatch', '0123456789'),
    ('E002', 'Eileen Yong Kai Qin', 'eileen@example.com', MD5('eileen123'), 'FlexMatch', '0123456789'),
    ('E003', 'Jessie Chang', 'jessie@example.com', MD5('jessie123'), 'FlexMatch', '0123456789'),
    ('E004', 'Oh Kai Xuan', 'oh@example.com', MD5('oh123'), 'FlexMatch', '0123456789'),
    ('E005', 'Tam Jia Hao', 'tam@example.com', MD5('tam123'), 'FlexMatch', '0123456789'),
    ('E006', 'Tan You Chun', 'tan@example.com', MD5('tan123'), 'FlexMatch', '0123456789');
