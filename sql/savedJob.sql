CREATE TABLE savedJob (
    savedJobID VARCHAR(10) PRIMARY KEY,
    jobPostID VARCHAR(10) NOT NULL, 
    userID VARCHAR(10) NOT NULL,   
    savedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jobPostID) REFERENCES jobPost(jobPostID),
    FOREIGN KEY (userID) REFERENCES login(userID)
);
