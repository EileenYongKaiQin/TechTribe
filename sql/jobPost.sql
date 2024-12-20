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
('JP001', 'Johor Roadshow Event Crew', 'Johor', 14.00, '2025-11-05', '2025-11-05', 'Day Shift', 'Monitor free gift, Game Booth, Assist ad-hoc tasks', 'No diva attitude, No last minute', 'Midvalley Southkey', 'English, Mandarin', 'Chinese', '09:00:00', '21:00:00', NOW(), NOW(), 'EP001'),
('JP002', 'Clothing Store Hiring', 'Kuala Lumpur', 16.00, '2025-04-01', '2025-07-01', 'Night Shift', 'Responsible for handling various sales activities in a retail store', '18 Years old or above, Responsible (Willing to learn, Good attitude)', 'Sunway Pyramid', 'English, Malay', 'Any', '17:00:00', '00:00:00', NOW(), NOW(), 'EP002'),
('JP003', 'Balakong Warehouse Part-time Crew', 'Selangor', 10.00, '2025-05-01', '2025-05-30', 'Day Shift', 'Follow the order list, pick items, pack them into boxes, and seal them up. Carry and deliver items when needed. Products: Healthy snacks & daily essentials', 'Age: 18 to 35 years old', 'Seri Kembangan', 'Malay', 'Malay', '09:00:00', '17:00:00', NOW(), NOW(), 'EP003'),
('JP004', 'Urgent permanent Promoter', 'Johor', 15.00, '2025-05-30', '2026-05-30', 'Night Shift', 'Job Scope: Promote Yogurt drink. Gurantee job every weekends, no need to change project.', 'Promoter with good attitude & aggressive are encourage to apply. Preferred those who had promoter.', 'Jusco Bkt Indah', 'English, Malay, Chinese', 'Any', '12:00:00', '21:00:00', NOW(), NOW(), 'EP004'),
('JP005', 'Talk/Seminar Event crew', 'Johor', 10.00, '2025-03-20', '2025-03-20', 'Night Shift', 'To prepare sampling milk for approx. 20-30 pharmacies. If needed, briefly explain the benefit of our products. However product specialist will be there to explain the product in details.', 'Female only. Pleasant, friendly and good working attitude. Preferably able to converse in basic English and have sampling promoter experience.', 'Grand Paragon', 'English', 'Any', '18:30:00', '22:30:00', NOW(), NOW(), 'EP001'),
('JP006', 'Game Ambassadors for chocolate drink', 'Kuala Lumpur', 12.00, '2025-06-15', '2025-06-17', 'Day Shift', '20 game ambassadors/promoters needed~! (Friday, Saturday, Sunday)', 'Approachable and Active. 158cm and above. Female and Male. MUST commit all days. MUST attend interview', 'Mid Valley Megamall', 'English', 'Any', '10:00:00', '22:00:00', NOW(), NOW(), 'EP002'),
('JP007', 'Event Waiters', 'Selangor', 10.00, '2025-04-18', '2025-04-18', 'Night Shift', 'We are urgently seeking enthusiastic individuals to join us as Waiters for an upcoming event! ', '18 Years old or above', 'Shah Alam', 'Any', 'Any', '16:30:00', '22:30:00', NOW(), NOW(), 'EP003'),
('JP008', 'Urgent permanent Promoter', 'Johor', 15.00, '2025-05-30', '2026-05-30', 'Night Shift', 'Job Scope: Promote Yogurt drink. Gurantee job every weekends, no need to change project.', 'Promoter with good attitude & aggressive are encourage to apply. Preferred those who had promoter.', 'Jusco Permas', 'English, Malay', 'Any', '12:00:00', '21:00:00', NOW(), NOW(), 'EP004');
