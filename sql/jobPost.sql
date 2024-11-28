-- SQLBook: Code
CREATE TABLE jobPost (
    jobPostID VARCHAR(10) PRIMARY KEY, -- Unique ID for the job post (e.g., JP001)
    jobTitle VARCHAR(255) NOT NULL, -- Title of the job post
    location VARCHAR(255), -- Location of the job
    salary DECIMAL(10, 2), -- Salary for the job
    startDate DATE, -- Start date of the job
    endDate DATE, -- End date of the job
    workingHour VARCHAR(50), -- Working hours for the job
    jobDescription TEXT, -- Description of the job
    jobRequirement TEXT, -- Requirements for the job
    createTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Job post creation timestamp
    updateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Last update timestamp
    userID VARCHAR(10) NOT NULL, -- ID of the employer who posted the job
    FOREIGN KEY (userID) REFERENCES login(userID) ON DELETE CASCADE -- Link to login table
);

INSERT INTO jobPost (jobPostID, jobTitle, location, salary, startDate, endDate, workingHour, jobDescription, jobRequirement, createTime, updateTime, userID)
VALUES ('JP001', 'Test Job Title', 'Kuala Lumpur', 12.00, '2024-01-01', '2024-12-31', '9AM-5PM', 'Test Job Description', 'Test Job Requirement', NOW(), NOW(), 'EP001');
