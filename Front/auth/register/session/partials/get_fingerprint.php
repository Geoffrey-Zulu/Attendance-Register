<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$studentId = $input['studentId'] ?? null;

$response = ['success' => false, 'fingerprint' => null];

if ($studentId) {
    $stmt = $conn->prepare("SELECT fingerprint_data FROM students WHERE id = ?");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $stmt->bind_result($fingerprintData);
    if ($stmt->fetch()) {
        $response['success'] = true;
        $response['fingerprint'] = $fingerprintData;
    }
    $stmt->close();
}

echo json_encode($response);
?>
