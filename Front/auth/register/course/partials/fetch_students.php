<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

// Assuming $lecturerId contains the ID of the current lecturer
$lecturerId = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 0;

// Fetch students associated with the lecturer
$courseId = isset($_POST['courseId']) ? $_POST['courseId'] : null;
echo "Received courseId: " . ($courseId ?? "null");  // Debug output

if ($courseId === "") {
    echo '|';  // Use a delimiter to separate the debug output and JSON response
    echo json_encode(["error" => "Empty courseId"]);
} else {
    $sql = "SELECT id, first_name, last_name FROM students WHERE lecturer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $lecturerId);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }

    // Separate the debug output from the JSON response
    echo '|';  // Use a delimiter to separate the debug output and JSON response
    echo json_encode($students);
}

$conn->close();
