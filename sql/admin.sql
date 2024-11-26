CREATE TABLE admin (
    userID VARCHAR(10) PRIMARY KEY, -- Matches userID in login table
    contactNo VARCHAR(15), -- Contact number
    fullName VARCHAR(100) NOT NULL, -- Admin's full name
    profilePic TEXT, -- Profile picture (optional)
    FOREIGN KEY (userID) REFERENCES login(userID) ON DELETE CASCADE -- Links to login table
);


INSERT INTO admin (userID, contactNo, fullName, profilePic) 
VALUES ('AD001', '0987654321', 'Oh Kai Xuan', NULL);