-- SQLBook: Code
-- Create reportPost table --
CREATE TABLE reportPost (
    reportID VARCHAR(10) PRIMARY KEY,
    description TEXT NOT NULL,
    reason VARCHAR(255) NOT NULL,
    evidence LONGBLOB,
    createTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    reportStatus ENUM('Pending', 'Under Review', 'Resolved') DEFAULT 'Pending',
    jobPostID VARCHAR(10) NULL,
    reporterID VARCHAR(10) NOT NULL,
    reportedUserID VARCHAR(10) NOT NULL,
    comment TEXT,
    FOREIGN KEY (jobPostID) REFERENCES jobPost(jobPostID) ON DELETE CASCADE,
    FOREIGN KEY (reporterID) REFERENCES login(userID) ON DELETE CASCADE,
    FOREIGN KEY (reportedUserID) REFERENCES login(userID) ON DELETE CASCADE
);

