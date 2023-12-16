<?php
session_start();
include 'C:\xampp\htdocs\Attendance-Register\Front\partials\connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
</head>
<style>
/* Add this to your custom CSS or in the head of your HTML file */
        h4 {
            text-align: center;
            margin-top: 50px;
            color: #A0594A;
            text-transform: uppercase;
            /* Adjust the margin as needed */
        }
    </style>

<body>
    <!-- header start -->
    <?php include 'partials/header.php'; ?>
    <!-- header finish -->

    <!-- Main Screen contents start-->
    <div class="container mt-5">

        <h4 class="text-center">Welcome back, <?php echo $first_name; ?></h4>

        <div class="row">

            <!-- column 1 start -->
            <div class="col-md-6">
                <div class="card dashboard-item">
                    <div class="card-body">
                        <h5 class="card-title">Add Student</h5>
                        <!-- 'Add Tutorial' content here -->
                        <p class="card-text text-muted">
                            click here to add and edit students
                        </p>
                        <button class="btn btn-primary" onclick="location.href='student/students.php'">Start</button>
                    </div>
                </div>
                <div class="card dashboard-item">
                    <div class="card-body">
                        <h5 class="card-title">Start Session</h5>
                        <!-- 'Add Course' content here -->
                        <p class="card-text text-muted">
                            Click here to begin a lecture
                        </p>
                        <button class="btn btn-primary" onclick="location.href='session/session.php'">Start</button>
                    </div>
                </div>
            </div>
            <!-- column 1 end -->

            <!-- column 2 start -->
            <div class="col-md-6">
                <div class="card dashboard-item">
                    <div class="card-body">
                        <h5 class="card-title">Add Course</h5>
                        <!-- 'Tutorial Duration' content here -->
                        <p class="card-text text-muted">
                            Click here to add and edit courses
                        </p>
                        <button class="btn btn-primary" onclick="location.href='course/course.php'">Start</button>
                    </div>
                </div>
                <div class="card dashboard-item">
                    <div class="card-body">
                        <h5 class="card-title">Finalize Semester</h5>
                        <!-- 'Course Duration' content here -->
                        <p class="card-text text-muted">
                            Click here to end a semester
                        </p>
                        <button class="btn btn-primary" onclick="location.href='finalize/finalize.php'">Start</button>
                    </div>
                </div>
            </div>
            <!-- column 2 end -->

        </div>
    </div>
    <!-- Main Screen contents end-->

    <!-- Footer start-->
    <?php include 'partials/footer.php'; ?>
    <!-- footer end -->
</body>

</html>