<?php
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

header('Content-Type: application/json'); // Set content type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the course ID and student IDs are provided in the POST data
    if (isset($_POST['courseId']) && isset($_POST['studentIds'])) {
        $courseId = $_POST['courseId'];
        $studentIds = $_POST['studentIds'];

        // Check if the course_id exists in the courses table
        $stmt = $conn->prepare("SELECT course_id FROM courses WHERE course_id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the course_id exists in the courses table
        if ($result->num_rows === 0) {
            // Return a JSON response for the error
            echo json_encode(['status' => 'error', 'message' => "Error: course_id $courseId does not exist in the courses table."]);
            exit();
        }

        // Use a transaction for multiple inserts
        $conn->begin_transaction();

        try {
            // Loop through the array of student IDs and insert each into the Course_Student table
            foreach ($studentIds as $studentId) {
                $stmt = $conn->prepare("INSERT INTO Course_Student (course_id, student_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $courseId, $studentId);
                $stmt->execute();

                // Check if the insert was successful
                if ($stmt->affected_rows <= 0) {
                    throw new Exception("Error inserting student with ID $studentId into Course_Student table");
                }
            }

            // Commit the transaction if all inserts are successful
            $conn->commit();
            
            // Return a JSON response for success
            echo json_encode(['status' => 'success', 'message' => 'Students added successfully', 'data' => null]);
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();

            // Return a JSON response for the error
            echo json_encode(['status' => 'error', 'message' => 'Course ID or student IDs not provided', 'data' => null]);
        }
    } else {
        // Return a JSON response for the error
        echo json_encode(['status' => 'error', 'message' => 'Course ID or student IDs not provided']);
    }
} else {
    // Return a JSON response for the error
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>



