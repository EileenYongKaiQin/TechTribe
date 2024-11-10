CREATE TABLE employers (
    employer_id VARCHAR(10) PRIMARY KEY,  
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    phone_number VARCHAR(255) NOT NULL
);

INSERT INTO employers (employer_id, name, email, password, company_name, phone_number)
VALUES 
    ('E001', 'Chua Ern Qi', 'chua@example.com', 'chua123', 'FlexMatch', '0123456789');
    ('E002', 'Eileen Yong Kai Qin', 'eileen@example.com', 'eileen123', 'FlexMatch', '0123456789');
    ('E003', 'Jessie Chang', 'jessie@example.com', 'jessie123', 'FlexMatch', '0123456789');
    ('E004', 'Oh Kai Xuan', 'oh@example.com', 'oh123', 'FlexMatch', '0123456789');
    ('E005', 'Tam Jia Hao', 'tam@example.com', 'tam123', 'FlexMatch', '0123456789');
    ('E006', 'Tan You Chun', 'tan@example.com', 'tan123', 'FlexMatch', '0123456789');