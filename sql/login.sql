-- SQLBook: Code
CREATE TABLE login (
    userID VARCHAR(10) PRIMARY KEY, -- Unique user ID (e.g., JS001, EX001, AD001)
    username VARCHAR(50) UNIQUE NOT NULL, -- Unique username for login
    password VARCHAR(255) NOT NULL, -- Hashed password for secure authentication
    email VARCHAR(100) UNIQUE NOT NULL, -- User's email address
    role ENUM('jobSeeker', 'employer', 'admin') NOT NULL, -- Role of the user
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Account creation timestamp
);

INSERT INTO login (userID, username, password, email, role)
VALUES ('JS001', 'qiqi03', 'chuaqi@123', 'chloee031023@gmail.com', 'jobSeeker');

-- Insert an employer into login
INSERT INTO login (userID, username, password, email, role)
VALUES ('EP001', 'eileen', 'eileen@123', 'eileen@gmail.com', 'employer');

-- Insert an admin into login
INSERT INTO login (userID, username, password, email, role)
VALUES ('AD001', 'kaixuan', 'kaixuan@123', 'kaixuan@gmail.com', 'admin');
