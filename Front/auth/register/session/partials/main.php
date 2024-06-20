<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

// Fetch students data
$students = [];
$stmt = $conn->prepare("SELECT id, first_name, last_name, student_number FROM students");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Fingerprint Verification</title>
    <style>
        .fingerprint-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .student-list {
            width: 80%;
            margin: 20px auto;
            text-align: center;
        }
        .student-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-list th, .student-list td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .student-list th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .hidden {
            display: none;
        }
        .alert {
            padding: 15px;
            margin-top: 10px;
            text-align: center;
            color: #333;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
    <div class="fingerprint-container">
        <h3>Fingerprint Verification</h3>
        <div class="student-list">
            <h4>Students</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Student Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['id']); ?></td>
                            <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['student_number']); ?></td>
                            <td>
                                <button onclick="startVerification(<?php echo $student['id']; ?>)">Verify</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Hidden images for fingerprint processing -->
        <img id="FPImage1" class="hidden" alt="Fingerprint Image 1">
        <img id="FPImage2" class="hidden" alt="Fingerprint Image 2">
        <div>
            <input type="hidden" id="studentId">
        </div>
        <div>
            <p id="result"></p>
            <label for="quality">Match Quality Threshold:</label>
            <input type="text" id="quality" value="100">
        </div>
    </div>

    <script src="fingerprint.js"></script>
    <script>
        function startVerification(studentId) {
            document.getElementById('studentId').value = studentId;
            document.getElementById('result').innerText = '';
            // Initiate fingerprint scan
            CallSGIFPGetData(SuccessFunc1, ErrorFunc);
        }
    </script>
</body>
</html>
