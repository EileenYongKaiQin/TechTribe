-- Create reports table
CREATE TABLE reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_type ENUM('job seeker', 'employer') NOT NULL,
    reporter_id VARCHAR(10) NOT NULL,
    report_reason VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('pending', 'resolved', 'dismissed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create evidence table
CREATE TABLE report_evidence (
    evidence_id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_data LONGBLOB,  -- For storing the actual file content
    file_type VARCHAR(100) NOT NULL,
    file_size INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(report_id) ON DELETE CASCADE
);

-- Create report history table
CREATE TABLE report_status_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    comment TEXT,
    updated_by VARCHAR(50),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(report_id) ON DELETE CASCADE
);

-- Insert some sample data
INSERT INTO reports (reporter_type, reporter_id, report_reason, description, status)
VALUES 
('job seeker', 'JS001', 'Fraud or Scam', 'This job posting seems to be a scam', 'pending'),
('employer', 'E001', 'False Information', 'Job Seeker is sharing false credentials', 'pending');