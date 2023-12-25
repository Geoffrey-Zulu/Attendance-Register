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
    $stmt = $conn->prepare("SELECT cs.course_id, cs.student_id, s.last_name, s.first_name, s.student_number, c.course_name FROM Course_Student cs
    JOIN students s ON cs.student_id = s.id
    JOIN courses c ON cs.course_id = c.course_id
    WHERE cs.course_id = ?");
    $stmt->bind_param("i", $selectedCourseId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are students available
    if ($result->num_rows > 0) {
        $students = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // If no students are available, you can handle this case as needed
        $students = array();
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle the case when the course ID is not set
    echo "Course ID not set.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Students</title>
    <style>
        .dashboard-item:hover {
            transform: none !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Add this to your custom CSS or in the head of your HTML file */
        h4.page-header {
            text-align: center;
            margin-top: 50px;
            color: #A0594A;
            text-transform: uppercase;
            /* Adjust the margin as needed */
        }
    </style>
</head>

<body>
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php'; ?>
    <h4 class="page-header">Register for <?php echo $courseName; ?></h4>

    <div id="overlay" class="overlay">
        <div class="lds-facebook">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div class="card dashboard-item">
        <div class="card-body">
            <!-- Student Table -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Student Number</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Present</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student) : ?>
                        <tr>
                            <td><?php echo $student['student_id']; ?></td>
                            <td><?php echo $student['student_number']; ?></td>
                            <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                            <td><input type="checkbox" name="present[]" value="<?php echo $student['student_id']; ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- End Session Button -->
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-success" name="end_session">
                    End Session
                </button>
            </div>

        </div>
    </div>

    <!-- Footer and Scripts -->
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\footer.php'; ?>

    <script>
        // call the spinner 
        function spinner() {
            document.getElementById("overlay").style.display = "block";
            // set delay
            setTimeout(function() {
                window.location.href = "students.php";
            }, 3000); // 2 seconds delay
        }
    </script>
</body>

</html>