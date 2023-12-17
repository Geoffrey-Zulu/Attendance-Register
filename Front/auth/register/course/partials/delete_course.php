<?php
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the course ID is provided in the POST data
    if (isset($_POST['courseId'])) {
        $courseId = $_POST['courseId'];

        // Prepare and execute the SQL statement to delete the course
        $stmt = $conn->prepare("DELETE FROM courses WHERE course_id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();

        // Check if the deletion was successful
        if ($stmt->affected_rows > 0) {
            echo "Course deleted successfully";
        } else {
            echo "Error deleting course";
        }

        $stmt->close();
    } else {
        echo "Course ID not provided";
    }
} else {
    echo "Invalid request method";
}

$conn->close();
?>
