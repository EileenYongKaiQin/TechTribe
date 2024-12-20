CREATE TABLE jobApplication (
    applicationID VARCHAR(10) PRIMARY KEY,  -- Unique ID for the application
    jobPostID VARCHAR(10),                  -- The job post this application is linked to
    applicantID VARCHAR(10),                -- The applicant's user ID 
    applyDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Time when the application was submitted
    applyStatus ENUM('Pending', 'Accepted', 'Rejected', 'Under Review') DEFAULT 'Pending', -- Status of the application
    additionalDetails TEXT,                 -- Stores additional details requested from the employer
    applicantResponse TEXT,                -- Stores responses from job seeker
    FOREIGN KEY (jobPostID) REFERENCES jobPost(jobPostID) ON DELETE CASCADE,  -- Link to the job post
    FOREIGN KEY (applicantID) REFERENCES login(userID) ON DELETE CASCADE   -- Link to the applicant 
);
