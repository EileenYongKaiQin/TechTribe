CREATE TABLE jobHistory (
    historyID INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each history record
    applicationID VARCHAR(10), -- References the application
    jobPostID VARCHAR(10), -- References the job post
    jobSeekerID VARCHAR(10), -- References the job seeker
    acceptedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp when the job was accepted
    FOREIGN KEY (applicationID) REFERENCES jobApplication(applicationID) ON DELETE CASCADE, -- Ensures linkage with applications
    FOREIGN KEY (jobPostID) REFERENCES jobPost(jobPostID) ON DELETE CASCADE, -- Link to the job post
    FOREIGN KEY (jobSeekerID) REFERENCES jobSeeker(userID) ON DELETE CASCADE -- Link to the job seeker
);
