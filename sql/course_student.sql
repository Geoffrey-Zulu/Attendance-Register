USE attendance_register;

CREATE TABLE Course_Student (
    course_id INT,
    student_id INT,
    PRIMARY KEY (course_id, student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (student_id) REFERENCES students(id)
);
