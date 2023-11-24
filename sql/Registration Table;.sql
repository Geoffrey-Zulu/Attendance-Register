USE attendance_register;

CREATE TABLE registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    employee_no INT,
    email VARCHAR(255),
    password VARCHAR(255),
    status ENUM('pending', 'verified', 'active') DEFAULT 'pending',
    verification_token VARCHAR(255),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);