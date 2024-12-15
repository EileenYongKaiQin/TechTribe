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
    expirationDate DATE,
    FOREIGN KEY (accountIssueID) REFERENCES login(userID) ON DELETE CASCADE
);

INSERT INTO `accountissue` (`issueID`, `issueDate`, `suspendReason`, `suspendDuration`, `violation`, `accountIssueID`, `expirationDate`) VALUES
('IS001', '2024-12-15 20:26:06', 'Share False Information', 'Temporary', 1, 'JS003', '2025-06-15');