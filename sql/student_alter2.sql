USE attendance_register;

ALTER TABLE students
ADD UNIQUE (`student_number`);
