-- SQLBook: Code
CREATE TABLE jobPost (
    jobPostID VARCHAR(10) PRIMARY KEY, -- Unique ID for the job post (e.g., JP001)
    jobTitle VARCHAR(255) NOT NULL, -- Title of the job post
    location VARCHAR(255), -- Location of the job
    salary DECIMAL(10, 2), -- Salary for the job
    startDate DATE, -- Start date of the job
    endDate DATE, -- End date of the job
    workingHour VARCHAR(50), -- Working shift (e.g., Day/Night)
    jobDescription TEXT, -- Description of the job
    jobRequirement TEXT, -- Requirements for the job
    venue VARCHAR(255), -- Venue of the job
    language VARCHAR(255), -- Language requirements for the job
    race VARCHAR(50), -- Race preference for the job
    workingTimeStart TIME, -- Job starting time
    workingTimeEnd TIME, -- Job ending time
    createTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Job post creation timestamp
    updateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Last update timestamp
    userID VARCHAR(10) NOT NULL, -- ID of the employer who posted the job
    FOREIGN KEY (userID) REFERENCES login(userID) ON DELETE CASCADE -- Link to login table
);


INSERT INTO jobPost (jobPostID, jobTitle, location, salary, startDate, endDate, workingHour, jobDescription, jobRequirement, venue, language, race, workingTimeStart, workingTimeEnd, createTime, updateTime, userID)
VALUES 
('JP001', 'Johor Roadshow Event Crew', 'Johor Bahru', 14.00, '2025-02-15', '2025-02-27', 'Day Shift', 'Monitor free gift, Game Booth, Assist ad-hoc tasks', 'No diva attitude, No last minute', 'Midvalley Southkey', 'English, Mandarin', 'Chinese', '09:00:00', '21:00:00', NOW(), NOW(), 'EP001'),
('JP002', 'Clothing Store Hiring', 'Kuala Lumpur', 16.00, '2025-04-01', '2025-07-01', 'Night Shift', 'Responsible for handling various sales activities in a retail store', '18 Years old or above, Responsible (Willing to learn, Good attitude)', 'Sunway Pyramid', 'English, Malay', 'Any', '17:00:00', '00:00:00', NOW(), NOW(), 'EP002');
