USE attendance_register;
ALTER TABLE Course_Student
DROP FOREIGN KEY course_student_ibfk_1;  -- Drop the existing constraint

ALTER TABLE Course_Student
ADD CONSTRAINT course_student_ibfk_1 FOREIGN KEY (`course_id`) REFERENCES courses (`course_id`) ON DELETE CASCADE;  -- Recreate with ON DELETE CASCADE
