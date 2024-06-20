<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

// Initialize $selectedDate to avoid undefined variable warning
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Interpretation</title>
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

        .result-box {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h2>Attendance Charts</h2>
    <div class="date-selector">
        <form method="GET" action="charts.php">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
            <button type="submit">Update Charts</button>
        </form>
    </div>
    <div class="chart-container">
        <div id="attendance_by_course" class="chart-box"></div>
        <div id="attendance_by_student" class="chart-box"></div>
    </div>
    <div class="result-box">
        <button id="aiInterpretButton" onclick="interpretData()">AI Interpretation</button>
        <p id="aiResult"></p>
    </div>

    <!-- Import the Google AI SDK -->
    <script type="importmap">
        {
        "imports": {
          "@google/generative-ai": "https://esm.run/@google/generative-ai"
        }
      }
    </script>

    <script type="module">
        import {
            GoogleGenerativeAI
        } from "@google/generative-ai";

        // Fetch your API_KEY
        const API_KEY = 'YOUR_API_KEY'; // Replace with your actual API key

        // Initialize GoogleGenerativeAI
        const genAI = new GoogleGenerativeAI(API_KEY);
        const model = genAI.getGenerativeModel({
            model: "gemini-1.5-flash-latest"
        });

        async function interpretData() {
            const date = document.getElementById('date').value;
            const data = <?php echo json_encode($attendance_data); ?>;
            const prompt = `Provide an interpretation for the following attendance data on ${date}: ${JSON.stringify(data)}`;

            try {
                const result = await model.generateContent(prompt);
                const response = await result.response;
                const text = await response.text();
                document.getElementById('aiResult').innerText = `AI Interpretation: ${text}`;
            } catch (error) {
                document.getElementById('aiResult').innerText = `Error: ${error.message}`;
            }
        }
    </script>
</body>

</html>