<?php
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the student number and new details are provided in the POST data
    if (isset($_POST['id']) && isset($_POST['studentNumber']) && isset($_POST['firstName']) && isset($_POST['lastName'])) {
        $id = $_POST['id'];
        $studentNumber = $_POST['studentNumber'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];

        // Prepare and execute the SQL statement to update the student

        $stmt = $conn->prepare("UPDATE students SET student_number = ?, first_name = ?, last_name = ? WHERE id = ?");
        $stmt->bind_param("sssi", $studentNumber, $firstName, $lastName, $id);

        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            echo "Student updated successfully";
        } else {
            echo "Error updating student";
        }

        $stmt->close();
    } else {
        echo "Student number or new details not provided";
    }
} else {
    echo "Invalid request method";
}

$conn->close();