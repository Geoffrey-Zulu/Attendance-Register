<?php
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the necessary data is present
    if (isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["studentNumber"])) {
        // Get the form data
        $first_name = $_POST["firstName"];
        $last_name = $_POST["lastName"];
        $student_number = $_POST["studentNumber"];
        $lecturer_id = $_SESSION["user_id"]; 
        
        // Prepare and execute the SQL statement to insert student details
        $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, student_number, lecturer_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $first_name, $last_name, $student_number, $lecturer_id);
        

        if ($stmt->execute()) {
            $_SESSION["success_message"] = "Student details inserted successfully.";
        } else {
            echo "Error: Unable to insert student details. ";
        }
    } else {
        echo "Error: Insufficient data received. ";
    }

    // Check if fingerprint data is received
    if (isset($_POST['fingerprint'])) {
        $fingerprintData = $_POST['fingerprint'];

        // Prepare and execute the SQL statement to insert fingerprint data
        $stmt = $conn->prepare("UPDATE students SET fingerprint_data = ? WHERE student_number = ?");
        $stmt->bind_param("ss", $fingerprintData, $student_number);

        if ($stmt->execute()) {
            // echo "Fingerprint data inserted successfully. ";
        } else {
            echo "Error: Unable to insert fingerprint data. ";
        }
    } else {
        echo "Fingerprint data not received. ";
    }
}
?>