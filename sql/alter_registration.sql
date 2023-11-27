USE attendance_register;

ALTER TABLE registration
ADD UNIQUE (`employee_no`);
