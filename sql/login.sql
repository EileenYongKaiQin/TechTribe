-- SQLBook: Code
CREATE TABLE login (
    userID VARCHAR(10) PRIMARY KEY, -- Unique user ID (e.g., JS001, EX001, AD001)
    username VARCHAR(50) UNIQUE NOT NULL, -- Unique username for login
    password VARCHAR(255) NOT NULL, -- Hashed password for secure authentication
    role ENUM('jobSeeker', 'employer', 'admin') NOT NULL, -- Role of the user
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Account creation timestamp
    lastLogin TIMESTAMP NULL DEFAULT NULL
);

-- Insert an admin into login
INSERT INTO login (userID, username, password, role)
VALUES ('AD001', 'kaixuan', MD5('Kaixuan@123'), 'admin');

-- Insert an employer into login
INSERT INTO login (userID, username, password, role)
VALUES ('EP001', 'eileen', MD5('Eileen@123'), 'employer');
INSERT INTO login (userID, username, password, role)
VALUES ('EP002', 'emjessie', MD5('Jessie@123'), 'employer');
INSERT INTO login (userID, username, password, role)
VALUES ('EP003', 'alicee', MD5('Alice@123'), 'employer');
INSERT INTO login (userID, username, password, role)
VALUES ('EP004', 'nabilah', MD5('Nabilah@123'), 'employer');

-- Insert a jobseeker into login
INSERT INTO login (userID, username, password, role)
VALUES ('JS001', 'qiqi03', MD5('Chuaqi@123'), 'jobSeeker');
INSERT INTO login (userID, username, password, role)
VALUES ('JS002', 'jsjessie', MD5('Jessie@123'), 'jobSeeker');
INSERT INTO login (userID, username, password, role)
VALUES ('JS003', 'bernice', MD5('Bernice@123'), 'jobSeeker');
INSERT INTO login (userID, username, password, role)
VALUES ('JS004', 'jesslyn', MD5('Jesslyn@123'), 'jobSeeker');