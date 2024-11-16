CREATE TABLE admin (
    userID VARCHAR(10) PRIMARY KEY, -- Matches userID in login table
    contactNo VARCHAR(15), -- Contact number
    fullName VARCHAR(100) NOT NULL, -- Admin's full name
    profilePic TEXT, -- Profile picture (optional)
    FOREIGN KEY (userID) REFERENCES login(userID) ON DELETE CASCADE -- Links to login table
);


