<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

if (isset($_SESSION["user_id"]) && !empty($_POST)) {
    $lecturer_id = $_SESSION["user_id"];
    $course_name = $_POST["courseName"];
    $course_code = $_POST["courseCode"];

    // Check if the course already exists
    $select_stmt = $conn->prepare("SELECT * FROM courses WHERE course_code = ?");
    $select_stmt->bind_param("s", $course_code);
    $select_stmt->execute();
    $result = $select_stmt->get_result();

    if ($result->num_rows > 0) {
        // Course code already exists
        echo "<div class='alert alert-danger'>Course code already exists. Please use a different code.</div>";
    } else {
        // Prepare an SQL statement
        $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, lecturer_id) VALUES (?, ?, ?)");
        // Bind the form data and the lecturer ID to the SQL statement
        $stmt->bind_param("ssi", $course_name, $course_code, $lecturer_id);

        // Execute the SQL statement
        $stmt->execute();

        // Close the statement
        $stmt->close();
        header("Location: ../course.php");
        exit();
    }

    // Close the select statement
    $select_stmt->close();
}
?>
