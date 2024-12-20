CREATE TABLE jobRating (
    ratingID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each rating
    historyID INT, -- References the jobHistory table
    rating INT CHECK (rating BETWEEN 1 AND 5), -- Numeric rating (1–5 stars)
    feedback TEXT, -- Optional feedback
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Rating timestamp
    FOREIGN KEY (historyID) REFERENCES jobHistory(historyID) ON DELETE CASCADE -- Link to jobHistory
);
