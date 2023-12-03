<?php
include 'connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["studentNumber"])) {
    $lecturer_id = $_SESSION["user_id"];
    $studentNumber = $_POST["studentNumber"];

    // Prepare and execute the SQL statement to delete the student
    $stmt = $conn->prepare("DELETE FROM students WHERE lecturer_id = ? AND student_number = ?");
    $stmt->bind_param("is", $lecturer_id, $studentNumber);

    if ($stmt->execute()) {
        // Deletion successful
        echo "Student deleted successfully.";
    } else {
        // Error in deletion
        echo "Error deleting student.";
    }

    $stmt->close();
    $conn->close();
}
?>
