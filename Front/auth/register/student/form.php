<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize error message
    $error_message = null;

    // Check if the necessary data is present
    if (isset($_POST["firstName"], $_POST["lastName"], $_POST["studentNumber"])) {
        // Get the form data
        $first_name = $_POST["firstName"];
        $last_name = $_POST["lastName"];
        $student_number = $_POST["studentNumber"];
        $lecturer_id = $_SESSION["user_id"] ?? null; // Use null coalescing operator to avoid warnings

        if ($lecturer_id) {
            // Insert or update student details
            $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, student_number, lecturer_id) 
                                    VALUES (?, ?, ?, ?) 
                                    ON DUPLICATE KEY UPDATE 
                                        first_name = VALUES(first_name), 
                                        last_name = VALUES(last_name), 
                                        lecturer_id = VALUES(lecturer_id)");
            $stmt->bind_param("ssss", $first_name, $last_name, $student_number, $lecturer_id);

            if ($stmt->execute()) {
                $_SESSION["success_message"] = "Student details inserted successfully.";
            } else {
                $error_message = "Error: Unable to insert student details.";
            }
            $stmt->close();
        } else {
            $error_message = "Error: Lecturer ID is not set in the session.";
        }
    } else {
        $error_message = "Error: Insufficient data received.";
    }

    // Check if fingerprint data is received
    if (isset($_POST['fingerprint'])) {
        $fingerprintData = $_POST['fingerprint'];

        // Update the fingerprint data
        $stmt = $conn->prepare("UPDATE students SET fingerprint_data = ? WHERE student_number = ?");
        $stmt->bind_param("ss", $fingerprintData, $student_number);

        if ($stmt->execute()) {
        } else {
            $error_message = "Error: Unable to insert fingerprint data.";
        }
        $stmt->close();
    }

    if ($error_message) {
        $_SESSION["error_message"] = $error_message;
    }
    header("Location: http://localhost/Attendance-Register/Front/auth/register/home.php");
    exit();
}
?>
