<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

// Check if the course ID is set in the URL
if (isset($_GET['courseId'])) {
    $selectedCourseId = $_GET['courseId'];

    // Fetch course name
    $stmt = $conn->prepare("SELECT course_name FROM courses WHERE course_id = ?");
    $stmt->bind_param("i", $selectedCourseId);
    $stmt->execute();
    $stmt->bind_result($courseName);
    $stmt->fetch();
    $stmt->close();

    // Fetch students for the selected course
    $stmt = $conn->prepare("SELECT cs.course_id, cs.student_id, s.last_name, s.first_name, s.student_number, c.course_name 
                            FROM Course_Student cs
                            JOIN students s ON cs.student_id = s.id
                            JOIN courses c ON cs.course_id = c.course_id
                            WHERE cs.course_id = ?");
    $stmt->bind_param("i", $selectedCourseId);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $stmt->close();
} else {
    echo "Course ID not set.";
    exit();
}

// Handle session ending and storing results
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['end_session'])) {
    $attendance = json_decode($_POST['attendance'], true);

    foreach ($attendance as $studentId => $status) {
        $stmt = $conn->prepare("INSERT INTO attendance (course_id, student_id, status) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $selectedCourseId, $studentId, $status);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION["success_message"] = "Session ended and results stored successfully.";
    // Redirect to home page
    header("Location: http://localhost/Attendance-Register/Front/auth/register/home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Attendance Register for <?php echo htmlspecialchars($courseName); ?></title>
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
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php'; ?>
    <div class="fingerprint-container">
        <h3>Attendance Register for <?php echo htmlspecialchars($courseName); ?></h3>
        <?php
        if (isset($_SESSION["success_message"])) {
            echo '<div class="alert alert-success">' . $_SESSION["success_message"] . '</div>';
            unset($_SESSION["success_message"]);
        }
        ?>
        <div class="student-list">
            <form id="attendanceForm" method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Student Number</th>
                            <th>Verified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_number']); ?></td>
                                <td id="status-<?php echo $student['student_id']; ?>">Not Verified</td>
                                <td>
                                    <button type="button"  class="btn btn-success" onclick="startVerification(<?php echo $student['student_id']; ?>)">Verify</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <input type="hidden" name="attendance" id="attendanceData">
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success" name="end_session">End Session</button>
                </div>
            </form>
        </div>
        <!-- Hidden images for fingerprint processing -->
        <img id="FPImage1" class="hidden" alt="Fingerprint Image 1">
        <img id="FPImage2" class="hidden" alt="Fingerprint Image 2">
        <input type="hidden" id="studentId">
    </div>

    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\footer.php'; ?>
    <script src="fingerprint.js"></script>
    <script>
        function startVerification(studentId) {
            document.getElementById('studentId').value = studentId;
            // Initiate fingerprint scan
            CallSGIFPGetData(SuccessFunc1, ErrorFunc);
        }

        function matchScore(succFunction, failFunction) {
            var studentId = document.getElementById('studentId').value;
            if (template_1 === "") {
                alert("Please scan a fingerprint to verify!!");
                return;
            }

            // Fetch stored fingerprint data
            fetch('get_fingerprint.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ studentId: studentId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.fingerprint) {
                    var template_2 = data.fingerprint;

                    var uri = "https://localhost:8443/SGIMatchScore";
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            var fpobject = JSON.parse(xmlhttp.responseText);
                            succFunction(fpobject);
                        } else if (xmlhttp.status == 404) {
                            failFunction(xmlhttp.status);
                        }
                    };
                    xmlhttp.onerror = function () {
                        failFunction(xmlhttp.status);
                    };
                    var params = "template1=" + encodeURIComponent(template_1) + "&template2=" + encodeURIComponent(template_2) + "&licstr=" + encodeURIComponent(secugen_lic) + "&templateFormat=ISO";
                    xmlhttp.open("POST", uri, false);
                    xmlhttp.send(params);
                } else {
                    document.getElementById('result').innerText = "Stored fingerprint not found or error occurred.";
                }
            })
            .catch(error => console.error('Error fetching stored fingerprint:', error));
        }

        function succMatch(result) {
            var studentId = document.getElementById('studentId').value;
            var idQuality = 100; // Preset quality threshold
            if (result.ErrorCode == 0) {
                if (result.MatchingScore >= idQuality) {
                    document.getElementById('status-' + studentId).innerText = "Verified ✔";
                    document.getElementById('status-' + studentId).style.color = "green";
                } else {
                    document.getElementById('status-' + studentId).innerText = "Not Verified";
                }
            } else {
                alert("Error Matching Fingerprints: " + result.ErrorCode);
            }
        }

        function failureFunc(error) {
    alert("On Match Process, failure has been called. Error: " + error);
}

function SuccessFunc1(result) {
    if (result.ErrorCode == 0) {
        template_1 = result.TemplateBase64;
        matchScore(succMatch, failureFunc);
    } else {
        alert("Fingerprint Capture Error Code: " + result.ErrorCode);
    }
}

function ErrorFunc(status) {
    alert("Check if SGIBIOSRV is running; status = " + status + ":");
}

async function CallSGIFPGetData(successCall, failCall) {
    const uri = "https://localhost:8443/SGIFPCapture";
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            const fpobject = JSON.parse(xmlhttp.responseText);
            successCall(fpobject);
        } else if (xmlhttp.status == 404) {
            failCall(xmlhttp.status);
        }
    };
    xmlhttp.onerror = function () {
        failCall(xmlhttp.status);
    };
    const params = "Timeout=" + "10000" + "&Quality=" + "50" + "&licstr=" + encodeURIComponent(secugen_lic) + "&templateFormat=" + "ISO";
    xmlhttp.open("POST", uri, true);
    xmlhttp.send(params);
}

function endSession() {
    const attendance = {};
    document.querySelectorAll('[id^=status-]').forEach(function (element) {
        const studentId = element.id.split('-')[1];
        attendance[studentId] = element.innerText === 'Verified ✔' ? 'Present' : 'Absent';
    });
    document.getElementById('attendanceData').value = JSON.stringify(attendance);
    document.getElementById('attendanceForm').submit();
}
</script>
</body>
</html>

