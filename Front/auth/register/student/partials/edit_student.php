<?php
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the course ID and new details are provided in the POST data
    if (isset($_POST['courseId']) && isset($_POST['newCourseName']) && isset($_POST['newCourseCode'])) {
        $courseId = $_POST['courseId'];
        $newCourseName = $_POST['newCourseName'];
        $newCourseCode = $_POST['newCourseCode'];

        // Prepare and execute the SQL statement to update the course
        $stmt = $conn->prepare("UPDATE courses SET course_name = ?, course_code = ? WHERE course_id = ?");
        $stmt->bind_param("ssi", $newCourseName, $newCourseCode, $courseId);

        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            echo "Course updated successfully";
        } else {
            echo "Error updating course";
        }

        $stmt->close();
    } else {
        echo "Course ID or new details not provided";
    }
} else {
    echo "Invalid request method";
}

$conn->close();
?>
