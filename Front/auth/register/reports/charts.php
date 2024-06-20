<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

// Get the selected date from the GET request or default to today
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Fetch attendance data for the selected date
$stmt = $conn->prepare("
    SELECT a.timestamp, c.course_name, s.first_name, s.last_name, a.status
    FROM attendance a
    JOIN courses c ON a.course_id = c.course_id
    JOIN students s ON a.student_id = s.id
    WHERE DATE(a.timestamp) = ?
    ORDER BY a.timestamp
");
$stmt->bind_param("s", $selectedDate);
$stmt->execute();
$result = $stmt->get_result();

$attendance_data = [];
while ($row = $result->fetch_assoc()) {
    $attendance_data[] = $row;
}
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Attendance Charts</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart', 'bar', 'line']
        });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            drawAttendanceByCourse();
            drawAttendanceByStudent();
        }

        function drawAttendanceByCourse() {
            var data = google.visualization.arrayToDataTable([
                ['Course', 'Present', 'Absent'],
                <?php
                // Process data for chart
                $course_data = [];
                foreach ($attendance_data as $entry) {
                    $course = $entry['course_name'];
                    $status = $entry['status'];
                    if (!isset($course_data[$course])) {
                        $course_data[$course] = ['Present' => 0, 'Absent' => 0];
                    }
                    $course_data[$course][$status]++;
                }
                foreach ($course_data as $course => $counts) {
                    echo "['$course', {$counts['Present']}, {$counts['Absent']}],";
                }
                ?>
            ]);

            var options = {
                title: 'Attendance by Course for <?php echo htmlspecialchars($selectedDate); ?>',
                hAxis: {
                    title: 'Course'
                },
                vAxis: {
                    title: 'Count'
                },
                bars: 'vertical',
                isStacked: true
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('attendance_by_course'));
            chart.draw(data, options);
        }

        function drawAttendanceByStudent() {
            var data = google.visualization.arrayToDataTable([
                ['Student', 'Present', 'Absent'],
                <?php
                // Process data for chart
                $student_data = [];
                foreach ($attendance_data as $entry) {
                    $student = $entry['first_name'] . ' ' . $entry['last_name'];
                    $status = $entry['status'];
                    if (!isset($student_data[$student])) {
                        $student_data[$student] = ['Present' => 0, 'Absent' => 0];
                    }
                    $student_data[$student][$status]++;
                }
                foreach ($student_data as $student => $counts) {
                    echo "['$student', {$counts['Present']}, {$counts['Absent']}],";
                }
                ?>
            ]);

            var options = {
                title: 'Attendance by Student for <?php echo htmlspecialchars($selectedDate); ?>',
                hAxis: {
                    title: 'Student'
                },
                vAxis: {
                    title: 'Count'
                },
                bars: 'vertical',
                isStacked: true
            };

            var chart = new google.visualization.BarChart(document.getElementById('attendance_by_student'));
            chart.draw(data, options);
        }
    </script>
    <style>
        .chart-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .chart-box {
            width: 45%;
            margin: 20px;
        }

        .date-selector {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php'; ?>
    <h2>Attendance Charts</h2>
    <div class="date-selector">
        <form method="GET" action="attendance_charts.php">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
        </form>
    </div>
    <div class="chart-container">
        <div id="attendance_by_course" class="chart-box"></div>
        <div id="attendance_by_student" class="chart-box"></div>
    </div>
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\footer.php'; ?>
</body>

</html>