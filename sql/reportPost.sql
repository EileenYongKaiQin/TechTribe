-- SQLBook: Code
-- Create reportPost table --
CREATE TABLE reportPost (
    reportID VARCHAR(10) PRIMARY KEY,
    description TEXT NOT NULL,
    reason VARCHAR(255) NOT NULL,
    evidence LONGBLOB,
    createTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    reportStatus ENUM('Pending', 'Reviewed', 'Resolved') DEFAULT 'Pending',
    jobPostID VARCHAR(10) NOT NULL,
    userID VARCHAR(10) NOT NULL,
    FOREIGN KEY (jobPostID) REFERENCES jobPost(jobPostID) ON DELETE CASCADE
    
);

