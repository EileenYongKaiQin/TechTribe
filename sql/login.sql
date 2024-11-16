-- SQLBook: Code
CREATE TABLE login (
    userID VARCHAR(10) PRIMARY KEY, -- Unique user ID (e.g., JS001, EX001, AD001)
    username VARCHAR(50) UNIQUE NOT NULL, -- Unique username for login
    password VARCHAR(255) NOT NULL, -- Hashed password for secure authentication
    email VARCHAR(100) UNIQUE NOT NULL, -- User's email address
    role ENUM('jobSeeker', 'employer', 'admin') NOT NULL, -- Role of the user
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Account creation timestamp
);
