<?php
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the student number is provided in the POST data
    if (isset($_POST['studentNumber'])) {
        $studentNumber = $_POST['studentNumber'];

        // Prepare and execute the SQL statement to delete the student
        $stmt = $conn->prepare("DELETE FROM students WHERE student_number = ?");
        $stmt->bind_param("s", $studentNumber);
        $stmt->execute();

        // Check if the deletion was successful
        if ($stmt->affected_rows > 0) {
            echo "Student deleted successfully";
        } else {
            echo "Error deleting student";
        }

        $stmt->close();
    } else {
        echo "Student number not provided";
    }
} else {
    echo "Invalid request method";
}

$conn->close();
?>
