<?php
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';
if (isset($_SESSION["user_id"]) && !empty($_POST)) { // Check if session variable and POST data are set
    $lecturer_id = $_SESSION["user_id"]; // Get the logged in lecturer's ID from the session
    $first_name = $_POST["firstName"]; // Get the first name from the form data
    $last_name = $_POST["lastName"]; // Get the last name from the form data
    $student_number = $_POST["studentNumber"]; // Get the student number from the form data
    $fingerprint_data = $_POST["fingerprint"]; // Get the fingerprint data from the form data

    // Prepare an SQL statement
    $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, student_number, fingerprint_data, lecturer_id) VALUES (?, ?, ?, ?, ?)");

    // Bind the form data and the lecturer ID to the SQL statement
    $stmt->bind_param("ssssi", $first_name, $last_name, $student_number, $fingerprint_data, $lecturer_id);

    $studentNumber = $_POST["studentNumber"];
    $sql = "SELECT * FROM students WHERE student_number = ?";
    $select_stmt = $conn->prepare($sql);

    // prevent duplicate entry
    $select_stmt->bind_param("s", $studentNumber);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    if ($result->num_rows > 0) {
        // Student number already exists
        echo "<div class='alert alert-danger'>Student number already exists. Please use a different number.</div>";
    } else {
        // Execute the SQL statement
        $stmt->execute();
    }
}
