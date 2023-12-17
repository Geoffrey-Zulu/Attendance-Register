USE attendance_register;

CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  student_number VARCHAR(50) NOT NULL,
  fingerprint_data BLOB,
  lecturer_id INT,
  UNIQUE(student_number)
);
