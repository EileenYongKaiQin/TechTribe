CREATE TABLE jobRating (
    ratingID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each rating
    historyID INT, -- References the jobHistory table
    userID VARCHAR(10),
    rating INT CHECK (rating BETWEEN 1 AND 5), -- Numeric rating (1–5 stars)
    feedback TEXT, -- Optional feedback
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Rating timestamp
    FOREIGN KEY (historyID) REFERENCES jobHistory(historyID) ON DELETE CASCADE, -- Link to jobHistory
    FOREIGN KEY (userID) REFERENCES jobSeeker(userID) ON DELETE CASCADE, -- Link to jobSeeker
    UNIQUE KEY unique_rating (historyID, userID) -- Ensure each user can only rate each job once
);
