CREATE TABLE jobPost (
    jobPostID INT AUTO_INCREMENT PRIMARY KEY,
    employer_id VARCHAR(10),
    jobTitle VARCHAR(255) NOT NULL,    
    location VARCHAR(255) NOT NULL,     
    salary DECIMAL(10, 2) NOT NULL,   
    jobDescription TEXT NOT NULL,      
    jobRequirements TEXT,                    
    jobType VARCHAR(50) NOT NULL,         
    startDate DATE NOT NULL,             
    endDate DATE NOT NULL,                
    createdTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES employers(employer_id) 
);
