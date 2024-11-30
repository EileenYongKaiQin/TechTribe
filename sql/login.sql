-- SQLBook: Code
CREATE TABLE login (
    userID VARCHAR(10) PRIMARY KEY, -- Unique user ID (e.g., JS001, EX001, AD001)
    username VARCHAR(50) UNIQUE NOT NULL, -- Unique username for login
    password VARCHAR(255) NOT NULL, -- Hashed password for secure authentication
    email VARCHAR(100), -- User's email address
    role ENUM('jobSeeker', 'employer', 'admin') NOT NULL, -- Role of the user
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Account creation timestamp
    lastLogin TIMESTAMP NULL DEFAULT NULL
);

INSERT INTO login (userID, username, password, email, role)
VALUES ('JS001', 'qiqi03', MD5('chuaqi@123'), 'chloee031023@gmail.com', 'jobSeeker');

-- Insert an employer into login
INSERT INTO login (userID, username, password, email, role)
VALUES ('EP001', 'eileen', MD5('eileen@123'), 'eileen@gmail.com', 'employer');

-- Insert an admin into login
INSERT INTO login (userID, username, password, email, role)
VALUES ('AD001', 'kaixuan', MD5('kaixuan@123'), 'kaixuan@gmail.com', 'admin');

-- For delete profile use purpose login
INSERT INTO login (userID, username, password, email, role)
VALUES ('JS002', 'jsjessie', MD5('jc123'), 'NULL', 'jobSeeker');
INSERT INTO login (userID, username, password, email, role)
VALUES ('EP002', 'emjessie', MD5('jc1234'), 'NULL', 'jobSeeker');