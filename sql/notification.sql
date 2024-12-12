CREATE TABLE notification (
    notiID INT AUTO_INCREMENT PRIMARY KEY,
    userID VARCHAR(10),  -- Admin userID
    notificationText TEXT,
    notificationType ENUM('status-change', 'warning') DEFAULT 'status-change',
    isRead BOOLEAN DEFAULT FALSE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES login(userID)
);
