<?php
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';
// Fetching student data
$lecturer_id = $_SESSION["user_id"];
$sql = "SELECT first_name, last_name, id, student_number FROM students WHERE lecturer_id = ?";
$select_stmt = $conn->prepare($sql);

if (!$select_stmt) {
    die('Error preparing selection statement: ' . $conn->error);
}

$select_stmt->bind_param("i", $lecturer_id);
$select_success = $select_stmt->execute();

// Check for successful execution
if (!$select_success) {
    die('Error executing selection statement: ' . $select_stmt->error);
}

$result = $select_stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<tr id='studentRow_" . $row['student_number'] . "'>"; // Add an id attribute to each table row
    echo "<td>" . $row['first_name'] . "</td>";
    echo "<td>" . $row['last_name'] . "</td>";
    echo "<td>" . $row['student_number'] . "</td>";
    echo "<td>";

    // Edit button
    echo "<button type='button' class='btn btn-link edit-student' data-toggle='modal' data-target='#editModal' data-id='" . $row['id'] . "' data-first-name='" . $row['first_name'] . "' data-last-name='" . $row['last_name'] . "' data-student-number='" . $row['student_number'] . "'>";
    echo "<i class='fa-solid fa-pencil' style='color: #ffc107;'></i>";
    echo "</button>";


    // Delete button
    echo "<button type='button' class='btn btn-link delete-student' data-toggle='modal' data-target='#deleteModal' data-student-number='" . $row['student_number'] . "'>";
    echo "<i class='fa-solid fa-trash' style='color: #e7133d;'></i>";
    echo "</button>";
    echo "</td>";
    echo "</tr>";
}

$select_stmt->close();
$conn->close(); // Close the second connection
