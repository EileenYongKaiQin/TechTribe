CREATE TABLE accountIssue (
    issueID VARCHAR(20) PRIMARY KEY,
    issueDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    suspendReason ENUM(
        'Fraud or Scam', 
        'Share False Information', 
        'Spam', 
        'Employer Misconduct', 
        'Job Seeker Misconduct', 
        'Inappropriate Behavior', 
        'Others'
    ) NOT NULL,
    suspendDuration ENUM('Temporary', 'Permanent') NOT NULL,
    violation INT DEFAULT 0,
    accountIssueID VARCHAR(20) NOT NULL,
    expirationDate DATETIME,
    FOREIGN KEY (accountIssueID) REFERENCES login(userID) ON DELETE CASCADE
);