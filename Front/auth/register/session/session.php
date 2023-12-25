<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';

// Fetch courses from the database
$sql = "SELECT course_id, course_name FROM courses";
$result = $conn->query($sql);

// Check if there are courses available
if ($result->num_rows > 0) {
    $courses = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // If no courses are available, you can handle this case as needed
    $courses = array();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Start Session</title>
    <link rel="stylesheet" href="http://localhost/Attendance-Register/Front/styles/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .dashboard-item:hover {
            transform: none !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            text-align: center;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\header.php'; ?>
    <div id="overlay" class="overlay">
        <div class="lds-facebook">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card dashboard-item">
                <div class="card-body">
                    <h5 class="card-title">Start Session</h5>

                    <!-- session.php -->
                    <form action="http://localhost/Attendance-Register/Front/auth/register/session/partials/main.php" method="get">
                        <div class="form-container">
                            <!-- Populate the dropdown with courses -->
                            <select class="form-control mb-3" name="courseId">
                                <?php foreach ($courses as $course) : ?>
                                    <option value="<?php echo $course['course_id']; ?>"><?php echo $course['course_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <!-- Add your 'Start Session' button here -->
                            <button type="submit" class="btn btn-primary">
                                Start Session
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


    <?php include 'C:\xampp\htdocs\Attendance-Register\Front\auth\register\partials\footer.php'; ?>

    <!-- Additional scripts go here -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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