CREATE TABLE jobPost (
    jobPost_id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id VARCHAR(10),
    job_title VARCHAR(255) NOT NULL,    
    location VARCHAR(255) NOT NULL,     
    salary DECIMAL(10, 2) NOT NULL,   
    job_description TEXT NOT NULL,      
    job_requirements TEXT,                    
    job_type VARCHAR(50) NOT NULL,         
    start_date DATE NOT NULL,             
    end_date DATE NOT NULL,                
    created_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES employers(employer_id) 
);
